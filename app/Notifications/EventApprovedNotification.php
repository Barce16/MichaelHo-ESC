<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EventApprovedNotification extends Notification
{
    use Queueable;

    protected $event;
    protected $username;
    protected $password;
    protected $billing;

    public function __construct(Event $event, string $username, string $password, $billing = null)
    {
        $this->event = $event;
        $this->username = $username;
        $this->password = $password;
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
            ->view('emails.event-approved', [
                'event' => $this->event,
                'username' => $this->username,
                'password' => $this->password,
                'downpayment' => $downpayment,
                'total' => $total,
                'customer' => $this->event->customer,
            ]);
    }
}
