<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Payroll Management</h2>
            <div class="text-sm text-gray-600">
                Event Staff Payments
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
            <div class="bg-white border-l-4 border-gray-800 rounded-lg p-4 shadow-sm">
                <p class="text-gray-800 font-medium">{{ session('success') }}</p>
            </div>
            @endif

            {{-- Statistics Cards --}}
            <div class="grid md:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Events</p>
                            <p class="text-3xl font-bold text-slate-700 mt-2">{{ $stats['total_events'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Pending Payroll</p>
                            <p class="text-3xl font-bold text-slate-700 mt-2">{{ $stats['pending_payroll'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Pending Amount</p>
                            <p class="text-2xl font-bold text-slate-700 mt-2">₱{{
                                number_format($stats['total_pending_amount'], 2) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 36 36"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M14.18,13.8V16h9.45a5.26,5.26,0,0,0,.08-.89,4.72,4.72,0,0,0-.2-1.31Z">
                                </path>
                                <path d="M14.18,19.7h5.19a4.28,4.28,0,0,0,3.5-1.9H14.18Z"></path>
                                <path d="M19.37,10.51H14.18V12h8.37A4.21,4.21,0,0,0,19.37,10.51Z"></path>
                                <path
                                    d="M17.67,2a16,16,0,1,0,16,16A16,16,0,0,0,17.67,2Zm10.5,15.8H25.7a6.87,6.87,0,0,1-6.33,4.4H14.18v6.54a1.25,1.25,0,1,1-2.5,0V17.8H8.76a.9.9,0,1,1,0-1.8h2.92V13.8H8.76a.9.9,0,1,1,0-1.8h2.92V9.26A1.25,1.25,0,0,1,12.93,8h6.44a6.84,6.84,0,0,1,6.15,4h2.65a.9.9,0,0,1,0,1.8H26.09a6.91,6.91,0,0,1,.12,1.3,6.8,6.8,0,0,1-.06.9h2a.9.9,0,0,1,0,1.8Z">
                                </path>
                            </svg>

                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-slate-700 to-gray-600 rounded-lg shadow-sm p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-300 uppercase tracking-wide">Total Paid</p>
                            <p class="text-2xl font-bold mt-2">₱{{ number_format($stats['total_paid_amount'], 2) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-white/10 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filters --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <form method="GET" class="flex flex-wrap gap-3">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" name="search" value="{{ $search }}"
                            placeholder="Search by event or customer name..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-700 focus:border-slate-700">
                    </div>
                    <div>
                        <select name="status"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-700 focus:border-slate-700">
                            <option value="all" {{ $status==='all' ? 'selected' : '' }}>All Status</option>
                            <option value="scheduled" {{ $status==='scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="completed" {{ $status==='completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <button type="submit"
                        class="px-6 py-2 bg-slate-700 text-white font-medium rounded-lg hover:bg-gray-600 transition">
                        Filter
                    </button>
                    @if($search || $status !== 'all')
                    <a href="{{ route('admin.payroll.index') }}"
                        class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                        Clear
                    </a>
                    @endif
                </form>
            </div>

            {{-- Events Table --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-700 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Event
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Customer
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Staff
                                    Count</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider">Total
                                    Payroll</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Status
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($events as $event)
                            @php
                            $totalPayroll = $event->staffs->sum('pivot.pay_rate');
                            $paidCount = $event->staffs->where('pivot.pay_status', 'paid')->count();
                            $totalStaff = $event->staffs->count();
                            $allPaid = $paidCount === $totalStaff;
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-700">{{ $event->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $event->package->name ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $event->customer->customer_name }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                        {{ $totalStaff }} Staff
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-slate-700">
                                    ₱{{ number_format($totalPayroll, 2) }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($allPaid)
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-700 text-white">
                                        ✓ Paid
                                    </span>
                                    @else
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-200 text-gray-700">
                                        {{ $paidCount }}/{{ $totalStaff }} Paid
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.payroll.viewStaffs', $event) }}"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-slate-700 text-white text-sm font-medium rounded-lg hover:bg-gray-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View Details
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <p class="text-lg font-medium">No events with staff assignments found</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($events->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $events->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>