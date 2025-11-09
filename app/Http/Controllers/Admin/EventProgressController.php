<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventProgress;
use App\Mail\EventProgressNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EventProgressController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'status' => 'required|string|max:255',
            'details' => 'nullable|string',
            'progress_date' => 'required|date',
        ]);

        $progress = EventProgress::create([
            'event_id' => $event->id,
            'status' => $validated['status'],
            'details' => $validated['details'],
            'progress_date' => $validated['progress_date'],
        ]);

        // Send email to customer
        if ($event->customer && $event->customer->user) {
            Mail::to($event->customer->user->email)
                ->send(new EventProgressNotification($event, $progress));
        }

        return back()->with('success', 'Progress update added and customer notified.');
    }
}
