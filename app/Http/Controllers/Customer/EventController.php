<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\Package;
use App\Models\Inclusion;
use App\Models\Payment;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;

class EventController extends Controller
{

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function index(Request $request)
    {
        $customer = $request->user()->customer;
        abort_if(!$customer, 403);

        $events = Event::with(['package'])
            ->where('customer_id', $customer->id)
            ->orderByDesc('event_date')
            ->paginate(12);

        return view('customers.events.index', compact('events'));
    }

    public function create()
    {
        $packages = Package::where('is_active', true)
            ->with(['inclusions' => function ($query) {
                $query->where('is_active', true);
            }])
            ->get();

        $allInclusions = Inclusion::where('is_active', true)
            ->get()
            ->groupBy('category');

        return view('customers.events.create', compact('packages', 'allInclusions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => ['required', 'string', 'max:150'],
            'event_date'   => ['required', 'date'],
            'package_id'   => ['required', 'exists:packages,id'],
            'venue'        => ['nullable', 'string', 'max:255'],
            'theme'        => ['nullable', 'string', 'max:255'],
            'budget'       => ['nullable', 'numeric', 'min:0'],
            'guests'       => ['nullable', 'string', 'max:5000'],
            'notes'        => ['nullable', 'string', 'max:5000'],

            'inclusions'   => ['nullable', 'array'],
            'inclusions.*' => ['integer', 'exists:inclusions,id'],
        ]);

        $package = Package::findOrFail($data['package_id']);

        // Get selected inclusions (or default to package inclusions if none selected)
        $selectedIds = collect($request->input('inclusions', []))
            ->map(fn($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();

        // If no inclusions selected, use package's default inclusions
        if ($selectedIds->isEmpty()) {
            $selectedIds = $package->inclusions->pluck('id');
        }

        // Validate that all selected inclusions are active
        $validInclusions = Inclusion::where('is_active', true)
            ->whereIn('id', $selectedIds)
            ->pluck('id');

        $invalid = $selectedIds->diff($validInclusions);
        if ($invalid->isNotEmpty()) {
            return back()
                ->withErrors(['inclusions' => 'Some selected inclusions are not available.'])
                ->withInput();
        }

        // Calculate pricing
        $inclusionPrices    = Inclusion::whereIn('id', $selectedIds)->pluck('price', 'id');
        $inclusionsSubtotal = $selectedIds->sum(fn($id) => (float) ($inclusionPrices[$id] ?? 0));
        $coordination       = (float) ($package->coordination_price ?? 25000);
        $styling            = (float) ($package->event_styling_price ?? 55000);
        $grandTotal         = $inclusionsSubtotal + $coordination + $styling;

        $user = $request->user();
        $customer = $user->customer ?? Customer::firstOrCreate(
            ['user_id' => $user->id],
            [
                'customer_name' => $user->name,
                'email'         => $user->email,
                'phone'         => $user->phone ?? null,
                'address'       => $user->address ?? null,
            ]
        );

        DB::transaction(function () use ($data, $customer, $selectedIds, $inclusionPrices) {
            $event = Event::create([
                'customer_id' => $customer->id,
                'name'        => $data['name'],
                'event_date'  => $data['event_date'],
                'package_id'  => $data['package_id'],
                'venue'       => $data['venue'] ?? null,
                'theme'       => $data['theme'] ?? null,
                'budget'      => $data['budget'] ?? null,
                'guests'      => $data['guests'] ?? null,
                'notes'       => $data['notes'] ?? null,
                'status'      => Event::STATUS_REQUESTED,
            ]);

            if ($selectedIds->isNotEmpty()) {
                $attach = [];
                foreach ($selectedIds as $incId) {
                    $attach[$incId] = ['price_snapshot' => (float) ($inclusionPrices[$incId] ?? 0)];
                }
                $event->inclusions()->attach($attach);
            }

            $this->notificationService->notifyAdminNewEventRequest($event);
        });

        return redirect()
            ->route('customer.events.index')
            ->with('success', 'Event request submitted. Please wait for admin approval.');
    }

    public function show(Event $event)
    {
        $customer = Auth::user()->customer;

        if (!$customer || $event->customer_id !== $customer->id) {
            abort(403);
        }

        $event->load(['package', 'inclusions', 'customer', 'billing.payments']);

        // Check for pending payments
        $pendingIntroPayment = null;
        $pendingDownpayment = null;

        if ($event->billing) {
            $pendingIntroPayment = $event->billing->payments()
                ->where('payments.payment_type', Payment::TYPE_INTRODUCTORY)
                ->where('payments.status', Payment::STATUS_PENDING)
                ->latest('payments.created_at')
                ->first();

            $pendingDownpayment = $event->billing->payments()
                ->where('payments.payment_type', Payment::TYPE_DOWNPAYMENT)
                ->where('payments.status', Payment::STATUS_PENDING)
                ->latest('payments.created_at')
                ->first();
        }

        // Calculate amounts
        $introAmount = 15000;
        $downpaymentAmount = 0;

        if ($event->billing && $event->billing->downpayment_amount > 0) {
            // Only subtract intro payment if it's been approved
            $introDeduction = ($event->billing->introductory_payment_status === 'paid') ? 15000 : 0;
            $downpaymentAmount = $event->billing->downpayment_amount - $introDeduction;
        }

        // Check if downpayment is paid (required for balance payments)
        $isDownpaymentPaid = $event->hasDownpaymentPaid();
        $canPayBalance = $isDownpaymentPaid && $event->billing && $event->billing->remaining_balance > 0;

        return view('customers.events.show', compact(
            'event',
            'pendingIntroPayment',
            'pendingDownpayment',
            'introAmount',
            'downpaymentAmount',
            'isDownpaymentPaid',
            'canPayBalance'
        ));
    }

    public function edit(Request $request, Event $event)
    {
        $customer = $request->user()->customer;
        abort_if(!$customer || $event->customer_id !== $customer->id, 403);

        // Can only edit if status is requested, request_meeting, or meeting
        if (!in_array($event->status, [Event::STATUS_REQUESTED, Event::STATUS_REQUEST_MEETING, Event::STATUS_MEETING])) {
            return redirect()
                ->route('customer.events.show', $event)
                ->with('error', 'Cannot edit event in current status.');
        }

        $packages = Package::with(['inclusions'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $event->load(['package']);

        // Get all active inclusions grouped by category
        $allInclusions = Inclusion::where('is_active', true)
            ->get()
            ->groupBy('category');

        return view('customers.events.edit', compact('event', 'packages', 'allInclusions'));
    }

    public function update(Request $request, Event $event)
    {
        $customer = $request->user()->customer;
        abort_if(!$customer || $event->customer_id !== $customer->id, 403);

        // Can only edit if status is requested, request_meeting, or meeting
        if (!in_array($event->status, [Event::STATUS_REQUESTED, Event::STATUS_REQUEST_MEETING, Event::STATUS_MEETING])) {
            return redirect()
                ->route('customer.events.show', $event)
                ->with('error', 'Cannot edit event in current status.');
        }

        $data = $request->validate([
            'name'         => ['required', 'string', 'max:150'],
            'event_date'   => ['required', 'date'],
            'package_id'   => ['required', 'exists:packages,id'],
            'venue'        => ['nullable', 'string', 'max:255'],
            'theme'        => ['nullable', 'string', 'max:255'],
            'budget'       => ['nullable', 'numeric', 'min:0'],
            'guests'       => ['nullable', 'string', 'max:5000'],
            'notes'        => ['nullable', 'string', 'max:5000'],

            'inclusions'   => ['nullable', 'array'],
            'inclusions.*' => ['integer', 'exists:inclusions,id'],
        ]);

        $package = Package::findOrFail($data['package_id']);

        // Get selected inclusions
        $selectedIds = collect($request->input('inclusions', []))
            ->map(fn($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();

        // Validate that all selected inclusions are active
        $validInclusions = Inclusion::where('is_active', true)
            ->whereIn('id', $selectedIds)
            ->pluck('id');

        $invalid = $selectedIds->diff($validInclusions);
        if ($invalid->isNotEmpty()) {
            return back()
                ->withErrors(['inclusions' => 'Some selected inclusions are not available.'])
                ->withInput();
        }

        DB::transaction(function () use ($event, $data, $selectedIds) {
            $event->update([
                'name'        => $data['name'],
                'event_date'  => $data['event_date'],
                'package_id'  => $data['package_id'],
                'venue'       => $data['venue'] ?? null,
                'theme'       => $data['theme'] ?? null,
                'budget'      => $data['budget'] ?? null,
                'guests'      => $data['guests'] ?? null,
                'notes'       => $data['notes'] ?? null,
            ]);

            // Sync inclusions with price snapshots
            if ($selectedIds->isNotEmpty()) {
                $inclusionPrices = Inclusion::whereIn('id', $selectedIds)->pluck('price', 'id');
                $attach = [];
                foreach ($selectedIds as $incId) {
                    $attach[$incId] = ['price_snapshot' => (float) ($inclusionPrices[$incId] ?? 0)];
                }
                $event->inclusions()->sync($attach);
            } else {
                $event->inclusions()->detach();
            }
        });

        return redirect()
            ->route('customer.events.show', $event)
            ->with('success', 'Event updated successfully.');
    }
}
