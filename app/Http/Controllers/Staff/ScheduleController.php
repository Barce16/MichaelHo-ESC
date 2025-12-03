<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Staff;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $staff = Staff::where('user_id', $user->id)->first();

        if (!$staff) {
            return redirect()->route('dashboard')->with('error', 'Staff profile not found.');
        }

        $status = $request->get('status', 'all');
        $month = $request->get('month', now()->format('Y-m'));

        $query = Event::with(['customer', 'package'])
            ->whereHas('staffs', function ($q) use ($staff) {
                $q->where('staff_id', $staff->id);
            })
            ->orderBy('event_date', 'asc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($month) {
            $date = \Carbon\Carbon::parse($month . '-01');
            $query->whereYear('event_date', $date->year)
                ->whereMonth('event_date', $date->month);
        }

        $events = $query->paginate(10)->withQueryString();

        // Get staff assignments with pivot data and auto-update work status
        $events->getCollection()->transform(function ($event) use ($staff) {
            $pivot = $event->staffs()->where('staff_id', $staff->id)->first()?->pivot;

            // Auto-update work_status to 'ongoing' if event date is today
            if ($pivot && $pivot->work_status === 'pending') {
                $eventDate = \Carbon\Carbon::parse($event->event_date);
                $today = \Carbon\Carbon::today();

                if ($eventDate->isSameDay($today)) {
                    DB::table('event_staff')
                        ->where('event_id', $event->id)
                        ->where('staff_id', $staff->id)
                        ->update(['work_status' => 'ongoing']);

                    // Refresh pivot data
                    $pivot = $event->staffs()->where('staff_id', $staff->id)->first()?->pivot;
                }
            }

            $event->staff_assignment = $pivot;
            return $event;
        });

        // Get ALL assignments for the calendar (no pagination, no month filter)
        $allAssignments = Event::with(['customer', 'package'])
            ->whereHas('staffs', function ($q) use ($staff) {
                $q->where('staff_id', $staff->id);
            })
            ->orderBy('event_date', 'asc')
            ->get()
            ->map(function ($event) use ($staff) {
                $pivot = $event->staffs()->where('staff_id', $staff->id)->first()?->pivot;
                $event->staff_assignment = $pivot;
                return $event;
            });

        // Calculate stats
        $stats = [
            'total_assignments' => Event::whereHas('staffs', function ($q) use ($staff) {
                $q->where('staff_id', $staff->id);
            })->count(),

            'upcoming' => Event::whereHas('staffs', function ($q) use ($staff) {
                $q->where('staff_id', $staff->id);
            })->where('event_date', '>=', now())->count(),

            'completed' => Event::whereHas('staffs', function ($q) use ($staff) {
                $q->where('staff_id', $staff->id);
            })->where('status', 'completed')->count(),

            'total_earnings' => DB::table('event_staff')
                ->where('staff_id', $staff->id)
                ->where('pay_status', 'paid')
                ->sum('pay_rate'),
        ];

        return view('staff.schedules.index', compact('events', 'allAssignments', 'stats', 'status', 'month', 'staff'));
    }

    public function show(Event $event)
    {
        $user = Auth::user();
        $staff = Staff::where('user_id', $user->id)->first();

        if (!$staff) {
            return redirect()->route('dashboard')->with('error', 'Staff profile not found.');
        }

        // Check if staff is assigned to this event
        if (!$event->staffs()->where('staff_id', $staff->id)->exists()) {
            return redirect()->route('staff.schedules.index')->with('error', 'You are not assigned to this event.');
        }

        $event->load(['customer', 'package', 'staffs']);

        $assignment = $event->staffs()->where('staff_id', $staff->id)->first()?->pivot;

        // Auto-update work_status to 'ongoing' if event date is today
        if ($assignment && $assignment->work_status === 'pending') {
            $eventDate = \Carbon\Carbon::parse($event->event_date);
            $today = \Carbon\Carbon::today();

            if ($eventDate->isSameDay($today)) {
                DB::table('event_staff')
                    ->where('event_id', $event->id)
                    ->where('staff_id', $staff->id)
                    ->update(['work_status' => 'ongoing']);

                // Refresh assignment data
                $assignment = $event->staffs()->where('staff_id', $staff->id)->first()?->pivot;
            }
        }

        return view('staff.schedules.show', compact('event', 'staff', 'assignment'));
    }

    /**
     * Mark staff work as finished for an event
     */
    public function finishWork(Event $event)
    {
        $user = Auth::user();
        $staff = Staff::where('user_id', $user->id)->first();

        if (!$staff) {
            return redirect()->route('dashboard')->with('error', 'Staff profile not found.');
        }

        // Check if staff is assigned to this event
        $assignment = $event->staffs()->where('staff_id', $staff->id)->first();

        if (!$assignment) {
            return redirect()->route('staff.schedules.index')->with('error', 'You are not assigned to this event.');
        }

        // Check if already finished
        if ($assignment->pivot->work_status === 'finished') {
            return back()->with('info', 'You have already marked this work as finished.');
        }

        // Update work status to finished
        DB::table('event_staff')
            ->where('event_id', $event->id)
            ->where('staff_id', $staff->id)
            ->update([
                'work_status' => 'finished',
                'updated_at' => now(),
            ]);

        // Notify admin that staff has finished their work
        $this->notificationService->notifyAdminStaffWorkFinished($staff, $event);

        return back()->with('success', 'Your work has been marked as finished. Admin has been notified.');
    }

    public function earnings(Request $request)
    {
        $user = Auth::user();
        $staff = Staff::where('user_id', $user->id)->first();

        if (!$staff) {
            return redirect()->route('dashboard')->with('error', 'Staff profile not found.');
        }

        $year = $request->get('year', now()->year);

        // Get all assignments with event data
        $assignments = Event::with(['customer', 'package'])
            ->whereHas('staffs', function ($q) use ($staff) {
                $q->where('staff_id', $staff->id);
            })
            ->whereYear('event_date', $year)
            ->orderBy('event_date', 'desc')
            ->get()
            ->map(function ($event) use ($staff) {
                $pivot = $event->staffs()->where('staff_id', $staff->id)->first()?->pivot;
                $event->staff_assignment = $pivot;
                return $event;
            });

        // Calculate monthly earnings
        $monthlyEarnings = $assignments->groupBy(function ($event) {
            return \Carbon\Carbon::parse($event->event_date)->format('Y-m');
        })->map(function ($events) {
            return [
                'total' => $events->sum(fn($e) => $e->staff_assignment?->pay_rate ?? 0),
                'paid' => $events->where('staff_assignment.pay_status', 'paid')->sum(fn($e) => $e->staff_assignment?->pay_rate ?? 0),
                'pending' => $events->where('staff_assignment.pay_status', 'pending')->sum(fn($e) => $e->staff_assignment?->pay_rate ?? 0),
                'count' => $events->count(),
            ];
        });

        // Summary stats
        $stats = [
            'total_earned' => $assignments->where('staff_assignment.pay_status', 'paid')->sum(fn($e) => $e->staff_assignment?->pay_rate ?? 0),
            'pending_payment' => $assignments->where('staff_assignment.pay_status', 'pending')->sum(fn($e) => $e->staff_assignment?->pay_rate ?? 0),
            'total_events' => $assignments->count(),
            'paid_events' => $assignments->where('staff_assignment.pay_status', 'paid')->count(),
        ];

        return view('staff.schedules.earnings', compact('assignments', 'monthlyEarnings', 'stats', 'year', 'staff'));
    }
}
