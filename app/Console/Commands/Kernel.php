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
        // Run every minute to catch status changes immediately
        $schedule->command('events:update-statuses')->everyMinute();

        // OR run every hour if you prefer less frequent checks
        // $schedule->command('events:update-statuses')->hourly();

        // OR run at specific times (e.g., every day at midnight and noon)
        // $schedule->command('events:update-statuses')->twiceDaily(0, 12);
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
