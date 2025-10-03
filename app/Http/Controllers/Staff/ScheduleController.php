<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Staff;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $user  = $request->user();
        $staff = Staff::where('user_id', $user->id)->firstOrFail();

        $eventsQuery = $staff->events()->with(['customer', 'package'])->orderBy('event_date');

        $eventsQuery->when($request->status, fn($query, $status) => $query->where('status', $status))
            ->when($request->from, fn($query, $from) => $query->whereDate('event_date', '>=', $from))
            ->when($request->to, fn($query, $to) => $query->whereDate('event_date', '<=', $to))
            ->when(!$request->has('from') && !$request->has('to'), fn($query) => $query->whereDate('event_date', '>=', now()->toDateString()));

        $events = $eventsQuery->paginate(10);

        return view('staff.schedule.index', compact('events', 'staff'));
    }

    public function show(Request $request, Event $event)
    {
        $staff = Staff::where('user_id', $request->user()->id)->firstOrFail();

        if (!$event->staffs->contains('id', $staff->id)) {
            abort(403, 'You are not assigned to this event.');
        }

        $event->load(['customer', 'package', 'staffs.user']);

        return view('staff.schedule.show', compact('event', 'staff'));
    }
}
