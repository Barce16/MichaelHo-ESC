<?php

namespace App\Notifications;

use App\Models\Event;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentRejectedNotification extends Notification
{
    use Queueable;

    protected $event;
    protected $payment;
    protected $reason;

    public function __construct(Event $event, Payment $payment, $reason = null)
    {
        $this->event = $event;
        $this->payment = $payment;
        $this->reason = $reason;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $paymentTypeLabel = $this->payment->payment_type === 'introductory'
            ? 'Introductory Payment'
            : 'Downpayment';

        return (new MailMessage)
            ->subject('Payment Verification Issue - Action Required')
            ->view('emails.payment-rejected', [
                'event' => $this->event,
                'payment' => $this->payment,
                'customer' => $this->event->customer,
                'reason' => $this->reason,
                'paymentTypeLabel' => $paymentTypeLabel,
            ]);
    }
}
