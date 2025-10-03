<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EventRejectedNotification extends Notification
{
    use Queueable;

    protected $event;
    protected $rejectionReason;

    public function __construct(Event $event, $rejectionReason = null)
    {
        $this->event = $event;
        $this->rejectionReason = $rejectionReason;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Update on Your Event Booking Request')
            ->view('emails.event-rejected', [
                'event' => $this->event,
                'rejectionReason' => $this->rejectionReason,
                'customer' => $this->event->customer,
            ]);
    }
}
