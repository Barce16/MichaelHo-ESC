<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\Payment;
use App\Models\Event;
use App\Models\InclusionChangeRequest;
use Carbon\Carbon;


class SmsNotifier
{
    protected $client;
    protected $apiToken;
    protected $apiUrl;

    public function __construct()
    {
        $this->apiToken = config('services.iprogtech.api_token');
        $this->apiUrl = config('services.iprogtech.api_url');

        $this->client = new Client([
            'base_uri' => $this->apiUrl,
            'timeout' => 30,
        ]);
    }

    /**
     * Send SMS notification using iprogtech API
     */
    public function sendSms(string $to, string $message): bool
    {
        try {
            $formattedPhone = $this->formatPhoneNumber($to);

            Log::info('Attempting to send SMS via iprogtech', [
                'to' => $formattedPhone,
                'message_length' => strlen($message),
                'api_url' => $this->apiUrl . '/sms_messages'
            ]);

            $response = $this->client->post('sms_messages', [
                'form_params' => [
                    'api_token' => $this->apiToken,
                    'phone_number' => $formattedPhone,
                    'message' => $message,
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            Log::info('iprogtech SMS sent successfully', [
                'to' => $formattedPhone,
                'response' => $result
            ]);

            return true;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $statusCode = $response ? $response->getStatusCode() : 'N/A';
            $responseBody = $response ? $response->getBody()->getContents() : 'No response body';

            Log::error('iprogtech API Client Error', [
                'to' => $to,
                'status_code' => $statusCode,
                'error' => $e->getMessage(),
                'response_body' => $responseBody
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Failed to send iprogtech SMS - General Error', [
                'to' => $to,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
    /**
     * Format phone number
     */
    protected function formatPhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // If starts with 0, replace with 63
        if (substr($phone, 0, 1) === '0') {
            $phone = '63' . substr($phone, 1);
        }

        // If starts with +63, remove the +
        if (substr($phone, 0, 3) === '+63') {
            $phone = substr($phone, 1);
        }

        // If doesn't start with 63, add it
        if (substr($phone, 0, 2) !== '63') {
            $phone = '63' . $phone;
        }

        return $phone;
    }

    /**
     * Get greeting based on gender
     */
    protected function getGreeting(?string $gender): string
    {
        return match (strtolower($gender ?? '')) {
            'male' => 'Mr.',
            'female' => 'Ms.',
            default => '',
        };
    }

    /**
     * Notify existing customer that their event was approved (no login details)
     */
    public function notifyEventApprovedExistingUser(Event $event): bool
    {
        $customer = $event->customer;

        // Check if phone exists
        if (empty($customer->phone)) {
            Log::warning('SMS not sent: Customer has no phone number', [
                'customer_id' => $customer->id,
                'event_id' => $event->id
            ]);
            return false;
        }

        $prefix = match (strtolower($customer->gender)) {
            'male' => 'Mr.',
            'female' => 'Ms.',
            default => '',
        };

        $message = "Good news {$prefix} {$customer->customer_name}! Your event '{$event->name}' has been approved.\n\n";
        $message .= "Next Step: Pay P5,000 introductory payment to schedule your meeting.\n\n";
        $message .= "Login to your account to submit payment: " . url('/login');

        return $this->sendSms($customer->phone, $message);
    }

    /**
     * Notify customer that their event was approved
     */
    public function notifyEventApproved(Event $event, string $username, string $password): bool
    {
        $customer = $event->customer;

        $prefix = match (strtolower($customer->gender)) {
            'male' => 'Mr.',
            'female' => 'Ms.',
            default => '',
        };


        $message = "Good news {$prefix} {$customer->customer_name}! Your event '{$event->name}' has been approved.\n\n";
        $message .= "Login Details:\n";
        $message .= "Username: {$username}\n";
        $message .= "Password: {$password}\n\n";
        $message .= "Next Step: Pay P5,000 introductory payment.\n\n";
        $message .= "Login at: " . url('/login');

        return $this->sendSms($customer->phone, $message);
    }

    /**
     * Notify customer that their payment was confirmed
     */
    public function notifyPaymentConfirmed(Payment $payment): bool
    {
        $event = $payment->billing->event;
        $customer = $event->customer;


        $prefix = match (strtolower($customer->gender)) {
            'male' => 'Mr.',
            'female' => 'Ms.',
            default => '',
        };

        $paymentType = match ($payment->payment_type) {
            'introductory' => 'introductory payment',
            'downpayment' => 'downpayment',
            'balance' => 'balance payment',
            default => 'payment'
        };

        $message = "Payment Confirmed!\n\n";
        $message .= "Hello {$prefix} {$customer->customer_name},\n\n";
        $message .= "Your {$paymentType} of P" . number_format($payment->amount, 2) . " for '{$event->name}' has been approved.\n\n";

        // Add What's Next based on payment type
        $message .= "NEXT: ";
        if ($payment->payment_type === 'introductory') {
            $message .= "We'll contact you within 24-48hrs to schedule your planning meeting.\n\n";
        } elseif ($payment->payment_type === 'downpayment') {
            $message .= "Your event is now SCHEDULED! We'll keep you updated as we prepare.\n\n";
        } else {
            $billing = $payment->billing;
            if ($billing->remaining_balance > 0) {
                $message .= "Remaining balance: P" . number_format($billing->remaining_balance, 2) . "\n\n";
            } else {
                $message .= "Your event is now FULLY PAID! Just relax and enjoy your day.\n\n";
            }
        }

        $message .= "View details: " . route('customer.events.show', $event);

        return $this->sendSms($customer->phone, $message);
    }

    /**
     * Notify customer that their payment was rejected
     */
    public function notifyPaymentRejected(Payment $payment, string $reason): bool
    {
        $event = $payment->billing->event;
        $customer = $event->customer;

        $prefix = match (strtolower($customer->gender)) {
            'male' => 'Mr.',
            'female' => 'Ms.',
            default => '',
        };

        $message = "Payment Issue\n\n";
        $message .= "Hello {$prefix} {$customer->customer_name},\n\n";
        $message .= "Your payment of P" . number_format($payment->amount, 2) . " for '{$event->name}' could not be verified.\n\n";
        $message .= "Reason: {$reason}\n\n";
        $message .= "NEXT STEPS:\n";
        $message .= "1. Log in to your account\n";
        $message .= "2. Upload a clear photo of your payment receipt\n";
        $message .= "3. Wait for verification (24-48hrs)\n\n";
        $message .= "Resubmit here: " . route('customer.events.show', $event);

        return $this->sendSms($customer->phone, $message);
    }

    /**
     * Notify customer when admin updates their event inclusions
     */
    public function notifyInclusionsUpdated(Event $event, $oldTotal, $newTotal): bool
    {
        $customer = $event->customer;

        if (empty($customer->phone)) {
            Log::warning('SMS not sent: Customer has no phone number', [
                'customer_id' => $customer->id,
                'event_id' => $event->id
            ]);
            return false;
        }

        $greeting = $this->getGreeting($customer->gender);
        $difference = $newTotal - $oldTotal;
        $changeText = $difference >= 0
            ? '+P' . number_format($difference, 2)
            : '-P' . number_format(abs($difference), 2);

        $message = "{$greeting} {$customer->customer_name}, your event '{$event->name}' inclusions have been updated by our team. ";
        $message .= "New total: P" . number_format($newTotal, 2) . " ({$changeText}). ";

        // Add What's Next
        $message .= "NEXT: Log in to review changes. ";
        if ($difference > 0) {
            $message .= "Note: Balance increased. ";
        }
        $message .= "Contact us with questions. - Michael Ho Events";

        return $this->sendSms($customer->phone, $message);
    }

    /**
     * Notify customer of event progress update (new progress added)
     */
    public function notifyEventProgress(Event $event, string $progressStatus, ?string $details = null): bool
    {
        $customer = $event->customer;

        if (empty($customer->phone)) {
            Log::warning('SMS not sent: Customer has no phone number', [
                'customer_id' => $customer->id,
                'event_id' => $event->id
            ]);
            return false;
        }

        $greeting = $this->getGreeting($customer->gender);

        $message = "{$greeting} {$customer->customer_name}, progress update for '{$event->name}': {$progressStatus}. ";
        if ($details) {
            $message .= "{$details} ";
        }
        $message .= "- Michael Ho Events";

        return $this->sendSms($customer->phone, $message);
    }

    /**
     * Notify customer of event progress modification (existing progress edited)
     */
    public function notifyEventProgressUpdate(Event $event, string $progressStatus, ?string $details = null): bool
    {
        $customer = $event->customer;

        if (empty($customer->phone)) {
            Log::warning('SMS not sent: Customer has no phone number', [
                'customer_id' => $customer->id,
                'event_id' => $event->id
            ]);
            return false;
        }

        $greeting = $this->getGreeting($customer->gender);

        $message = "{$greeting} {$customer->customer_name}, a progress update for '{$event->name}' has been modified: {$progressStatus}. ";
        if ($details) {
            $message .= "{$details} ";
        }
        $message .= "Check your account for details. - Michael Ho Events";

        return $this->sendSms($customer->phone, $message);
    }

    /**
     * Notify customer of event schedule update
     */
    public function notifyEventScheduleUpdate(Event $event, array $schedules, string $action = 'updated'): bool
    {
        $customer = $event->customer;

        if (empty($customer->phone)) {
            Log::warning('SMS not sent: Customer has no phone number', [
                'customer_id' => $customer->id,
                'event_id' => $event->id
            ]);
            return false;
        }

        $greeting = $this->getGreeting($customer->gender);
        $scheduleCount = count($schedules);

        $actionText = match ($action) {
            'created' => 'scheduled',
            'updated' => 'updated',
            default => 'updated'
        };

        $message = "{$greeting} {$customer->customer_name}, ";

        if ($scheduleCount === 1) {
            $schedule = $schedules[0];
            $schedule->load('inclusion');
            $inclusionName = $schedule->inclusion->name ?? 'an item';
            $scheduleDate = Carbon::parse($schedule->scheduled_date)->format('M d, Y');

            $message .= "schedule {$actionText} for '{$event->name}': {$inclusionName} on {$scheduleDate}. ";
        } else {
            $message .= "{$scheduleCount} schedules have been {$actionText} for '{$event->name}'. ";
        }

        $message .= "Check your account for full details. - Michael Ho Events";

        return $this->sendSms($customer->phone, $message);
    }

    /**
     * Notify customer about event reminder - 1 month before
     */
    public function notifyEventReminder1Month(Event $event): bool
    {
        try {
            $customer = $event->customer;

            if (!$customer || !$customer->phone) {
                Log::warning('Cannot send 1 month reminder SMS - missing customer or phone', [
                    'event_id' => $event->id
                ]);
                return false;
            }

            $phone = $this->formatPhoneNumber($customer->phone);
            $greeting = $this->getGreeting($customer->gender);
            $eventDate = Carbon::parse($event->event_date)->format('F d, Y');

            $message = "{$greeting} {$customer->customer_name}, your event '{$event->name}' is 1 MONTH away ({$eventDate})! ";
            $message .= "THIS MONTH: 1) Review your inclusions 2) Check payment balance 3) Finalize guest count 4) Contact us for any changes. - Michael Ho Events";

            return $this->sendSms($phone, $message);
        } catch (\Exception $e) {
            Log::error('Exception sending 1 month reminder SMS', [
                'event_id' => $event->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Notify customer about event reminder - 7 days before
     */
    public function notifyEventReminder7Days(Event $event): bool
    {
        try {
            $customer = $event->customer;

            if (!$customer || !$customer->phone) {
                Log::warning('Cannot send 7 days reminder SMS - missing customer or phone', [
                    'event_id' => $event->id
                ]);
                return false;
            }

            $phone = $this->formatPhoneNumber($customer->phone);
            $greeting = $this->getGreeting($customer->gender);
            $eventDate = Carbon::parse($event->event_date)->format('l, F d');

            $message = "{$greeting} {$customer->customer_name}, 1 WEEK to go! Your event '{$event->name}' is on {$eventDate}. ";
            $message .= "THIS WEEK: 1) Settle remaining balance 2) Confirm final guest count 3) Prepare personal items. We're getting everything ready! - Michael Ho Events";

            return $this->sendSms($phone, $message);
        } catch (\Exception $e) {
            Log::error('Exception sending 7 days reminder SMS', [
                'event_id' => $event->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Notify customer about event reminder - 3 days before
     */
    public function notifyEventReminder3Days(Event $event): bool
    {
        try {
            $customer = $event->customer;

            if (!$customer || !$customer->phone) {
                Log::warning('Cannot send 3 days reminder SMS - missing customer or phone', [
                    'event_id' => $event->id
                ]);
                return false;
            }

            $phone = $this->formatPhoneNumber($customer->phone);
            $greeting = $this->getGreeting($customer->gender);
            $eventDate = Carbon::parse($event->event_date)->format('l, F d');

            $message = "{$greeting} {$customer->customer_name}, only 3 DAYS left! Your event '{$event->name}' is on {$eventDate}. ";
            $message .= "FINAL PREP: 1) Ensure balance is paid 2) Get plenty of rest 3) Call 0917-306-2531 for any last-minute needs. See you soon! - Michael Ho Events";

            return $this->sendSms($phone, $message);
        } catch (\Exception $e) {
            Log::error('Exception sending 3 days reminder SMS', [
                'event_id' => $event->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send SMS notification when inclusion change request is approved
     */
    public function notifyChangeRequestApproved(InclusionChangeRequest $changeRequest): bool
    {
        try {
            $customer = $changeRequest->customer;
            $event = $changeRequest->event;

            if (!$customer || !$customer->phone) {
                Log::warning('Cannot send change request approved SMS - missing customer or phone', [
                    'change_request_id' => $changeRequest->id
                ]);
                return false;
            }

            $phone = $this->formatPhoneNumber($customer->phone);
            $greeting = $this->getGreeting($customer->gender);

            $message = "{$greeting} {$customer->customer_name}, great news! Your inclusion change request for '{$event->name}' has been APPROVED! ";
            $message .= "NEXT: Log in to view your updated inclusions and check your new balance. ";
            $message .= "No further action needed. - Michael Ho Events";

            return $this->sendSms($phone, $message);
        } catch (\Exception $e) {
            Log::error('Exception sending change request approved SMS', [
                'change_request_id' => $changeRequest->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send SMS notification when inclusion change request is rejected
     */
    public function notifyChangeRequestRejected(InclusionChangeRequest $changeRequest): bool
    {
        try {
            $customer = $changeRequest->customer;
            $event = $changeRequest->event;

            if (!$customer || !$customer->phone) {
                Log::warning('Cannot send change request rejected SMS - missing customer or phone', [
                    'change_request_id' => $changeRequest->id
                ]);
                return false;
            }

            $phone = $this->formatPhoneNumber($customer->phone);
            $greeting = $this->getGreeting($customer->gender);

            $message = "{$greeting} {$customer->customer_name}, your inclusion change request for '{$event->name}' was not approved. ";

            if ($changeRequest->admin_notes) {
                $message .= "Reason: {$changeRequest->admin_notes}. ";
            }

            $message .= "NEXT: Contact us to discuss alternatives or submit a different request. Your current inclusions remain unchanged. - Michael Ho Events";

            return $this->sendSms($phone, $message);
        } catch (\Exception $e) {
            Log::error('Exception sending change request rejected SMS', [
                'change_request_id' => $changeRequest->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Notify customer about event status change
     */
    public function notifyEventStatusChange(Event $event, string $newStatus): void
    {
        $customer = $event->customer;
        if (!$customer || !$customer->phone) {
            return;
        }

        $prefix = $this->getGreeting($customer->gender);
        $customerName = $customer->customer_name;
        $eventName = $event->name;

        switch ($newStatus) {
            case Event::STATUS_REJECTED:
                $message = "{$prefix} {$customerName}, we regret to inform you that your event booking \"{$eventName}\" has been declined. ";
                if ($event->rejection_reason) {
                    $message .= "Reason: {$event->rejection_reason}. ";
                }
                $message .= "NEXT: Contact us to discuss alternatives or submit a new booking. - Michael Ho Events";
                break;

            case Event::STATUS_MEETING:
                $message = "{$prefix} {$customerName}, great news! Your introductory payment for \"{$eventName}\" has been confirmed. ";
                $message .= "NEXT: We'll contact you within 24-48hrs to schedule your planning meeting. Prepare your ideas & questions! - Michael Ho Events";
                break;

            case Event::STATUS_SCHEDULED:
                $message = "{$prefix} {$customerName}, your event \"{$eventName}\" is now officially SCHEDULED! ";
                $message .= "NEXT: Relax! Our team is preparing everything. We'll keep you updated. - Michael Ho Events";
                break;

            case Event::STATUS_ONGOING:
                $message = "{$prefix} {$customerName}, your event \"{$eventName}\" is happening TODAY! ";
                $message .= "Our team is on-site. Enjoy your special day! Contact 0917-306-2531 if needed. - Michael Ho Events";
                break;

            case Event::STATUS_COMPLETED:
                $message = "{$prefix} {$customerName}, your event \"{$eventName}\" has been marked as completed. ";
                $message .= "Thank you for choosing Michael Ho Events! We hope you had an amazing celebration. - Michael Ho Events";
                break;

            default:
                $message = "{$prefix} {$customerName}, your event \"{$eventName}\" status has been updated to: {$newStatus}. ";
                $message .= "Log in to view details. - Michael Ho Events";
                break;
        }

        $this->sendSms($customer->phone, $message);
    }
}
