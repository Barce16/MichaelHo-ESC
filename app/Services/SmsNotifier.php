<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\Payment;
use App\Models\Event;
use App\Models\InclusionChangeRequest;


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

        $billing = $payment->billing;
        if ($billing->remaining_balance > 0) {
            $message .= "Remaining Balance: P" . number_format($billing->remaining_balance, 2) . "\n\n";
        } else {
            $message .= "Your event is now fully paid!\n\n";
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
        $message .= "Your payment of P" . number_format($payment->amount, 2) . " for '{$event->name}' needs attention.\n\n";
        $message .= "Reason: {$reason}\n\n";
        $message .= "Please resubmit your payment.\n\n";
        $message .= "View event: " . route('customer.events.show', $event);

        return $this->sendSms($customer->phone, $message);
    }

    /**
     * Notify customer about event status change
     */
    public function notifyEventStatusChange(Event $event, string $newStatus): bool
    {
        $customer = $event->customer;

        $statusMessages = [
            'scheduled' => "Your event '{$event->name}' has been scheduled for " . $event->event_date->format('M d, Y') . "!",
            'ongoing' => "Your event '{$event->name}' is now ongoing! Have a great celebration!",
            'completed' => "Your event '{$event->name}' has been completed. Thank you for choosing us!",
            'rejected' => "Unfortunately, your event request '{$event->name}' was not approved. Please contact us for details.",
        ];

        $message = $statusMessages[$newStatus] ?? "Your event '{$event->name}' status has been updated to: {$newStatus}";
        $message .= "\n\nView details: " . route('customer.events.show', $event);

        return $this->sendSms($customer->phone, $message);
    }

    /**
     * Get gender-appropriate greeting
     */
    protected function getGreeting(?string $gender): string
    {
        if (!$gender) {
            return 'Dear';
        }

        return match (strtolower($gender)) {
            'male' => 'Mr.',
            'female' => 'Ms.',
            default => 'Dear',
        };
    }

    public function notifyEventToday(Event $event): bool
    {
        try {
            $customer = $event->customer;

            if (!$customer || !$customer->contact_number) {
                Log::warning('Cannot send event today SMS - missing customer or contact number', [
                    'event_id' => $event->id
                ]);
                return false;
            }

            $phone = $this->formatPhoneNumber($customer->contact_number);
            $greeting = $this->getGreeting($customer->gender);

            $message = "{$greeting} {$customer->customer_name}, good morning! This is a reminder that your event '{$event->name}' is happening TODAY at " . ($event->venue ?? 'the venue') . ". Our team is ready to make your celebration memorable. For any concerns, contact us immediately. - Michael Ho Events";

            $response = Http::timeout(30)->post($this->apiUrl, [
                'token' => $this->apiToken,
                'phone' => $phone,
                'message' => $message,
            ]);

            if ($response->successful()) {
                Log::info('Event today SMS sent successfully', [
                    'event_id' => $event->id,
                    'customer_id' => $customer->id,
                    'phone' => $phone
                ]);
                return true;
            }

            Log::error('Failed to send event today SMS', [
                'event_id' => $event->id,
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('Exception sending event today SMS', [
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

            if (!$customer || !$customer->contact_number) {
                Log::warning('Cannot send change request approved SMS - missing customer or contact number', [
                    'change_request_id' => $changeRequest->id
                ]);
                return false;
            }

            $phone = $this->formatPhoneNumber($customer->contact_number);
            $greeting = $this->getGreeting($customer->gender);

            // Count changes
            $addedCount = count($changeRequest->added_inclusions ?? []);
            $removedCount = count($changeRequest->removed_inclusions ?? []);

            $message = "{$greeting} {$customer->customer_name}, your inclusion change request for event '{$event->name}' has been approved! ";

            if ($addedCount > 0) {
                $message .= "{$addedCount} inclusion(s) added. ";
            }
            if ($removedCount > 0) {
                $message .= "{$removedCount} inclusion(s) removed. ";
            }

            $message .= "Your billing has been updated. Check your account for details. - Michael Ho Events";

            $response = Http::timeout(30)->post($this->apiUrl, [
                'token' => $this->apiToken,
                'phone' => $phone,
                'message' => $message,
            ]);

            if ($response->successful()) {
                Log::info('Change request approved SMS sent successfully', [
                    'change_request_id' => $changeRequest->id,
                    'customer_id' => $customer->id,
                    'phone' => $phone
                ]);
                return true;
            }

            Log::error('Failed to send change request approved SMS', [
                'change_request_id' => $changeRequest->id,
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            return false;
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

            if (!$customer || !$customer->contact_number) {
                Log::warning('Cannot send change request rejected SMS - missing customer or contact number', [
                    'change_request_id' => $changeRequest->id
                ]);
                return false;
            }

            $phone = $this->formatPhoneNumber($customer->contact_number);
            $greeting = $this->getGreeting($customer->gender);

            $message = "{$greeting} {$customer->customer_name}, your inclusion change request for event '{$event->name}' has been rejected. ";

            if ($changeRequest->admin_notes) {
                $message .= "Reason: {$changeRequest->admin_notes}. ";
            }

            $message .= "Please contact us if you have questions. - Michael Ho Events";

            $response = Http::timeout(30)->post($this->apiUrl, [
                'token' => $this->apiToken,
                'phone' => $phone,
                'message' => $message,
            ]);

            if ($response->successful()) {
                Log::info('Change request rejected SMS sent successfully', [
                    'change_request_id' => $changeRequest->id,
                    'customer_id' => $customer->id,
                    'phone' => $phone
                ]);
                return true;
            }

            Log::error('Failed to send change request rejected SMS', [
                'change_request_id' => $changeRequest->id,
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('Exception sending change request rejected SMS', [
                'change_request_id' => $changeRequest->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
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

        $message = "{$greeting} {$customer->customer_name}, your event '{$event->name}' inclusions have been updated. ";
        $message .= "New total: P" . number_format($newTotal, 2) . " ({$changeText}). ";
        $message .= "Check your account for details. - Michael Ho Events";

        return $this->sendSms($customer->phone, $message);
    }

    /**
     * Notify customer of event progress update
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
}
