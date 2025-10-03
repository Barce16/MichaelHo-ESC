<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Package;
use App\Models\Inclusion;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
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


    public function create(Request $request)
    {
        $customer = $request->user()->customer;
        abort_if(!$customer, 403);

        $packages = Package::with([
            'inclusions'
        ])->where('is_active', true)->orderBy('price')->get();

        return view('customers.events.create', compact('packages'));
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

        $package = Package::with(['inclusions:id,price'])->findOrFail($data['package_id']);

        $allowedInclusionIds = $package->inclusions->pluck('id')->all();
        $selectedIds = collect($request->input('inclusions', $allowedInclusionIds))
            ->map(fn($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();

        $invalid = $selectedIds->diff($allowedInclusionIds);
        if ($invalid->isNotEmpty()) {
            return back()
                ->withErrors(['inclusions' => 'Invalid inclusions for the selected package.'])
                ->withInput();
        }

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
                'status'      => 'requested',
            ]);

            if ($selectedIds->isNotEmpty()) {
                $attach = [];
                foreach ($selectedIds as $incId) {
                    $attach[$incId] = ['price_snapshot' => (float) ($inclusionPrices[$incId] ?? 0)];
                }
                $event->inclusions()->attach($attach);
            }
        });

        return redirect()
            ->route('customer.events.index')
            ->with('success', 'Event request submitted.');
    }


    public function show(Request $request, Event $event)
    {
        $customer = $request->user()->customer;
        abort_if(!$customer || $event->customer_id !== $customer->id, 403);

        $event->load(['package', 'billing']);

        $incs = $event->inclusions ?? collect();
        $incSubtotal = $incs->sum(fn($i) => (float)($i->pivot->price_snapshot ?? $i->price ?? 0));

        return view('customers.events.show', compact('event', 'incSubtotal'));
    }


    public function edit(Request $request, Event $event)
    {
        $customer = $request->user()->customer;
        abort_if(!$customer || $event->customer_id !== $customer->id, 403);

        $packages = Package::with([
            'inclusions'
        ])->where('is_active', true)->orderBy('name')->get();


        $event->load(['package']);

        return view('customers.events.edit', compact('event', 'packages'));
    }

    public function update(Request $request, Event $event)
    {
        $customer = $request->user()->customer;
        abort_if(!$customer || $event->customer_id !== $customer->id, 403);

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:150',
                'regex:/^[A-Za-z0-9 .-]+$/',
            ],
            'package_id'   => ['required', 'exists:packages,id'],
            'event_date'   => ['required', 'date', 'after:today'],
            'venue'        => ['nullable', 'string', 'max:255'],
            'theme'        => ['nullable', 'string', 'max:120'],
            'budget' => [
                'nullable',
                'numeric',
                'min:0',
                'regex:/^\d+(\.\d+)?$/',
            ],
            'guests'       => ['nullable', 'string', 'max:5000'], // Fixed: removed extra comma
            'notes'        => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($event, $data) {
            $event->update([
                'package_id'  => $data['package_id'],
                'name'        => $data['name'],
                'event_date'  => $data['event_date'],
                'venue'       => $data['venue'] ?? null,
                'theme'       => $data['theme'] ?? null,
                'budget'      => $data['budget'] ?? null,
                'guests'      => $data['guests'] ?? null,
                'notes'       => $data['notes'] ?? null,
            ]);
        });

        return redirect()->route('customer.events.show', $event)->with('success', 'Event updated.');
    }
}
