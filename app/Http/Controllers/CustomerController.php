<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

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
}
