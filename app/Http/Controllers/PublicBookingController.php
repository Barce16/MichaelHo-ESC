<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Customer;
use App\Models\Event;
use App\Models\Inclusion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
        // Validate event details with custom messages
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'event_name' => ['required', 'string', 'min:5', 'max:150'],
            'event_date' => ['required', 'date', 'after:today'],
            'venue' => ['required', 'string', 'min:10', 'max:255'],
            'theme' => ['nullable', 'string', 'min:3', 'max:255'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'inclusions' => ['nullable', 'array'],
            'inclusions.*' => ['integer', 'exists:inclusions,id'],
        ], [
            'event_name.required' => 'Event name is required.',
            'event_name.min' => 'Event name must be at least 5 characters.',
            'event_date.required' => 'Event date is required.',
            'event_date.after' => 'Event date must be a future date.',
            'venue.required' => 'Venue is required.',
            'venue.min' => 'Venue must be at least 10 characters. Please provide detailed address.',
            'theme.min' => 'Theme must be at least 3 characters.',
        ]);

        // Redirect back to package page if validation fails
        if ($validator->fails()) {
            return redirect()
                ->route('services.show', $package)
                ->withErrors($validator)
                ->withInput();
        }

        $eventData = $validator->validated();

        // Store event data in session
        session(['booking_event_data' => $eventData]);

        // Show booking form with customer details
        return view('book', [
            'package' => $package,
            'eventData' => $eventData
        ]);
    }

    /**
     * GET route: Display booking form (Step 2) if session data exists
     */
    public function showBookingFormGet(Package $package)
    {
        // Get event data from session
        $eventData = session('booking_event_data');

        if (!$eventData) {
            return redirect()
                ->route('services.show', $package)
                ->with('error', 'Session expired. Please start over.');
        }

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
        $validator = Validator::make($request->all(), [
            'customer_name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email', 'max:120'],
            'phone' => ['required', 'string', 'min:10', 'max:12'],
            'gender' => ['required', 'string'],
            'address' => ['nullable', 'string', 'min:10', 'max:255'],
            'guests' => ['nullable', 'integer', 'min:1'],
        ]);

        // Check if email exists in users table - block if already registered
        $validator->after(function ($validator) use ($request) {
            $email = $request->input('email');

            // If email exists in users table, block booking
            if (User::where('email', $email)->exists()) {
                $validator->errors()->add('email', 'This email is already registered. Please login to your account to book an event. Staff members should use a separate email than your work email.');
            }
        });

        if ($validator->fails()) {
            return redirect()
                ->route('booking.form', $package)
                ->withErrors($validator)
                ->withInput();
        }

        $customerData = $validator->validated();

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
                    'notes' => $eventData['notes'] ?? null,
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
