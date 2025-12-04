<?php

namespace App\Notifications;

use App\Models\EventSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class StaffScheduleAssignmentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected EventSchedule $schedule;

    /**
     * Create a new notification instance.
     */
    public function __construct(EventSchedule $schedule)
    {
        $this->schedule = $schedule;
    }

    /**
     * Get the notification's delivery channels.
     * Note: Email is sent separately via StaffScheduleAssignmentMail
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // In-app notification only
    }

    /**
     * Get the array representation of the notification (for database/in-app).
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $schedule = $this->schedule;
        $event = $schedule->event;
        $inclusion = $schedule->inclusion;

        $date = $schedule->scheduled_date->format('M d, Y');
        $time = $schedule->scheduled_time
            ? Carbon::parse($schedule->scheduled_time)->format('g:i A')
            : 'TBA';

        return [
            'type' => 'schedule_assignment',
            'title' => 'New Schedule Assignment',
            'message' => "You've been assigned to {$inclusion->name} for {$event->name} on {$date} at {$time}.",
            'schedule_id' => $schedule->id,
            'event_id' => $event->id,
            'event_name' => $event->name,
            'inclusion_id' => $inclusion->id,
            'inclusion_name' => $inclusion->name,
            'scheduled_date' => $schedule->scheduled_date->toDateString(),
            'scheduled_time' => $schedule->scheduled_time,
            'venue' => $schedule->venue,
            'remarks' => $schedule->remarks,
            'icon' => 'calendar',
            'color' => 'amber',
            'action_url' => '/staff/schedules/' . $schedule->id,
        ];
    }
}
