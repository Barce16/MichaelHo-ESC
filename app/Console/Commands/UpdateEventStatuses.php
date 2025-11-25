<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Services\SmsNotifier;
use App\Services\NotificationService;
use App\Notifications\EventTodayNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateEventStatuses extends Command
{
    protected $signature = 'events:update-statuses';
    protected $description = 'Update event statuses based on event dates and send notifications';

    protected $smsNotifier;
    protected $notificationService;

    public function __construct(SmsNotifier $smsNotifier, NotificationService $notificationService)
    {
        parent::__construct();
        $this->smsNotifier = $smsNotifier;
        $this->notificationService = $notificationService;
    }

    public function handle()
    {
        $this->info('Updating event statuses...');

        $notificationsSent = 0;

        // Get scheduled events that are TODAY (before updating)
        $eventsToday = Event::where('status', Event::STATUS_SCHEDULED)
            ->whereDate('event_date', today())
            ->with(['customer.user', 'package'])
            ->get();

        // Update scheduled events to ongoing if event date is TODAY
        $ongoingCount = Event::where('status', Event::STATUS_SCHEDULED)
            ->whereDate('event_date', today())
            ->update(['status' => Event::STATUS_ONGOING]);

        if ($ongoingCount > 0) {
            $this->info("✓ {$ongoingCount} event(s) updated to ONGOING");
            Log::info("Updated {$ongoingCount} events to ongoing status");

            // Send notifications for events happening today
            foreach ($eventsToday as $event) {
                // Refresh to get updated status
                $event->refresh();

                if ($event->customer && $event->customer->user) {
                    $this->info("Sending notifications for event: {$event->name}");

                    try {
                        // Send email notification
                        $event->customer->user->notify(new EventTodayNotification($event));
                        $this->info("  ✓ Email sent to {$event->customer->user->email}");
                    } catch (\Exception $e) {
                        Log::error('Failed to send event today email', [
                            'event_id' => $event->id,
                            'error' => $e->getMessage()
                        ]);
                        $this->error("  ✗ Failed to send email: {$e->getMessage()}");
                    }

                    try {
                        // Send SMS notification
                        $smsSent = $this->smsNotifier->notifyEventToday($event);
                        if ($smsSent) {
                            $this->info("  ✓ SMS sent to {$event->customer->contact_number}");
                        } else {
                            $this->warn("  ⚠ SMS sending failed for {$event->customer->contact_number}");
                        }
                    } catch (\Exception $e) {
                        Log::error('Failed to send event today SMS', [
                            'event_id' => $event->id,
                            'error' => $e->getMessage()
                        ]);
                        $this->error("  ✗ Failed to send SMS: {$e->getMessage()}");
                    }

                    try {
                        // Send in-app notification
                        $this->notificationService->notifyCustomerEventStatus(
                            $event,
                            Event::STATUS_SCHEDULED,
                            Event::STATUS_ONGOING,
                            "🎉 Your event '{$event->name}' is happening today! Our team is ready to make it memorable."
                        );
                        $this->info("  ✓ In-app notification sent");

                        $notificationsSent++;
                    } catch (\Exception $e) {
                        Log::error('Failed to send in-app notification', [
                            'event_id' => $event->id,
                            'error' => $e->getMessage()
                        ]);
                        $this->error("  ✗ Failed to send in-app notification: {$e->getMessage()}");
                    }
                } else {
                    $this->warn("  ⚠ Skipping notifications - no customer user found for event #{$event->id}");
                }
            }

            if ($notificationsSent > 0) {
                $this->info("📧 Total notifications sent: {$notificationsSent}");
            }
        }

        // Update scheduled OR ongoing events to completed if event date is in the PAST
        $completedCount = Event::whereIn('status', [Event::STATUS_SCHEDULED, Event::STATUS_ONGOING])
            ->whereDate('event_date', '<', today())
            ->update(['status' => Event::STATUS_COMPLETED]);

        if ($completedCount > 0) {
            $this->info("✓ {$completedCount} event(s) updated to COMPLETED");
            Log::info("Updated {$completedCount} events to completed status");
        }

        if ($ongoingCount === 0 && $completedCount === 0) {
            $this->info('No events needed status updates.');
        }

        $this->info('Event status update complete!');

        // Summary log
        Log::info('Event status update summary', [
            'ongoing_count' => $ongoingCount,
            'completed_count' => $completedCount,
            'notifications_sent' => $notificationsSent,
            'timestamp' => now()->toDateTimeString()
        ]);

        return Command::SUCCESS;
    }
}
