<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EventScheduleController extends Controller
{
    /**
     * Save/Update all schedules for an event (called from modal)
     */
    public function saveAll(Request $request, Event $event)
    {
        try {
            $validated = $request->validate([
                'schedules' => ['required', 'array'],
                'schedules.*.inclusion_id' => ['required', 'exists:inclusions,id'],
                'schedules.*.scheduled_date' => ['nullable', 'date'],
                'schedules.*.scheduled_time' => ['nullable'],
                'schedules.*.remarks' => ['nullable', 'string', 'max:500'],
            ]);

            $savedCount = 0;

            foreach ($validated['schedules'] as $scheduleData) {
                // Skip if no date is set
                if (empty($scheduleData['scheduled_date'])) {
                    // If schedule exists but date is cleared, delete it
                    EventSchedule::where('event_id', $event->id)
                        ->where('inclusion_id', $scheduleData['inclusion_id'])
                        ->delete();
                    continue;
                }

                EventSchedule::updateOrCreate(
                    [
                        'event_id' => $event->id,
                        'inclusion_id' => $scheduleData['inclusion_id'],
                    ],
                    [
                        'scheduled_date' => $scheduleData['scheduled_date'],
                        'scheduled_time' => !empty($scheduleData['scheduled_time']) ? $scheduleData['scheduled_time'] : null,
                        'remarks' => !empty($scheduleData['remarks']) ? $scheduleData['remarks'] : null,
                        'created_by' => Auth::id(),
                    ]
                );
                $savedCount++;
            }

            return back()->with('success', "Schedules saved successfully.");
        } catch (\Exception $e) {
            Log::error('Failed to save schedules', [
                'event_id' => $event->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to save schedules: ' . $e->getMessage());
        }
    }

    /**
     * Store a new schedule (legacy method - keep for compatibility)
     */
    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'inclusion_id' => ['required', 'exists:inclusions,id'],
            'scheduled_date' => ['required', 'date'],
            'scheduled_time' => ['nullable'],
            'remarks' => ['nullable', 'string', 'max:1000'],
        ]);

        // Check if schedule already exists
        $exists = EventSchedule::where('event_id', $event->id)
            ->where('inclusion_id', $validated['inclusion_id'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'A schedule already exists for this inclusion.');
        }

        EventSchedule::create([
            'event_id' => $event->id,
            'inclusion_id' => $validated['inclusion_id'],
            'scheduled_date' => $validated['scheduled_date'],
            'scheduled_time' => $validated['scheduled_time'] ?? null,
            'remarks' => $validated['remarks'] ?? null,
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', 'Schedule created successfully.');
    }

    /**
     * Update an existing schedule
     */
    public function update(Request $request, Event $event, EventSchedule $schedule)
    {
        if ($schedule->event_id !== $event->id) {
            abort(403);
        }

        $validated = $request->validate([
            'scheduled_date' => ['required', 'date'],
            'scheduled_time' => ['nullable'],
            'remarks' => ['nullable', 'string', 'max:1000'],
        ]);

        $schedule->update([
            'scheduled_date' => $validated['scheduled_date'],
            'scheduled_time' => $validated['scheduled_time'] ?? null,
            'remarks' => $validated['remarks'] ?? null,
        ]);

        return back()->with('success', 'Schedule updated successfully.');
    }

    /**
     * Delete a schedule
     */
    public function destroy(Event $event, EventSchedule $schedule)
    {
        if ($schedule->event_id !== $event->id) {
            abort(403);
        }

        $schedule->delete();

        return back()->with('success', 'Schedule deleted successfully.');
    }
}
