<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Event;
use App\Models\Billing;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
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

        $payment->status = 'approved';
        $payment->save();

        $billing = $event->billing;
        if ($billing) {
            $billing->downpayment_amount = 0;

            $billing->total_amount = $billing->total_amount - $payment->amount;

            $billing->save();
        }

        if ($event->status === 'request_meeting') {
            $event->status = 'meeting';
            $event->save();
        }

        return redirect()->route('admin.events.show', $event)->with('success', 'Payment approved successfully.');
    }


    public function rejectPayment(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);

        $payment = $event->payments()->latest()->first();

        if (!$payment) {
            return redirect()->back()->with('error', 'No payment found for this event.');
        }

        $payment->status = 'rejected';
        $payment->save();

        return redirect()->route('admin.events.show', $event)->with('success', 'Payment has been rejected.');
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
