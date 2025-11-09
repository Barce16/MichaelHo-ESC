<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use App\Models\Package;
use App\Models\Inclusion;
use App\Models\Event;
use App\Models\Billing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function index()
    {
        $q = request('q');
        $hasEvents = request('has_events');
        $dateRange = request('date_range');

        $customers = Customer::query()
            // Search filter
            ->when($q, function ($query) use ($q) {
                $searchTerm = "%" . strtolower($q) . "%";
                $query->where(function ($query) use ($searchTerm) {
                    $query->whereRaw('LOWER(customer_name) LIKE ?', [$searchTerm])
                        ->orWhereRaw('LOWER(email) LIKE ?', [$searchTerm])
                        ->orWhereRaw('LOWER(phone) LIKE ?', [$searchTerm]);
                });
            })
            // Has events filter (Status)
            ->when($hasEvents !== null && $hasEvents !== '', function ($query) use ($hasEvents) {
                if ($hasEvents === '1') {
                    // Customers with events
                    $query->has('events');
                } else {
                    // Customers without events
                    $query->doesntHave('events');
                }
            })
            // Date range filter (Joined)
            ->when($dateRange, function ($query) use ($dateRange) {
                switch ($dateRange) {
                    case 'today':
                        $query->whereDate('created_at', today());
                        break;
                    case 'week':
                        $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                        break;
                    case 'month':
                        $query->whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year);
                        break;
                    case 'year':
                        $query->whereYear('created_at', now()->year);
                        break;
                }
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        $packages = Package::where('is_active', true)
            ->with(['inclusions' => function ($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        // Prepare packages data for Alpine.js
        $packagesData = $packages->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'type' => $p->type,
                'price' => $p->price,
                'coordination_price' => $p->coordination_price,
                'event_styling_price' => $p->event_styling_price,
                'inclusions' => $p->inclusions->map(fn($i) => [
                    'id' => $i->id,
                    'name' => $i->name,
                    'price' => $i->price,
                    'image_url' => $i->image_url,
                ])
            ];
        });

        $allInclusions = Inclusion::where('is_active', true)
            ->get()
            ->map(function ($inclusion) {
                return [
                    'id' => $inclusion->id,
                    'name' => $inclusion->name,
                    'category' => $inclusion->category->value ?? $inclusion->category,
                    'price' => $inclusion->price,
                    'image_url' => $inclusion->image_url,
                    'package_type' => $inclusion->package_type,
                ];
            })
            ->groupBy('category');

        return view('customers.create', compact('packages', 'packagesData', 'allInclusions'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'customer_name' => ['required', 'string', 'max:100'],
            'email'         => ['required', 'email', 'max:120', 'unique:customers,email'],
            'phone'         => ['nullable', 'string', 'max:30'],
            'address'       => ['nullable', 'string', 'max:255'],
        ]);

        Customer::create($data);
        return redirect()->route('admin.customers.index')->with('success', 'Customer added.');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $r, Customer $customer)
    {
        $data = $r->validate([
            'customer_name' => ['required', 'string', 'max:100'],
            'email'         => ['required', 'email', 'max:120', 'unique:customers,email,' . $customer->id],
            'phone'         => ['nullable', 'string', 'max:30'],
            'address'       => ['nullable', 'string', 'max:255'],
        ]);

        $customer->update($data);
        return redirect()->route('admin.customers.index')->with('success', 'Customer updated.');
    }

    public function show(Customer $customer)
    {
        $customer->load([
            'user',
            'events' => function ($q) {
                $q->orderByDesc('event_date')->limit(10);
            },
        ]);

        return view('customers.show', compact('customer'));
    }


    public function destroy(Customer $customer)
    {
        $customer->delete(); // soft delete
        return back()->with('success', 'Customer removed.');
    }

    public function storeWithEvent(Request $request)
    {
        $validated = $request->validate([
            // Customer info
            'customer_name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email', 'max:120', 'unique:users,email'],
            'phone' => ['required', 'string', 'min:10', 'max:12'],
            'gender' => ['required', 'in:male,female,other'],
            'address' => ['nullable', 'string', 'max:255'],

            // Package
            'package_id' => ['required', 'exists:packages,id'],

            // Event details
            'event_name' => ['required', 'string', 'min:2', 'max:150'],
            'event_date' => ['required', 'date', 'after:today'],
            'venue' => ['required', 'string', 'min:5', 'max:255'],
            'theme' => ['nullable', 'string', 'max:100'],
            'guests' => ['nullable', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:1000'],

            // NEW: Inclusions
            'inclusions' => ['nullable', 'array'],
            'inclusions.*' => ['integer', 'exists:inclusions,id'],
            'inclusion_notes' => ['nullable', 'array'],
            'inclusion_notes.*' => ['nullable', 'string', 'max:500'],

            'send_credentials_email' => ['nullable', 'boolean'],
        ]);

        try {
            DB::transaction(function () use ($validated, $request) {
                // Generate random password
                $randomPassword = Str::random(12);

                // Create User
                $user = User::create([
                    'name' => $validated['customer_name'],
                    'username' => Str::slug($validated['customer_name']) . '-' . rand(1000, 9999),
                    'email' => $validated['email'],
                    'password' => Hash::make($randomPassword),
                    'user_type' => 'customer',
                    'gender' => $validated['gender'],
                    'status' => 'active',
                ]);

                // Create Customer
                $customer = Customer::create([
                    'user_id' => $user->id,
                    'customer_name' => $validated['customer_name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'address' => $validated['address'] ?? null,
                ]);

                // Get package
                $package = Package::with('inclusions')->findOrFail($validated['package_id']);

                // Create Event
                $event = Event::create([
                    'customer_id' => $customer->id,
                    'package_id' => $package->id,
                    'name' => $validated['event_name'],
                    'event_date' => $validated['event_date'],
                    'venue' => $validated['venue'],
                    'theme' => $validated['theme'] ?? null,
                    'guests' => $validated['guests'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                    'status' => 'request_meeting', // Auto-approve walk-in events
                ]);

                // NEW: Handle custom or default inclusions
                $selectedInclusionIds = $request->input('inclusions', []);

                // If no inclusions selected, use package defaults
                if (empty($selectedInclusionIds)) {
                    $selectedInclusionIds = $package->inclusions()->where('is_active', true)->pluck('id')->toArray();
                }

                // Attach inclusions with price snapshot
                if (!empty($selectedInclusionIds)) {
                    $inclusions = Inclusion::whereIn('id', $selectedInclusionIds)
                        ->where('is_active', true)
                        ->get();

                    $inclusionNotes = $request->input('inclusion_notes', []);

                    $attach = [];
                    foreach ($inclusions as $inclusion) {
                        $attach[$inclusion->id] = [
                            'price_snapshot' => (float) $inclusion->price,
                            'notes' => $inclusionNotes[$inclusion->id] ?? null,
                        ];
                    }

                    $event->inclusions()->attach($attach);
                }

                // Calculate totals and create billing (like approve method)
                $inclusionsSubtotal = !empty($selectedInclusionIds)
                    ? $inclusions->sum('price')
                    : 0;
                $coord = (float) ($package->coordination_price ?? 25000);
                $styling = (float) ($package->event_styling_price ?? 55000);
                $grandTotal = $inclusionsSubtotal + $coord + $styling;

                // Create billing record
                Billing::create([
                    'event_id' => $event->id,
                    'total_amount' => $grandTotal,
                    'introductory_payment_amount' => 5000,
                    'introductory_payment_status' => 'pending',
                    'downpayment_amount' => $grandTotal / 2,
                    'status' => 'pending',
                ]);

                // Send credentials email if requested
                if ($request->has('send_credentials_email')) {
                    Mail::to($user->email)->send(
                        new \App\Mail\WalkinCustomerCredentials($user, $randomPassword, $event)
                    );
                }

                // Store password temporarily in session for display
                session()->flash('new_customer_password', $randomPassword);
                session()->flash('new_customer_username', $user->username);
            });

            return redirect()
                ->route('admin.customers.index')
                ->with('success', 'Walk-in customer and event created successfully!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error creating customer: ' . $e->getMessage());
        }
    }
}
