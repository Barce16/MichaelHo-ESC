<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Event;
use App\Models\Billing;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class CustomerPaymentController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        $paymentType = $request->get('payment_type');

        $payments = Payment::with(['billing.event.customer', 'event'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($paymentType, fn($q) => $q->where('payment_type', $paymentType))
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.payments.index', compact('payments'));
    }

    public function approve($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);

        if ($payment->status !== Payment::STATUS_PENDING) {
            return back()->with('error', 'Payment is not pending.');
        }

        // Approve the payment
        $payment->update([
            'status' => Payment::STATUS_APPROVED,
            'payment_date' => now(),
        ]);

        $event = $payment->event;
        $billing = $payment->billing;

        // Handle based on payment type
        if ($payment->isIntroductory()) {
            // Mark intro payment as paid in billing
            $billing->markIntroPaid();

            // Update event status to meeting
            $oldStatus = $event->status;
            $event->update(['status' => Event::STATUS_MEETING]);
            $this->notificationService->notifyCustomerEventStatus($event, $oldStatus, Event::STATUS_MEETING);

            $message = 'Introductory payment approved. Event status updated to Meeting.';
        } elseif ($payment->isDownpayment()) {
            // Update event status to scheduled
            $oldStatus = $event->status;
            $event->update(['status' => Event::STATUS_SCHEDULED]);
            $this->notificationService->notifyCustomerEventStatus($event, $oldStatus, Event::STATUS_SCHEDULED);
            $message = 'Downpayment approved. Event is now SCHEDULED.';
        } else {
            // Balance or other payment type
            $message = 'Payment approved successfully.';
        }

        if ($billing->isFullyPaid()) {
            $billing->update(['status' => 'paid']);
        }

        // Send in-app notification
        $this->notificationService->notifyCustomerPaymentApproved($payment);

        return redirect()->route('admin.payments.index')->with('success', $message);
    }

    public function reject(Request $request, $paymentId)
    {
        $payment = Payment::findOrFail($paymentId);

        if ($payment->status !== Payment::STATUS_PENDING) {
            return back()->with('error', 'Payment is not pending.');
        }

        $reason = $request->input('rejection_reason');

        $payment->update([
            'status' => Payment::STATUS_REJECTED,
            'rejection_reason' => $reason,
        ]);

        $this->notificationService->notifyCustomerPaymentRejected($payment, $reason);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment rejected. Customer can resubmit.');
    }

    /**
     * Show payment details for verification
     */
    public function show(Payment $payment)
    {
        $payment->load(['billing.event.customer', 'event']);

        return view('admin.payments.show', compact('payment'));
    }
}
