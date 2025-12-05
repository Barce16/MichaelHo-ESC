<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">My Billings & Payments</h2>
    </x-slot>

    <div class="py-6" x-data="{ activeModal: null }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Stats --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-rose-50 border border-rose-200 rounded-lg p-4">
                    <div class="text-xs text-rose-600 mb-1">Total Outstanding</div>
                    <div class="text-2xl font-bold text-rose-700">₱{{ number_format($stats['total_outstanding'], 2) }}
                    </div>
                    @if($stats['expenses_outstanding'] > 0)
                    <div class="text-xs text-rose-500 mt-1">
                        Includes ₱{{ number_format($stats['expenses_outstanding'], 2) }} expenses
                    </div>
                    @endif
                </div>

                <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                    <div class="text-xs text-emerald-600 mb-1">Total Paid</div>
                    <div class="text-2xl font-bold text-emerald-700">₱{{ number_format($stats['total_paid'], 2) }}</div>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="text-xs text-blue-600 mb-1">Package Balance</div>
                    <div class="text-2xl font-bold text-blue-700">₱{{ number_format($stats['package_outstanding'], 2) }}
                    </div>
                </div>

                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <div class="text-xs text-amber-600 mb-1">Unpaid Expenses</div>
                    <div class="text-2xl font-bold text-amber-700">₱{{ number_format($stats['expenses_outstanding'], 2)
                        }}</div>
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
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Package
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Expenses
                                </th>
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
                            $packageTotal = $billing->total_amount ?? 0;
                            $packagePaid = $billing->payments->where('status', 'approved')->whereIn('payment_type',
                            ['introductory', 'downpayment', 'balance'])->sum('amount');
                            $packageBalance = max(0, $packageTotal - $packagePaid);
                            $expensesTotal = $event->expenses->sum('amount');
                            $unpaidExpenses = $event->expenses->where('payment_status', 'unpaid')->sum('amount');
                            $unpaidExpensesCount = $event->expenses->where('payment_status', 'unpaid')->count();
                            $totalPaid = $billing->payments->where('status', 'approved')->sum('amount');
                            $totalBalance = $packageBalance + $unpaidExpenses;
                            $isFullyPaid = $packageBalance <= 0 && $unpaidExpenses <=0;
                                $hasUnpaidExpenses=$unpaidExpenses> 0 && $packageBalance <= 0; @endphp <tr
                                    class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $event->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $event->event_date->format('M d, Y')
                                                }}</div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-gray-900">₱{{ number_format($packageTotal, 2)
                                            }}</div>
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($expensesTotal > 0)
                                        <div class="text-sm font-bold text-amber-600">₱{{ number_format($expensesTotal,
                                            2) }}</div>
                                        @if($unpaidExpensesCount > 0)
                                        <div class="text-xs text-amber-500">{{ $unpaidExpensesCount }} unpaid</div>
                                        @else
                                        <div class="text-xs text-green-500">All paid</div>
                                        @endif
                                        @else
                                        <div class="text-sm text-gray-400">—</div>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-emerald-600">₱{{ number_format($totalPaid, 2)
                                            }}</div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div
                                            class="text-sm font-bold {{ $totalBalance > 0 ? 'text-rose-600' : 'text-gray-400' }}">
                                            ₱{{ number_format($totalBalance, 2) }}
                                        </div>
                                        @if($packageBalance > 0 && $unpaidExpenses > 0)
                                        <div class="text-xs text-gray-500">
                                            Pkg: ₱{{ number_format($packageBalance, 2) }} | Exp: ₱{{
                                            number_format($unpaidExpenses, 2) }}
                                        </div>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($isFullyPaid)
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Settled
                                        </span>
                                        @elseif($hasUnpaidExpenses)
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Expenses Due
                                        </span>
                                        @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Pending
                                        </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-2">
                                            @if($packageBalance > 0)
                                            <a href="{{ route('customer.payments.create', ['event' => $event->id, 'type' => 'balance']) }}"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                Pay
                                            </a>
                                            @endif

                                            @if($event->expenses->count() > 0)
                                            <button type="button" @@click="activeModal = {{ $event->id }}"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-white bg-amber-600 rounded-lg hover:bg-amber-700 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                                                </svg>
                                                Expenses
                                                @if($unpaidExpensesCount > 0)
                                                <span class="px-1.5 py-0.5 text-xs bg-white/20 rounded">{{
                                                    $unpaidExpensesCount }}</span>
                                                @endif
                                            </button>
                                            @endif

                                            <a href="{{ route('customer.events.show', $event) }}"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-slate-700 bg-white border border-gray-200 rounded-lg hover:bg-slate-50 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
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

        {{-- Modals rendered outside the table --}}
        @foreach($eventsWithBillings as $event)
        @if($event->expenses->count() > 0)
        @php
        $modalExpensesTotal = $event->expenses->sum('amount');
        $modalUnpaidExpenses = $event->expenses->where('payment_status', 'unpaid')->sum('amount');
        $modalPaidExpenses = $event->expenses->where('payment_status', 'paid')->sum('amount');
        $modalUnpaidCount = $event->expenses->where('payment_status', 'unpaid')->count();
        @endphp
        <div x-show="activeModal === {{ $event->id }}" x-cloak
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @@click.self="activeModal = null">

            <div class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full mx-auto max-h-[85vh] flex flex-col"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-90" @@click.stop>

                {{-- Modal Header --}}
                <div class="bg-gradient-to-r from-rose-500 to-pink-600 px-6 py-4 rounded-t-2xl flex-shrink-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-white">{{ $event->name }}</h3>
                                <p class="text-sm text-rose-100">Event Expenses</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-right">
                                <div class="text-xs text-rose-200">Total</div>
                                <div class="text-xl font-bold text-white">₱{{ number_format($modalExpensesTotal, 2) }}
                                </div>
                            </div>
                            <button type="button" @@click="activeModal = null"
                                class="text-white/80 hover:text-white transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Modal Body --}}
                <div class="flex-1 overflow-y-auto p-6">
                    {{-- Summary Cards --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                        <div class="bg-gray-50 rounded-lg border border-gray-200 p-3 text-center">
                            <div class="text-xs text-gray-500 mb-1">Total</div>
                            <div class="text-lg font-bold text-rose-600">₱{{ number_format($modalExpensesTotal, 2) }}
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg border border-gray-200 p-3 text-center">
                            <div class="text-xs text-gray-500 mb-1">Items</div>
                            <div class="text-lg font-bold text-gray-900">{{ $event->expenses->count() }}</div>
                        </div>
                        <div class="bg-green-50 rounded-lg border border-green-200 p-3 text-center">
                            <div class="text-xs text-green-600 mb-1">Paid</div>
                            <div class="text-lg font-bold text-green-700">₱{{ number_format($modalPaidExpenses, 2) }}
                            </div>
                        </div>
                        <div class="bg-amber-50 rounded-lg border border-amber-200 p-3 text-center">
                            <div class="text-xs text-amber-600 mb-1">Unpaid</div>
                            <div class="text-lg font-bold text-amber-700">₱{{ number_format($modalUnpaidExpenses, 2) }}
                            </div>
                        </div>
                    </div>

                    {{-- Expenses List --}}
                    <div class="bg-white rounded-lg border border-gray-200">
                        <div class="bg-gray-50 border-b border-gray-200 px-4 py-3">
                            <h5 class="font-medium text-gray-800 text-sm">Expense Details</h5>
                        </div>
                        <div class="divide-y divide-gray-100 max-h-[300px] overflow-y-auto">
                            @foreach($event->expenses->sortByDesc('expense_date') as $expense)
                            <div class="p-4 hover:bg-gray-50 transition">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                                            <h6 class="font-medium text-gray-900 text-sm">{{ $expense->description }}
                                            </h6>
                                            @if($expense->category)
                                            <span
                                                class="px-1.5 py-0.5 text-xs font-medium bg-rose-100 text-rose-700 rounded">{{
                                                $expense->category_label }}</span>
                                            @endif
                                            @if($expense->isPaid())
                                            <span
                                                class="inline-flex items-center gap-1 px-1.5 py-0.5 text-xs font-medium bg-green-100 text-green-700 rounded">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Paid
                                            </span>
                                            @else
                                            <span
                                                class="px-1.5 py-0.5 text-xs font-medium bg-amber-100 text-amber-700 rounded">Unpaid</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $expense->expense_date?->format('M d, Y') ?? 'No date' }}
                                            @if($expense->isPaid() && $expense->paid_at)
                                            <span class="text-green-600 ml-2">• Paid {{ $expense->paid_at->format('M d,
                                                Y') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <div
                                            class="text-base font-bold {{ $expense->isPaid() ? 'text-green-600' : 'text-rose-600' }}">
                                            ₱{{ number_format($expense->amount, 2) }}
                                        </div>
                                        @if($expense->isUnpaid())
                                        <a href="{{ route('customer.payments.createExpense', ['event' => $event->id, 'expense' => $expense->id]) }}"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 mt-1 text-xs font-medium text-white bg-amber-500 hover:bg-amber-600 rounded-lg transition shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            Pay Now
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Info Note --}}
                    <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="text-sm text-blue-800">
                                <p class="font-medium mb-1">About Event Expenses</p>
                                <p class="text-xs text-blue-700">These are additional costs incurred during your event
                                    preparation. Click "Pay Now" to submit payment.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div
                    class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex items-center justify-between flex-shrink-0 rounded-b-2xl">
                    <div class="text-sm text-gray-500">
                        {{ $event->expenses->count() }} expense(s)
                        @if($modalUnpaidCount > 0)
                        <span class="ml-2 text-amber-600">• {{ $modalUnpaidCount }} unpaid</span>
                        @endif
                    </div>
                    <button type="button" @@click="activeModal = null"
                        class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition">
                        Close
                    </button>
                </div>
            </div>
        </div>
        @endif
        @endforeach
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</x-app-layout>