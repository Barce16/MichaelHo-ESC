<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventProgress;
use App\Mail\EventProgressNotification;
use App\Services\NotificationService;
use App\Services\SmsNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EventProgressController extends Controller
{
    protected $notificationService;
    protected $smsNotifier;

    public function __construct(NotificationService $notificationService, SmsNotifier $smsNotifier)
    {
        $this->notificationService = $notificationService;
        $this->smsNotifier = $smsNotifier;
    }

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
            try {
                Mail::to($event->customer->user->email)
                    ->send(new EventProgressNotification($event, $progress));
            } catch (\Exception $e) {
                Log::error('Failed to send event progress email', ['event_id' => $event->id, 'error' => $e->getMessage()]);
            }
        }

        // Send SMS to customer
        try {
            $this->smsNotifier->notifyEventProgress($event, $validated['status'], $validated['details']);
        } catch (\Exception $e) {
            Log::error('Failed to send event progress SMS', ['event_id' => $event->id, 'error' => $e->getMessage()]);
        }

        // Send in-app notification
        $this->notificationService->notifyCustomerEventProgress($event, $progress);

        return back()->with('success', 'Progress update added and customer notified.');
    }
}
