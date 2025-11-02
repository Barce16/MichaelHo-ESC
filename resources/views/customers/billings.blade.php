<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">My Billings & Payments</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Stats --}}
            @php
            $totalOutstanding = $eventsWithBillings->sum(fn($e) => $e->billing->remaining_balance ?? 0);
            $totalPaid = $eventsWithBillings->sum(fn($e) => $e->billing->total_paid ?? 0);
            @endphp

            <div class="grid grid-cols-2 gap-4">
                <div class="bg-rose-50 border border-rose-200 rounded-lg p-4">
                    <div class="text-xs text-rose-600 mb-1">Outstanding Balance</div>
                    <div class="text-2xl font-bold text-rose-700">₱{{ number_format($totalOutstanding, 2) }}</div>
                </div>

                <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                    <div class="text-xs text-emerald-600 mb-1">Total Paid</div>
                    <div class="text-2xl font-bold text-emerald-700">₱{{ number_format($totalPaid, 2) }}</div>
                </div>
            </div>

            {{-- Billings Table --}}
            @if($eventsWithBillings->isEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Billings</h3>
                <p class="text-gray-500">You don't have any billings yet</p>
            </div>
            @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Event</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Total
                                    Amount</th>
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
                            $totalAmount = $billing->total_amount ?? 0;
                            $totalPaid = $billing->total_paid ?? 0;
                            $balance = $billing->remaining_balance ?? 0;
                            $isPaid = $billing && $billing->isFullyPaid();
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                {{-- Event --}}
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $event->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $event->event_date->format('M d, Y') }}
                                        </div>
                                    </div>
                                </td>

                                {{-- Total Amount --}}
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">₱{{ number_format($totalAmount, 2) }}
                                    </div>
                                </td>

                                {{-- Paid --}}
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-emerald-600">₱{{ number_format($totalPaid, 2) }}
                                    </div>
                                </td>

                                {{-- Balance --}}
                                <td class="px-6 py-4">
                                    <div
                                        class="text-sm font-bold {{ $balance > 0 ? 'text-rose-600' : 'text-gray-400' }}">
                                        ₱{{ number_format($balance, 2) }}
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4">
                                    @if($isPaid)
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Paid
                                    </span>
                                    @else
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Pending
                                    </span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        @if(!$isPaid && $balance > 0)
                                        <a href="{{ route('customer.payments.create', ['event' => $event->id, 'type' => 'balance']) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            Pay
                                        </a>
                                        @endif

                                        <a href="{{ route('customer.events.show', $event) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-slate-700 bg-white border border-gray-200 rounded-lg hover:bg-slate-50 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>