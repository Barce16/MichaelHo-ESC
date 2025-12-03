<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Run event status updates and reminders once daily at 7:00 AM
        // This handles:
        // - 1 month reminders (30 days before)
        // - 7 days reminders
        // - 3 days reminders
        // - Event today notifications
        // - Status updates (scheduled → ongoing → completed)
        $schedule->command('events:update-statuses')
            ->dailyAt('07:00')
            ->timezone('Asia/Manila')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/event-reminders.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
