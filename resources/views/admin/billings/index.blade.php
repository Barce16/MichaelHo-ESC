<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800">Billings & Payments</h2>
                <p class="text-sm text-gray-500 mt-1">Process payments on behalf of customers</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Outstanding
                            </div>
                            <div class="text-2xl font-bold text-rose-600 mt-1">₱{{ number_format($totalOutstanding, 2)
                                }}</div>
                            <div class="text-xs text-gray-400 mt-1">Package + Unpaid Expenses</div>
                        </div>
                        <div class="w-12 h-12 bg-rose-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Collected</div>
                            <div class="text-2xl font-bold text-emerald-600 mt-1">₱{{ number_format($totalPaid, 2) }}
                            </div>
                            <div class="text-xs text-gray-400 mt-1">All approved payments</div>
                        </div>
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Events</div>
                            <div class="text-2xl font-bold text-violet-600 mt-1">{{ $eventsWithBillings->total() }}
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-violet-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filters --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <form method="GET" action="{{ route('admin.billings.index') }}"
                    class="flex flex-wrap items-center gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" name="search" value="{{ $search ?? '' }}"
                            placeholder="Search event or customer..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                    </div>
                    <div>
                        <select name="status"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                            <option value="">All Status</option>
                            <option value="pending" {{ ($status ?? '' )==='pending' ? 'selected' : '' }}>Has Balance
                            </option>
                            <option value="paid" {{ ($status ?? '' )==='paid' ? 'selected' : '' }}>Fully Settled
                            </option>
                        </select>
                    </div>
                    <button type="submit"
                        class="px-4 py-2 bg-violet-600 text-white rounded-lg hover:bg-violet-700 transition">
                        Filter
                    </button>
                    @if($search || $status)
                    <a href="{{ route('admin.billings.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-900">
                        Clear
                    </a>
                    @endif
                </form>
            </div>

            {{-- Billings Table --}}
            @if($eventsWithBillings->isEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Billings Found</h3>
                <p class="text-gray-500">No events with billings match your criteria</p>
            </div>
            @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Customer
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Event</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Grand
                                    Total</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Paid</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Balance
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($eventsWithBillings as $event)
                            @php
                            $billing = $event->billing;
                            $grandTotal = $billing->grand_total ?? 0;
                            $grandTotalPaid = $billing->grand_total_paid ?? 0;
                            $overallBalance = $billing->overall_remaining_balance ?? 0;
                            $isEverythingPaid = $billing && $billing->isEverythingPaid();
                            $hasPending = $billing->payments()->where('status', 'pending')->exists();
                            $hasUnpaidExpenses = $event->expenses()->where('payment_status', 'unpaid')->exists();
                            $unpaidExpensesCount = $event->expenses()->where('payment_status', 'unpaid')->count();
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                {{-- Customer --}}
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $event->customer->customer_name
                                        ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">{{ $event->customer->email ?? '' }}</div>
                                </td>

                                {{-- Event --}}
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $event->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $event->event_date->format('M d, Y') }}
                                        </div>
                                    </div>
                                </td>

                                {{-- Grand Total (Package + Expenses) --}}
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">₱{{ number_format($grandTotal, 2) }}
                                    </div>
                                    @if($billing->expenses_total > 0)
                                    <div class="text-xs text-orange-600">
                                        +₱{{ number_format($billing->expenses_total, 2) }} expenses
                                    </div>
                                    @endif
                                </td>

                                {{-- Paid --}}
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-emerald-600">₱{{ number_format($grandTotalPaid,
                                        2) }}</div>
                                </td>

                                {{-- Balance --}}
                                <td class="px-6 py-4">
                                    <div
                                        class="text-sm font-bold {{ $overallBalance > 0 ? 'text-rose-600' : 'text-gray-400' }}">
                                        ₱{{ number_format($overallBalance, 2) }}
                                    </div>
                                    @if($hasUnpaidExpenses)
                                    <div class="text-xs text-orange-600">
                                        {{ $unpaidExpensesCount }} unpaid expense(s)
                                    </div>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4">
                                    @if($isEverythingPaid)
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Settled
                                    </span>
                                    @elseif($hasPending)
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                        Pending Approval
                                    </span>
                                    @elseif($hasUnpaidExpenses && $billing->isFullyPaid())
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                                        Expenses Due
                                    </span>
                                    @else
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Awaiting Payment
                                    </span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        @if(!$billing->isFullyPaid())
                                        <a href="{{ route('admin.billings.create-payment', $event) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            Pay
                                        </a>
                                        @endif

                                        <a href="{{ route('admin.billings.show', $event) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-slate-700 bg-white border border-gray-200 rounded-lg hover:bg-slate-50 transition"
                                            title="View Details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        <a href="{{ route('admin.events.show', $event) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-violet-700 bg-violet-50 border border-violet-200 rounded-lg hover:bg-violet-100 transition"
                                            title="View Event">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="bg-slate-50 px-6 py-4 border-t border-gray-200">
                    {{ $eventsWithBillings->links() }}
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>