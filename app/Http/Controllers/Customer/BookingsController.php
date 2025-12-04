<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Package;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class BookingsController extends Controller
{
    public function index()
    {
        $customer = Auth::user()->customer;

        if (!$customer) {
            return redirect()->route('home')->with('error', 'Customer not found.');
        }

        // Get all events for this customer
        $events = Event::where('customer_id', $customer->id)->get();

        // Stats
        $totalEvents = $events->count();
        $upcomingEvents = $events->whereIn('status', ['approved', 'request_meeting', 'meeting', 'scheduled', 'ongoing'])
            ->where('event_date', '>=', now())
            ->count();
        $pendingEvents = $events->where('status', 'requested')->count();
        $completedEvents = $events->where('status', 'completed')->count();
        $cancelledEvents = $events->whereIn('status', ['cancelled', 'rejected'])->count();

        // Recent events (paginated)
        $recentEvents = Event::where('customer_id', $customer->id)
            ->with('package')
            ->orderByDesc('event_date')
            ->take(10)
            ->get();

        // Chart data - Events by Status
        $customerStatusData = [
            $upcomingEvents,
            $completedEvents,
            $cancelledEvents,
        ];

        // Payment data for chart
        $payments = Payment::whereHas('billing.event', function ($query) use ($customer) {
            $query->where('customer_id', $customer->id);
        })
            ->where('status', 'approved')
            ->selectRaw('MONTH(payment_date) as month, SUM(amount) as total')
            ->whereYear('payment_date', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $customerPaymentLabels = [];
        $customerPaymentData = [];

        for ($i = 1; $i <= 12; $i++) {
            $customerPaymentLabels[] = date('M', mktime(0, 0, 0, $i, 1));
            $payment = $payments->firstWhere('month', $i);
            $customerPaymentData[] = $payment ? (float) $payment->total : 0;
        }

        // Payment summary
        $totalPaid = Payment::whereHas('billing.event', function ($query) use ($customer) {
            $query->where('customer_id', $customer->id);
        })
            ->where('status', 'approved')
            ->sum('amount');

        $totalPending = Payment::whereHas('billing.event', function ($query) use ($customer) {
            $query->where('customer_id', $customer->id);
        })
            ->where('status', 'pending')
            ->sum('amount');

        // Calculate remaining balance from all billings
        $totalBalance = 0;
        $eventsWithBilling = Event::where('customer_id', $customer->id)
            ->whereHas('billing')
            ->with('billing')
            ->get();

        foreach ($eventsWithBilling as $event) {
            if ($event->billing && $event->billing->remaining_balance > 0) {
                $totalBalance += $event->billing->remaining_balance;
            }
        }

        // Available packages
        $packages = Package::where('is_active', true)
            ->with(['inclusions', 'images'])
            ->get();

        return view('customers.bookings', compact(
            'totalEvents',
            'upcomingEvents',
            'pendingEvents',
            'completedEvents',
            'recentEvents',
            'customerStatusData',
            'customerPaymentLabels',
            'customerPaymentData',
            'totalPaid',
            'totalPending',
            'totalBalance',
            'packages'
        ));
    }
}
