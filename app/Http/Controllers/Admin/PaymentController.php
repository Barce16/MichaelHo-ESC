<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Event;
use App\Models\Billing;
use Illuminate\Http\Request;
use App\Services\NotificationService;
use App\Services\SmsNotifier;
use App\Notifications\IntroPaymentApprovedNotification;
use App\Notifications\DownpaymentApprovedNotification;
use App\Notifications\PaymentRejectedNotification;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{

    protected $notificationService;
    protected $smsNotifier;

    public function __construct(NotificationService $notificationService, SmsNotifier $smsNotifier)
    {
        $this->notificationService = $notificationService;
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
        $payment = $event->billing->payments()->where('status', Payment::STATUS_PENDING)->latest()->first();

        if (!$payment) {
            return redirect()->back()->with('error', 'No pending payment found for this event.');
        }

        // Approve the payment
        $payment->update(['status' => Payment::STATUS_APPROVED]);

        $billing = $event->billing;

        // Handle different payment types
        if ($payment->payment_type === Payment::TYPE_INTRODUCTORY) {
            // Update intro payment status
            $billing->update(['introductory_payment_status' => 'paid']);

            // Change event status to meeting
            if ($event->status === Event::STATUS_REQUEST_MEETING) {
                $event->update(['status' => Event::STATUS_MEETING]);
            }
        } elseif ($payment->payment_type === Payment::TYPE_DOWNPAYMENT) {
            // Update downpayment status
            $billing->update(['downpayment_payment_status' => 'paid']);

            // Change event status to scheduled
            if ($event->status === Event::STATUS_MEETING) {
                $event->update(['status' => Event::STATUS_SCHEDULED]);
            }
        }

        // Check if billing is fully paid
        if ($billing->isFullyPaid()) {
            $billing->update(['status' => 'paid']);
        }

        $this->notificationService->notifyCustomerPaymentApproved($payment);

        // Send email notification based on payment type
        $customer = $event->customer;
        if ($customer->user) {
            try {
                if ($payment->payment_type === Payment::TYPE_INTRODUCTORY) {
                    $customer->user->notify(new IntroPaymentApprovedNotification($event, $payment));
                } elseif ($payment->payment_type === Payment::TYPE_DOWNPAYMENT) {
                    $customer->user->notify(new DownpaymentApprovedNotification($event, $payment));
                }
            } catch (\Exception $e) {
                Log::error('Failed to send payment approval email', ['payment_id' => $payment->id, 'error' => $e->getMessage()]);
            }
        }

        // Send SMS notification
        try {
            $this->smsNotifier->notifyPaymentConfirmed($payment);
        } catch (\Exception $e) {
            Log::error('Failed to send payment approval SMS', ['payment_id' => $payment->id, 'error' => $e->getMessage()]);
        }

        return redirect()->route('admin.events.show', $event)
            ->with('success', 'Payment approved successfully.');
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


        $this->notificationService->notifyCustomerPaymentRejected($payment, $request->rejection_reason);

        // Send email notification
        if ($event->customer->user) {
            try {
                $event->customer->user->notify(new PaymentRejectedNotification($event, $payment, $reason));
            } catch (\Exception $e) {
                Log::error('Failed to send payment rejection email', ['payment_id' => $payment->id, 'error' => $e->getMessage()]);
            }
        }

        // Send SMS notification
        try {
            $this->smsNotifier->notifyPaymentRejected($payment, $reason);
        } catch (\Exception $e) {
            Log::error('Failed to send payment rejection SMS', ['payment_id' => $payment->id, 'error' => $e->getMessage()]);
        }

        return redirect()->route('admin.events.show', $event)
            ->with('success', 'Payment rejected successfully.');
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
