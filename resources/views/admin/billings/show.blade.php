<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800">Billing Details</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $event->name }}</p>
            </div>
            <div class="flex items-center gap-2">
                @if($event->billing && !$event->billing->isFullyPaid())
                <a href="{{ route('admin.billings.create-payment', $event) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Process Payment
                </a>
                @endif
                <a href="{{ route('admin.billings.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @php
            $billing = $event->billing;
            $totalAmount = $billing->total_amount ?? 0;
            $totalPaid = $billing->total_paid ?? 0;
            $packageBalance = $billing->remaining_balance ?? 0;
            $isPackagePaid = $billing && $billing->isFullyPaid();

            // Expense calculations
            $expensesTotal = $billing->expenses_total ?? 0;
            $unpaidExpenses = $billing->unpaid_expenses_total ?? 0;
            $paidExpenses = $billing->paid_expenses_total ?? 0;

            // Grand totals
            $grandTotal = $billing->grand_total ?? 0;
            $grandTotalPaid = $billing->grand_total_paid ?? 0;
            $overallBalance = $billing->overall_remaining_balance ?? 0;
            $isEverythingPaid = $billing && $billing->isEverythingPaid();
            @endphp

            {{-- Flash Messages --}}
            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 rounded-xl p-4">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-rose-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            {{-- Customer & Event Info --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-violet-600 to-indigo-600 px-6 py-4">
                    <div class="flex items-center justify-between text-white">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm opacity-80">Customer</p>
                                <h3 class="text-xl font-bold">{{ $event->customer->customer_name ?? 'N/A' }}</h3>
                                <p class="text-sm opacity-80">{{ $event->customer->email ?? '' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm opacity-80">Event</p>
                            <h3 class="text-lg font-bold">{{ $event->name }}</h3>
                            <p class="text-sm opacity-80">{{ $event->event_date->format('F d, Y') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Billing Summary --}}
                <div class="p-6">
                    {{-- Package Summary --}}
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Package Payment
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="bg-slate-50 rounded-lg p-4 text-center">
                                <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Package Total</div>
                                <div class="text-2xl font-bold text-gray-900">₱{{ number_format($totalAmount, 2) }}
                                </div>
                            </div>
                            <div class="bg-emerald-50 rounded-lg p-4 text-center">
                                <div class="text-xs text-emerald-600 uppercase tracking-wide mb-1">Package Paid</div>
                                <div class="text-2xl font-bold text-emerald-600">₱{{ number_format($totalPaid, 2) }}
                                </div>
                            </div>
                            <div class="bg-rose-50 rounded-lg p-4 text-center">
                                <div class="text-xs text-rose-600 uppercase tracking-wide mb-1">Package Balance</div>
                                <div class="text-2xl font-bold text-rose-600">₱{{ number_format($packageBalance, 2) }}
                                </div>
                            </div>
                            <div
                                class="rounded-lg p-4 text-center {{ $isPackagePaid ? 'bg-emerald-100' : 'bg-amber-100' }}">
                                <div
                                    class="text-xs {{ $isPackagePaid ? 'text-emerald-600' : 'text-amber-600' }} uppercase tracking-wide mb-1">
                                    Package Status</div>
                                <div
                                    class="text-xl font-bold {{ $isPackagePaid ? 'text-emerald-700' : 'text-amber-700' }}">
                                    {{ $isPackagePaid ? 'Fully Paid' : 'Pending' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Expenses Summary (if any expenses exist) --}}
                    @if($expensesTotal > 0)
                    <div class="mb-6 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Additional Expenses
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="bg-orange-50 rounded-lg p-4 text-center">
                                <div class="text-xs text-orange-600 uppercase tracking-wide mb-1">Total Expenses</div>
                                <div class="text-2xl font-bold text-orange-700">₱{{ number_format($expensesTotal, 2) }}
                                </div>
                            </div>
                            <div class="bg-emerald-50 rounded-lg p-4 text-center">
                                <div class="text-xs text-emerald-600 uppercase tracking-wide mb-1">Expenses Paid</div>
                                <div class="text-2xl font-bold text-emerald-600">₱{{ number_format($paidExpenses, 2) }}
                                </div>
                            </div>
                            <div class="bg-rose-50 rounded-lg p-4 text-center">
                                <div class="text-xs text-rose-600 uppercase tracking-wide mb-1">Expenses Unpaid</div>
                                <div class="text-2xl font-bold text-rose-600">₱{{ number_format($unpaidExpenses, 2) }}
                                </div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4 text-center">
                                <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Expense Count</div>
                                <div class="text-xl font-bold text-gray-700">
                                    {{ $billing->paid_expenses_count }} / {{ $event->expenses->count() }} paid
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Grand Total Summary --}}
                    <div class="pt-6 border-t-2 border-gray-300">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Overall Summary
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="bg-violet-50 rounded-lg p-4 text-center border-2 border-violet-200">
                                <div class="text-xs text-violet-600 uppercase tracking-wide mb-1">Grand Total</div>
                                <div class="text-2xl font-bold text-violet-700">₱{{ number_format($grandTotal, 2) }}
                                </div>
                            </div>
                            <div class="bg-emerald-50 rounded-lg p-4 text-center border-2 border-emerald-200">
                                <div class="text-xs text-emerald-600 uppercase tracking-wide mb-1">Total Collected</div>
                                <div class="text-2xl font-bold text-emerald-600">₱{{ number_format($grandTotalPaid, 2)
                                    }}</div>
                            </div>
                            <div class="bg-rose-50 rounded-lg p-4 text-center border-2 border-rose-200">
                                <div class="text-xs text-rose-600 uppercase tracking-wide mb-1">Total Remaining</div>
                                <div class="text-2xl font-bold text-rose-600">₱{{ number_format($overallBalance, 2) }}
                                </div>
                            </div>
                            <div
                                class="rounded-lg p-4 text-center border-2 {{ $isEverythingPaid ? 'bg-emerald-100 border-emerald-300' : 'bg-amber-100 border-amber-300' }}">
                                <div
                                    class="text-xs {{ $isEverythingPaid ? 'text-emerald-600' : 'text-amber-600' }} uppercase tracking-wide mb-1">
                                    Overall Status</div>
                                <div
                                    class="text-xl font-bold {{ $isEverythingPaid ? 'text-emerald-700' : 'text-amber-700' }}">
                                    {{ $isEverythingPaid ? 'Fully Settled' : 'Has Balance' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Progress Bar --}}
                    @php
                    $progressPercentage = $grandTotal > 0 ? min(100, ($grandTotalPaid / $grandTotal) * 100) : 0;
                    @endphp
                    <div class="mt-6">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600">Overall Payment Progress</span>
                            <span class="font-semibold text-gray-900">{{ number_format($progressPercentage, 1)
                                }}%</span>
                        </div>
                        <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full transition-all duration-500"
                                style="width: {{ $progressPercentage }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Additional Expenses Section --}}
            @if($event->expenses->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
                x-data="{ showPaidExpenses: false }">
                <div class="bg-orange-50 border-b border-orange-200 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-orange-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Additional Expenses
                        </h3>
                        @if($billing->paid_expenses_count > 0)
                        <button type="button" @click="showPaidExpenses = !showPaidExpenses"
                            class="text-sm text-orange-700 hover:text-orange-900 font-medium">
                            <span
                                x-text="showPaidExpenses ? 'Hide Paid' : 'Show Paid ({{ $billing->paid_expenses_count }})'"></span>
                        </button>
                        @endif
                    </div>
                </div>

                <div class="divide-y divide-gray-200">
                    {{-- Unpaid Expenses --}}
                    @foreach($event->expenses->where('payment_status', 'unpaid') as $expense)
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 bg-rose-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $expense->description }}</div>
                                    <div class="text-sm text-gray-500 flex items-center gap-2">
                                        <span class="px-2 py-0.5 bg-gray-100 rounded text-xs">{{
                                            $expense->category_label }}</span>
                                        @if($expense->expense_date)
                                        <span>{{ $expense->expense_date->format('M d, Y') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="text-right">
                                    <div class="text-lg font-bold text-gray-900">₱{{ number_format($expense->amount, 2)
                                        }}</div>
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Unpaid
                                    </span>
                                </div>
                                <a href="{{ route('admin.billings.create-payment', ['event' => $event, 'expense_id' => $expense->id]) }}"
                                    class="inline-flex items-center gap-1 px-3 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Pay
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    {{-- Paid Expenses (hidden by default) --}}
                    <template x-if="showPaidExpenses">
                        <div>
                            @foreach($event->expenses->where('payment_status', 'paid') as $expense)
                            <div class="p-4 bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">{{ $expense->description }}</div>
                                            <div class="text-sm text-gray-500 flex items-center gap-2">
                                                <span class="px-2 py-0.5 bg-gray-100 rounded text-xs">{{
                                                    $expense->category_label }}</span>
                                                @if($expense->paid_at)
                                                <span>Paid {{ $expense->paid_at->format('M d, Y') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="text-right">
                                            <div class="text-lg font-bold text-gray-900">₱{{
                                                number_format($expense->amount, 2) }}</div>
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Paid
                                            </span>
                                        </div>
                                        <form method="POST"
                                            action="{{ route('admin.billings.expense.mark-unpaid', [$event, $expense]) }}"
                                            onsubmit="return confirm('Are you sure you want to mark this expense as unpaid? This will also delete the payment record.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-sm text-gray-500 hover:text-rose-600 font-medium">
                                                Undo
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </template>

                    {{-- Empty State for Unpaid --}}
                    @if($event->expenses->where('payment_status', 'unpaid')->count() === 0)
                    <div class="p-8 text-center">
                        <svg class="w-12 h-12 text-emerald-300 mx-auto mb-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-500 font-medium">All expenses have been paid!</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Payment History --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Payment History
                    </h3>
                </div>

                @if($billing->payments->isEmpty())
                <div class="p-8 text-center text-gray-500">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p>No payments recorded yet.</p>
                </div>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Method
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Reference
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Amount
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Status
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($billing->payments as $payment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $payment->created_at->format('M d, Y') }}
                                    <div class="text-xs text-gray-500">{{ $payment->created_at->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($payment->payment_type === 'introductory') bg-orange-100 text-orange-800
                                        @elseif($payment->payment_type === 'downpayment') bg-violet-100 text-violet-800
                                        @elseif($payment->payment_type === 'expense') bg-amber-100 text-amber-800
                                        @else bg-emerald-100 text-emerald-800 @endif">
                                        {{ ucfirst($payment->payment_type) }}
                                    </span>
                                    @if($payment->payment_type === 'expense' && $payment->expense)
                                    <div class="text-xs text-gray-500 mt-1">{{
                                        Str::limit($payment->expense->description, 20) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $payment->reference_number ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">
                                    ₱{{ number_format($payment->amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($payment->status === 'approved')
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Approved
                                    </span>
                                    @elseif($payment->status === 'pending')
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Pending
                                    </span>
                                    @elseif($payment->status === 'rejected')
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Rejected
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.payments.show', $payment) }}"
                                            class="text-violet-600 hover:text-violet-900" title="View Details">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        @if($payment->status === 'approved')
                                        <a href="{{ route('admin.payments.download-receipt', $payment) }}"
                                            target="_blank" class="text-emerald-600 hover:text-emerald-900"
                                            title="Download Receipt">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

            {{-- Package & Inclusions Summary --}}
            @if($event->package || $event->inclusions->isNotEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        Package & Inclusions
                    </h3>
                </div>

                <div class="p-6">
                    @if($event->package)
                    <div class="mb-4 p-4 bg-violet-50 rounded-lg border border-violet-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-xs text-violet-600 uppercase tracking-wide">Package</span>
                                <h4 class="font-bold text-violet-900">{{ $event->package->name }}</h4>
                            </div>
                            <span class="text-lg font-bold text-violet-700">₱{{ number_format($event->package->price ??
                                0, 2) }}</span>
                        </div>
                    </div>
                    @endif

                    @if($event->inclusions->isNotEmpty())
                    <div class="space-y-2">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Additional Inclusions</h4>
                        @foreach($event->inclusions as $inclusion)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                            <span class="text-sm text-gray-700">{{ $inclusion->name }}</span>
                            <span class="text-sm font-semibold text-gray-900">₱{{
                                number_format($inclusion->pivot->price_snapshot ?? $inclusion->price, 2) }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            @endif

        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</x-app-layout>