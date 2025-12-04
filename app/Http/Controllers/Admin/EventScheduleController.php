<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventSchedule;
use App\Mail\EventScheduleNotification;
use App\Mail\StaffScheduleAssignmentMail;
use App\Services\NotificationService;
use App\Services\SmsNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EventScheduleController extends Controller
{
    protected $notificationService;
    protected $smsNotifier;

    public function __construct(NotificationService $notificationService, SmsNotifier $smsNotifier)
    {
        $this->notificationService = $notificationService;
        $this->smsNotifier = $smsNotifier;
    }

    /**
     * Save/Update all schedules for an event (called from modal)
     * Auto-notifies staff if newly assigned or schedule changed
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
                'schedules.*.staff_id' => ['nullable', 'exists:staffs,id'],
                'schedules.*.contact_number' => ['nullable', 'string', 'max:50'],
                'schedules.*.venue' => ['nullable', 'string', 'max:255'],
            ]);

            $savedCount = 0;
            $staffToNotify = [];

            foreach ($validated['schedules'] as $scheduleData) {
                // Skip if no date is set
                if (empty($scheduleData['scheduled_date'])) {
                    // If schedule exists but date is cleared, delete it
                    EventSchedule::where('event_id', $event->id)
                        ->where('inclusion_id', $scheduleData['inclusion_id'])
                        ->delete();
                    continue;
                }

                // Find existing schedule to check for changes
                $existingSchedule = EventSchedule::where('event_id', $event->id)
                    ->where('inclusion_id', $scheduleData['inclusion_id'])
                    ->first();

                // Check if we need to notify staff
                $newStaffId = !empty($scheduleData['staff_id']) ? $scheduleData['staff_id'] : null;
                $shouldNotify = false;

                if ($newStaffId) {
                    if (!$existingSchedule) {
                        // New schedule with staff assigned
                        $shouldNotify = true;
                    } elseif ($existingSchedule->staff_id != $newStaffId) {
                        // Staff changed
                        $shouldNotify = true;
                    } elseif (!$existingSchedule->notified_at) {
                        // Staff assigned but never notified
                        $shouldNotify = true;
                    } elseif ($this->scheduleDetailsChanged($existingSchedule, $scheduleData)) {
                        // Key details changed (date, time, venue)
                        $shouldNotify = true;
                    }
                }

                $schedule = EventSchedule::updateOrCreate(
                    [
                        'event_id' => $event->id,
                        'inclusion_id' => $scheduleData['inclusion_id'],
                    ],
                    [
                        'scheduled_date' => $scheduleData['scheduled_date'],
                        'scheduled_time' => !empty($scheduleData['scheduled_time']) ? $scheduleData['scheduled_time'] : null,
                        'remarks' => !empty($scheduleData['remarks']) ? $scheduleData['remarks'] : null,
                        'staff_id' => $newStaffId,
                        'contact_number' => !empty($scheduleData['contact_number']) ? $scheduleData['contact_number'] : null,
                        'venue' => !empty($scheduleData['venue']) ? $scheduleData['venue'] : null,
                        'created_by' => $existingSchedule?->created_by ?? Auth::id(),
                        // Reset notified_at if we're going to notify
                        'notified_at' => $shouldNotify ? null : ($existingSchedule?->notified_at),
                    ]
                );

                if ($shouldNotify && $newStaffId) {
                    $staffToNotify[] = $schedule;
                }

                $savedCount++;
            }

            // Auto-notify staff members (only staff, not customers)
            $notifiedStaffCount = 0;
            foreach ($staffToNotify as $schedule) {
                try {
                    $this->notifyStaffAboutSchedule($schedule);
                    $notifiedStaffCount++;
                } catch (\Exception $e) {
                    Log::error('Failed to auto-notify staff', [
                        'schedule_id' => $schedule->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            $message = "Schedules saved successfully.";
            if ($notifiedStaffCount > 0) {
                $message .= " {$notifiedStaffCount} staff member(s) notified.";
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Failed to save schedules', [
                'event_id' => $event->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to save schedules: ' . $e->getMessage());
        }
    }

    /**
     * Check if schedule details have changed (date, time, venue)
     */
    protected function scheduleDetailsChanged(?EventSchedule $existing, array $newData): bool
    {
        if (!$existing) {
            return true;
        }

        // Check date
        $existingDate = $existing->scheduled_date?->format('Y-m-d');
        $newDate = $newData['scheduled_date'] ?? null;
        if ($existingDate !== $newDate) {
            return true;
        }

        // Check time
        $existingTime = $existing->scheduled_time ? \Carbon\Carbon::parse($existing->scheduled_time)->format('H:i') : null;
        $newTime = !empty($newData['scheduled_time']) ? $newData['scheduled_time'] : null;
        if ($existingTime !== $newTime) {
            return true;
        }

        // Check venue
        $existingVenue = $existing->venue;
        $newVenue = !empty($newData['venue']) ? $newData['venue'] : null;
        if ($existingVenue !== $newVenue) {
            return true;
        }

        return false;
    }

    /**
     * Notify a single staff member about their schedule assignment
     * Uses NotificationService for in-app + SmsNotifier for SMS
     */
    protected function notifyStaffAboutSchedule(EventSchedule $schedule): void
    {
        $schedule->load(['event', 'inclusion', 'staff.user']);

        $staff = $schedule->staff;
        $user = $staff?->user;

        if (!$user) {
            Log::warning('Cannot notify staff - no user account', ['schedule_id' => $schedule->id]);
            return;
        }

        // 1. Send In-App Notification via NotificationService
        $this->notificationService->notifyStaffInclusionSchedule($schedule);

        // 2. Send SMS Notification
        $contactNumber = $schedule->contact_number ?: $staff->contact_number;
        if ($contactNumber) {
            $smsMessage = $this->buildStaffSmsMessage($schedule);
            $this->smsNotifier->sendSms($contactNumber, $smsMessage);
        }

        // 3. Send Email Notification
        if ($user->email) {
            Mail::to($user->email)->queue(new StaffScheduleAssignmentMail($schedule));
        }

        // Mark as notified
        $schedule->update(['notified_at' => now()]);
    }

    /**
     * Send notification to assigned staff for a specific schedule
     * Sends: In-app + SMS + Email
     */
    public function notifyStaff(EventSchedule $schedule)
    {
        // Load relationships
        $schedule->load(['event.customer', 'inclusion', 'staff.user']);

        // Validate schedule has required data
        if (!$schedule->staff_id) {
            return response()->json([
                'success' => false,
                'message' => 'No staff assigned to this schedule.'
            ], 400);
        }

        if (!$schedule->scheduled_date) {
            return response()->json([
                'success' => false,
                'message' => 'Schedule date is not set.'
            ], 400);
        }

        $staff = $schedule->staff;
        $user = $staff->user;

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Staff does not have a user account.'
            ], 400);
        }

        $contactNumber = $schedule->contact_number ?: $staff->contact_number;

        try {
            // 1. Send In-App Notification via NotificationService
            $this->notificationService->notifyStaffInclusionSchedule($schedule);

            // 2. Send SMS Notification
            if ($contactNumber) {
                $smsMessage = $this->buildStaffSmsMessage($schedule);
                $this->smsNotifier->sendSms($contactNumber, $smsMessage);
            }

            // 3. Send Email Notification via Mailable
            if ($user->email) {
                Mail::to($user->email)->queue(new StaffScheduleAssignmentMail($schedule));
            }

            // Mark as notified
            $schedule->markAsNotified();

            return response()->json([
                'success' => true,
                'message' => 'Notification sent successfully to ' . $staff->name
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send staff schedule notification', [
                'schedule_id' => $schedule->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send notification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Build SMS message for staff schedule notification
     */
    protected function buildStaffSmsMessage(EventSchedule $schedule): string
    {
        $event = $schedule->event;
        $inclusion = $schedule->inclusion;
        $date = $schedule->scheduled_date->format('M d, Y');
        $time = $schedule->scheduled_time
            ? \Carbon\Carbon::parse($schedule->scheduled_time)->format('g:i A')
            : 'TBA';

        $message = "MICHAEL HO EVENTS\n\n";
        $message .= "Hi {$schedule->staff->name}!\n\n";
        $message .= "You've been assigned to:\n";
        $message .= "• {$inclusion->name}\n";
        $message .= "• Event: {$event->name}\n";
        $message .= "• Date: {$date}\n";
        $message .= "• Time: {$time}\n";

        if ($schedule->venue) {
            $message .= "• Venue: {$schedule->venue}\n";
        }

        if ($schedule->remarks) {
            $message .= "\nNote: {$schedule->remarks}\n";
        }

        $message .= "\nPlease upload proof after completion. Log in to your dashboard for details.";

        return $message;
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

        $schedule = EventSchedule::create([
            'event_id' => $event->id,
            'inclusion_id' => $validated['inclusion_id'],
            'scheduled_date' => $validated['scheduled_date'],
            'scheduled_time' => $validated['scheduled_time'] ?? null,
            'remarks' => $validated['remarks'] ?? null,
            'created_by' => Auth::id(),
        ]);

        // Send notifications
        $this->sendScheduleNotifications($event, [$schedule], 'created');

        return back()->with('success', 'Schedule created and customer notified.');
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

        // Send notifications
        $this->sendScheduleNotifications($event, [$schedule], 'updated');

        return back()->with('success', 'Schedule updated and customer notified.');
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

    /**
     * Mark schedule as completed
     */
    public function markComplete(EventSchedule $schedule)
    {
        $schedule->markAsCompleted();

        return response()->json([
            'success' => true,
            'message' => 'Schedule marked as completed.'
        ]);
    }

    /**
     * Mark schedule as incomplete
     */
    public function markIncomplete(EventSchedule $schedule)
    {
        $schedule->markAsIncomplete();

        return response()->json([
            'success' => true,
            'message' => 'Schedule marked as incomplete.'
        ]);
    }

    /**
     * Send email, SMS, and in-app notifications for schedule changes (to CUSTOMER)
     */
    protected function sendScheduleNotifications(Event $event, array $schedules, string $action = 'updated')
    {
        // Load relationships
        $event->load('customer.user');

        if (!$event->customer) {
            Log::warning('Cannot send schedule notification - no customer', ['event_id' => $event->id]);
            return;
        }

        // Send email notification
        if ($event->customer->user && $event->customer->user->email) {
            try {
                Mail::to($event->customer->user->email)
                    ->send(new EventScheduleNotification($event, $schedules, $action));
            } catch (\Exception $e) {
                Log::error('Failed to send schedule notification email', [
                    'event_id' => $event->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Send SMS notification
        try {
            $this->smsNotifier->notifyEventScheduleUpdate($event, $schedules, $action);
        } catch (\Exception $e) {
            Log::error('Failed to send schedule notification SMS', [
                'event_id' => $event->id,
                'error' => $e->getMessage()
            ]);
        }

        // Send in-app notification
        try {
            $this->notificationService->notifyCustomerScheduleUpdate($event, $schedules, $action);
        } catch (\Exception $e) {
            Log::error('Failed to send schedule in-app notification', [
                'event_id' => $event->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
