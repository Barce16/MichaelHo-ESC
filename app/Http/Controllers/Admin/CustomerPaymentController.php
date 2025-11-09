<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Event;
use App\Models\Billing;
use App\Services\NotificationService;
use App\Services\SmsNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            // Get billing and event through the proper relationship
            $billing = $payment->billing;

            if (!$billing) {
                DB::rollBack();
                return back()->with('error', 'Billing information not found for this payment.');
            }

            $event = $billing->event;

            if (!$event) {
                DB::rollBack();
                return back()->with('error', 'Event not found for this payment.');
            }

            $originalPaymentAmount = $payment->amount;

            // Check if this is a FULL PAYMENT
            $isFullPayment = false;

            if ($payment->isIntroductory() && $billing->total_amount > 0) {
                if (abs($originalPaymentAmount - $billing->total_amount) < 0.01) {
                    $isFullPayment = true;
                }
            } elseif ($payment->isDownpayment() && $billing->remaining_balance > 0) {
                if (abs($originalPaymentAmount - $billing->remaining_balance) < 0.01) {
                    $isFullPayment = true;
                }
            }

            if ($isFullPayment && $payment->isIntroductory()) {
                // INTRO FULL PAYMENT - Split into 3 records
                $payment->update([
                    'status' => Payment::STATUS_APPROVED,
                    'payment_date' => now(),
                    'amount' => 5000,
                ]);

                $billing->markIntroPaid();

                $downpaymentAmount = $billing->downpayment_amount - 5000;
                if ($downpaymentAmount > 0) {
                    Payment::create([
                        'billing_id' => $billing->id,
                        'payment_type' => Payment::TYPE_DOWNPAYMENT,
                        'payment_image' => $payment->payment_image,
                        'amount' => $downpaymentAmount,
                        'payment_method' => $payment->payment_method,
                        'status' => Payment::STATUS_APPROVED,
                        'payment_date' => now(),
                    ]);
                }

                $balanceAmount = $billing->total_amount - $billing->downpayment_amount;
                if ($balanceAmount > 0) {
                    Payment::create([
                        'billing_id' => $billing->id,
                        'payment_type' => Payment::TYPE_BALANCE,
                        'payment_image' => $payment->payment_image,
                        'amount' => $balanceAmount,
                        'payment_method' => $payment->payment_method,
                        'status' => Payment::STATUS_APPROVED,
                        'payment_date' => now(),
                    ]);
                }

                $billing->update(['status' => 'paid']);
                $oldStatus = $event->status;
                $event->update(['status' => Event::STATUS_MEETING]);
                $this->notificationService->notifyCustomerEventStatus($event, $oldStatus, Event::STATUS_MEETING);

                try {
                    $this->smsNotifier->notifyEventStatusChange($event, Event::STATUS_MEETING);
                } catch (\Exception $e) {
                    Log::error('Failed to send event status SMS', ['event_id' => $event->id, 'error' => $e->getMessage()]);
                }

                $message = 'Full payment approved! Event is now fully paid.';
            } elseif ($isFullPayment && $payment->isDownpayment()) {
                // DOWNPAYMENT FULL PAYMENT - Split into downpayment + balance
                $downpaymentPortion = $billing->downpayment_amount - $billing->introductory_payment_amount;
                $payment->update([
                    'status' => Payment::STATUS_APPROVED,
                    'payment_date' => now(),
                    'amount' => $downpaymentPortion,
                ]);

                $balanceAmount = $originalPaymentAmount - $downpaymentPortion;
                if ($balanceAmount > 0) {
                    Payment::create([
                        'billing_id' => $billing->id,
                        'payment_type' => Payment::TYPE_BALANCE,
                        'payment_image' => $payment->payment_image,
                        'amount' => $balanceAmount,
                        'payment_method' => $payment->payment_method,
                        'status' => Payment::STATUS_APPROVED,
                        'payment_date' => now(),
                    ]);
                }

                $billing->update(['status' => 'paid']);
                $oldStatus = $event->status;
                $event->update(['status' => Event::STATUS_SCHEDULED]);
                $this->notificationService->notifyCustomerEventStatus($event, $oldStatus, Event::STATUS_SCHEDULED);

                try {
                    $this->smsNotifier->notifyEventStatusChange($event, Event::STATUS_SCHEDULED);
                } catch (\Exception $e) {
                    Log::error('Failed to send event status SMS', ['event_id' => $event->id, 'error' => $e->getMessage()]);
                }

                $message = 'Full payment approved! Event is now fully paid.';
            } else {
                // REGULAR PAYMENT
                $payment->update([
                    'status' => Payment::STATUS_APPROVED,
                    'payment_date' => now(),
                ]);

                if ($payment->isIntroductory()) {
                    $billing->markIntroPaid();
                    $oldStatus = $event->status;
                    $event->update(['status' => Event::STATUS_MEETING]);
                    $this->notificationService->notifyCustomerEventStatus($event, $oldStatus, Event::STATUS_MEETING);

                    try {
                        $this->smsNotifier->notifyEventStatusChange($event, Event::STATUS_MEETING);
                    } catch (\Exception $e) {
                        Log::error('Failed to send event status SMS', ['event_id' => $event->id, 'error' => $e->getMessage()]);
                    }

                    $message = 'Introductory payment approved. Event status: Meeting.';
                } elseif ($payment->isDownpayment()) {
                    $oldStatus = $event->status;
                    $event->update(['status' => Event::STATUS_SCHEDULED]);
                    $this->notificationService->notifyCustomerEventStatus($event, $oldStatus, Event::STATUS_SCHEDULED);

                    try {
                        $this->smsNotifier->notifyEventStatusChange($event, Event::STATUS_SCHEDULED);
                    } catch (\Exception $e) {
                        Log::error('Failed to send event status SMS', ['event_id' => $event->id, 'error' => $e->getMessage()]);
                    }

                    $message = 'Downpayment approved. Event is now SCHEDULED.';
                } else {
                    $message = 'Payment approved successfully.';
                }

                if ($billing->isFullyPaid()) {
                    $billing->update(['status' => 'paid']);
                }
            }

            $this->notificationService->notifyCustomerPaymentApproved($payment);

            $smsSent = false;
            try {
                $this->smsNotifier->notifyPaymentConfirmed($payment);
                $smsSent = true;
            } catch (\Exception $e) {
                Log::error('Failed to send payment approval SMS', ['payment_id' => $payment->id, 'error' => $e->getMessage()]);
            }

            if ($smsSent) {
                $message .= ' Customer notified via SMS and in-app.';
            } else {
                $message .= ' Customer notified via in-app.';
            }

            DB::commit();

            return redirect()->route('admin.payments.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to approve payment', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Failed to approve payment: ' . $e->getMessage());
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

    /**
     * Generate receipt PDF for approved payment
     */
    public function createReceipt(Payment $payment)
    {
        // Authorization check
        if ($payment->status !== Payment::STATUS_APPROVED) {
            return back()->with('error', 'Receipt can only be generated for approved payments.');
        }

        if (!$payment->hasReceiptRequested()) {
            return back()->with('error', 'No receipt request found for this payment.');
        }

        if ($payment->hasReceiptCreated()) {
            return back()->with('info', 'Receipt has already been generated for this payment.');
        }

        // Mark receipt as created
        $payment->markReceiptCreated();

        // Notify customer that receipt is ready
        $this->notificationService->notifyCustomerReceiptReady($payment);

        return back()->with('success', 'Receipt generated successfully! Customer has been notified and can now download it.');
    }

    /**
     * Download receipt PDF
     */
    public function downloadReceipt(Payment $payment)
    {
        if (!$payment->hasReceiptCreated()) {
            abort(404, 'Receipt not found.');
        }

        $event = $payment->billing->event;
        $customer = $event->customer;

        // Get current admin user or find an admin with signature
        $admin = Auth::user()->user_type === 'admin'
            ? Auth::user()
            : (\App\Models\User::where('user_type', 'admin')
                ->whereNotNull('signature_path')
                ->first()
                ?? \App\Models\User::where('user_type', 'admin')->first());

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.payments.receipt-pdf', compact('payment', 'event', 'customer', 'admin'))
            ->setPaper('a4', 'portrait')
            ->setOption('margin-top', 5)
            ->setOption('margin-bottom', 5)
            ->setOption('margin-left', 10)
            ->setOption('margin-right', 10);

        $filename = 'receipt-' . $payment->id . '-' . now()->format('Y-m-d') . '.pdf';

        // Use stream() instead of download() to open in browser
        return $pdf->stream($filename);
    }
}
