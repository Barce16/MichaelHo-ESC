<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Staff;
use App\Models\Billing;
use App\Models\Package;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\EventApprovedNotification;
use App\Notifications\EventRejectedNotification;
use App\Notifications\IntroPaymentApprovedNotification;
use App\Notifications\DownpaymentApprovedNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class AdminEventController extends Controller
{
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
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.events.index',
            compact('events', 'packages', 'q', 'status', 'packageId', 'dateFrom', 'dateTo')
        );
    }

    public function show(Event $event)
    {
        $event->load([
            'customer.user:id,name,profile_photo_path',
            'package.images',
            'package.inclusions',
            'inclusions' => fn($q) => $q->withPivot('price_snapshot'),
            'billing.payments',
        ]);

        $inclusionsSubtotal = $event->inclusions->sum(fn($i) => (float) ($i->pivot->price_snapshot ?? 0));
        $coord  = (float) ($event->package?->coordination_price ?? 25000);
        $styling = (float) ($event->package?->event_styling_price ?? 55000);
        $grandTotal = $inclusionsSubtotal + $coord + $styling;

        $pendingIntroPayment = null;
        $pendingDownpayment = null;

        if ($event->billing) {
            $pendingIntroPayment = $event->billing->payments()
                ->where('payment_type', Payment::TYPE_INTRODUCTORY)
                ->where('status', Payment::STATUS_PENDING)
                ->latest()
                ->first();

            $pendingDownpayment = $event->billing->payments()
                ->where('payment_type', Payment::TYPE_DOWNPAYMENT)
                ->where('status', Payment::STATUS_PENDING)
                ->latest()
                ->first();
        }

        return view('admin.events.show', [
            'event' => $event,
            'grandTotal' => $grandTotal,
            'pendingIntroPayment' => $pendingIntroPayment,
            'pendingDownpayment' => $pendingDownpayment,
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
                'introductory_payment_amount' => 15000,
                'introductory_payment_status' => 'pending',
                'downpayment_amount' => $grandTotal / 2, // Automatically set to 50% of total
                'status' => 'pending',
            ]
        );

        // Create user account if customer doesn't have one
        $customer = $event->customer;
        $user = null;
        $password = '12345678';
        $username = null;

        if (!$customer->user_id) {
            $baseName = Str::slug(Str::lower($customer->customer_name));
            $username = $baseName;

            $counter = 1;
            while (User::where('username', $username)->exists()) {
                $username = $baseName . $counter;
                $counter++;
            }

            $user = User::create([
                'name' => $customer->customer_name,
                'username' => $username,
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
            $password = null;
        }

        // Update event status to request_meeting
        $event->update(['status' => Event::STATUS_REQUEST_MEETING]);

        // Send email notification
        if ($password && $user) {
            try {
                $user->notify(new EventApprovedNotification($event, $username, $password, $billing));
            } catch (\Exception $e) {
                Log::error('Failed to send approval email: ' . $e->getMessage());
            }
        }

        $message = 'Event approved! Customer must pay ₱15,000 introductory payment to schedule meeting.';
        if ($password) {
            $message .= ' Account credentials sent.';
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

        $event->update([
            'status' => Event::STATUS_REJECTED,
            'rejection_reason' => $data['rejection_reason'],
        ]);

        $customer = $event->customer;

        // Send email notification
        try {
            Notification::route('mail', $customer->email)
                ->notify(new EventRejectedNotification($event, $data['rejection_reason']));
        } catch (\Exception $e) {
            Log::error('Failed to send rejection email: ' . $e->getMessage());
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

        // Approve the payment
        $payment->update([
            'status' => Payment::STATUS_APPROVED,
            'payment_date' => now(),
        ]);

        // Mark billing intro payment as paid
        $event->billing->markIntroPaid();

        // Update event status to meeting
        $event->update(['status' => Event::STATUS_MEETING]);

        $billing = $event->billing;
        if ($billing && $billing->isFullyPaid()) {
            $billing->update(['status' => 'paid']);
        }

        // Send email notification
        if ($event->customer->user) {
            try {
                $event->customer->user->notify(new IntroPaymentApprovedNotification($event, $payment));
            } catch (\Exception $e) {
                Log::error('Failed to send intro payment approval email: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Introductory payment approved. Event status updated to Meeting. Customer notified via email.');
    }

    /**
     * Reject introductory payment
     */
    public function rejectIntroPayment(Request $request, Event $event)
    {
        $reason = $request->input('rejection_reason');

        $payment = $event->payments()
            ->where('payment_type', Payment::TYPE_INTRODUCTORY)
            ->where('status', Payment::STATUS_PENDING)
            ->latest()
            ->first();

        if (!$payment) {
            return back()->with('error', 'No pending introductory payment found.');
        }

        $payment->update([
            'status' => Payment::STATUS_REJECTED,
            'rejection_reason' => $reason,
        ]);

        return back()->with('success', 'Introductory payment rejected. Customer notified to resubmit.');
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

        return back()->with('success', 'Downpayment requested. Customer will pay ₱' . number_format($actualAmount, 2) . ' (after ₱15k deduction).');
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

        // Approve the payment
        $payment->update([
            'status' => Payment::STATUS_APPROVED,
            'payment_date' => now(),
        ]);


        $billing = $event->billing;
        if ($billing && $billing->isFullyPaid()) {
            $billing->update(['status' => 'paid']);
        }

        // Send email notification
        if ($event->customer->user) {
            try {
                $event->customer->user->notify(new DownpaymentApprovedNotification($event, $payment));
            } catch (\Exception $e) {
                Log::error('Failed to send downpayment approval email: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Downpayment approved. Customer notified via email.');
    }

    /**
     * Reject downpayment
     */
    public function rejectDownpayment(Request $request, Event $event)
    {
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

        return back()->with('success', 'Downpayment rejected. Customer notified to resubmit.');
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

        // Add staff with role and rate
        if ($request->has('staff')) {
            foreach ($request->staff as $staffId => $data) {
                $event->staffs()->syncWithoutDetaching([
                    $staffId => [
                        'assignment_role' => $data['role'],
                        'pay_rate' => $data['rate'],
                        'pay_status' => 'pending'
                    ]
                ]);
            }
        }

        // Remove staff
        if ($request->has('removed_staff_ids')) {
            $event->staffs()->detach($request->removed_staff_ids);
        }

        return redirect()->route('admin.events.assignStaffPage', $event)
            ->with('success', 'Staff assignment updated successfully');
    }

    public function updateStaff(Request $request, Event $event)
    {
        $data = $request->validate([
            'staff_ids'   => ['array'],
            'staff_ids.*' => ['integer', 'exists:staffs,id'],
        ]);

        $event->staffs()->sync($data['staff_ids'] ?? []);

        return back()->with('success', 'Staff assignments updated.');
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
        $event->staffs()->detach($staff->id);

        return back()->with('success', 'Staff removed.');
    }

    public function completeMeeting(Event $event)
    {
        if ($event->status !== Event::STATUS_MEETING) {
            return back()->with('error', 'Event is not in meeting status.');
        }

        $event->update(['status' => Event::STATUS_SCHEDULED]);

        return back()->with('success', 'Meeting marked as complete. Event is now SCHEDULED. You can now add Staffs.');
    }
}
