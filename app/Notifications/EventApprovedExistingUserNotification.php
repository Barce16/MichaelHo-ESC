<?php

namespace App\Notifications;

use App\Models\Event;
use App\Models\Billing;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EventApprovedExistingUserNotification extends Notification
{
    use Queueable;

    protected $event;
    protected $billing;

    public function __construct(Event $event, Billing $billing)
    {
        $this->event = $event;
        $this->billing = $billing;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $downpayment = $this->billing ? number_format($this->billing->downpayment_amount, 2) : '0.00';
        $total = $this->billing ? number_format($this->billing->total_amount, 2) : '0.00';

        return (new MailMessage)
            ->subject('Your Event Booking Has Been Approved!')
            ->view('emails.event-approved-existing', [
                'event' => $this->event,
                'downpayment' => $downpayment,
                'total' => $total,
                'customer' => $this->event->customer,
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'event_id' => $this->event->id,
            'event_name' => $this->event->name,
        ];
    }
}
