<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Event;
use App\Models\Billing;
use App\Services\NotificationService;
use App\Services\SmsNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CustomerPaymentController extends Controller
{
    protected $notificationService;
    protected $smsNotifier;

    public function __construct(NotificationService $notificationService, SmsNotifier $smsNotifier)
    {
        $this->notificationService = $notificationService;
        $this->smsNotifier = $smsNotifier;
    }

    public function index(Request $request)
    {
        $status = $request->get('status');
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

        DB::beginTransaction();

        try {
            $event = $payment->event;
            $billing = $payment->billing;

            // Check if this is a FULL PAYMENT at intro stage
            $isFullPayment = false;
            if ($payment->isIntroductory() && $billing->total_amount > 0) {
                // Check if payment amount equals total amount (full payment)
                if (abs($payment->amount - $billing->total_amount) < 0.01) {
                    $isFullPayment = true;
                }
            }

            if ($isFullPayment) {
                // FULL PAYMENT - Create additional payment records

                // 1. Approve the intro payment (₱5,000 portion)
                $payment->update([
                    'status' => Payment::STATUS_APPROVED,
                    'payment_date' => now(),
                    'amount' => 5000, // Split to intro portion only
                ]);

                $billing->markIntroPaid();

                // 2. Create and approve DOWNPAYMENT record
                $downpaymentAmount = $billing->downpayment_amount - 5000; // Downpayment minus intro
                if ($downpaymentAmount > 0) {
                    $downpayment = Payment::create([
                        'billing_id' => $billing->id,
                        'payment_type' => Payment::TYPE_DOWNPAYMENT,
                        'payment_image' => $payment->payment_image, // Same receipt
                        'amount' => $downpaymentAmount,
                        'payment_method' => $payment->payment_method,
                        'status' => Payment::STATUS_APPROVED,
                        'payment_date' => now(),
                    ]);

                    $billing->update(['downpayment_payment_status' => 'paid']);
                }

                // 3. Create and approve BALANCE record
                $balanceAmount = $billing->total_amount - $billing->downpayment_amount;
                if ($balanceAmount > 0) {
                    $balance = Payment::create([
                        'billing_id' => $billing->id,
                        'payment_type' => Payment::TYPE_BALANCE,
                        'payment_image' => $payment->payment_image, // Same receipt
                        'amount' => $balanceAmount,
                        'payment_method' => $payment->payment_method,
                        'status' => Payment::STATUS_APPROVED,
                        'payment_date' => now(),
                    ]);
                }

                // Mark billing as fully paid
                $billing->update(['status' => 'paid']);

                // Update event status to MEETING (keep the meeting stage)
                $oldStatus = $event->status;
                $event->update(['status' => Event::STATUS_MEETING]);
                $this->notificationService->notifyCustomerEventStatus($event, $oldStatus, Event::STATUS_MEETING);

                // Send SMS for status change
                try {
                    $this->smsNotifier->notifyEventStatusChange($event, Event::STATUS_MEETING);
                } catch (\Exception $e) {
                    Log::error('Failed to send event status SMS', [
                        'event_id' => $event->id,
                        'error' => $e->getMessage()
                    ]);
                }

                $message = 'Full payment approved! All payment stages completed. Event status: MEETING (fully paid).';
            } else {
                // REGULAR PAYMENT - Normal flow

                // Approve the payment
                $payment->update([
                    'status' => Payment::STATUS_APPROVED,
                    'payment_date' => now(),
                ]);

                // Handle based on payment type
                if ($payment->isIntroductory()) {
                    // Mark intro payment as paid in billing
                    $billing->markIntroPaid();

                    // Update event status to meeting
                    $oldStatus = $event->status;
                    $event->update(['status' => Event::STATUS_MEETING]);
                    $this->notificationService->notifyCustomerEventStatus($event, $oldStatus, Event::STATUS_MEETING);

                    // Send SMS for status change
                    try {
                        $this->smsNotifier->notifyEventStatusChange($event, Event::STATUS_MEETING);
                    } catch (\Exception $e) {
                        Log::error('Failed to send event status SMS', [
                            'event_id' => $event->id,
                            'error' => $e->getMessage()
                        ]);
                    }

                    $message = 'Introductory payment approved. Event status updated to Meeting.';
                } elseif ($payment->isDownpayment()) {
                    // Update event status to scheduled
                    $oldStatus = $event->status;
                    $event->update(['status' => Event::STATUS_SCHEDULED]);
                    $this->notificationService->notifyCustomerEventStatus($event, $oldStatus, Event::STATUS_SCHEDULED);

                    // Send SMS for status change
                    try {
                        $this->smsNotifier->notifyEventStatusChange($event, Event::STATUS_SCHEDULED);
                    } catch (\Exception $e) {
                        Log::error('Failed to send event status SMS', [
                            'event_id' => $event->id,
                            'error' => $e->getMessage()
                        ]);
                    }

                    $message = 'Downpayment approved. Event is now SCHEDULED.';
                } else {
                    // Balance or other payment type
                    $message = 'Payment approved successfully.';
                }

                if ($billing->isFullyPaid()) {
                    $billing->update(['status' => 'paid']);
                }
            }

            // Send in-app notification
            $this->notificationService->notifyCustomerPaymentApproved($payment);

            // Send SMS notification for payment approval
            $smsSent = false;
            try {
                $this->smsNotifier->notifyPaymentConfirmed($payment);
                $smsSent = true;
            } catch (\Exception $e) {
                Log::error('Failed to send payment approval SMS', [
                    'payment_id' => $payment->id,
                    'error' => $e->getMessage()
                ]);
            }

            if ($smsSent) {
                $message .= ' Customer notified via SMS and in-app notification.';
            } else {
                $message .= ' Customer notified via in-app notification.';
            }

            DB::commit();

            return redirect()->route('admin.payments.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to approve payment', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Failed to approve payment. Please try again.');
        }
    }

    public function reject(Request $request, $paymentId)
    {
        $payment = Payment::findOrFail($paymentId);

        if ($payment->status !== Payment::STATUS_PENDING) {
            return back()->with('error', 'Payment is not pending.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $reason = $request->input('rejection_reason');

        $payment->update([
            'status' => Payment::STATUS_REJECTED,
            'rejection_reason' => $reason,
        ]);

        // Send in-app notification
        $this->notificationService->notifyCustomerPaymentRejected($payment, $reason);

        // Send SMS notification
        $smsSent = false;
        try {
            $this->smsNotifier->notifyPaymentRejected($payment, $reason);
            $smsSent = true;
        } catch (\Exception $e) {
            Log::error('Failed to send payment rejection SMS', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
        }

        $message = 'Payment rejected. Customer can resubmit.';
        if ($smsSent) {
            $message .= ' Customer notified via SMS and in-app notification.';
        } else {
            $message .= ' Customer notified via in-app notification.';
        }

        return redirect()->route('admin.payments.index')
            ->with('success', $message);
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
