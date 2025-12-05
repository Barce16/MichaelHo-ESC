<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;


class BillingPageController extends Controller
{
    public function index()
    {
        $customer = Auth::user()->customer;

        if (!$customer) {
            abort(403, 'Unauthorized access');
        }

        // Get all events with billings for this customer (include expenses)
        $eventsWithBillings = Event::where('customer_id', $customer->id)
            ->whereHas('billing')
            ->with([
                'billing.payments' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                },
                'expenses' // Load expenses
            ])
            ->orderBy('event_date', 'desc')
            ->get();

        // Calculate stats including expenses
        $stats = [
            'package_outstanding' => 0,
            'expenses_outstanding' => 0,
            'total_outstanding' => 0,
            'total_paid' => 0,
        ];

        foreach ($eventsWithBillings as $event) {
            $billing = $event->billing;

            // Package balance (excluding expense payments)
            $packagePaid = $billing->payments
                ->where('status', 'approved')
                ->whereIn('payment_type', ['introductory', 'downpayment', 'balance'])
                ->sum('amount');
            $packageBalance = max(0, ($billing->total_amount ?? 0) - $packagePaid);

            // Unpaid expenses
            $unpaidExpenses = $event->expenses->where('payment_status', 'unpaid')->sum('amount');

            // Total paid (all approved payments)
            $totalPaid = $billing->payments->where('status', 'approved')->sum('amount');

            $stats['package_outstanding'] += $packageBalance;
            $stats['expenses_outstanding'] += $unpaidExpenses;
            $stats['total_outstanding'] += ($packageBalance + $unpaidExpenses);
            $stats['total_paid'] += $totalPaid;
        }

        return view('customers.billings', compact('eventsWithBillings', 'stats'));
    }
}
