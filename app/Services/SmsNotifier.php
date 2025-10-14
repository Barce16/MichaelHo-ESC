<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;
use App\Models\Event;

class SmsNotifier
{
    protected $client;
    protected $apiKey;
    protected $phoneNumber;

    public function __construct()
    {
        $this->apiKey = config('services.httpsms.api_key');
        $this->phoneNumber = config('services.httpsms.phone_number');

        // httpSMS uses a different URL structure - NO /v1 in base
        $this->client = new Client([
            'base_uri' => 'https://api.httpsms.com',
            'timeout' => 30,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'x-api-key' => $this->apiKey,
            ]
        ]);
    }

    /**
     * Send SMS notification using httpSMS API
     */
    public function sendSms(string $to, string $message): bool
    {
        try {
            // Format phone number to international format
            $formattedPhone = $this->formatPhoneNumber($to);

            Log::info('Attempting to send SMS via httpSMS', [
                'to' => $formattedPhone,
                'from' => $this->phoneNumber,
                'message_length' => strlen($message)
            ]);

            $response = $this->client->post('/v1/messages/send', [
                'json' => [
                    'from' => $this->phoneNumber,
                    'to' => $formattedPhone,
                    'content' => $message,
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            Log::info('httpSMS sent successfully', [
                'to' => $formattedPhone,
                'response' => $result
            ]);

            return true;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $statusCode = $response ? $response->getStatusCode() : 'N/A';
            $responseBody = $response ? $response->getBody()->getContents() : 'No response body';

            Log::error('httpSMS API Client Error', [
                'to' => $to,
                'status_code' => $statusCode,
                'error' => $e->getMessage(),
                'response_body' => $responseBody
            ]);

            return false;
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            $response = $e->getResponse();
            $responseBody = $response ? $response->getBody()->getContents() : 'No response body';

            Log::error('httpSMS API Server Error', [
                'to' => $to,
                'status_code' => $response ? $response->getStatusCode() : 'N/A',
                'error' => $e->getMessage(),
                'response_body' => $responseBody
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('Failed to send httpSMS - General Error', [
                'to' => $to,
                'error' => $e->getMessage(),
                'class' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Format phone number to international format (+639XXXXXXXXX)
     */
    protected function formatPhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // If starts with 0, replace with +63 (Philippines)
        if (substr($phone, 0, 1) === '0') {
            $phone = '+63' . substr($phone, 1);
        }

        // If starts with 63, add +
        if (substr($phone, 0, 2) === '63') {
            $phone = '+' . $phone;
        }

        // If doesn't start with +, add it
        if (substr($phone, 0, 1) !== '+') {
            $phone = '+' . $phone;
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
