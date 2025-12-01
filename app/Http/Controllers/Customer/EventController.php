<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Package;
use App\Models\Inclusion;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\EventSchedule;
use App\Models\InclusionChangeRequest;
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

        // Only this customer's schedules
        $schedules = EventSchedule::with(['inclusion', 'event'])
            ->whereHas('event', fn($q) => $q->where('customer_id', $customer->id))
            ->whereNotNull('scheduled_date')
            ->get();

        $hasPendingBillings = $customer->hasPendingPayments();

        return view('customers.events.index', compact('events', 'schedules', 'hasPendingBillings'));
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
        $approvedIntroPayments = $payments->where('payment_type', Payment::TYPE_INTRODUCTORY)
            ->where('status', Payment::STATUS_APPROVED)
            ->sum('amount');

        $approvedDownpayments = $payments->where('payment_type', Payment::TYPE_DOWNPAYMENT)
            ->where('status', Payment::STATUS_APPROVED)
            ->sum('amount');

        $approvedBalancePayments = $payments->where('payment_type', Payment::TYPE_BALANCE)
            ->where('status', Payment::STATUS_APPROVED)
            ->sum('amount');

        $totalPaid = $approvedIntroPayments + $approvedDownpayments + $approvedBalancePayments;

        $introAmount = 5000.00;
        // balance calculation
        $remainingBalance = $grandTotal - $totalPaid;

        // Required downpayment calculation
        if ($event->billing && $event->billing->downpayment_amount > 0) {
            if ($approvedIntroPayments > 0) {
                $requiredDownpayment = max(0, $event->billing->downpayment_amount - $introAmount);
            } else {
                $requiredDownpayment = $event->billing->downpayment_amount;
            }
        } else {
            $requiredDownpayment = 0;
        }

        // payment checks
        $canPayIntro = $event->isReadyForIntroPayment();
        $isIntroPaid = ($approvedIntroPayments > 0);
        $canPayDownpayment = $event->isReadyForDownpayment() && $isIntroPaid;
        $isDownpaymentPaid = ($approvedDownpayments > 0);
        $canPayBalance = $isIntroPaid && $isDownpaymentPaid && $remainingBalance > 0;

        // progress
        $progress = $event->progress()->orderBy('progress_date', 'desc')->get();

        return view('customers.events.show', compact(
            'event',
            'payments',
            'pendingIntroPayment',
            'pendingDownpayment',
            'approvedIntroPayments',
            'approvedDownpayments',
            'approvedBalancePayments',
            'canPayIntro',
            'isIntroPaid',
            'canPayDownpayment',
            'isDownpaymentPaid',
            'canPayBalance',
            'incSubtotal',
            'coord',
            'styl',
            'grandTotal',
            'totalPaid',
            'remainingBalance',
            'introAmount',
            'requiredDownpayment',
        ));
    }

    public function edit(Request $request, Event $event)
    {
        $customer = $request->user()->customer;
        abort_if(!$customer || $event->customer_id !== $customer->id, 403);

        // Can only edit if status is not rejected or completed
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

        // Determine if removal is allowed based on status
        $canRemoveInclusions = in_array($event->status, [
            Event::STATUS_REQUESTED,
            Event::STATUS_APPROVED,
            Event::STATUS_REQUEST_MEETING,
            Event::STATUS_MEETING,
        ]);

        // Get original inclusion IDs (these cannot be removed if canRemoveInclusions is false)
        $originalInclusionIds = $event->inclusions->pluck('id')->toArray();

        return view('customers.events.edit', compact(
            'event',
            'packages',
            'allInclusions',
            'existingNotes',
            'customer',
            'canRemoveInclusions',
            'originalInclusionIds'
        ));
    }

    public function update(Request $request, Event $event)
    {
        // Ensure customer owns this event
        $customer = $request->user()->customer;
        abort_if(!$customer || $event->customer_id !== $customer->id, 403);

        $data = $request->validate([
            'name'         => ['required', 'string', 'max:150'],
            'event_date'   => ['required', 'date'],
            'package_id'   => ['required', 'exists:packages,id'],
            'venue'        => ['nullable', 'string', 'max:255'],
            'theme'        => ['nullable', 'string', 'max:255'],
            'guests'       => ['nullable', 'integer', 'min:1'],
            'notes'        => ['nullable', 'string', 'max:5000'],
            'inclusions'   => ['nullable', 'array'],
            'inclusions.*' => ['integer', 'exists:inclusions,id'],
            'inclusion_notes' => ['nullable', 'array'],
            'inclusion_notes.*' => ['nullable', 'string', 'max:500'],
            'locked_inclusions' => ['nullable', 'array'],
            'locked_inclusions.*' => ['integer', 'exists:inclusions,id'],
        ]);

        $package = Package::findOrFail($data['package_id']);

        // Determine if removal is allowed based on status
        $canRemoveInclusions = in_array($event->status, [
            Event::STATUS_REQUESTED,
            Event::STATUS_APPROVED,
            Event::STATUS_REQUEST_MEETING,
            Event::STATUS_MEETING,
        ]);

        // Get current inclusions
        $currentInclusionIds = $event->inclusions->pluck('id')->sort()->values();

        // Get submitted inclusions
        $submittedInclusionIds = collect($request->input('inclusions', []))
            ->map(fn($id) => (int) $id)
            ->filter()
            ->unique();

        // If removal is not allowed, merge locked inclusions to ensure they're always included
        if (!$canRemoveInclusions) {
            $lockedInclusionIds = collect($request->input('locked_inclusions', []))
                ->map(fn($id) => (int) $id)
                ->filter();

            // Merge and remove duplicates
            $newInclusionIds = $submittedInclusionIds->merge($lockedInclusionIds)
                ->unique()
                ->sort()
                ->values();
        } else {
            $newInclusionIds = $submittedInclusionIds->sort()->values();
        }

        // Check if inclusions changed - Using Laravel-compatible comparison
        $currentArray = $currentInclusionIds->toArray();
        $newArray = $newInclusionIds->toArray();

        // Check if arrays are different
        $inclusionsChanged = (count($currentArray) !== count($newArray)) ||
            (count(array_diff($currentArray, $newArray)) > 0) ||
            (count(array_diff($newArray, $currentArray)) > 0);

        if ($inclusionsChanged) {
            // Create change request instead of updating directly
            return $this->createChangeRequest(
                $event,
                $customer,
                $package,
                $currentInclusionIds,
                $newInclusionIds,
                $request->input('inclusion_notes', []),
                $data,
                $canRemoveInclusions
            );
        }

        // No inclusion changes - update event normally
        $event->update([
            'name'        => $data['name'],
            'event_date'  => $data['event_date'],
            'package_id'  => $data['package_id'],
            'venue'       => $data['venue'] ?? null,
            'theme'       => $data['theme'] ?? null,
            'guests'      => $data['guests'] ?? null,
            'notes'       => $data['notes'] ?? null,
        ]);

        return redirect()
            ->route('customer.events.show', $event)
            ->with('success', 'Event updated successfully.');
    }

    protected function createChangeRequest($event, $customer, $package, $currentInclusionIds, $newInclusionIds, $inclusionNotes, $eventData, $canRemoveInclusions = true)
    {
        // Get current inclusions with details
        $currentInclusions = Inclusion::whereIn('id', $currentInclusionIds)
            ->get()
            ->map(fn($inc) => [
                'id' => $inc->id,
                'name' => $inc->name,
                'price' => (float) $inc->price,
                'category' => $inc->category,
            ])
            ->toArray();

        // Get new inclusions with details
        $newInclusions = Inclusion::whereIn('id', $newInclusionIds)
            ->get()
            ->map(fn($inc) => [
                'id' => $inc->id,
                'name' => $inc->name,
                'price' => (float) $inc->price,
                'category' => $inc->category,
            ])
            ->toArray();

        // Calculate totals
        $coordination = (float) ($package->coordination_price ?? 25000);
        $styling = (float) ($package->event_styling_price ?? 55000);

        $oldInclusionsTotal = collect($currentInclusions)->sum('price');
        $oldTotal = $oldInclusionsTotal + $coordination + $styling;

        $newInclusionsTotal = collect($newInclusions)->sum('price');
        $newTotal = $newInclusionsTotal + $coordination + $styling;

        $difference = $newTotal - $oldTotal;

        // If removal is not allowed, validate that no locked inclusions were removed
        if (!$canRemoveInclusions) {
            $removedIds = array_diff(
                collect($currentInclusions)->pluck('id')->toArray(),
                collect($newInclusions)->pluck('id')->toArray()
            );

            if (!empty($removedIds)) {
                return redirect()
                    ->back()
                    ->with('error', 'Cannot remove existing inclusions at this stage. Only new inclusions can be added.');
            }
        }

        // Check if there's already a pending change request
        $existingRequest = InclusionChangeRequest::where('event_id', $event->id)
            ->where('status', InclusionChangeRequest::STATUS_PENDING)
            ->first();

        if ($existingRequest) {
            // Update existing pending request
            $existingRequest->update([
                'new_inclusions' => $newInclusions,
                'inclusion_notes' => $inclusionNotes,
                'new_total' => $newTotal,
                'difference' => $difference,
            ]);

            // 🔔 NOTIFY ADMINS - Request Updated
            // 1. Send email notification
            $admins = \App\Models\User::where('user_type', 'admin')
                ->where('status', 'active')
                ->get();

            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\InclusionChangeRequestNotification($existingRequest, true));
            }

            // 2. Create in-app notification
            $this->notificationService->notifyAdminsInclusionChangeRequest($existingRequest, true);

            return redirect()
                ->route('customer.events.show', $event)
                ->with('info', 'Your previous change request has been updated and is awaiting admin approval.');
        }

        // Create new change request
        $changeRequest = InclusionChangeRequest::create([
            'event_id' => $event->id,
            'customer_id' => $customer->id,
            'old_inclusions' => $currentInclusions,
            'new_inclusions' => $newInclusions,
            'inclusion_notes' => $inclusionNotes,
            'old_total' => $oldTotal,
            'new_total' => $newTotal,
            'difference' => $difference,
            'status' => InclusionChangeRequest::STATUS_PENDING,
        ]);

        // Update non-inclusion fields
        $event->update([
            'name' => $eventData['name'],
            'event_date' => $eventData['event_date'],
            'package_id' => $eventData['package_id'],
            'venue' => $eventData['venue'] ?? null,
            'theme' => $eventData['theme'] ?? null,
            'guests' => $eventData['guests'] ?? null,
            'notes' => $eventData['notes'] ?? null,
        ]);

        // 🔔 NOTIFY ADMINS - New Request
        // 1. Send email notification
        $admins = \App\Models\User::where('user_type', 'admin')
            ->where('status', 'active')
            ->get();

        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\InclusionChangeRequestNotification($changeRequest, false));
        }

        // 2. Create in-app notification
        $this->notificationService->notifyAdminsInclusionChangeRequest($changeRequest, false);

        return redirect()
            ->route('customer.events.show', $event)
            ->with('info', 'Event updated. Your inclusion changes are pending admin approval.');
    }
}
