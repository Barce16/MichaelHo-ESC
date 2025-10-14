<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;

class CustomerFeedbackController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Show feedback form
     */
    public function create(Event $event)
    {
        $customer = Auth::user()->customer;

        if (!$customer || $event->customer_id !== $customer->id) {
            abort(403);
        }

        // Check if event is completed
        if ($event->status !== Event::STATUS_COMPLETED) {
            return redirect()->route('customer.events.show', $event)
                ->with('error', 'You can only submit feedback for completed events.');
        }

        // Check if feedback already exists
        if ($event->hasFeedback()) {
            return redirect()->route('customer.events.show', $event)
                ->with('info', 'You have already submitted feedback for this event.');
        }

        return view('customers.feedback.create', compact('event'));
    }

    /**
     * Store feedback
     */
    public function store(Request $request, Event $event)
    {
        $customer = Auth::user()->customer;

        if (!$customer || $event->customer_id !== $customer->id) {
            abort(403);
        }

        // Check if event is completed
        if ($event->status !== Event::STATUS_COMPLETED) {
            return redirect()->route('customer.events.show', $event)
                ->with('error', 'You can only submit feedback for completed events.');
        }

        // Check if feedback already exists
        if ($event->hasFeedback()) {
            return redirect()->route('customer.events.show', $event)
                ->with('info', 'You have already submitted feedback for this event.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $feedback = Feedback::create([
            'event_id' => $event->id,
            'customer_id' => $customer->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        // Notify admins
        $this->notificationService->notifyAdminCustomerFeedback($feedback);

        return redirect()->route('customer.events.show', $event)
            ->with('success', 'Thank you for your feedback! We appreciate your input.');
    }

    /**
     * Show edit form
     */
    public function edit(Event $event)
    {
        $customer = Auth::user()->customer;

        if (!$customer || $event->customer_id !== $customer->id) {
            abort(403);
        }

        $feedback = $event->feedback;

        if (!$feedback) {
            return redirect()->route('customer.feedback.create', $event);
        }

        return view('customers.feedback.edit', compact('event', 'feedback'));
    }

    /**
     * Update feedback
     */
    public function update(Request $request, Event $event)
    {
        $customer = Auth::user()->customer;

        if (!$customer || $event->customer_id !== $customer->id) {
            abort(403);
        }

        $feedback = $event->feedback;

        if (!$feedback) {
            return redirect()->route('customer.events.show', $event)
                ->with('error', 'No feedback found.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $feedback->update($validated);

        return redirect()->route('customer.events.show', $event)
            ->with('success', 'Feedback updated successfully!');
    }
}
