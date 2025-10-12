<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AvailabilityController extends Controller
{
    public function getMonthAvailability(Request $request)
    {
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        // Get all events in this month (excluding rejected)
        $events = Event::whereBetween('event_date', [$startDate, $endDate])
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->selectRaw('DATE(event_date) as date, COUNT(*) as count')
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $availability = [];
        $maxEvents = config('events.max_events_per_day', 2);

        // Build availability data for each day
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dateString = $currentDate->format('Y-m-d');
            $eventCount = $events->get($dateString)->count ?? 0;

            $availability[$dateString] = [
                'count' => $eventCount,
                'available' => $maxEvents - $eventCount,
                'status' => $this->getDateStatus($eventCount, $maxEvents, $currentDate),
            ];

            $currentDate->addDay();
        }

        return response()->json([
            'availability' => $availability,
            'max_events' => $maxEvents,
        ]);
    }

    private function getDateStatus(int $eventCount, int $maxEvents, Carbon $date): string
    {
        // Past dates
        if ($date->isPast() && !$date->isToday()) {
            return 'past';
        }

        // Fully booked
        if ($eventCount >= $maxEvents) {
            return 'full';
        }

        // Partially booked
        if ($eventCount > 0) {
            return 'partial';
        }

        // Available
        return 'available';
    }
}
