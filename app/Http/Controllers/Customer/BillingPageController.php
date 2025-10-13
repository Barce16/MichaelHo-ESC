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

        // Get all events with billings for this customer
        $eventsWithBillings = Event::where('customer_id', $customer->id)
            ->whereHas('billing')
            ->with([
                'billing.payments' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                }
            ])
            ->orderBy('event_date', 'desc')
            ->get();

        return view('customers.billings', compact('eventsWithBillings'));
    }
}
