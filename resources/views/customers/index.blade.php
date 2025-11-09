<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Customers</h2>
            <div class="flex gap-3">
                <a href="{{ route('admin.customers.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    Add Walk-in Customer
                </a>
            </div>
        </div>
    </x-slot>

    @if(session('success') && session('new_customer_password'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-6 rounded-lg shadow-sm">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-green-900 mb-2">Walk-in Customer Created Successfully!</h3>
                <p class="text-sm text-green-800 mb-4">{{ session('success') }}</p>

                <div class="bg-white border border-green-200 rounded-lg p-4 mb-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Customer Login Credentials:</h4>
                    <div class="space-y-2">
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-medium text-gray-600 w-24">Username:</span>
                            <code
                                class="text-sm bg-gray-100 px-3 py-1 rounded">{{ session('new_customer_username') }}</code>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-medium text-gray-600 w-24">Password:</span>
                            <code
                                class="text-sm bg-gray-100 px-3 py-1 rounded font-mono">{{ session('new_customer_password') }}</code>
                            <button onclick="copyPassword()"
                                class="text-xs bg-gray-800 text-white px-2 py-1 rounded hover:bg-gray-900">
                                Copy
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function copyPassword() {
        const password = "{{ session('new_customer_password') }}";
        navigator.clipboard.writeText(password).then(() => {
            alert('Password copied to clipboard!');
        });
}
    </script>
    @endif

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Statistics Dashboard --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @php
                $totalCustomers = $customers->total();
                $activeCustomers = \App\Models\Customer::whereHas('events', function($q) {
                $q->where('event_date', '>=', now()->subMonths(6));
                })->count();
                $totalEvents = \App\Models\Event::count();
                $avgEventsPerCustomer = $totalCustomers > 0 ? round($totalEvents / $totalCustomers, 1) : 0;
                @endphp

                {{-- Total Customers --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 uppercase tracking-wide">Total</div>
                            <div class="text-2xl font-bold text-gray-900">{{ $totalCustomers }}</div>
                        </div>
                    </div>
                </div>

                {{-- Active Customers --}}
                <div class="bg-emerald-50 rounded-xl shadow-sm border border-emerald-200 p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-emerald-700 uppercase tracking-wide">Active</div>
                            <div class="text-2xl font-bold text-emerald-800">{{ $activeCustomers }}</div>
                        </div>
                    </div>
                </div>

                {{-- Total Events Booked --}}
                <div class="bg-sky-50 rounded-xl shadow-sm border border-sky-200 p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-sky-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-sky-700 uppercase tracking-wide">Events</div>
                            <div class="text-2xl font-bold text-sky-800">{{ $totalEvents }}</div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Filters Section --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <h3 class="font-semibold text-gray-800">Filter Customers</h3>
                </div>

                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    {{-- Search --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Search</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" name="q" value="{{ request('q') }}"
                                placeholder="Name, email, or phone..."
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400">
                        </div>
                    </div>

                    {{-- Has Events Filter --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                        <select name="has_events"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400">
                            <option value="">All Customers</option>
                            <option value="1" @selected(request('has_events')==='1' )>With Events</option>
                            <option value="0" @selected(request('has_events')==='0' )>No Events</option>
                        </select>
                    </div>

                    {{-- Date Range Filter --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Joined</label>
                        <select name="date_range"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400">
                            <option value="">All Time</option>
                            <option value="today" @selected(request('date_range')==='today' )>Today</option>
                            <option value="week" @selected(request('date_range')==='week' )>This Week</option>
                            <option value="month" @selected(request('date_range')==='month' )>This Month</option>
                            <option value="year" @selected(request('date_range')==='year' )>This Year</option>
                        </select>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex gap-2 items-end">
                        <a href="{{ route('admin.customers.index') }}"
                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition text-center">
                            Reset
                        </a>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-slate-700 text-white rounded-lg text-sm font-medium hover:bg-slate-800 transition">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            {{-- Customers Table --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Customer
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Contact Information
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Address
                                </th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Events
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Last Event
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($customers as $c)
                            @php
                            $eventsCount = $c->events()->count();
                            $lastEvent = $c->events()->latest('event_date')->first();
                            $initials = collect(explode(' ', $c->customer_name))->map(fn($word) =>
                            strtoupper(substr($word, 0, 1)))->take(2)->implode('');
                            @endphp
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-sky-400 to-blue-500 rounded-full flex items-center justify-center flex-shrink-0 shadow-sm">
                                            <span class="text-sm font-bold text-white">{{ $initials }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $c->customer_name }}
                                            </div>
                                            <div class="text-xs text-gray-500">ID: #{{ str_pad($c->id, 4, '0',
                                                STR_PAD_LEFT) }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2 text-sm text-gray-900">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            {{ $c->email }}
                                        </div>
                                        @if($c->phone)
                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            {{ $c->phone }}
                                        </div>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    @if($c->address)
                                    <div class="flex items-start gap-2 text-sm text-gray-600">
                                        <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="line-clamp-2">{{ $c->address }}</span>
                                    </div>
                                    @else
                                    <span class="text-sm text-gray-400">—</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($eventsCount > 0)
                                    <span
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full text-xs font-bold bg-violet-100 text-violet-700">
                                        {{ $eventsCount }}
                                    </span>
                                    @else
                                    <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    @if($lastEvent)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ \Carbon\Carbon::parse($lastEvent->event_date)->format('M d, Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $lastEvent->name }}</div>
                                        </div>
                                    </div>
                                    @else
                                    <span class="text-sm text-gray-400">No events yet</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <a href="{{ route('admin.customers.show', $c) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <p class="text-gray-500 font-medium">
                                        @if(request('q') || request('has_events') !== null || request('date_range'))
                                        No customers found matching your filters
                                        @else
                                        No customers yet
                                        @endif
                                    </p>
                                    <p class="text-gray-400 text-sm mt-1">
                                        @if(request('q') || request('has_events') !== null || request('date_range'))
                                        Try adjusting your filters
                                        @else
                                        Get started by adding your first customer
                                        @endif
                                    </p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($customers->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-slate-50">
                    {{ $customers->withQueryString()->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>