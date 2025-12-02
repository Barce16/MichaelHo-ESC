<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Payment;
use App\Models\Billing;
use App\Services\NotificationService;
use App\Services\SmsNotifier;
use App\Notifications\IntroPaymentApprovedNotification;
use App\Notifications\DownpaymentApprovedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminBillingController extends Controller
{
    protected $notificationService;
    protected $smsNotifier;

    public function __construct(NotificationService $notificationService, SmsNotifier $smsNotifier)
    {
        $this->notificationService = $notificationService;
        $this->smsNotifier = $smsNotifier;
    }

    /**
     * Display all billings
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        $search = $request->get('search');

        $eventsWithBillings = Event::whereHas('billing')
            ->with([
                'customer',
                'billing.payments' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                }
            ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhereHas('customer', function ($cq) use ($search) {
                            $cq->where('customer_name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($status === 'paid', function ($query) {
                // Filter for fully paid: sum of approved payments >= total_amount
                $query->whereHas('billing', function ($q) {
                    $q->whereRaw('(
                        SELECT COALESCE(SUM(amount), 0) 
                        FROM payments 
                        WHERE payments.billing_id = billings.id 
                        AND payments.status = ?
                    ) >= billings.total_amount', [Payment::STATUS_APPROVED]);
                });
            })
            ->when($status === 'pending', function ($query) {
                // Filter for pending: sum of approved payments < total_amount
                $query->whereHas('billing', function ($q) {
                    $q->whereRaw('(
                        SELECT COALESCE(SUM(amount), 0) 
                        FROM payments 
                        WHERE payments.billing_id = billings.id 
                        AND payments.status = ?
                    ) < billings.total_amount', [Payment::STATUS_APPROVED]);
                });
            })
            ->orderBy('event_date', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Calculate totals
        $totalOutstanding = Event::whereHas('billing')
            ->with('billing')
            ->get()
            ->sum(fn($e) => $e->billing->remaining_balance ?? 0);

        $totalPaid = Event::whereHas('billing')
            ->with('billing')
            ->get()
            ->sum(fn($e) => $e->billing->total_paid ?? 0);

        return view('admin.billings.index', compact('eventsWithBillings', 'totalOutstanding', 'totalPaid', 'status', 'search'));
    }

    /**
     * Show payment form for an event
     */
    public function createPayment(Event $event)
    {
        if (!$event->billing) {
            return redirect()
                ->route('admin.billings.index')
                ->with('error', 'No billing found for this event.');
        }

        $event->load(['customer', 'billing.payments']);

        // Determine payment type based on event status and payment history
        $paymentType = $this->determinePaymentType($event);
        $amount = $this->calculatePaymentAmount($event, $paymentType);

        // Check for pending payments
        $hasPendingPayment = $event->billing->payments()
            ->where('status', Payment::STATUS_PENDING)
            ->exists();

        // Check if downpayment is paid
        $hasApprovedDownpayment = $event->billing->payments()
            ->where('payment_type', Payment::TYPE_DOWNPAYMENT)
            ->where('status', Payment::STATUS_APPROVED)
            ->exists();

        return view('admin.billings.create-payment', compact(
            'event',
            'paymentType',
            'amount',
            'hasPendingPayment',
            'hasApprovedDownpayment'
        ));
    }

    /**
     * Store payment (admin submitting on behalf of customer)
     */
    public function storePayment(Request $request, Event $event)
    {
        if (!$event->billing) {
            return back()->with('error', 'No billing found for this event.');
        }

        $data = $request->validate([
            'payment_type' => ['required', 'in:introductory,downpayment,balance'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'in:gcash,bank_transfer,bpi,cash'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'payment_receipt' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
            'auto_approve' => ['nullable', 'boolean'],
        ]);

        $billing = $event->billing;
        $autoApprove = $request->boolean('auto_approve');

        // Validation based on payment type
        $validationResult = $this->validatePayment($event, $data);
        if ($validationResult !== true) {
            return back()->with('error', $validationResult);
        }

        DB::beginTransaction();

        try {
            // Store payment receipt if provided
            $filePath = null;
            if ($request->hasFile('payment_receipt')) {
                $filePath = $request->file('payment_receipt')->store('payment_receipts', 'public');
            }

            $originalAmount = $data['amount'];

            // Create payment record
            $payment = Payment::create([
                'billing_id' => $billing->id,
                'payment_type' => $data['payment_type'],
                'payment_image' => $filePath,
                'amount' => $data['amount'],
                'payment_method' => $data['payment_method'],
                'reference_number' => $data['reference_number'],
                'status' => $autoApprove ? Payment::STATUS_APPROVED : Payment::STATUS_PENDING,
                'payment_date' => now(),
            ]);

            // If auto-approve, process payment with full logic
            if ($autoApprove) {
                $this->processApprovedPayment($payment, $event, $billing, $originalAmount, $filePath);
            }

            DB::commit();

            $message = $autoApprove
                ? 'Payment recorded and approved successfully.'
                : 'Payment recorded successfully. Awaiting approval.';

            return redirect()
                ->route('admin.billings.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to store admin payment', [
                'event_id' => $event->id,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Failed to process payment: ' . $e->getMessage());
        }
    }

    /**
     * Show billing details with payment history
     */
    public function show(Event $event)
    {
        if (!$event->billing) {
            return redirect()
                ->route('admin.billings.index')
                ->with('error', 'No billing found for this event.');
        }

        $event->load([
            'customer',
            'package',
            'inclusions',
            'billing.payments' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }
        ]);

        return view('admin.billings.show', compact('event'));
    }

    /**
     * Determine what type of payment is needed
     */
    private function determinePaymentType(Event $event): string
    {
        $billing = $event->billing;

        // Check if intro is paid
        $hasApprovedIntro = $billing->payments()
            ->where('payment_type', Payment::TYPE_INTRODUCTORY)
            ->where('status', Payment::STATUS_APPROVED)
            ->exists();

        if (!$hasApprovedIntro && $event->status === Event::STATUS_REQUEST_MEETING) {
            return 'introductory';
        }

        // Check if downpayment is paid
        $hasApprovedDownpayment = $billing->payments()
            ->where('payment_type', Payment::TYPE_DOWNPAYMENT)
            ->where('status', Payment::STATUS_APPROVED)
            ->exists();

        if (!$hasApprovedDownpayment) {
            return 'downpayment';
        }

        return 'balance';
    }

    /**
     * Calculate payment amount based on type
     */
    private function calculatePaymentAmount(Event $event, string $paymentType): float
    {
        $billing = $event->billing;

        return match ($paymentType) {
            'introductory' => 5000.00,
            'downpayment' => $billing->downpayment_amount - $billing->introductory_payment_amount,
            'balance' => $billing->remaining_balance,
            default => 0,
        };
    }

    /**
     * Validate payment data
     */
    private function validatePayment(Event $event, array $data): bool|string
    {
        $billing = $event->billing;

        if ($data['payment_type'] === 'introductory') {
            if (abs((float)$data['amount'] - 5000.00) > 0.01) {
                return 'Introductory payment must be exactly ₱5,000.00';
            }
        }

        if ($data['payment_type'] === 'downpayment') {
            $minAmount = $billing->downpayment_amount - $billing->introductory_payment_amount;
            if ((float)$data['amount'] < $minAmount && (float)$data['amount'] != $billing->remaining_balance) {
                return 'Downpayment must be at least ₱' . number_format($minAmount, 2);
            }
        }

        if ($data['payment_type'] === 'balance') {
            if ((float)$data['amount'] > $billing->remaining_balance) {
                return 'Amount cannot exceed remaining balance of ₱' . number_format($billing->remaining_balance, 2);
            }
        }

        return true;
    }

    /**
     * Process approved payment - update statuses, handle full payments, send notifications
     */
    private function processApprovedPayment(Payment $payment, Event $event, Billing $billing, float $originalAmount, ?string $filePath): void
    {
        // Check if this is a FULL PAYMENT (matching CustomerPaymentController logic)
        $isFullPayment = false;

        if ($payment->isIntroductory() && $billing->total_amount > 0) {
            // Full payment = paying the entire event total upfront
            if (abs($originalAmount - $billing->total_amount) < 0.01) {
                $isFullPayment = true;
            }
        } elseif ($payment->isDownpayment() && $billing->remaining_balance > 0) {
            // Full payment = paying the entire remaining balance
            if (abs($originalAmount - $billing->remaining_balance) < 0.01) {
                $isFullPayment = true;
            }
        }

        if ($isFullPayment && $payment->isIntroductory()) {
            // INTRO FULL PAYMENT - Split into intro + downpayment + balance records
            $payment->update(['amount' => 5000]);
            $billing->markIntroPaid();

            // Create downpayment record
            $downpaymentAmount = $billing->downpayment_amount - 5000;
            if ($downpaymentAmount > 0) {
                Payment::create([
                    'billing_id' => $billing->id,
                    'payment_type' => Payment::TYPE_DOWNPAYMENT,
                    'payment_image' => $filePath,
                    'amount' => $downpaymentAmount,
                    'payment_method' => $payment->payment_method,
                    'status' => Payment::STATUS_APPROVED,
                    'payment_date' => now(),
                ]);
            }

            // Create balance record
            $balanceAmount = $billing->total_amount - $billing->downpayment_amount;
            if ($balanceAmount > 0) {
                Payment::create([
                    'billing_id' => $billing->id,
                    'payment_type' => Payment::TYPE_BALANCE,
                    'payment_image' => $filePath,
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
        } elseif ($isFullPayment && $payment->isDownpayment()) {
            // DOWNPAYMENT FULL PAYMENT - Split into downpayment + balance
            $downpaymentPortion = $billing->downpayment_amount - $billing->introductory_payment_amount;
            $payment->update(['amount' => $downpaymentPortion]);

            // Create balance record
            $balanceAmount = $originalAmount - $downpaymentPortion;
            if ($balanceAmount > 0) {
                Payment::create([
                    'billing_id' => $billing->id,
                    'payment_type' => Payment::TYPE_BALANCE,
                    'payment_image' => $filePath,
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
        } else {
            // REGULAR PAYMENT (not full balance)
            if ($payment->isIntroductory()) {
                $billing->markIntroPaid();
                if ($event->status === Event::STATUS_REQUEST_MEETING) {
                    $oldStatus = $event->status;
                    $event->update(['status' => Event::STATUS_MEETING]);
                    $this->notificationService->notifyCustomerEventStatus($event, $oldStatus, Event::STATUS_MEETING);

                    try {
                        $this->smsNotifier->notifyEventStatusChange($event, Event::STATUS_MEETING);
                    } catch (\Exception $e) {
                        Log::error('Failed to send event status SMS', ['event_id' => $event->id, 'error' => $e->getMessage()]);
                    }
                }
            } elseif ($payment->isDownpayment()) {
                if ($event->status === Event::STATUS_MEETING) {
                    $oldStatus = $event->status;
                    $event->update(['status' => Event::STATUS_SCHEDULED]);
                    $this->notificationService->notifyCustomerEventStatus($event, $oldStatus, Event::STATUS_SCHEDULED);

                    try {
                        $this->smsNotifier->notifyEventStatusChange($event, Event::STATUS_SCHEDULED);
                    } catch (\Exception $e) {
                        Log::error('Failed to send event status SMS', ['event_id' => $event->id, 'error' => $e->getMessage()]);
                    }
                }
            }

            // Check if fully paid after this payment
            if ($billing->fresh()->isFullyPaid()) {
                $billing->update(['status' => 'paid']);
            }
        }

        // Send in-app notification
        $this->notificationService->notifyCustomerPaymentApproved($payment);

        // Send email notification
        $customer = $event->customer;
        if ($customer && $customer->user) {
            try {
                if ($payment->isIntroductory()) {
                    $customer->user->notify(new IntroPaymentApprovedNotification($event, $payment));
                } elseif ($payment->isDownpayment()) {
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
    }
}
