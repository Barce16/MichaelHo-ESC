<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $client;
    protected $from;
    protected $enabled;

    public function __construct()
    {
        $this->enabled = config('services.twilio.enabled', false);

        if ($this->enabled) {
            $sid = config('services.twilio.sid');
            $token = config('services.twilio.token');
            $this->from = config('services.twilio.from');

            $this->client = new Client($sid, $token);
        }
    }

    /**
     * Send SMS to a phone number
     */
    public function send(string $to, string $message): bool
    {
        if (!$this->enabled) {
            Log::info('SMS disabled. Would have sent:', [
                'to' => $to,
                'message' => $message,
            ]);
            return true;
        }

        try {
            // Clean phone number (remove spaces, dashes, etc.)
            $to = $this->cleanPhoneNumber($to);

            $this->client->messages->create($to, [
                'from' => $this->from,
                'body' => $message,
            ]);

            Log::info('SMS sent successfully', [
                'to' => $to,
                'message_preview' => substr($message, 0, 50) . '...',
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send SMS', [
                'to' => $to,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Clean and format phone number for Twilio
     * Assumes Philippine numbers, adjust as needed
     */
    protected function cleanPhoneNumber(string $phone): string
    {
        // Remove all non-digit characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // If it starts with 0, replace with +63 (Philippines)
        if (substr($phone, 0, 1) === '0') {
            $phone = '+63' . substr($phone, 1);
        }
        // If it doesn't have country code, add +63
        elseif (substr($phone, 0, 1) !== '+' && strlen($phone) === 10) {
            $phone = '+63' . $phone;
        }
        // If it starts with 63, add +
        elseif (substr($phone, 0, 2) === '63') {
            $phone = '+' . $phone;
        }

        return $phone;
    }

    /**
     * Send bulk SMS to multiple recipients
     */
    public function sendBulk(array $recipients, string $message): array
    {
        $results = [];

        foreach ($recipients as $recipient) {
            $results[$recipient] = $this->send($recipient, $message);
        }

        return $results;
    }
}
