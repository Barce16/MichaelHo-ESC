<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Customer;
use App\Models\Event;
use App\Models\Inclusion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicBookingController extends Controller
{
    protected $notificationService;

    public function __construct(\App\Services\NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Step 1: Store event details in session and show customer form
     */
    public function showBookingForm(Request $request, Package $package)
    {
        // Validate event details
        $eventData = $request->validate([
            'event_name' => ['required', 'string', 'max:150'],
            'event_date' => ['required', 'date', 'after:today'],
            'venue' => ['required', 'string', 'min:10', 'max:255'],
            'theme' => ['nullable', 'string', 'max:255'],
            'inclusions' => ['nullable', 'array'],
            'inclusions.*' => ['integer', 'exists:inclusions,id'],
        ]);

        // Store event data in session
        session(['booking_event_data' => $eventData]);

        // Show booking form with customer details
        return view('book', [
            'package' => $package,
            'eventData' => $eventData
        ]);
    }

    /**
     * Step 2: Complete booking with customer details
     */
    public function store(Request $request, Package $package)
    {
        // Get event data from session
        $eventData = session('booking_event_data');

        if (!$eventData) {
            return redirect()
                ->route('services.show', $package)
                ->with('error', 'Session expired. Please start over.');
        }

        // Validate customer details
        $customerData = $request->validate([
            'customer_name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email', 'max:120'],
            'phone' => ['required', 'string', 'min:10', 'max:12'],
            'gender' => ['required', 'string'],
            'address' => ['nullable', 'string', 'min:10', 'max:255'],
            'guests' => ['nullable', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        try {
            DB::transaction(function () use ($eventData, $customerData, $package) {
                // Find existing customer by email or create new one
                $customer = Customer::where('email', $customerData['email'])->first();

                if (!$customer) {
                    $customer = Customer::create([
                        'customer_name' => $customerData['customer_name'],
                        'email' => $customerData['email'],
                        'gender' => $customerData['gender'],
                        'phone' => $customerData['phone'],
                        'address' => $customerData['address'] ?? null,
                        'user_id' => null,
                    ]);
                } else {
                    // Update existing customer info
                    $customer->update([
                        'customer_name' => $customerData['customer_name'],
                        'phone' => $customerData['phone'],
                        'address' => $customerData['address'] ?? null,
                    ]);
                }

                // Create event
                $event = Event::create([
                    'customer_id' => $customer->id,
                    'name' => $eventData['event_name'],
                    'event_date' => $eventData['event_date'],
                    'package_id' => $package->id,
                    'venue' => $eventData['venue'],
                    'theme' => $eventData['theme'] ?? null,
                    'guests' => $customerData['guests'] ?? null,
                    'notes' => $customerData['notes'] ?? null,
                    'status' => 'requested',
                ]);

                // Attach selected inclusions with price snapshot
                $selectedInclusionIds = $eventData['inclusions'] ?? [];

                if (!empty($selectedInclusionIds)) {
                    $inclusions = Inclusion::whereIn('id', $selectedInclusionIds)->get();
                    $attach = [];

                    foreach ($inclusions as $inclusion) {
                        $attach[$inclusion->id] = ['price_snapshot' => (float) $inclusion->price];
                    }

                    $event->inclusions()->attach($attach);
                } else {
                    // If no custom inclusions, use package default inclusions
                    $packageInclusions = $package->inclusions()->where('is_active', true)->get();

                    if ($packageInclusions->isNotEmpty()) {
                        $attach = [];
                        foreach ($packageInclusions as $inclusion) {
                            $attach[$inclusion->id] = ['price_snapshot' => (float) $inclusion->price];
                        }
                        $event->inclusions()->attach($attach);
                    }
                }

                // Send notification to admin
                $this->notificationService->notifyAdminNewEventRequest($event);
            });

            // Clear session data
            session()->forget('booking_event_data');

            return redirect()
                ->route('booking.success')
                ->with('success', 'Your booking request has been submitted successfully! We will contact you at ' . $customerData['email'] . ' shortly.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'An error occurred: ' . $e->getMessage())
                ->withInput();
        }
    }
}
