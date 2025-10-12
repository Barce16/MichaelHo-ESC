<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Payment;

class EventSmsNotifier
{
    protected $sms;

    public function __construct(SmsService $sms)
    {
        $this->sms = $sms;
    }

    /**
     * Send SMS when event is approved
     */
    public function notifyEventApproved(Event $event): bool
    {
        $customer = $event->customer;
        $phone = $customer->phone ?? $customer->user->phone ?? null;

        if (!$phone) {
            return false;
        }

        $message = "Hello {$customer->customer_name}! Your event '{$event->name}' on " .
            $event->event_date->format('M d, Y') .
            " has been APPROVED by Michael Ho Events! We'll contact you soon with next steps. " .
            "View details: " . route('customer.events.show', $event);

        return $this->sms->send($phone, $message);
    }

    /**
     * Send SMS when event is rejected
     */
    public function notifyEventRejected(Event $event, ?string $reason = null): bool
    {
        $customer = $event->customer;
        $phone = $customer->phone ?? $customer->user->phone ?? null;

        if (!$phone) {
            return false;
        }

        $message = "Hello {$customer->customer_name}. Unfortunately, your event '{$event->name}' on " .
            $event->event_date->format('M d, Y') .
            " cannot be accommodated.";

        if ($reason) {
            $message .= " Reason: {$reason}";
        }

        $message .= " Please contact us for alternative options.";

        return $this->sms->send($phone, $message);
    }

    /**
     * Send SMS when payment is required
     */
    public function notifyPaymentRequired(Payment $payment): bool
    {
        $event = $payment->event;
        $customer = $event->customer;
        $phone = $customer->phone ?? $customer->user->phone ?? null;

        if (!$phone) {
            return false;
        }

        $message = "Payment request for '{$event->name}': " .
            "₱" . number_format($payment->amount, 2) .
            " ({$payment->description}). " .
            "Due: " . $payment->due_date->format('M d, Y') . ". " .
            "Pay now: " . route('customer.payments.show', $payment);

        return $this->sms->send($phone, $message);
    }

    /**
     * Send SMS when payment is confirmed
     */
    public function notifyPaymentConfirmed(Payment $payment): bool
    {
        $event = $payment->event;
        $customer = $event->customer;
        $phone = $customer->phone ?? $customer->user->phone ?? null;

        if (!$phone) {
            return false;
        }

        $message = "Payment confirmed! ₱" . number_format($payment->amount, 2) .
            " received for '{$event->name}'. " .
            "Thank you for your payment. " .
            "Balance: ₱" . number_format($event->remaining_balance ?? 0, 2);

        return $this->sms->send($phone, $message);
    }

    /**
     * Send SMS when payment is rejected
     */
    public function notifyPaymentRejected(Payment $payment, ?string $reason = null): bool
    {
        $event = $payment->event;
        $customer = $event->customer;
        $phone = $customer->phone ?? $customer->user->phone ?? null;

        if (!$phone) {
            return false;
        }

        $message = "Payment verification failed for '{$event->name}'. ";

        if ($reason) {
            $message .= "Reason: {$reason}. ";
        }

        $message .= "Please resubmit your payment proof or contact us for assistance.";

        return $this->sms->send($phone, $message);
    }

    /**
     * Send reminder SMS for upcoming event
     */
    public function notifyEventReminder(Event $event, int $daysUntil): bool
    {
        $customer = $event->customer;
        $phone = $customer->phone ?? $customer->user->phone ?? null;

        if (!$phone) {
            return false;
        }

        $message = "Reminder: Your event '{$event->name}' is in {$daysUntil} day" .
            ($daysUntil > 1 ? 's' : '') . "! " .
            "Date: " . $event->event_date->format('M d, Y') . ". " .
            "Venue: " . ($event->venue ?? 'TBD') . ". " .
            "Michael Ho Events looks forward to making your event special!";

        return $this->sms->send($phone, $message);
    }

    /**
     * Send SMS when event status changes
     */
    public function notifyStatusChange(Event $event, string $oldStatus, string $newStatus): bool
    {
        $customer = $event->customer;
        $phone = $customer->phone ?? $customer->user->phone ?? null;

        if (!$phone) {
            return false;
        }

        $statusMessages = [
            'requested' => 'submitted for review',
            'approved' => 'approved',
            'confirmed' => 'confirmed',
            'completed' => 'completed',
            'cancelled' => 'cancelled',
        ];

        $statusText = $statusMessages[$newStatus] ?? $newStatus;

        $message = "Status update for '{$event->name}': " .
            "Your event is now {$statusText}. " .
            "View details: " . route('customer.events.show', $event);

        return $this->sms->send($phone, $message);
    }
}
