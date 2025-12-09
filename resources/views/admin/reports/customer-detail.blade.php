<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.reports.index') }}" class="text-gray-500 hover:text-gray-700 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800">Individual Customer Report</h2>
            </div>
        </div>
    </x-slot>

    {{-- Print Styles --}}
    <style>
        @media print {

            nav,
            header,
            .no-print,
            .no-print * {
                display: none !important;
            }

            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .print-container {
                padding: 0 !important;
                margin: 0 !important;
                max-width: 100% !important;
            }

            .print-content {
                box-shadow: none !important;
            }
        }
    </style>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 print-container">

            {{-- Customer Selection (if no customer selected) --}}
            @if(!isset($customer))
            <div class="bg-white rounded-xl shadow-sm overflow-hidden no-print">
                {{-- Header --}}
                <div class="bg-gradient-to-r from-teal-600 to-cyan-600 px-6 py-8 text-white">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold">Generate Customer Report</h3>
                            <p class="text-teal-100 mt-1">Select a customer to view their complete breakdown</p>
                        </div>
                    </div>
                </div>

                {{-- Search & Stats Section --}}
                <div class="p-6 border-b border-gray-200 bg-gray-50">
                    <div class="flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">
                        {{-- Search Form --}}
                        <form method="GET" class="flex-1 max-w-md">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Search by name, email, or phone..."
                                    class="w-full pl-12 pr-4 py-3 rounded-xl border-gray-300 focus:ring-2 focus:ring-teal-200 focus:border-teal-400 text-gray-900">
                            </div>
                        </form>

                        {{-- Quick Stats --}}
                        <div class="flex flex-wrap gap-3 text-sm">
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white rounded-lg border border-gray-200">
                                <span class="w-2 h-2 bg-teal-500 rounded-full"></span>
                                <span class="text-gray-600">Total:</span>
                                <span class="font-semibold text-gray-900">{{ $totalCustomers ?? $customers->total()
                                    }}</span>
                            </span>
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white rounded-lg border border-gray-200">
                                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                <span class="text-gray-600">With Events:</span>
                                <span class="font-semibold text-gray-900">{{ $customersWithEvents ?? 0 }}</span>
                            </span>
                        </div>
                    </div>

                    @if(request('search'))
                    <div class="mt-3 flex items-center gap-2">
                        <span class="text-sm text-gray-600">Showing results for "<strong>{{ request('search')
                                }}</strong>"</span>
                        <a href="{{ route('admin.reports.customer-detail') }}"
                            class="text-sm text-teal-600 hover:text-teal-800 underline">Clear</a>
                    </div>
                    @endif
                </div>

                {{-- Customer Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-6 py-4 text-left font-semibold text-gray-700">Customer</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-700">Contact</th>
                                <th class="px-6 py-4 text-center font-semibold text-gray-700">Events</th>
                                <th class="px-6 py-4 text-right font-semibold text-gray-700">Total Spent</th>
                                <th class="px-6 py-4 text-center font-semibold text-gray-700">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($customers as $c)
                            <tr class="hover:bg-gray-50 transition">
                                {{-- Customer Name & Avatar --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-teal-400 to-cyan-500 rounded-full flex items-center justify-center flex-shrink-0">
                                            <span class="text-white font-bold">{{ strtoupper(substr($c->customer_name,
                                                0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">{{ $c->customer_name }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Contact Info --}}
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-1.5 text-gray-600">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            <span class="truncate max-w-[180px]">{{ $c->email }}</span>
                                        </div>
                                        @if($c->phone ?? $c->contact_number)
                                        <div class="flex items-center gap-1.5 text-gray-600">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            {{ $c->phone ?? $c->contact_number }}
                                        </div>
                                        @endif
                                    </div>
                                </td>

                                {{-- Events Count --}}
                                <td class="px-6 py-4 text-center">
                                    @if(($c->events_count ?? 0) > 0)
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-teal-100 text-teal-700">
                                        {{ $c->events_count }} {{ Str::plural('event', $c->events_count) }}
                                    </span>
                                    @else
                                    <span class="text-gray-400">—</span>
                                    @endif
                                </td>

                                {{-- Total Spent --}}
                                <td class="px-6 py-4 text-right">
                                    @if(($c->total_spent ?? 0) > 0)
                                    <span class="font-bold text-green-600">₱{{ number_format($c->total_spent ?? 0, 2)
                                        }}</span>
                                    @else
                                    <span class="text-gray-400">₱0.00</span>
                                    @endif
                                </td>

                                {{-- Action --}}
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.reports.customer-detail', ['customer_id' => $c->id]) }}"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-teal-600 text-white text-xs font-semibold rounded-lg hover:bg-teal-700 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        View Report
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    @if(request('search'))
                                    <p class="text-gray-500">No customers found matching "<strong>{{ request('search')
                                            }}</strong>"</p>
                                    <a href="{{ route('admin.reports.customer-detail') }}"
                                        class="text-teal-600 hover:text-teal-800 text-sm mt-2 inline-block">Clear
                                        search</a>
                                    @else
                                    <p class="text-gray-500">No customers found.</p>
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($customers->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $customers->links() }}
                </div>
                @endif
            </div>

            @else

            {{-- Export Buttons --}}
            <div
                class="bg-white rounded-lg shadow-sm p-6 mb-6 flex flex-wrap justify-between items-center gap-4 no-print">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-teal-400 to-cyan-500 rounded-full flex items-center justify-center">
                        <span class="text-white font-bold">{{ strtoupper(substr($customer->customer_name, 0, 1))
                            }}</span>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Viewing report for</div>
                        <div class="font-semibold text-gray-900">{{ $customer->customer_name }}</div>
                    </div>
                </div>
                <div class="flex gap-2 flex-wrap">
                    <a href="{{ route('admin.reports.customer-detail') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Change Customer
                    </a>
                    <button onclick="window.print()"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print
                    </button>
                    <form method="GET" class="inline">
                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                        <input type="hidden" name="export" value="csv">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export CSV
                        </button>
                    </form>
                    <form method="GET" class="inline">
                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                        <input type="hidden" name="export" value="pdf">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            Export PDF
                        </button>
                    </form>
                </div>
            </div>

            {{-- Report Content --}}
            <div class="bg-white rounded-xl shadow-sm p-8 print-content">
                {{-- Header --}}
                <div class="text-center border-b-2 border-gray-300 pb-6 mb-8">
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">CUSTOMER REPORT</h1>
                    <p class="text-gray-600">{{ $customer->customer_name }}</p>
                    <p class="text-sm text-gray-500 mt-2">Generated: {{ now()->format('F d, Y - h:i A') }}</p>
                </div>

                {{-- Customer Info --}}
                <div class="mb-8 grid md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-4">Customer
                            Information
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Name:</span>
                                <span class="font-semibold">{{ $customer->customer_name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Email:</span>
                                <span class="font-semibold">{{ $customer->email }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Phone:</span>
                                <span class="font-semibold">{{ $customer->phone ?? $customer->phone ?? 'N/A'
                                    }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Address:</span>
                                <span class="font-semibold text-right max-w-[200px]">{{ $customer->address ?? 'N/A'
                                    }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-teal-50 to-cyan-50 rounded-xl p-6 border border-teal-200">
                        <h3 class="text-sm font-semibold text-teal-800 uppercase tracking-wide mb-4">Financial Summary
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-teal-700">Total Events:</span>
                                <span class="text-2xl font-bold text-teal-900">{{ $stats['total_events'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-teal-700">Total Billed:</span>
                                <span class="text-xl font-bold text-gray-900">₱{{ number_format($stats['total_billed'],
                                    2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-teal-700">Total Paid:</span>
                                <span class="text-xl font-bold text-green-600">₱{{ number_format($stats['total_paid'],
                                    2) }}</span>
                            </div>
                            <div class="flex justify-between items-center pt-3 border-t border-teal-300">
                                <span class="text-teal-700 font-semibold">Balance Due:</span>
                                <span
                                    class="text-xl font-bold {{ $stats['total_balance'] > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    ₱{{ number_format($stats['total_balance'], 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Events List --}}
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Events History
                    </h3>

                    @forelse($events as $event)
                    <div class="border-2 border-gray-200 rounded-xl p-6 mb-4">
                        {{-- Event Header --}}
                        <div
                            class="flex flex-wrap items-start justify-between gap-4 mb-4 pb-4 border-b border-gray-200">
                            <div>
                                <h4 class="text-lg font-bold text-gray-900">{{ $event->name }}</h4>
                                <div class="flex flex-wrap items-center gap-3 mt-2 text-sm text-gray-600">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ $event->venue ?? 'TBD' }}
                                    </span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold
                                    @if($event->status === 'completed') bg-green-100 text-green-800
                                    @elseif($event->status === 'scheduled') bg-blue-100 text-blue-800
                                    @elseif($event->status === 'approved') bg-teal-100 text-teal-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($event->status) }}
                                </span>
                                <div class="mt-2 text-sm text-gray-600">
                                    Package: <span class="font-semibold">{{ $event->package->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Billing Info --}}
                        @if($event->billing)
                        <div class="grid md:grid-cols-3 gap-4 mb-4">
                            <div class="bg-gray-50 rounded-lg p-4 text-center">
                                <div class="text-sm text-gray-600">Total Amount</div>
                                <div class="text-xl font-bold text-gray-900">₱{{
                                    number_format($event->billing->total_amount, 2) }}</div>
                            </div>
                            <div class="bg-green-50 rounded-lg p-4 text-center">
                                <div class="text-sm text-green-700">Paid</div>
                                <div class="text-xl font-bold text-green-700">₱{{
                                    number_format($event->billing->payments->where('status',
                                    'approved')->sum('amount'), 2) }}</div>
                            </div>
                            <div
                                class="{{ $event->billing->total_amount - $event->billing->payments->where('status', 'approved')->sum('amount') > 0 ? 'bg-red-50' : 'bg-green-50' }} rounded-lg p-4 text-center">
                                <div
                                    class="text-sm {{ $event->billing->total_amount - $event->billing->payments->where('status', 'approved')->sum('amount') > 0 ? 'text-red-700' : 'text-green-700' }}">
                                    Balance</div>
                                <div
                                    class="text-xl font-bold {{ $event->billing->total_amount - $event->billing->payments->where('status', 'approved')->sum('amount') > 0 ? 'text-red-700' : 'text-green-700' }}">
                                    ₱{{ number_format($event->billing->total_amount -
                                    $event->billing->payments->where('status', 'approved')->sum('amount'), 2) }}
                                </div>
                            </div>
                        </div>

                        {{-- Payments Table --}}
                        @if($event->billing->payments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="py-2 px-4 text-left font-semibold text-gray-700">Date</th>
                                        <th class="py-2 px-4 text-left font-semibold text-gray-700">Type</th>
                                        <th class="py-2 px-4 text-left font-semibold text-gray-700">Method</th>
                                        <th class="py-2 px-4 text-right font-semibold text-gray-700">Amount</th>
                                        <th class="py-2 px-4 text-center font-semibold text-gray-700">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($event->billing->payments as $payment)
                                    <tr class="border-t border-gray-200">
                                        <td class="py-2 px-4">{{ $payment->created_at->format('M d, Y') }}</td>
                                        <td class="py-2 px-4 capitalize">{{ str_replace('_', ' ',
                                            $payment->payment_type) }}</td>
                                        <td class="py-2 px-4 capitalize">{{ str_replace('_', ' ',
                                            $payment->payment_method) }}</td>
                                        <td class="py-2 px-4 text-right font-semibold">₱{{
                                            number_format($payment->amount, 2) }}</td>
                                        <td class="py-2 px-4 text-center">
                                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                                @if($payment->status === 'approved') bg-green-100 text-green-800
                                                @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Event Payment Summary --}}
                        @php
                        $eventPaid = $event->billing->payments->where('status', 'approved')->sum('amount');
                        $eventBalance = $event->billing->total_amount - $eventPaid;
                        @endphp
                        <div class="mt-4 flex justify-end gap-6 text-sm">
                            <div>
                                <span class="text-gray-600">Paid:</span>
                                <span class="font-bold text-green-700 ml-1">₱{{ number_format($eventPaid, 2)
                                    }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Balance:</span>
                                <span
                                    class="font-bold {{ $eventBalance > 0 ? 'text-red-700' : 'text-green-700' }} ml-1">
                                    ₱{{ number_format($eventBalance, 2) }}
                                </span>
                            </div>
                        </div>
                        @endif
                        @endif
                    </div>
                    @empty
                    <div class="text-center py-12 bg-gray-50 rounded-xl">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-gray-500">This customer has no events yet.</p>
                    </div>
                    @endforelse
                </div>

                {{-- All Payments Summary --}}
                @if($allPayments->count() > 0)
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        Complete Payment History
                    </h3>
                    <div class="overflow-x-auto bg-gray-50 rounded-xl p-4">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b-2 border-gray-300">
                                    <th class="py-3 px-4 text-left font-semibold text-gray-700">Date</th>
                                    <th class="py-3 px-4 text-left font-semibold text-gray-700">Event</th>
                                    <th class="py-3 px-4 text-left font-semibold text-gray-700">Type</th>
                                    <th class="py-3 px-4 text-left font-semibold text-gray-700">Method</th>
                                    <th class="py-3 px-4 text-right font-semibold text-gray-700">Amount</th>
                                    <th class="py-3 px-4 text-center font-semibold text-gray-700">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allPayments as $payment)
                                <tr class="border-b border-gray-200 hover:bg-white">
                                    <td class="py-3 px-4">{{ $payment->created_at->format('M d, Y') }}</td>
                                    <td class="py-3 px-4 font-medium">{{ $payment->billing->event->name ?? '-' }}</td>
                                    <td class="py-3 px-4 capitalize">{{ str_replace('_', ' ', $payment->payment_type) }}
                                    </td>
                                    <td class="py-3 px-4 capitalize">{{ str_replace('_', ' ', $payment->payment_method)
                                        }}</td>
                                    <td class="py-3 px-4 text-right font-semibold">₱{{ number_format($payment->amount,
                                        2) }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                            @if($payment->status === 'approved') bg-green-100 text-green-800
                                            @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="border-t-2 border-gray-300 bg-green-50">
                                    <td colspan="4" class="py-3 px-4 text-right font-bold text-gray-900">Total Paid
                                        (Approved):</td>
                                    <td class="py-3 px-4 text-right font-bold text-green-700 text-lg">₱{{
                                        number_format($stats['total_paid'], 2) }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                @endif

                {{-- Footer --}}
                <div class="mt-8 pt-6 border-t border-gray-300 text-center text-sm text-gray-600">
                    <p>MichaelHo Events - Event Management System</p>
                    <p>Contact: michaelhoevents@gmail.com | Phone: 0917 306 2531</p>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>