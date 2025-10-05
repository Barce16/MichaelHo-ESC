<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Customers Report</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Filters --}}
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6 flex justify-between items-end">
                <form method="GET" class="flex gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">From</label>
                        <input type="date" name="from" value="{{ $dateFrom->format('Y-m-d') }}"
                            class="rounded-lg border-gray-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">To</label>
                        <input type="date" name="to" value="{{ $dateTo->format('Y-m-d') }}"
                            class="rounded-lg border-gray-300">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="px-6 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-700">
                            Generate Report
                        </button>
                    </div>
                </form>
                <div class="flex gap-2">
                    <form method="GET" class="inline">
                        <input type="hidden" name="from" value="{{ $dateFrom->format('Y-m-d') }}">
                        <input type="hidden" name="to" value="{{ $dateTo->format('Y-m-d') }}">
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
                    <form method="GET" class="inline">
                        <input type="hidden" name="from" value="{{ $dateFrom->format('Y-m-d') }}">
                        <input type="hidden" name="to" value="{{ $dateTo->format('Y-m-d') }}">
                        <input type="hidden" name="export" value="csv">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export CSV
                        </button>
                    </form>
                </div>
            </div>

            {{-- Report Content --}}
            <div class="bg-white rounded-lg shadow-sm p-8">

                {{-- Report Header --}}
                <div class="border-b-2 border-gray-300 pb-6 mb-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <span class="text-2xl font-bold text-gray-600">MH</span>
                                </div>
                                <div>
                                    <h1 class="text-2xl font-bold text-gray-900">MichaelHo Events</h1>
                                    <p class="text-sm text-gray-600">Event Management System</p>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <h2 class="text-xl font-bold text-gray-900">Customers Report</h2>
                            <p class="text-sm text-gray-600 mt-1">
                                Period: {{ $dateFrom->format('M d, Y') }} - {{ $dateTo->format('M d, Y') }}
                            </p>
                            <p class="text-sm text-gray-600">
                                Generated: {{ now()->format('M d, Y g:i A') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Summary Statistics --}}
                <div class="grid md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg p-6 border-2 border-purple-200">
                        <div class="text-sm text-purple-700 font-medium mb-1">Total Customers</div>
                        <div class="text-4xl font-bold text-purple-900">{{ $stats['total_customers'] }}</div>
                    </div>

                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-6 border-2 border-blue-200">
                        <div class="text-sm text-blue-700 font-medium mb-1">Active Customers</div>
                        <div class="text-4xl font-bold text-blue-900">{{ $stats['active_customers'] }}</div>
                    </div>

                    <div class="bg-gradient-to-br from-orange-50 to-amber-50 rounded-lg p-6 border-2 border-orange-200">
                        <div class="text-sm text-orange-700 font-medium mb-1">Total Events</div>
                        <div class="text-4xl font-bold text-orange-900">{{ $stats['total_events'] }}</div>
                    </div>

                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-6 border-2 border-green-200">
                        <div class="text-sm text-green-700 font-medium mb-1">Total Revenue</div>
                        <div class="text-3xl font-bold text-green-900">₱{{ number_format($stats['total_revenue'], 2) }}
                        </div>
                    </div>
                </div>

                {{-- Customers Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b-2 border-gray-300">
                                <th class="text-left py-3 font-semibold text-gray-700">Customer Name</th>
                                <th class="text-left py-3 font-semibold text-gray-700">Email</th>
                                <th class="text-left py-3 font-semibold text-gray-700">Phone</th>
                                <th class="text-center py-3 font-semibold text-gray-700">Total Events</th>
                                <th class="text-right py-3 font-semibold text-gray-700">Total Spent</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $customer)
                            @php
                            $totalSpent = $customer->events->sum(fn($e) => $e->billing?->total_amount ?? 0);
                            @endphp
                            <tr class="border-b border-gray-200">
                                <td class="py-3 font-medium">{{ $customer->customer_name }}</td>
                                <td class="py-3">{{ $customer->email }}</td>
                                <td class="py-3">{{ $customer->phone ?? '-' }}</td>
                                <td class="py-3 text-center">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                        {{ $customer->events_count }}
                                    </span>
                                </td>
                                <td class="py-3 text-right font-semibold text-green-700">₱{{ number_format($totalSpent,
                                    2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-500">No customers found in this period
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-gray-300 font-bold bg-green-50">
                                <td colspan="4" class="py-4 text-right text-lg">TOTAL REVENUE:</td>
                                <td class="py-4 text-right text-2xl text-green-700">₱{{
                                    number_format($stats['total_revenue'], 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Footer --}}
                <div class="mt-8 pt-6 border-t border-gray-300 text-center text-sm text-gray-600">
                    <p>MichaelHo Events - Event Management System</p>
                    <p>Contact: michaelhoevents@gmail.com | Phone: 0917 306 2531</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>