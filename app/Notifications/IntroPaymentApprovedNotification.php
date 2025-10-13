<?php

namespace App\Notifications;

use App\Models\Event;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class IntroPaymentApprovedNotification extends Notification
{
    use Queueable;

    protected $event;
    protected $payment;

    public function __construct(Event $event, Payment $payment)
    {
        $this->event = $event;
        $this->payment = $payment;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Introductory Payment Approved - Next Steps')
            ->view('emails.intro-payment-approved', [
                'event' => $this->event,
                'payment' => $this->payment,
                'customer' => $this->event->customer,
            ]);
    }
}
