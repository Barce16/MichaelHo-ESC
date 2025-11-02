<?php

namespace App\Http\Controllers\Customer;

use App\Models\Event;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;

class PaymentController extends Controller
{
    public function index()
    {
        $customer = Auth::user()->customer;

        if (!$customer) {
            return redirect()->route('home')->with('error', 'Customer not found.');
        }

        $payments = Payment::whereHas('billing.event', function ($query) use ($customer) {
            $query->where('customer_id', $customer->id);
        })
            ->with('billing.event')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('customers.payments.index', compact('payments'));
    }

    /**
     * Generic create - routes to correct payment type based on event status
     */
    public function create(Event $event)
    {
        $customer = Auth::user()->customer;

        if (!$customer || $event->customer_id !== $customer->id) {
            abort(403);
        }

        // Check if downpayment is already paid
        $hasDownpaymentPaid = false;
        if ($event->billing) {
            $hasDownpaymentPaid = $event->billing->payments()
                ->where('payment_type', Payment::TYPE_DOWNPAYMENT)
                ->where('status', Payment::STATUS_APPROVED)
                ->exists();
        }

        if ($event->status === Event::STATUS_REQUEST_MEETING) {
            return $this->createIntro($event);
        } elseif ($event->status === Event::STATUS_MEETING) {
            // If downpayment already paid, route to balance
            if ($hasDownpaymentPaid) {
                return $this->createBalancePayment($event);
            }
            return $this->createDownpayment($event);
        } elseif (in_array($event->status, [Event::STATUS_SCHEDULED, Event::STATUS_ONGOING, Event::STATUS_COMPLETED])) {
            if (!$hasDownpaymentPaid) {
                return redirect()
                    ->route('customer.events.show', $event)
                    ->with('error', 'Please complete your downpayment before making balance payments.');
            }
            return $this->createBalancePayment($event);
        }

        return redirect()
            ->route('customer.events.show', $event)
            ->with('error', 'No payment is required at this stage.');
    }

    /**
     * Show introductory payment form
     */
    public function createIntro(Event $event)
    {
        $customer = Auth::user()->customer;

        if (!$customer || $event->customer_id !== $customer->id) {
            abort(403);
        }

        if ($event->status !== Event::STATUS_REQUEST_MEETING) {
            return redirect()
                ->route('customer.events.show', $event)
                ->with('error', 'Introductory payment not required at this stage.');
        }

        // Check if already has pending intro payment
        if ($event->billing) {
            $hasPending = $event->billing->payments()
                ->where('payments.payment_type', Payment::TYPE_INTRODUCTORY)
                ->where('payments.status', Payment::STATUS_PENDING)
                ->exists();

            if ($hasPending) {
                return redirect()
                    ->route('customer.events.show', $event)
                    ->with('info', 'You already have a pending introductory payment submission.');
            }
        }

        $amount = 5000;
        $paymentType = 'introductory';

        return view('customers.payments.create', compact('event', 'amount', 'paymentType'));
    }

    /**
     * Show downpayment form
     */
    public function createDownpayment(Event $event)
    {
        $customer = Auth::user()->customer;

        if (!$customer || $event->customer_id !== $customer->id) {
            abort(403);
        }

        if ($event->status === Event::STATUS_REQUESTED) {
            return redirect()
                ->route('customer.events.show', $event)
                ->with('error', 'Downpayment not required at this stage.');
        }

        if (!$event->billing || $event->billing->downpayment_amount <= 0) {
            return redirect()
                ->route('customer.events.show', $event)
                ->with('error', 'Admin has not yet requested downpayment.');
        }

        // Check if already has pending downpayment
        if ($event->billing) {
            $hasPending = $event->billing->payments()
                ->where('payments.payment_type', Payment::TYPE_DOWNPAYMENT)
                ->where('payments.status', Payment::STATUS_PENDING)
                ->exists();

            if ($hasPending) {
                return redirect()
                    ->route('customer.events.show', $event)
                    ->with('info', 'You already have a pending downpayment submission.');
            }
        }

        // Calculate amount (downpayment - intro payment already paid)
        $amount = $event->billing->downpayment_amount - $event->billing->introductory_payment_amount;
        $paymentType = 'downpayment';


        $hasApprovedDownpayment = $event->billing->payments()
            ->where('payment_type', Payment::TYPE_DOWNPAYMENT)
            ->where('status', Payment::STATUS_APPROVED)
            ->exists();

        return view('customers.payments.create', compact('event', 'amount', 'paymentType', 'hasApprovedDownpayment'));
    }

    /**
     * Show Balance form
     */
    public function createBalancePayment(Event $event)
    {
        $customer = Auth::user()->customer;

        if (!$customer || $event->customer_id !== $customer->id) {
            abort(403);
        }

        if (!$event->billing) {
            return redirect()
                ->route('customer.events.show', $event)
                ->with('error', 'No billing information found for this event.');
        }

        if (!$event->hasDownpaymentPaid()) {
            return redirect()
                ->route('customer.events.show', $event)
                ->with('error', 'You must complete your downpayment before making balance payments.');
        }

        $remainingBalance = $event->billing->remaining_balance;

        if ($remainingBalance <= 0) {
            return redirect()
                ->route('customer.events.show', $event)
                ->with('info', 'Your event is fully paid! No remaining balance.');
        }

        $amount = $remainingBalance;
        $paymentType = 'balance';

        $hasApprovedDownpayment = $event->billing->payments()
            ->where('payment_type', Payment::TYPE_DOWNPAYMENT)
            ->where('status', Payment::STATUS_APPROVED)
            ->exists();


        return view('customers.payments.create', compact('event', 'amount', 'paymentType', 'hasApprovedDownpayment'));
    }

    /**
     * Store payment submission
     */
    public function store(Request $request, Event $event)
    {
        $customer = Auth::user()->customer;

        if (!$customer || $event->customer_id !== $customer->id) {
            abort(403);
        }

        $data = $request->validate([
            'payment_type' => ['required', 'in:introductory,downpayment,balance'],
            'payment_receipt' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
            'amount' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string', 'max:255'],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'pay_in_full' => ['nullable', 'boolean'],
        ]);

        $payInFull = $request->boolean('pay_in_full');

        // Validate introductory payment
        if ($data['payment_type'] === 'introductory') {
            if ($event->status !== Event::STATUS_REQUEST_MEETING) {
                return back()->with('error', 'Cannot submit introductory payment at this stage.');
            }

            if ($payInFull) {
                // Paying full amount
                if (!$event->billing || $event->billing->total_amount <= 0) {
                    return back()->with('error', 'Billing information not available.');
                }

                $expectedAmount = $event->billing->total_amount;
                if (abs((float)$data['amount'] - $expectedAmount) > 0.01) {
                    return back()->with('error', 'Full payment amount must be exactly ₱' . number_format($expectedAmount, 2));
                }
            } else {
                // Paying intro only
                if ((float)$data['amount'] !== 5000.00) {
                    return back()->with('error', 'Introductory payment must be exactly ₱5,000.00');
                }
            }

            // Check for existing pending
            if ($event->billing) {
                $hasPending = $event->billing->payments()
                    ->where('payments.payment_type', Payment::TYPE_INTRODUCTORY)
                    ->where('payments.status', Payment::STATUS_PENDING)
                    ->exists();

                if ($hasPending) {
                    return back()->with('error', 'You already have a pending introductory payment.');
                }
            }
        }

        // Validate downpayment
        elseif ($data['payment_type'] === 'downpayment') {
            if ($event->status === Event::STATUS_REQUESTED) {
                return back()->with('error', 'Cannot submit downpayment at this stage.');
            }

            if (!$event->billing || $event->billing->downpayment_amount <= 0) {
                return back()->with('error', 'Downpayment has not been requested yet.');
            }

            if ($payInFull) {
                // Paying full remaining balance
                $expectedAmount = $event->billing->remaining_balance;
                if (abs((float)$data['amount'] - $expectedAmount) > 0.01) {
                    return back()->with('error', 'Full payment amount must be exactly ₱' . number_format($expectedAmount, 2));
                }
            } else {
                // Paying downpayment only (minus intro already paid)
                $minAmount = $event->billing->downpayment_amount - $event->billing->introductory_payment_amount;
                $maxAmount = $event->billing->remaining_balance;

                if ((float)$data['amount'] < $minAmount) {
                    return back()->with('error', 'Payment must be at least ₱' . number_format($minAmount, 2) . ' (downpayment amount)');
                }

                if ((float)$data['amount'] > $maxAmount) {
                    return back()->with('error', 'Payment cannot exceed ₱' . number_format($maxAmount, 2) . ' (remaining balance)');
                }
            }

            // Check for existing pending
            if ($event->billing) {
                $hasPending = $event->billing->payments()
                    ->where('payments.payment_type', Payment::TYPE_DOWNPAYMENT)
                    ->where('payments.status', Payment::STATUS_PENDING)
                    ->exists();

                if ($hasPending) {
                    return back()->with('error', 'You already have a pending downpayment.');
                }
            }
        }

        // Validate balance payment
        elseif ($data['payment_type'] === 'balance') {
            if (!in_array($event->status, [Event::STATUS_SCHEDULED, Event::STATUS_ONGOING, Event::STATUS_COMPLETED])) {
                return back()->with('error', 'Cannot submit balance payment at this stage.');
            }

            if (!$event->billing) {
                return back()->with('error', 'No billing information found.');
            }

            if ((float)$data['amount'] > $event->billing->remaining_balance) {
                return back()->with('error', 'Payment amount cannot exceed remaining balance of ₱' . number_format($event->billing->remaining_balance, 2));
            }

            if ((float)$data['amount'] < 100) {
                return back()->with('error', 'Minimum payment amount is ₱100.00');
            }
        }

        $billing = $event->billing;

        if (!$billing) {
            return back()->with('error', 'Billing information not found for this event.');
        }

        // Store payment receipt
        $filePath = $request->file('payment_receipt')->store('payment_receipts', 'public');

        // Create payment record
        $payment = Payment::create([
            'billing_id' => $billing->id,
            'payment_type' => $data['payment_type'],
            'payment_image' => $filePath,
            'amount' => $data['amount'],
            'payment_method' => $data['payment_method'],
            'status' => Payment::STATUS_PENDING,
            'payment_date' => now(),
        ]);

        // Set success message
        if ($payInFull && in_array($data['payment_type'], ['introductory', 'downpayment'])) {
            $message = 'Full payment proof submitted (₱' . number_format($data['amount'], 2) . '). Please wait for admin verification.';
        } else {
            $message = match ($data['payment_type']) {
                'introductory' => 'Introductory payment proof submitted. Please wait for admin verification.',
                'downpayment' => 'Downpayment proof submitted. Please wait for admin verification.',
                'balance' => 'Balance payment proof submitted. Please wait for admin verification.',
                default => 'Payment proof submitted. Please wait for admin verification.',
            };
        }

        // Notify admins
        app(NotificationService::class)->notifyAdminPaymentSubmitted($payment);

        return redirect()
            ->route('customer.events.show', $event)
            ->with('success', $message);
    }

    /**
     * Show payment details
     */
    public function show(Payment $payment)
    {
        $customer = Auth::user()->customer;

        if (!$customer || $payment->billing->event->customer_id !== $customer->id) {
            abort(403);
        }

        $payment->load('billing.event');

        return view('customers.payments.show', compact('payment'));
    }
}
