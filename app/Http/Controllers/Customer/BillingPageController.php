<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class BillingPageController extends Controller
{
    public function index(Request $request)
    {
        $customer = $request->user()->customer;

        if (!$customer) {
            return redirect()->route('home')->with('error', 'Customer not found.');
        }

        $eventsWithBillings = $customer->events()
            ->whereHas('billing', function ($query) {
                $query->where('downpayment_amount', 0)
                    ->where('total_amount', '>', 0);
            })
            ->with('billing')
            ->get();

        return view('customers.billings', compact('eventsWithBillings'));
    }
}
