<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $q = request('q');
        $customers = Customer::when($q, function ($query) use ($q) {
            $q = "%" . strtolower($q) . "%";
            $query->whereRaw('LOWER(customer_name) LIKE ?', [$q])
                ->orWhereRaw('LOWER(email) LIKE ?', [$q])
                ->orWhereRaw('LOWER(phone) LIKE ?', [$q]);
        })
            ->latest()->paginate(10)->withQueryString();

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
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
        return redirect()->route('customers.index')->with('success', 'Customer added.');
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
        return redirect()->route('customers.index')->with('success', 'Customer updated.');
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
}
