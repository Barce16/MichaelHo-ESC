<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;
use App\Models\Event;

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
     * Notify customer that their event was approved
     */
    public function notifyEventApproved(Event $event, string $username, string $password): bool
    {
        $customer = $event->customer;
        $message = "Good news {$customer->customer_name}! Your event '{$event->name}' has been approved.\n\n";
        $message .= "Login Details:\n";
        $message .= "Username: {$username}\n";
        $message .= "Password: {$password}\n\n";
        $message .= "Next Step: Pay P15,000 introductory payment.\n\n";
        $message .= "Login at: " . url('/login');

        return $this->sendSms($customer->contact_number, $message);
    }

    /**
     * Notify customer that their payment was confirmed
     */
    public function notifyPaymentConfirmed(Payment $payment): bool
    {
        $event = $payment->billing->event;
        $customer = $event->customer;

        $paymentType = match ($payment->payment_type) {
            'introductory' => 'introductory payment',
            'downpayment' => 'downpayment',
            'balance' => 'balance payment',
            default => 'payment'
        };

        $message = "Payment Confirmed!\n\n";
        $message .= "Hello {$customer->customer_name},\n\n";
        $message .= "Your {$paymentType} of P" . number_format($payment->amount, 2) . " for '{$event->name}' has been approved.\n\n";

        $billing = $payment->billing;
        if ($billing->remaining_balance > 0) {
            $message .= "Remaining Balance: P" . number_format($billing->remaining_balance, 2) . "\n\n";
        } else {
            $message .= "Your event is now fully paid!\n\n";
        }

        $message .= "View details: " . route('customer.events.show', $event);

        return $this->sendSms($customer->contact_number, $message);
    }

    /**
     * Notify customer that their payment was rejected
     */
    public function notifyPaymentRejected(Payment $payment, string $reason): bool
    {
        $event = $payment->billing->event;
        $customer = $event->customer;

        $message = "Payment Issue\n\n";
        $message .= "Hello {$customer->customer_name},\n\n";
        $message .= "Your payment of P" . number_format($payment->amount, 2) . " for '{$event->name}' needs attention.\n\n";
        $message .= "Reason: {$reason}\n\n";
        $message .= "Please resubmit your payment.\n\n";
        $message .= "View event: " . route('customer.events.show', $event);

        return $this->sendSms($customer->contact_number, $message);
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

        return $this->sendSms($customer->contact_number, $message);
    }
}
