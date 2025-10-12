<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Event;
use App\Models\Billing;
use App\Services\EventSmsNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $smsNotifier;

    public function __construct(EventSmsNotifier $smsNotifier)
    {
        $this->smsNotifier = $smsNotifier;
    }

    public function verifyPayment(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $payment = $event->payments()->latest()->first();

        if (!$payment) {
            return redirect()->back()->with('error', 'No payment found for this event.');
        }

        return view('admin.payments.verify', [
            'event' => $event,
            'payment' => $payment,
        ]);
    }

    public function approvePayment(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $payment = $event->payments()->latest()->first();

        if (!$payment) {
            return redirect()->back()->with('error', 'No payment found for this event.');
        }

        // Approve the payment
        $payment->status = 'approved';
        $payment->save();

        // Update billing
        $billing = $event->billing;
        if ($billing) {
            $billing->downpayment_amount = 0;
            $billing->total_amount = $billing->total_amount - $payment->amount;
            $billing->save();
        }

        // Update event status if needed
        if ($event->status === 'request_meeting') {
            $event->status = 'meeting';
            $event->save();
        }

        // Send SMS notification
        $smsSent = false;
        try {
            $smsSent = $this->smsNotifier->notifyPaymentConfirmed($payment);
        } catch (\Exception $e) {
            Log::error('Failed to send payment approval SMS', [
                'event_id' => $event->id,
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
        }

        $message = 'Payment approved successfully.';
        if ($smsSent) {
            $message .= ' Customer notified via SMS.';
        } else {
            $message .= ' (SMS notification failed - please inform customer manually)';
        }

        return redirect()->route('admin.events.show', $event)
            ->with('success', $message);
    }

    public function rejectPayment(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $payment = $event->payments()->latest()->first();

        if (!$payment) {
            return redirect()->back()->with('error', 'No payment found for this event.');
        }

        // Validate rejection reason
        $reason = $request->input('rejection_reason');

        // Reject the payment
        $payment->status = 'rejected';
        $payment->rejection_reason = $reason;
        $payment->save();

        // Send SMS notification
        $smsSent = false;
        try {
            $smsSent = $this->smsNotifier->notifyPaymentRejected($payment, $reason);
        } catch (\Exception $e) {
            Log::error('Failed to send payment rejection SMS', [
                'event_id' => $event->id,
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
        }

        $message = 'Payment has been rejected.';
        if ($smsSent) {
            $message .= ' Customer notified via SMS.';
        } else {
            $message .= ' (SMS notification failed - please inform customer manually)';
        }

        return redirect()->route('admin.events.show', $event)
            ->with($smsSent ? 'success' : 'warning', $message);
    }

    private function scheduleMeeting(Event $event)
    {
        $meeting = new \App\Models\Meeting();
        $meeting->event_id = $event->id;
        $meeting->meeting_date = now()->addWeek();
        $meeting->location = 'Zoom Meeting (Link will be shared)';
        $meeting->agenda = 'Event preparation discussion';
        $meeting->save();
    }
}
