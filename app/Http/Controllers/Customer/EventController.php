<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
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

        $events = Event::with([
            'package',
            'progress' => function ($query) {
                $query->orderBy('progress_date', 'desc');
            }
        ])
            ->where('customer_id', $customer->id)
            ->orderByDesc('event_date')
            ->paginate(12);

        $hasPendingBillings = $customer->hasPendingPayments();

        return view('customers.events.index', compact('events', 'hasPendingBillings'));
    }
    public function create(Request $request)
    {
        // Get authenticated customer
        $customer = $request->user()->customer;
        abort_if(!$customer, 403);

        // Check if customer has pending billings
        $hasOutstandingBalance = $customer->hasOutstandingBalance();

        // Redirect if has pending billings
        if ($hasOutstandingBalance) {
            return redirect()->route('customer.events.index')
                ->with('error', 'Please settle your pending billings before requesting a new event.');
        }

        // Load packages and inclusions only if allowed
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
        $customer = $request->user()->customer;
        abort_if(!$customer, 403);

        $hasPendingBillings = $customer->hasPendingPayments();
        if ($hasPendingBillings) {
            return redirect()->route('customer.events.index')
                ->with('error', 'Please settle your pending billings before requesting a new event.');
        }

        $data = $request->validate([
            'name'         => ['required', 'string', 'max:150'],
            'event_date'   => ['required', 'date'],
            'package_id'   => ['required', 'exists:packages,id'],
            'venue'        => ['nullable', 'string', 'max:255'],
            'theme'        => ['nullable', 'string', 'max:255'],
            'guests'       => ['nullable', 'string', 'max:5000'],
            'notes'        => ['nullable', 'string', 'max:5000'],

            'inclusions'   => ['nullable', 'array'],
            'inclusions.*' => ['integer', 'exists:inclusions,id'],

            'inclusion_notes' => ['nullable', 'array'],
            'inclusion_notes.*' => ['nullable', 'string', 'max:500'],
        ]);

        $package = Package::findOrFail($data['package_id']);

        // Get selected inclusions
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

        DB::transaction(function () use ($data, $customer, $selectedIds, $inclusionPrices, $request) {
            $event = Event::create([
                'customer_id' => $customer->id,
                'name'        => $data['name'],
                'event_date'  => $data['event_date'],
                'package_id'  => $data['package_id'],
                'venue'       => $data['venue'] ?? null,
                'theme'       => $data['theme'] ?? null,
                'guests'      => $data['guests'] ?? null,
                'notes'       => $data['notes'] ?? null,
                'status'      => Event::STATUS_REQUESTED,
            ]);

            if ($selectedIds->isNotEmpty()) {
                // Get inclusion notes
                $inclusionNotes = $request->input('inclusion_notes', []);

                $attach = [];
                foreach ($selectedIds as $incId) {
                    $attach[$incId] = [
                        'price_snapshot' => (float) ($inclusionPrices[$incId] ?? 0),
                        'notes' => $inclusionNotes[$incId] ?? null, // NEW: Save notes
                    ];
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
        // auth
        $customer = Auth::user()->customer;
        if (!$customer || $event->customer_id !== $customer->id) abort(403);

        // load relations
        $event->load(['package', 'inclusions', 'customer', 'billing.payments']);

        // pricing
        $incSubtotal = $event->inclusions->sum(fn($i) => (float) ($i->pivot->price_snapshot ?? 0));
        $coord = (float) ($event->package?->coordination_price ?? 25000);
        $styl  = (float) ($event->package?->event_styling_price ?? 55000);
        $grandTotal = $incSubtotal + $coord + $styl;

        // payments collection
        $payments = $event->billing ? $event->billing->payments : collect();

        // pending checks
        $pendingIntroPayment = $payments->where('payment_type', Payment::TYPE_INTRODUCTORY)
            ->where('status', Payment::STATUS_PENDING)
            ->sortByDesc('created_at')
            ->first();

        $pendingDownpayment = $payments->where('payment_type', Payment::TYPE_DOWNPAYMENT)
            ->where('status', Payment::STATUS_PENDING)
            ->sortByDesc('created_at')
            ->first();

        // approved sums
        $totalPaid = (float) $payments->where('status', Payment::STATUS_APPROVED)->sum('amount');

        // remaining
        $remainingBalance = max(0, $grandTotal - $totalPaid);

        // intro/downpayment specifics
        $introAmount = 5000;
        $introPaid = (float) $payments->where('payment_type', Payment::TYPE_INTRODUCTORY)
            ->where('status', Payment::STATUS_APPROVED)
            ->sum('amount');

        $requiredDownpayment = $event->billing->downpayment_amount ?? 0;
        $requiredDownpaymentAfterIntro = max(0, $requiredDownpayment - $introPaid);

        $downpaymentPaid = (float) $payments->where('payment_type', Payment::TYPE_DOWNPAYMENT)
            ->where('status', Payment::STATUS_APPROVED)
            ->sum('amount');

        $downpaymentRemaining = max(0, $requiredDownpaymentAfterIntro - $downpaymentPaid);

        // flags
        $isDownpaymentPaid = $event->hasDownpaymentPaid() || ($downpaymentPaid >= $requiredDownpaymentAfterIntro && $requiredDownpaymentAfterIntro > 0);
        $canPayBalance = $isDownpaymentPaid && $event->billing && $remainingBalance > 0;

        // view
        return view('customers.events.show', compact(
            'event',
            'pendingIntroPayment',
            'pendingDownpayment',
            'introAmount',
            'downpaymentRemaining',
            'downpaymentPaid',
            'requiredDownpayment',
            'requiredDownpaymentAfterIntro',
            'isDownpaymentPaid',
            'canPayBalance',
            'incSubtotal',
            'coord',
            'styl',
            'grandTotal',
            'totalPaid',
            'remainingBalance'
        ));
    }

    public function edit(Request $request, Event $event)
    {
        $customer = $request->user()->customer;
        abort_if(!$customer || $event->customer_id !== $customer->id, 403);

        // Can only edit if status is requested, request_meeting, or meeting
        if (in_array($event->status, [Event::STATUS_REJECTED, Event::STATUS_COMPLETED])) {
            return redirect()
                ->route('customer.events.show', $event)
                ->with('error', 'Cannot edit event in current status.');
        }

        $packages = Package::with(['inclusions'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $event->load(['package', 'inclusions']);

        // Get all active inclusions grouped by category
        $allInclusions = Inclusion::where('is_active', true)
            ->get()
            ->groupBy('category');

        // Get existing inclusion notes from pivot table
        $existingNotes = $event->inclusions->pluck('pivot.notes', 'id')->toArray();

        return view('customers.events.edit', compact('event', 'packages', 'allInclusions', 'existingNotes'));
    }

    public function update(Request $request, Event $event)
    {
        $customer = $request->user()->customer;
        abort_if(!$customer || $event->customer_id !== $customer->id, 403);

        // Can only edit if status is requested, request_meeting, or meeting
        if (in_array($event->status, [Event::STATUS_REJECTED, Event::STATUS_COMPLETED])) {
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
            'guests'       => ['nullable', 'string', 'max:5000'],
            'notes'        => ['nullable', 'string', 'max:5000'],

            'inclusions'   => ['nullable', 'array'],
            'inclusions.*' => ['integer', 'exists:inclusions,id'],

            'inclusion_notes' => ['nullable', 'array'],
            'inclusion_notes.*' => ['nullable', 'string', 'max:500'],
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

        // Track changes for notification
        $changes = [];

        // Store old data for inclusion change detection
        $oldInclusionIds = $event->inclusions->pluck('id')->toArray();
        $oldInclusions = $event->inclusions; // Keep collection for detailed email
        $oldTotal = $event->billing ? $event->billing->total_amount : 0;

        // Check what changed
        if ($event->name !== $data['name']) {
            $changes[] = "Event name changed from '{$event->name}' to '{$data['name']}'";
        }
        if ($event->event_date->format('Y-m-d') !== $data['event_date']) {
            $changes[] = "Event date changed from {$event->event_date->format('M d, Y')} to " . date('M d, Y', strtotime($data['event_date']));
        }
        if ($event->package_id != $data['package_id']) {
            $oldPackage = Package::find($event->package_id);
            $newPackage = Package::find($data['package_id']);
            $changes[] = "Package changed from '{$oldPackage->name}' to '{$newPackage->name}'";
        }
        if ($event->venue !== $data['venue']) {
            $changes[] = "Venue changed to '{$data['venue']}'";
        }
        if ($event->theme !== $data['theme']) {
            $changes[] = "Theme changed to '{$data['theme']}'";
        }
        if ($event->guests !== $data['guests']) {
            $changes[] = "Guest details updated";
        }
        if ($event->notes !== $data['notes']) {
            $changes[] = "Event notes updated";
        }

        DB::transaction(function () use ($event, $data, $selectedIds, $request) {
            $event->update([
                'name'        => $data['name'],
                'event_date'  => $data['event_date'],
                'package_id'  => $data['package_id'],
                'venue'       => $data['venue'] ?? null,
                'theme'       => $data['theme'] ?? null,
                'guests'      => $data['guests'] ?? null,
                'notes'       => $data['notes'] ?? null,
            ]);

            // Sync inclusions with price snapshots and notes
            if ($selectedIds->isNotEmpty()) {
                $inclusionPrices = Inclusion::whereIn('id', $selectedIds)->pluck('price', 'id');
                $inclusionNotes = $request->input('inclusion_notes', []);

                $attach = [];
                foreach ($selectedIds as $incId) {
                    $attach[$incId] = [
                        'price_snapshot' => (float) ($inclusionPrices[$incId] ?? 0),
                        'notes' => $inclusionNotes[$incId] ?? null,
                    ];
                }
                $event->inclusions()->sync($attach);
            } else {
                $event->inclusions()->detach();
            }
        });

        // Check if inclusions changed
        $newInclusions = $selectedIds->toArray();
        $addedInclusionIds = array_diff($newInclusions, $oldInclusionIds);
        $removedInclusionIds = array_diff($oldInclusionIds, $newInclusions);

        $inclusionsChanged = !empty($addedInclusionIds) || !empty($removedInclusionIds);

        if ($inclusionsChanged) {
            // Get full inclusion objects for detailed email
            $addedInclusions = Inclusion::whereIn('id', $addedInclusionIds)->get();
            $removedInclusions = $oldInclusions->filter(function ($inclusion) use ($removedInclusionIds) {
                return in_array($inclusion->id, $removedInclusionIds);
            });

            // Add to changes array for simple notification
            if (!empty($addedInclusionIds)) {
                $added = $addedInclusions->pluck('name')->toArray();
                $changes[] = "Added inclusions: " . implode(', ', $added);
            }
            if (!empty($removedInclusionIds)) {
                $removed = $removedInclusions->pluck('name')->toArray();
                $changes[] = "Removed inclusions: " . implode(', ', $removed);
            }

            // Recalculate billing when inclusions change
            $this->recalculateBilling($event);

            // Refresh event to get updated billing
            $event->refresh();
            $newTotal = $event->billing ? $event->billing->total_amount : 0;

            // Create event progress record for inclusion changes
            $changeDetails = [];
            if ($addedInclusions->count() > 0) {
                $changeDetails[] = "Added: " . implode(', ', $addedInclusions->pluck('name')->toArray());
            }
            if ($removedInclusions->count() > 0) {
                $changeDetails[] = "Removed: " . implode(', ', $removedInclusions->pluck('name')->toArray());
            }

            $progressDetails = !empty($changeDetails)
                ? "Customer updated event inclusions. " . implode(". ", $changeDetails) . ". Total amount changed from ₱" . number_format($oldTotal, 2) . " to ₱" . number_format($newTotal, 2) . "."
                : "Customer updated event inclusions.";

            \App\Models\EventProgress::create([
                'event_id' => $event->id,
                'status' => 'Inclusions Updated by Customer',
                'details' => $progressDetails,
                'progress_date' => now(),
            ]);

            // Send detailed email to admins about inclusion changes
            $admins = \App\Models\User::where('user_type', 'admin')
                ->where('status', 'active')
                ->get();

            foreach ($admins as $admin) {
                // Send email notification
                $admin->notify(new \App\Notifications\CustomerInclusionsUpdatedNotification(
                    $event,
                    $customer,
                    $oldTotal,
                    $newTotal,
                    $addedInclusions,
                    $removedInclusions
                ));
            }

            // Create in-app notification for admins
            $this->notificationService->notifyAdminCustomerInclusionsUpdated(
                $event,
                $customer,
                $oldTotal,
                $newTotal,
                $addedInclusions,
                $removedInclusions
            );
        }

        // Notify admin if there were ANY changes (including non-inclusion changes)
        if (!empty($changes)) {
            $changeMessage = implode("\n", $changes);

            // Only send regular update notification if inclusions didn't change
            // (if inclusions changed, detailed email was already sent above)
            if (!$inclusionsChanged) {
                $this->notificationService->notifyAdminEventUpdated($event, $changeMessage);
            }
        }

        return redirect()
            ->route('customer.events.show', $event)
            ->with('success', 'Event updated successfully.' . ($inclusionsChanged ? ' Admin has been notified of your inclusion changes.' : ''));
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
            $billing = new \App\Models\Billing();
            $billing->event_id = $event->id;
        }

        // Update billing total only
        $billing->total_amount = $newTotal;

        $billing->save();
    }
}
