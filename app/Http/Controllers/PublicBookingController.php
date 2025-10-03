<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Event;
use App\Models\Customer;
use App\Models\Inclusion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicBookingController extends Controller
{
    public function show(Package $package)
    {
        $package->load(['inclusions', 'images']);

        // Get all active inclusions grouped by category
        $allInclusions = Inclusion::where('is_active', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy(fn($inc) => $inc->category ? $inc->category->value : 'Other');

        return view('booking.create', compact('package', 'allInclusions'));
    }

    public function store(Request $request, Package $package)
    {
        $data = $request->validate([
            // Event details
            'event_name' => ['required', 'string', 'max:150'],
            'event_date' => ['required', 'date', 'after:today'],
            'venue' => ['required', 'string', 'max:255'],
            'theme' => ['nullable', 'string', 'max:255'],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'guests' => ['nullable', 'string', 'max:5000'],
            'notes' => ['nullable', 'string', 'max:5000'],

            // Customer details
            'customer_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:120'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],

            // Inclusions - now array format from radio buttons
            'inclusions' => ['required', 'array'],
            'inclusions.*' => ['required', 'integer', 'exists:inclusions,id'],
        ]);

        // Get selected inclusion IDs from the array values
        $selectedIds = collect($data['inclusions'] ?? [])->values()->filter()->unique();

        if ($selectedIds->isEmpty()) {
            return back()
                ->with('error', 'Please select at least one inclusion from each category.')
                ->withInput();
        }

        // Get inclusion prices
        $inclusionPrices = Inclusion::whereIn('id', $selectedIds)->pluck('price', 'id');

        try {
            DB::transaction(function () use ($data, $package, $selectedIds, $inclusionPrices) {
                // Find existing customer by email or create new one
                $customer = Customer::where('email', $data['email'])->first();

                if (!$customer) {
                    $customer = Customer::create([
                        'customer_name' => $data['customer_name'],
                        'email' => $data['email'],
                        'phone' => $data['phone'],
                        'address' => $data['address'] ?? null,
                        'user_id' => null,
                    ]);
                } else {
                    // Update existing customer info
                    $customer->update([
                        'customer_name' => $data['customer_name'],
                        'phone' => $data['phone'],
                        'address' => $data['address'] ?? null,
                    ]);
                }

                // Create event
                $event = Event::create([
                    'customer_id' => $customer->id,
                    'name' => $data['event_name'],
                    'event_date' => $data['event_date'],
                    'package_id' => $package->id,
                    'venue' => $data['venue'],
                    'theme' => $data['theme'] ?? null,
                    'budget' => $data['budget'] ?? null,
                    'guests' => $data['guests'] ?? null,
                    'notes' => $data['notes'] ?? null,
                    'status' => 'requested',
                ]);

                // Attach inclusions with price snapshot
                if ($selectedIds->isNotEmpty()) {
                    $attach = [];
                    foreach ($selectedIds as $incId) {
                        $attach[$incId] = ['price_snapshot' => (float) ($inclusionPrices[$incId] ?? 0)];
                    }
                    $event->inclusions()->attach($attach);
                }
            });

            return redirect()
                ->route('booking.success')
                ->with('success', 'Your booking request has been submitted successfully! We will contact you at ' . $data['email'] . ' shortly.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'An error occurred: ' . $e->getMessage())
                ->withInput();
        }
    }
}
