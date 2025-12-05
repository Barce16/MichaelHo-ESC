<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Staff;
use App\Models\Billing;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Inclusion;
use App\Models\EventProgress;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\EventApprovedNotification;
use App\Notifications\EventRejectedNotification;
use App\Notifications\IntroPaymentApprovedNotification;
use App\Notifications\DownpaymentApprovedNotification;
use App\Notifications\EventApprovedExistingUserNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\NotificationService;
use App\Services\SmsNotifier;

class AdminEventController extends Controller
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
        $q         = (string) $request->query('q', '');
        $status    = (string) $request->query('status', '');
        $dateFrom  = $request->date('from');
        $dateTo    = $request->date('to');
        $packageId = $request->integer('package_id');

        $q = trim($q);
        $q = preg_replace('/[<>]/', '', $q);
        $q = mb_substr($q, 0, 120);

        $packages = Package::orderBy('name')->get(['id', 'name']);

        $events = Event::query()
            ->with(['customer:id,customer_name,email', 'package:id,name'])
            ->when($q !== '', function ($s) use ($q) {
                $s->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%{$q}%")
                        ->orWhere('venue', 'like', "%{$q}%")
                        ->orWhereHas('customer', function ($c) use ($q) {
                            $c->where('customer_name', 'like', "%{$q}%")
                                ->orWhere('email', 'like', "%{$q}%");
                        });
                });
            })
            ->when($status !== '', fn($s) => $s->where('status', $status))
            ->when($packageId, fn($s) => $s->where('package_id', $packageId))
            ->when($dateFrom, fn($s) => $s->whereDate('event_date', '>=', $dateFrom))
            ->when($dateTo,   fn($s) => $s->whereDate('event_date', '<=', $dateTo))
            ->orderByDesc('updated_at')
            ->paginate(10)
            ->withQueryString();

        // All schedules for admin calendar
        $schedules = \App\Models\EventSchedule::with(['inclusion', 'event'])
            ->whereNotNull('scheduled_date')
            ->get();

        return view(
            'admin.events.index',
            compact('events', 'packages', 'q', 'status', 'packageId', 'dateFrom', 'dateTo', 'schedules')
        );
    }

    public function show(Event $event)
    {
        $event->load([
            'customer.user:id,name,profile_photo_path',
            'package.images',
            'package.inclusions',
            'inclusions' => fn($q) => $q->withPivot('price_snapshot', 'notes'),
            'billing.payments',
            'schedules',
            'schedules.inclusion',
            'changeRequests',
            'expenses', // Load expenses
        ]);

        $inclusionsSubtotal = $event->inclusions->sum(fn($i) => (float) ($i->pivot->price_snapshot ?? 0));
        $coord  = (float) ($event->package?->coordination_price ?? 25000);
        $styling = (float) ($event->package?->event_styling_price ?? 55000);
        $packageTotal = $inclusionsSubtotal + $coord + $styling;

        // Expense calculations
        $expensesTotal = (float) $event->expenses->sum('amount');
        $unpaidExpensesTotal = (float) $event->expenses->where('payment_status', 'unpaid')->sum('amount');
        $paidExpensesTotal = (float) $event->expenses->where('payment_status', 'paid')->sum('amount');
        $unpaidExpensesCount = $event->expenses->where('payment_status', 'unpaid')->count();

        // Grand total now includes expenses
        $grandTotal = $packageTotal + $expensesTotal;

        $pendingIntroPayment = null;
        $pendingDownpayment = null;
        $hasDownpaymentPaid = false;

        $totalPaid = 0.0;
        $packagePaid = 0.0;
        $remainingBalance = $grandTotal;

        if ($event->billing) {
            // use the loaded payments collection to avoid extra queries
            $payments = $event->billing->payments;

            $pendingIntroPayment = $payments
                ->where('payment_type', Payment::TYPE_INTRODUCTORY)
                ->where('status', Payment::STATUS_PENDING)
                ->sortByDesc('created_at')
                ->first();

            $pendingDownpayment = $payments
                ->where('payment_type', Payment::TYPE_DOWNPAYMENT)
                ->where('status', Payment::STATUS_PENDING)
                ->sortByDesc('created_at')
                ->first();

            $hasDownpaymentPaid = $payments
                ->where('payment_type', Payment::TYPE_DOWNPAYMENT)
                ->where('status', Payment::STATUS_APPROVED)
                ->isNotEmpty();

            // sum all approved payments (including expense payments)
            $totalPaid = (float) $payments
                ->where('status', Payment::STATUS_APPROVED)
                ->sum('amount');

            // sum package payments only (excluding expense)
            $packagePaid = (float) $payments
                ->where('status', Payment::STATUS_APPROVED)
                ->where('payment_type', '!=', Payment::TYPE_EXPENSE)
                ->sum('amount');

            $remainingBalance = max(0, $grandTotal - $totalPaid);
        }

        // Package remaining balance (without expenses)
        $packageRemainingBalance = max(0, $packageTotal - $packagePaid);

        return view('admin.events.show', [
            'event' => $event,
            'grandTotal' => $grandTotal,
            'packageTotal' => $packageTotal,
            'pendingIntroPayment' => $pendingIntroPayment,
            'pendingDownpayment' => $pendingDownpayment,
            'hasDownpaymentPaid' => $hasDownpaymentPaid,
            'incSubtotal' => $inclusionsSubtotal,
            'coord' => $coord,
            'styl' => $styling,
            'totalPaid' => $totalPaid,
            'packagePaid' => $packagePaid,
            'remainingBalance' => $remainingBalance,
            'packageRemainingBalance' => $packageRemainingBalance,
            // Expense data
            'expensesTotal' => $expensesTotal,
            'unpaidExpensesTotal' => $unpaidExpensesTotal,
            'paidExpensesTotal' => $paidExpensesTotal,
            'unpaidExpensesCount' => $unpaidExpensesCount,
        ]);
    }
    /**
     * Approve event - Create billing and set status to request_meeting
     */
    public function approve(Request $request, Event $event)
    {
        // Calculate totals
        $inclusionsSubtotal = $event->inclusions->sum(fn($i) => (float) ($i->pivot->price_snapshot ?? 0));
        $coord = (float) ($event->package?->coordination_price ?? 25000);
        $styling = (float) ($event->package?->event_styling_price ?? 55000);
        $grandTotal = $inclusionsSubtotal + $coord + $styling;

        // Create or update billing
        $billing = Billing::updateOrCreate(
            ['event_id' => $event->id],
            [
                'total_amount' => $grandTotal,
                'introductory_payment_amount' => 5000,
                'introductory_payment_status' => 'pending',
                'downpayment_amount' => $grandTotal / 2,
                'status' => 'pending',
            ]
        );

        // Create user account if customer doesn't have one
        $customer = $event->customer;
        $user = null;
        $password = null;
        $username = null;
        $isNewUser = false;

        if (!$customer->user_id) {
            $isNewUser = true;
            $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 12);

            $firstName = explode(' ', $customer->customer_name)[0];
            $baseName = Str::slug(Str::lower($firstName));
            $username = $baseName . '-' . rand(100, 999);

            $counter = 1;
            while (User::where('username', $username)->exists()) {
                $username = $baseName . '-' . rand(100, 999);
                $counter++;

                // Safety: prevent infinite loop
                if ($counter > 10) {
                    $username = $baseName . '-' . rand(1000, 9999);
                    break;
                }
            }

            $user = User::create([
                'name' => $customer->customer_name,
                'username' => $username,
                'gender' => $customer->gender,
                'email' => $customer->email,
                'password' => Hash::make($password),
                'user_type' => 'customer',
                'status' => 'active',
            ]);

            $customer->user_id = $user->id;
            $customer->save();
        } else {
            $user = $customer->user;
            $username = $user->username;
        }

        // Store old status for notification
        $oldStatus = $event->status;

        // Update event status to request_meeting
        $event->update(['status' => Event::STATUS_REQUEST_MEETING]);


        // Create event progress record
        EventProgress::create([
            'event_id' => $event->id,
            'status' => 'Event Approved',
            'details' => "Event approved by admin. Customer account " . ($isNewUser ? "created" : "already exists") . ". Total event cost: ₱" . number_format($grandTotal, 2) . ". Introductory payment (₱5,000) required to proceed.",
            'progress_date' => now(),
        ]);

        // Send in-app notification
        $this->notificationService->notifyCustomerEventStatus($event, $oldStatus, Event::STATUS_REQUEST_MEETING);

        if ($isNewUser && $password) {
            // NEW USER: Send credentials

            // Send email with credentials
            try {
                $user->notify(new EventApprovedNotification($event, $username, $password, $billing));
            } catch (\Exception $e) {
                Log::error('Failed to send approval email: ' . $e->getMessage());
            }

            // Send SMS with credentials
            try {
                $this->smsNotifier->notifyEventApproved($event, $username, $password);
            } catch (\Exception $e) {
                Log::error('Failed to send event approval SMS', [
                    'event_id' => $event->id,
                    'error' => $e->getMessage()
                ]);
            }

            $message = 'Event approved! Customer must pay ₱5,000 introductory payment. Account credentials sent via email and SMS.';
        } else {
            // EXISTING USER: No credentials

            // Send email without credentials
            try {
                $user->notify(new EventApprovedExistingUserNotification($event, $billing));
            } catch (\Exception $e) {
                Log::error('Failed to send approval email: ' . $e->getMessage());
            }

            // Send SMS without credentials
            try {
                $this->smsNotifier->notifyEventApprovedExistingUser($event);
            } catch (\Exception $e) {
                Log::error('Failed to send event approval SMS', [
                    'event_id' => $event->id,
                    'error' => $e->getMessage()
                ]);
            }

            $message = 'Event approved! Customer must pay ₱5,000 introductory payment. Customer notified via email and SMS.';
        }

        return back()->with('success', $message);
    }

    /**
     * Reject event
     */
    public function reject(Request $request, Event $event)
    {
        $data = $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:1000'],
        ]);
        $oldStatus = $event->status;

        $event->update([
            'status' => Event::STATUS_REJECTED,
            'rejection_reason' => $data['rejection_reason'],
        ]);

        // Create event progress record
        EventProgress::create([
            'event_id' => $event->id,
            'status' => 'Event Rejected',
            'details' => $data['rejection_reason']
                ? "Event rejected by admin. Reason: {$data['rejection_reason']}"
                : "Event rejected by admin.",
            'progress_date' => now(),
        ]);

        $customer = $event->customer;

        // Send email notification
        try {
            Notification::route('mail', $customer->email)
                ->notify(new EventRejectedNotification($event, $data['rejection_reason']));
        } catch (\Exception $e) {
            Log::error('Failed to send rejection email: ' . $e->getMessage());
        }

        $this->notificationService->notifyCustomerEventStatus($event, $oldStatus, Event::STATUS_REJECTED);

        // Send SMS
        try {
            $this->smsNotifier->notifyEventStatusChange($event, Event::STATUS_REJECTED);
        } catch (\Exception $e) {
            Log::error('Failed to send event rejection SMS', [
                'event_id' => $event->id,
                'error' => $e->getMessage()
            ]);
        }

        return back()->with('success', 'Event rejected. Customer notified via email.');
    }

    /**
     * Verify and approve introductory payment
     */
    public function approveIntroPayment(Request $request, Event $event)
    {
        if (!$event->billing) {
            return back()->with('error', 'No billing found for this event.');
        }

        $payment = $event->billing->payments()
            ->where('payment_type', Payment::TYPE_INTRODUCTORY)
            ->where('status', Payment::STATUS_PENDING)
            ->latest()
            ->first();

        if (!$payment) {
            return back()->with('error', 'No pending introductory payment found.');
        }

        $billing = $event->billing;

        // Check if this is a FULL PAYMENT
        $isFullPayment = false;
        if ($billing->total_amount > 0) {
            // Check if payment amount equals total amount (full payment)
            if (abs($payment->amount - $billing->total_amount) < 0.01) {
                $isFullPayment = true;
            }
        }

        DB::beginTransaction();

        try {
            // Store old status for notification
            $oldStatus = $event->status;

            if ($isFullPayment) {
                // FULL PAYMENT - Split into 3 payment records

                // 1. Update intro payment to ₱5,000 portion only
                $payment->update([
                    'status' => Payment::STATUS_APPROVED,
                    'payment_date' => now(),
                    'amount' => 5000, // Split to intro portion only
                ]);

                // Mark billing intro payment as paid
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

                // Update event status to MEETING (keep meeting stage)
                $event->update(['status' => Event::STATUS_MEETING]);

                $message = 'Full payment approved! All payment stages completed. Event status: MEETING (fully paid).';
            } else {
                // REGULAR INTRO PAYMENT - Normal flow

                // Approve the payment
                $payment->update([
                    'status' => Payment::STATUS_APPROVED,
                    'payment_date' => now(),
                ]);

                // Mark billing intro payment as paid
                $billing->markIntroPaid();

                // Update event status to meeting
                $event->update(['status' => Event::STATUS_MEETING]);

                if ($billing->isFullyPaid()) {
                    $billing->update(['status' => 'paid']);
                }

                $message = 'Introductory payment approved. Event status updated to Meeting.';
            }

            // Send email notification
            if ($event->customer->user) {
                try {
                    $event->customer->user->notify(new IntroPaymentApprovedNotification($event, $payment));
                } catch (\Exception $e) {
                    Log::error('Failed to send intro payment approval email: ' . $e->getMessage());
                }
            }

            // Send in-app notification
            $this->notificationService->notifyCustomerPaymentApproved($payment);

            // Notify about event status change
            $this->notificationService->notifyCustomerEventStatus($event, $oldStatus, Event::STATUS_MEETING);

            // Send SMS notifications
            $smsSent = false;
            try {
                // SMS for payment approval
                $this->smsNotifier->notifyPaymentConfirmed($payment);

                // SMS for status change to meeting
                $this->smsNotifier->notifyEventStatusChange($event, Event::STATUS_MEETING);

                $smsSent = true;
            } catch (\Exception $e) {
                Log::error('Failed to send intro payment approval SMS', [
                    'payment_id' => $payment->id,
                    'event_id' => $event->id,
                    'error' => $e->getMessage()
                ]);
            }

            if ($smsSent) {
                $message .= ' Customer notified via email, SMS, and in-app notification.';
            } else {
                $message .= ' Customer notified via email and in-app notification.';
            }

            DB::commit();

            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to approve intro payment', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Failed to approve payment. Please try again.');
        }
    }

    /**
     * Reject introductory payment
     */
    public function rejectIntroPayment(Request $request, Event $event)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $reason = $request->input('rejection_reason');

        // Fix: Specify the table name for the status column
        $payment = $event->payments()
            ->where('payment_type', Payment::TYPE_INTRODUCTORY)
            ->where('payments.status', Payment::STATUS_PENDING) // Add table prefix here
            ->latest()
            ->first();

        if (!$payment) {
            return back()->with('error', 'No pending introductory payment found.');
        }

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
            Log::error('Failed to send intro payment rejection SMS', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
        }

        $message = 'Introductory payment rejected.';
        if ($smsSent) {
            $message .= ' Customer notified via SMS and in-app notification to resubmit.';
        } else {
            $message .= ' Customer notified via in-app notification to resubmit.';
        }

        return back()->with('success', $message);
    }

    /**
     * Request downpayment from customer
     */
    public function requestDownpayment(Request $request, Event $event)
    {
        $data = $request->validate([
            'downpayment_amount' => ['required', 'numeric', 'min:0'],
        ]);

        // Validate event is in meeting status
        if ($event->status !== Event::STATUS_MEETING) {
            return back()->with('error', 'Can only request downpayment when event is in Meeting status.');
        }

        $billing = $event->billing;

        if (!$billing) {
            return back()->with('error', 'No billing found for this event.');
        }

        // Calculate actual amount customer needs to pay (minus intro payment)
        $actualAmount = max(0, $data['downpayment_amount'] - $billing->introductory_payment_amount);

        // Update billing
        $billing->update([
            'downpayment_amount' => $data['downpayment_amount'], // Store full amount
        ]);

        return back()->with('success', 'Downpayment requested. Customer will pay ₱' . number_format($actualAmount, 2) . ' (after ₱5k deduction).');
    }

    /**
     * Approve downpayment 
     */
    public function approveDownpayment(Request $request, Event $event)
    {
        if (!$event->billing) {
            return back()->with('error', 'No billing found for this event.');
        }

        $payment = $event->billing->payments()
            ->where('payment_type', Payment::TYPE_DOWNPAYMENT)
            ->where('status', Payment::STATUS_PENDING)
            ->latest()
            ->first();

        if (!$payment) {
            return back()->with('error', 'No pending downpayment found.');
        }

        $billing = $event->billing;

        // Check if this is a FULL PAYMENT (paying remaining balance)
        $isFullPayment = false;
        if ($billing->remaining_balance > 0) {
            // Check if payment amount equals remaining balance (full payment)
            if (abs($payment->amount - $billing->remaining_balance) < 0.01) {
                $isFullPayment = true;
            }
        }

        DB::beginTransaction();

        try {

            $originalPaymentAmount = $payment->amount;
            $downpaymentPortion = $billing->downpayment_amount - $billing->introductory_payment_amount;

            // Check payment type
            $isFullPayment = abs($originalPaymentAmount - $billing->remaining_balance) < 0.01;
            $isCustomAmount = $originalPaymentAmount > $downpaymentPortion && !$isFullPayment;

            if ($isFullPayment || $isCustomAmount) {
                // Split payment

                // 1. Update to downpayment portion
                $payment->update([
                    'status' => Payment::STATUS_APPROVED,
                    'payment_date' => now(),
                    'amount' => $downpaymentPortion,
                ]);

                $billing->update(['downpayment_payment_status' => 'paid']);

                // 2. Create balance payment for the rest
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

                // 3. Check if fully paid
                if ($billing->isFullyPaid()) {
                    $billing->update(['status' => 'paid']);
                    $message = 'Full payment approved! Event is now fully paid.';
                } else {
                    $message = 'Payment approved! ₱' . number_format($downpaymentPortion, 2) . ' applied to downpayment, ₱' . number_format($balanceAmount, 2) . ' applied to balance.';
                }
            } else {
                // REGULAR DOWNPAYMENT - Normal flow

                // Approve the payment
                $payment->update([
                    'status' => Payment::STATUS_APPROVED,
                    'payment_date' => now(),
                ]);

                $billing->update(['downpayment_payment_status' => 'paid']);

                if ($billing->isFullyPaid()) {
                    $billing->update(['status' => 'paid']);
                }

                $message = 'Downpayment approved.';
            }

            // Send in-app notification
            $this->notificationService->notifyCustomerPaymentApproved($payment);

            // Send email notification
            if ($event->customer->user) {
                try {
                    $event->customer->user->notify(new DownpaymentApprovedNotification($event, $payment));
                } catch (\Exception $e) {
                    Log::error('Failed to send downpayment approval email: ' . $e->getMessage());
                }
            }

            // Send SMS notification
            $smsSent = false;
            try {
                $this->smsNotifier->notifyPaymentConfirmed($payment);
                $smsSent = true;
            } catch (\Exception $e) {
                Log::error('Failed to send downpayment approval SMS', [
                    'payment_id' => $payment->id,
                    'error' => $e->getMessage()
                ]);
            }

            if ($smsSent) {
                $message .= ' Customer notified via email, SMS, and in-app notification.';
            } else {
                $message .= ' Customer notified via email and in-app notification.';
            }

            DB::commit();

            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to approve downpayment', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Failed to approve payment. Please try again.');
        }
    }

    /**
     * Reject downpayment
     */
    public function rejectDownpayment(Request $request, Event $event)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $reason = $request->input('rejection_reason');

        if (!$event->billing) {
            return back()->with('error', 'No billing found for this event.');
        }

        $payment = $event->billing->payments()
            ->where('payment_type', Payment::TYPE_DOWNPAYMENT)
            ->where('status', Payment::STATUS_PENDING)
            ->latest()
            ->first();

        if (!$payment) {
            return back()->with('error', 'No pending downpayment found.');
        }

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
            Log::error('Failed to send downpayment rejection SMS', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
        }

        $message = 'Downpayment rejected.';
        if ($smsSent) {
            $message .= ' Customer notified via SMS and in-app notification to resubmit.';
        } else {
            $message .= ' Customer notified via in-app notification to resubmit.';
        }

        return back()->with('success', $message);
    }

    /**
     * Assign staff page - Only for scheduled events
     */
    public function assignStaffPage(Event $event)
    {
        // Check if event can have staff assigned
        if (!$event->canAssignStaff()) {
            return back()->with('error', 'Staff can only be assigned to scheduled or ongoing events.');
        }

        $event->load(['staffs']);

        $assignedStaffIds = $event->staffs->pluck('id')->toArray();
        $availableStaff = Staff::whereNotIn('id', $assignedStaffIds)->get();

        return view('admin.events.assign-staff', [
            'event' => $event,
            'availableStaff' => $availableStaff,
            'assignedStaff' => $event->staffs,
        ]);
    }

    public function assignStaff(Request $request, Event $event)
    {
        $request->validate([
            'staff' => 'array|nullable',
            'staff.*.role' => 'required_with:staff|string|max:255',
            'staff.*.rate' => 'required_with:staff|numeric|min:0',
            'removed_staff_ids' => 'array|nullable',
            'removed_staff_ids.*' => 'exists:staffs,id',
        ]);

        $newlyAssignedCount = 0;
        $removedCount = 0;

        // Add staff with role and rate
        if ($request->has('staff')) {
            foreach ($request->staff as $staffId => $data) {
                // Check if this is a new assignment
                $isNewAssignment = !$event->staffs()->where('staff_id', $staffId)->exists();

                $event->staffs()->syncWithoutDetaching([
                    $staffId => [
                        'assignment_role' => $data['role'],
                        'pay_rate' => $data['rate'],
                        'pay_status' => 'pending'
                    ]
                ]);

                // Send notification only for new assignments
                if ($isNewAssignment) {
                    $staff = Staff::find($staffId);
                    if ($staff && $staff->user) {
                        // Get the pivot data for the notification
                        $staffAssignment = (object)[
                            'staff' => $staff,
                            'event' => $event,
                            'assignment_role' => $data['role'],
                            'pay_rate' => $data['rate'],
                        ];

                        $this->notificationService->notifyStaffNewSchedule($staffAssignment);
                        $newlyAssignedCount++;
                    }
                }
            }
        }

        // Remove staff
        if ($request->has('removed_staff_ids')) {
            $removedCount = count($request->removed_staff_ids);
            $event->staffs()->detach($request->removed_staff_ids);
        }

        // Build success message
        $message = 'Staff assignment updated successfully.';
        if ($newlyAssignedCount > 0) {
            $message .= " {$newlyAssignedCount} staff member(s) notified of new assignment.";
        }
        if ($removedCount > 0) {
            $message .= " {$removedCount} staff member(s) removed.";
        }

        return redirect()->route('admin.events.assignStaffPage', $event)
            ->with('success', $message);
    }

    public function updateStaff(Request $request, Event $event)
    {
        $data = $request->validate([
            'staff_ids'   => ['array'],
            'staff_ids.*' => ['integer', 'exists:staffs,id'],
        ]);

        // Get currently assigned staff
        $currentStaffIds = $event->staffs()->pluck('staff_id')->toArray();
        $newStaffIds = $data['staff_ids'] ?? [];

        // Find newly added staff (not previously assigned)
        $addedStaffIds = array_diff($newStaffIds, $currentStaffIds);

        // Sync staff assignments
        $event->staffs()->sync($newStaffIds);

        // Notify newly added staff
        $notifiedCount = 0;
        foreach ($addedStaffIds as $staffId) {
            $staff = Staff::find($staffId);
            if ($staff && $staff->user) {
                $staffAssignment = (object)[
                    'staff' => $staff,
                    'event' => $event,
                ];

                $this->notificationService->notifyStaffNewSchedule($staffAssignment);
                $notifiedCount++;
            }
        }

        $message = 'Staff assignments updated.';
        if ($notifiedCount > 0) {
            $message .= " {$notifiedCount} staff member(s) notified of new assignment.";
        }

        return back()->with('success', $message);
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return back()->with('success', 'Event deleted.');
    }

    public function guests(Event $event)
    {
        $guests = $event->guests;

        return view('admin.events.guests', compact('event', 'guests'));
    }

    public function staffs(Event $event)
    {
        $staffs = $event->staffs;

        $availableStaff = Staff::whereDoesntHave('events', function ($query) use ($event) {
            $query->where('event_id', $event->id);
        })->get();

        return view('admin.events.staffs', compact('event', 'staffs', 'availableStaff'));
    }

    public function removeStaff(Event $event, Staff $staff)
    {
        // Check if staff is assigned to this event
        $wasAssigned = $event->staffs()->where('staff_id', $staff->id)->exists();

        if ($wasAssigned) {
            $event->staffs()->detach($staff->id);

            // Optional: Notify staff of removal
            if ($staff->user) {
                $this->notificationService->notifyStaffScheduleRemoved($staff, $event);
            }

            return back()->with('success', "{$staff->staff_name} removed from event.");
        }

        return back()->with('info', 'Staff was not assigned to this event.');
    }

    public function completeMeeting(Event $event)
    {
        if ($event->status !== Event::STATUS_MEETING) {
            return back()->with('error', 'Event is not in meeting status.');
        }

        $event->update(['status' => Event::STATUS_SCHEDULED]);

        // Create event progress record
        EventProgress::create([
            'event_id' => $event->id,
            'status' => 'Meeting Completed',
            'details' => 'Event meeting completed by admin. Event is now scheduled and ready for staff assignment.',
            'progress_date' => now(),
        ]);

        return back()->with('success', 'Meeting marked as complete. Event is now SCHEDULED. You can now add Staffs.');
    }


    /**
     * Show edit inclusions page
     */
    public function editInclusions(Event $event)
    {
        $event->load([
            'package',
            'customer',
            'inclusions' => fn($q) => $q->withPivot('notes')
        ]);

        $packageType = $event->package->type;

        $availableInclusions = Inclusion::where('is_active', true)
            ->where(function ($query) use ($packageType) {
                $query->where('package_type', $packageType)
                    ->orWhereNull('package_type')
                    ->orWhere('package_type', '');
            })
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        $selectedInclusionIds = $event->inclusions->pluck('id');

        return view('admin.events.edit-inclusions', compact('event', 'availableInclusions', 'selectedInclusionIds'));
    }

    /**
     * Update event inclusions and recalculate billing
     */
    public function updateInclusions(Request $request, Event $event)
    {
        $request->validate([
            'inclusions' => 'nullable|array',
            'inclusions.*' => 'exists:inclusions,id',
            'inclusion_notes' => 'nullable|array',
            'inclusion_notes.*' => 'nullable|string|max:500',
            'locked_inclusions' => 'nullable|array',
            'locked_inclusions.*' => 'exists:inclusions,id',
        ]);

        // Store old total before updating
        $oldTotal = $event->billing ? $event->billing->total_amount : 0;

        // Store old inclusions for comparison - LOAD FULL OBJECTS
        $oldInclusionIds = $event->inclusions->pluck('id')->toArray();
        $oldInclusions = $event->inclusions; // Keep the collection

        // Get categories that currently have inclusions
        $oldCategoryIds = $oldInclusions->pluck('category')->unique()->toArray();

        // Get submitted inclusions
        $selectedInclusionIds = $request->input('inclusions', []);

        // ALWAYS merge locked inclusions - they can never be removed
        $lockedInclusionIds = $request->input('locked_inclusions', []);
        $selectedInclusionIds = array_unique(array_merge($selectedInclusionIds, $lockedInclusionIds));

        // Get the NEW inclusions with their categories
        $newInclusions = Inclusion::whereIn('id', $selectedInclusionIds)->get();

        // Validate: Each category that had inclusions must still have at least one
        $missingCategories = [];
        foreach ($oldCategoryIds as $category) {
            $categoryHasInclusion = $newInclusions->contains(function ($inclusion) use ($category) {
                // Handle both enum and string category comparison
                $inclusionCategory = is_object($inclusion->category) ? $inclusion->category->value : $inclusion->category;
                $checkCategory = is_object($category) ? $category->value : $category;
                return $inclusionCategory === $checkCategory;
            });

            if (!$categoryHasInclusion) {
                // Get category name for error message
                $categoryName = is_object($category) ? $category->value : $category;
                $missingCategories[] = ucwords(str_replace('_', ' ', $categoryName));
            }
        }

        if (!empty($missingCategories)) {
            return redirect()
                ->back()
                ->with('error', 'Each category must have at least one inclusion. Missing: ' . implode(', ', $missingCategories));
        }

        // Get inclusion notes
        $inclusionNotes = $request->input('inclusion_notes', []);

        // Prepare sync data with price snapshots and notes
        $syncData = [];
        foreach ($newInclusions as $inclusion) {
            // For existing inclusions, preserve their original price_snapshot
            $existingPivot = $oldInclusions->find($inclusion->id)?->pivot;

            $syncData[$inclusion->id] = [
                'price_snapshot' => $existingPivot?->price_snapshot ?? $inclusion->price,
                'notes' => $inclusionNotes[$inclusion->id] ?? $existingPivot?->notes ?? null,
            ];
        }

        // Sync inclusions
        $event->inclusions()->sync($syncData);

        // Recalculate billing
        $this->recalculateBilling($event);

        // Refresh event to get updated billing
        $event->refresh();
        $newTotal = $event->billing->total_amount;

        // Track what changed - now includes both additions AND removals
        $addedInclusionIds = array_diff($selectedInclusionIds, $oldInclusionIds);
        $removedInclusionIds = array_diff($oldInclusionIds, $selectedInclusionIds);

        // Get full inclusion objects for added items
        $addedInclusions = Inclusion::whereIn('id', $addedInclusionIds)->get();

        // Get removed inclusions from old collection
        $removedInclusions = $oldInclusions->whereIn('id', $removedInclusionIds);

        // Build change details for progress log
        $changes = [];
        if ($addedInclusions->count() > 0) {
            $added = $addedInclusions->pluck('name')->toArray();
            $changes[] = "Added: " . implode(', ', $added);
        }
        if ($removedInclusions->count() > 0) {
            $removed = $removedInclusions->pluck('name')->toArray();
            $changes[] = "Swapped out: " . implode(', ', $removed);
        }

        // If no changes, just show success
        if (empty($changes)) {
            return redirect()
                ->route('admin.events.show', $event)
                ->with('info', 'No changes were made to the inclusions.');
        }

        $changeDetails = implode(". ", $changes) . ".";

        // Create event progress record
        EventProgress::create([
            'event_id' => $event->id,
            'status' => 'Inclusions Updated',
            'details' => "Event inclusions updated by admin. {$changeDetails} Total amount changed from ₱" . number_format($oldTotal, 2) . " to ₱" . number_format($newTotal, 2) . ".",
            'progress_date' => now(),
        ]);

        // Send notification to customer - PASS DETAILED CHANGES
        $customer = $event->customer;
        if ($customer->user) {
            $customer->user->notify(new \App\Notifications\InclusionsUpdatedNotification(
                $event,
                $oldTotal,
                $newTotal,
                $addedInclusions,
                $removedInclusions
            ));
        }

        // Create in-app notification
        $this->notificationService->notifyCustomerInclusionsUpdated($event, $oldTotal, $newTotal);

        // Send SMS notification
        try {
            $this->smsNotifier->notifyInclusionsUpdated($event, $oldTotal, $newTotal);
        } catch (\Exception $e) {
            Log::error('Failed to send inclusions updated SMS', ['event_id' => $event->id, 'error' => $e->getMessage()]);
        }

        return redirect()
            ->route('admin.events.show', $event)
            ->with('success', 'Event inclusions updated successfully. Customer has been notified via email with detailed changes.');
    }
    /**
     * Recalculate billing based on package and inclusions
     */
    protected function recalculateBilling(Event $event)
    {
        $event->load(['package', 'inclusions', 'billing']);

        // Calculate total from coordination + event_styling + inclusions
        // DO NOT use package->price as it includes base inclusions
        $coordinationPrice = $event->package->coordination_price ?? 0;
        $eventStylingPrice = $event->package->event_styling_price ?? 0;
        $inclusionsTotal = $event->inclusions->sum('pivot.price_snapshot');

        $newTotal = $coordinationPrice + $eventStylingPrice + $inclusionsTotal;

        // Get or create billing
        $billing = $event->billing;
        if (!$billing) {
            $billing = new Billing();
            $billing->event_id = $event->id;
        }

        // Update billing total only
        $billing->total_amount = $newTotal;

        $billing->save();
    }

    /**
     * Update event basic information (date, venue, theme, guests, notes)
     */
    public function updateInfo(Request $request, Event $event)
    {
        $validated = $request->validate([
            'event_date' => ['required', 'date'],
            'venue' => ['nullable', 'string', 'max:500'],
            'theme' => ['nullable', 'string', 'max:255'],
            'guests' => ['nullable', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        // Track what changed for notification
        $changes = [];

        if ($event->event_date->format('Y-m-d') !== $validated['event_date']) {
            $changes[] = 'Event date changed from ' . $event->event_date->format('M d, Y') . ' to ' . \Carbon\Carbon::parse($validated['event_date'])->format('M d, Y');
        }

        if ($event->venue !== $validated['venue']) {
            $changes[] = 'Venue updated';
        }

        if ($event->theme !== $validated['theme']) {
            $changes[] = 'Theme updated';
        }

        if ($event->guests !== $validated['guests']) {
            $changes[] = 'Guest details updated';
        }

        if ($event->notes !== $validated['notes']) {
            $changes[] = 'Notes updated';
        }

        // Update the event
        $event->update([
            'event_date' => $validated['event_date'],
            'venue' => $validated['venue'],
            'theme' => $validated['theme'],
            'guests' => $validated['guests'],
            'notes' => $validated['notes'],
        ]);

        // Create event progress record if changes were made
        if (!empty($changes)) {
            EventProgress::create([
                'event_id' => $event->id,
                'status' => 'Information Updated',
                'details' => 'Event information updated by admin. ' . implode('. ', $changes) . '.',
                'progress_date' => now(),
            ]);

            // Optionally notify the customer
            // $this->notificationService->notifyCustomerEventInfoUpdated($event, $changes);
        }

        return redirect()
            ->route('admin.events.show', $event)
            ->with('success', 'Event information updated successfully.');
    }

    /**
     * Show the schedules management page for an event
     */
    public function schedulesPage(Event $event)
    {
        $event->load([
            'customer.user',
            'package',
            'inclusions' => fn($q) => $q->withPivot('price_snapshot', 'notes'),
            'schedules.inclusion',
            'schedules.staff',
            'staffs' => fn($q) => $q->withPivot('assignment_role'),
        ]);

        return view('admin.events.schedules', compact('event'));
    }
}
