<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\EventProgress;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventProgressNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $progress;

    public function __construct(Event $event, EventProgress $progress)
    {
        $this->event = $event;
        $this->progress = $progress;
    }

    public function build()
    {
        return $this->subject('Event Update: ' . $this->event->name)
            ->view('emails.event-progress');
    }
}
