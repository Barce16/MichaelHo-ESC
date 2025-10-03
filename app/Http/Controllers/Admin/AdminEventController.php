<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Staff;
use App\Models\Billing;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Meeting;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\EventApprovedNotification;
use App\Notifications\EventRejectedNotification;
use Illuminate\Support\Facades\Hash;
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
            ->orderByDesc('event_date')
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
            'billing.payment',
        ]);

        $inclusionsSubtotal = $event->inclusions->sum(fn($i) => (float) ($i->pivot->price_snapshot ?? 0));
        $coord  = (float) ($event->package?->coordination_price ?? 25000);
        $styling = (float) ($event->package?->event_styling_price ?? 55000);
        $grandTotal = $inclusionsSubtotal + $coord + $styling;

        $paymentAmount = $event->billing?->payment?->amount ?? 0;

        $isDownpaymentPending = $event->isDownpaymentPending();

        return view('admin.events.show', [
            'event'        => $event,
            'grandTotal'   => $grandTotal,
            'paymentAmount' => $paymentAmount,
            'isDownpaymentPending'  => $isDownpaymentPending,
        ]);
    }


    public function updateStatus(Request $request, Event $event)
    {
        $data = $request->validate([
            'status' => ['required', 'in:requested,approved,scheduled,completed,cancelled'],
        ]);

        $event->update(['status' => $data['status']]);

        return back()->with('success', 'Event status updated.');
    }

    public function approve(Request $request, Event $event)
    {
        $data = $request->validate([
            'downpayment_amount' => ['required', 'numeric', 'min:0'],
        ]);

        $inclusionsSubtotal = $event->inclusions->sum(fn($i) => (float) ($i->pivot->price_snapshot ?? 0));
        $coord = (float) ($event->package?->coordination_price ?? 25000);
        $styling = (float) ($event->package?->event_styling_price ?? 55000);
        $grandTotal = $inclusionsSubtotal + $coord + $styling;

        $billing = Billing::where('event_id', $event->id)->first();

        if (!$billing) {
            $billing = Billing::create([
                'event_id' => $event->id,
                'downpayment_amount' => $data['downpayment_amount'],
                'total_amount' => $grandTotal,
                'status' => 'pending',
            ]);
        } else {
            $billing->downpayment_amount = $data['downpayment_amount'];
            $billing->total_amount = $grandTotal; // Make sure to update total
            $billing->status = 'pending';
            $billing->save();
        }

        // Refresh billing to get latest data
        $billing->refresh();

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

        $event->status = 'approved';
        $event->save();

        // Send email notification
        if ($password && $user) {
            try {
                // Pass the refreshed billing data
                $user->notify(new EventApprovedNotification($event, $username, $password, $billing));
            } catch (\Exception $e) {
                return back()->with('warning', 'Event approved but failed to send email notification. Please contact the customer manually.');
            }
        }

        return back()->with('success', 'Event approved! ' . ($password ? 'Account credentials sent to customer via email.' : 'Customer notified via email.'));
    }
    public function reject(Request $request, Event $event)
    {
        $data = $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $event->update([
            'status' => 'rejected',
            'rejection_reason' => $data['rejection_reason'],
        ]);

        // Send rejection email to customer
        $customer = $event->customer;

        try {
            // Send to customer's email directly (no user account needed)
            Notification::route('mail', $customer->email)
                ->notify(new EventRejectedNotification($event, $data['rejection_reason']));
        } catch (\Exception $e) {
            return back()->with('warning', 'Event rejected but failed to send email notification. Please contact the customer manually.');
        }

        return back()->with('success', 'Event rejected and notification sent to customer.');
    }

    public function confirm(Request $request, Event $event)
    {
        $data = $request->validate([
            'confirmation_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $event->update([
            'status' => 'scheduled',
        ]);

        return back()->with('success', 'Event confirmed and scheduled.');
    }

    public function assignStaffPage(Event $event)
    {
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
            'staff_ids' => 'array|nullable',
            'staff_ids.*' => 'exists:staffs,id',
            'removed_staff_ids' => 'array|nullable',
            'removed_staff_ids.*' => 'exists:staffs,id',
        ]);

        if ($request->has('staff_ids')) {
            $event->staffs()->syncWithoutDetaching($request->staff_ids);
        }

        if ($request->has('removed_staff_ids')) {
            $event->staffs()->detach($request->removed_staff_ids);
        }

        return redirect()->route('admin.events.assignStaffPage', $event)->with('success', 'Staff updated successfully');
    }

    public function approvePayment(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);

        $billing = $event->billing;

        $payment = new Payment();
        $payment->billing_id = $billing->id;
        $payment->amount = $request->amount;
        $payment->payment_method = $request->payment_method;
        $payment->payment_date = now();

        if ($request->hasFile('payment_image')) {
            $payment->payment_image = $request->file('payment_image')->store('payment_proofs');
        }

        $payment->status = 'approved';
        $payment->save();

        $billing->status = 'paid';
        $billing->save();

        $this->scheduleMeeting($event);

        return redirect()->route('admin.events.show', $event)->with('success', 'Payment approved and meeting scheduled.');
    }

    public function scheduleMeeting(Event $event)
    {
        $meeting = new Meeting();
        $meeting->event_id = $event->id;
        $meeting->meeting_date = now()->addWeek();
        $meeting->location = 'Online (Zoom link here)';
        $meeting->agenda = 'Event Preparation Meeting';
        $meeting->save();
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

    public function addStaff(Request $request, Event $event)
    {
        $data = $request->validate([
            'staff_id' => 'required|exists:staffs,id',
            'role' => 'required|string|max:255',
            'pay_rate' => 'required|numeric',
        ]);

        $event->staffs()->attach($data['staff_id'], [
            'assignment_role' => $data['role'],
            'pay_rate' => $data['pay_rate'],
            'pay_status' => 'pending',
        ]);

        return back()->with('success', 'Staff added to the event.');
    }

    // Remove staff from event
    public function removeStaff(Event $event, Staff $staff)
    {
        $event->staffs()->detach($staff->id);

        return back()->with('success', 'Staff removed.');
    }
}
