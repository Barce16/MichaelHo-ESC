<?php

namespace App\Mail;

use App\Models\EventSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class StaffScheduleAssignmentMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public EventSchedule $schedule;
    public string $staffName;
    public string $inclusionName;
    public string $eventName;
    public string $scheduledDate;
    public string $scheduledTime;
    public ?string $venue;
    public ?string $remarks;
    public string $actionUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(EventSchedule $schedule)
    {
        $this->schedule = $schedule;
        $this->staffName = $schedule->staff->name ?? 'Staff';
        $this->inclusionName = $schedule->inclusion->name ?? 'Task';
        $this->eventName = $schedule->event->name ?? 'Event';
        $this->scheduledDate = $schedule->scheduled_date->format('F d, Y');
        $this->scheduledTime = $schedule->scheduled_time
            ? Carbon::parse($schedule->scheduled_time)->format('g:i A')
            : 'To be announced';
        $this->venue = $schedule->venue;
        $this->remarks = $schedule->remarks;
        $this->actionUrl = url('/staff/schedules');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📋 New Schedule Assignment - ' . $this->inclusionName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.staff-schedule-assignment',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
