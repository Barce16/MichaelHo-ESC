<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Services\SmsNotifier;
use App\Services\NotificationService;
use App\Notifications\EventTodayNotification;
use App\Notifications\EventReminder3DaysNotification;
use App\Notifications\EventReminder7DaysNotification;
use App\Notifications\EventReminder1MonthNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateEventStatuses extends Command
{
    protected $signature = 'events:update-statuses';
    protected $description = 'Update event statuses based on event dates and send reminder notifications';

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
        $this->info('Starting event status updates and reminders...');
        $this->newLine();

        // Track notification counts
        $notificationCounts = [
            '1_month' => 0,
            '7_days' => 0,
            '3_days' => 0,
            'today' => 0,
        ];

        // =============================================
        // 1. Send 1 MONTH (30 days) reminders
        // =============================================
        $this->info('📅 Checking for 1 month reminders...');
        $events1Month = Event::where('status', Event::STATUS_SCHEDULED)
            ->whereDate('event_date', today()->addDays(30))
            ->with(['customer.user', 'package'])
            ->get();

        foreach ($events1Month as $event) {
            $this->sendReminder($event, '1_month', $notificationCounts);
        }
        $this->info("   Found: {$events1Month->count()} event(s)");
        $this->newLine();

        // =============================================
        // 2. Send 7 DAYS reminders
        // =============================================
        $this->info('📅 Checking for 7 days reminders...');
        $events7Days = Event::where('status', Event::STATUS_SCHEDULED)
            ->whereDate('event_date', today()->addDays(7))
            ->with(['customer.user', 'package'])
            ->get();

        foreach ($events7Days as $event) {
            $this->sendReminder($event, '7_days', $notificationCounts);
        }
        $this->info("   Found: {$events7Days->count()} event(s)");
        $this->newLine();

        // =============================================
        // 3. Send 3 DAYS reminders
        // =============================================
        $this->info('📅 Checking for 3 days reminders...');
        $events3Days = Event::where('status', Event::STATUS_SCHEDULED)
            ->whereDate('event_date', today()->addDays(3))
            ->with(['customer.user', 'package'])
            ->get();

        foreach ($events3Days as $event) {
            $this->sendReminder($event, '3_days', $notificationCounts);
        }
        $this->info("   Found: {$events3Days->count()} event(s)");
        $this->newLine();

        // =============================================
        // 4. Handle TODAY events (update status + notify)
        // =============================================
        $this->info('🎉 Checking for events happening TODAY...');

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
            $this->info("   ✓ {$ongoingCount} event(s) updated to ONGOING");
            Log::info("Updated {$ongoingCount} events to ongoing status");

            // Send notifications for events happening today
            foreach ($eventsToday as $event) {
                $event->refresh();
                $this->sendReminder($event, 'today', $notificationCounts);
            }
        } else {
            $this->info("   No events today");
        }
        $this->newLine();

        // =============================================
        // 5. Update PAST events to completed
        // =============================================
        $this->info('📋 Checking for past events to complete...');
        $completedCount = Event::whereIn('status', [Event::STATUS_SCHEDULED, Event::STATUS_ONGOING])
            ->whereDate('event_date', '<', today())
            ->update(['status' => Event::STATUS_COMPLETED]);

        if ($completedCount > 0) {
            $this->info("   ✓ {$completedCount} event(s) updated to COMPLETED");
            Log::info("Updated {$completedCount} events to completed status");
        } else {
            $this->info("   No events to complete");
        }
        $this->newLine();

        // =============================================
        // Summary
        // =============================================
        $this->info('═══════════════════════════════════════');
        $this->info('📊 SUMMARY');
        $this->info('═══════════════════════════════════════');
        $this->info("   1 Month Reminders:  {$notificationCounts['1_month']}");
        $this->info("   7 Days Reminders:   {$notificationCounts['7_days']}");
        $this->info("   3 Days Reminders:   {$notificationCounts['3_days']}");
        $this->info("   Event Today:        {$notificationCounts['today']}");
        $this->info("   Updated to Ongoing: {$ongoingCount}");
        $this->info("   Updated to Complete: {$completedCount}");
        $this->info('═══════════════════════════════════════');
        $this->newLine();

        // Log summary
        Log::info('Event status update and reminders summary', [
            'reminders_1_month' => $notificationCounts['1_month'],
            'reminders_7_days' => $notificationCounts['7_days'],
            'reminders_3_days' => $notificationCounts['3_days'],
            'reminders_today' => $notificationCounts['today'],
            'ongoing_count' => $ongoingCount,
            'completed_count' => $completedCount,
            'timestamp' => now()->toDateTimeString()
        ]);

        $this->info('✅ Event status update complete!');

        return Command::SUCCESS;
    }

    /**
     * Send reminder notifications (email, SMS, in-app)
     */
    protected function sendReminder(Event $event, string $type, array &$counts): void
    {
        $typeLabels = [
            '1_month' => '1 Month',
            '7_days' => '7 Days',
            '3_days' => '3 Days',
            'today' => 'Today',
        ];

        $label = $typeLabels[$type] ?? $type;

        if (!$event->customer || !$event->customer->user) {
            $this->warn("   ⚠ Skipping {$event->name} - no customer user found");
            return;
        }

        $this->line("   → Sending {$label} reminder for: {$event->name}");

        // Send Email
        try {
            $notification = match ($type) {
                '1_month' => new EventReminder1MonthNotification($event),
                '7_days' => new EventReminder7DaysNotification($event),
                '3_days' => new EventReminder3DaysNotification($event),
                'today' => new EventTodayNotification($event),
            };

            $event->customer->user->notify($notification);
            $this->info("     ✓ Email sent to {$event->customer->user->email}");
        } catch (\Exception $e) {
            Log::error("Failed to send {$type} reminder email", [
                'event_id' => $event->id,
                'error' => $e->getMessage()
            ]);
            $this->error("     ✗ Failed to send email: {$e->getMessage()}");
        }

        // Send SMS
        try {
            $smsSent = match ($type) {
                '1_month' => $this->smsNotifier->notifyEventReminder1Month($event),
                '7_days' => $this->smsNotifier->notifyEventReminder7Days($event),
                '3_days' => $this->smsNotifier->notifyEventReminder3Days($event),
                'today' => $this->smsNotifier->notifyEventToday($event),
            };

            if ($smsSent) {
                $this->info("     ✓ SMS sent");
            } else {
                $this->warn("     ⚠ SMS sending failed");
            }
        } catch (\Exception $e) {
            Log::error("Failed to send {$type} reminder SMS", [
                'event_id' => $event->id,
                'error' => $e->getMessage()
            ]);
            $this->error("     ✗ Failed to send SMS: {$e->getMessage()}");
        }

        // Send In-App Notification
        try {
            $message = match ($type) {
                '1_month' => "📅 Your event '{$event->name}' is 1 month away! Time to finalize your plans.",
                '7_days' => "⏰ Just 1 week until '{$event->name}'! We're getting everything ready.",
                '3_days' => "⚡ Only 3 days until '{$event->name}'! The excitement is building!",
                'today' => "🎉 Your event '{$event->name}' is happening today! Our team is ready to make it memorable.",
            };

            $this->notificationService->notifyCustomerEventStatus(
                $event,
                $event->status,
                $event->status,
                $message
            );
            $this->info("     ✓ In-app notification sent");

            $counts[$type]++;
        } catch (\Exception $e) {
            Log::error("Failed to send {$type} in-app notification", [
                'event_id' => $event->id,
                'error' => $e->getMessage()
            ]);
            $this->error("     ✗ Failed to send in-app notification: {$e->getMessage()}");
        }
    }
}
