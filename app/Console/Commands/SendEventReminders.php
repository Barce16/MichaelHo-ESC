<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Services\EventSmsNotifier;
use Illuminate\Console\Command;

class SendEventReminders extends Command
{
    protected $signature = 'events:send-reminders';
    protected $description = 'Send SMS reminders for upcoming events';

    public function handle(EventSmsNotifier $notifier)
    {
        // Send reminders for events happening in 3 days
        $events = Event::where('status', 'confirmed')
            ->whereDate('event_date', now()->addDays(3)->toDateString())
            ->get();

        foreach ($events as $event) {
            $notifier->notifyEventReminder($event, 3);
            $this->info("Sent reminder for event: {$event->name}");
        }

        // Send reminders for events happening tomorrow
        $events = Event::where('status', 'confirmed')
            ->whereDate('event_date', now()->addDay()->toDateString())
            ->get();

        foreach ($events as $event) {
            $notifier->notifyEventReminder($event, 1);
            $this->info("Sent reminder for event: {$event->name}");
        }

        $this->info('Event reminders sent successfully!');
    }
}
