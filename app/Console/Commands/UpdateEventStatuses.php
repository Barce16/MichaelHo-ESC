<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateEventStatuses extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'events:update-statuses';

    /**
     * The console command description.
     */
    protected $description = 'Update event statuses based on event dates (ongoing/completed)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating event statuses...');

        // Update scheduled events to ongoing if event date is today
        $ongoingCount = Event::where('status', Event::STATUS_SCHEDULED)
            ->whereDate('event_date', today())
            ->update(['status' => Event::STATUS_ONGOING]);

        if ($ongoingCount > 0) {
            $this->info("✓ {$ongoingCount} event(s) updated to ONGOING");
            Log::info("Updated {$ongoingCount} events to ongoing status");
        }

        // Update ongoing events to completed if event date is in the past
        $completedCount = Event::where('status', Event::STATUS_ONGOING)
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

        return Command::SUCCESS;
    }
}
