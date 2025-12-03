<?php

namespace App\Mail;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventScheduleNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $schedules;
    public $action;

    /**
     * Create a new message instance.
     */
    public function __construct(Event $event, array $schedules, string $action = 'updated')
    {
        $this->event = $event;
        $this->schedules = $schedules;
        $this->action = $action;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $actionText = match ($this->action) {
            'created' => 'New Schedule',
            'updated' => 'Schedule Updated',
            default => 'Schedule Update'
        };

        return new Envelope(
            subject: "📅 {$actionText} - {$this->event->name}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.event-schedule-notification',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
