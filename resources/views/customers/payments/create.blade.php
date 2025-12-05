<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">
                Submit
                @if($paymentType === 'introductory')
                Introductory Payment
                @elseif($paymentType === 'downpayment')
                Downpayment
                @elseif($paymentType === 'expense')
                Expense Payment
                @else
                Balance Payment
                @endif
            </h2>
            <a href="{{ route('customer.events.show', $event) }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Event
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Event Summary Card --}}
            @php
            $headerColor = match($paymentType) {
            'introductory' => 'from-orange-500 to-red-600',
            'downpayment' => 'from-violet-500 to-purple-600',
            'balance' => 'from-emerald-500 to-teal-600',
            'expense' => 'from-amber-500 to-orange-600',
            default => 'from-gray-500 to-slate-600',
            };

            $paymentLabel = match($paymentType) {
            'introductory' => 'Introductory Payment',
            'downpayment' => 'Downpayment',
            'balance' => 'Balance Payment',
            'expense' => 'Expense Payment',
            default => 'Payment',
            };
            @endphp

            <div class="bg-gradient-to-r {{ $headerColor }} rounded-xl shadow-lg p-8 text-white">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            @if($paymentType === 'expense')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                            </svg>
                            @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            @endif
                            <span class="text-sm font-semibold opacity-90 uppercase tracking-wide">
                                {{ $paymentLabel }}
                            </span>
                        </div>
                        <h3 class="text-2xl font-bold mb-1">{{ $event->name }}</h3>
                        <p class="opacity-90">{{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm opacity-90 mb-1">
                            @if($paymentType === 'expense')
                            Expense Amount
                            @elseif($paymentType === 'balance')
                            Remaining Balance
                            @else
                            Amount Due
                            @endif
                        </p>
                        <p class="text-4xl font-bold">₱{{ number_format($amount, 2) }}</p>
                    </div>
                </div>
            </div>

            {{-- Payment Instructions --}}
            @if($paymentType === 'introductory')
            <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-sm text-blue-900">
                        <p class="font-semibold mb-1">About Introductory Payment</p>
                        <p>This ₱5,000 payment secures your event booking and allows us to schedule your planning
                            meeting. This amount will be deducted from your total downpayment later.</p>
                    </div>
                </div>
            </div>
            @elseif($paymentType === 'downpayment')
            @if(isset($hasApprovedDownpayment) && !$hasApprovedDownpayment)
            <div class="bg-violet-50 border-l-4 border-violet-500 rounded-lg p-4">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-violet-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-sm text-violet-900">
                        <p class="font-semibold mb-1">About Downpayment</p>
                        <p>The ₱5,000 introductory payment has been deducted from your downpayment. You only need to
                            pay ₱{{ number_format($amount, 2) }} now to complete your downpayment and schedule your
                            event.</p>
                    </div>
                </div>
            </div>
            @endif
            @elseif($paymentType === 'balance')
            <div class="bg-emerald-50 border-l-4 border-emerald-500 rounded-lg p-4">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-sm text-emerald-900">
                        <p class="font-semibold mb-1">Balance Payment</p>
                        <p>You can pay any amount towards your remaining balance of ₱{{ number_format($amount, 2) }}.
                            Enter the amount you wish to pay.</p>
                    </div>
                </div>
            </div>
            @elseif($paymentType === 'expense' && isset($expense))
            {{-- Expense Details Card --}}
            <div class="bg-amber-50 border-l-4 border-amber-500 rounded-lg p-4">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                    </svg>
                    <div class="text-sm text-amber-900 flex-1">
                        <p class="font-semibold mb-2">Expense Details</p>
                        <div class="bg-white rounded-lg p-3 border border-amber-200">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $expense->description }}</h4>
                                    <div class="flex items-center gap-2 mt-1 flex-wrap">
                                        @if($expense->category)
                                        <span
                                            class="px-2 py-0.5 text-xs font-medium bg-amber-100 text-amber-700 rounded">
                                            {{ $expense->category_label }}
                                        </span>
                                        @endif
                                        @if($expense->expense_date)
                                        <span class="text-xs text-gray-500">
                                            {{ $expense->expense_date->format('M d, Y') }}
                                        </span>
                                        @endif
                                    </div>
                                    @if($expense->notes)
                                    <p class="text-xs text-gray-600 mt-2">{{ $expense->notes }}</p>
                                    @endif
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <div class="text-lg font-bold text-amber-700">₱{{ number_format($expense->amount, 2)
                                        }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Payment Form --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
                x-data="{ paymentMethod: 'gcash' }">

                {{-- Validation Errors --}}
                @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">There were errors with your submission:</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 {{ $paymentType === 'expense' ? 'text-amber-500' : 'text-emerald-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Payment Information
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">Please provide your payment details and upload proof of
                        payment</p>
                </div>

                <form method="POST" action="{{ route('customer.payments.store', $event) }}"
                    enctype="multipart/form-data" class="p-6">
                    @csrf
                    <input type="hidden" name="payment_type" value="{{ $paymentType }}">
                    @if($paymentType === 'expense' && isset($expense))
                    <input type="hidden" name="expense_id" value="{{ $expense->id }}">
                    @endif

                    <div class="space-y-6">
                        {{-- Payment Amount --}}
                        @php
                        $hasApprovedDown = isset($hasApprovedDownpayment) && $hasApprovedDownpayment;
                        $defaultOption = $hasApprovedDown ? '2' : '0';
                        $minCustomAmount = $hasApprovedDown ? 100 : $amount;
                        @endphp

                        {{-- EXPENSE PAYMENT: Fixed Amount --}}
                        @if($paymentType === 'expense')
                        <div>
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 36 36">
                                    <path d="M14.18,13.8V16h9.45a5.26,5.26,0,0,0,.08-.89,4.72,4.72,0,0,0-.2-1.31Z">
                                    </path>
                                    <path d="M14.18,19.7h5.19a4.28,4.28,0,0,0,3.5-1.9H14.18Z"></path>
                                    <path d="M19.37,10.51H14.18V12h8.37A4.21,4.21,0,0,0,19.37,10.51Z"></path>
                                    <path
                                        d="M17.67,2a16,16,0,1,0,16,16A16,16,0,0,0,17.67,2Zm10.5,15.8H25.7a6.87,6.87,0,0,1-6.33,4.4H14.18v6.54a1.25,1.25,0,1,1-2.5,0V17.8H8.76a.9.9,0,1,1,0-1.8h2.92V13.8H8.76a.9.9,0,1,1,0-1.8h2.92V9.26A1.25,1.25,0,0,1,12.93,8h6.44a6.84,6.84,0,0,1,6.15,4h2.65a.9.9,0,0,1,0,1.8H26.09a6.91,6.91,0,0,1,.12,1.3,6.8,6.8,0,0,1-.06.9h2a.9.9,0,0,1,0,1.8Z">
                                    </path>
                                </svg>
                                Payment Amount <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₱</span>
                                <input name="amount" type="number" step="0.01" value="{{ $amount }}" readonly
                                    class="block w-full pl-10 pr-4 py-3 rounded-lg border-gray-300 bg-amber-50 cursor-not-allowed text-lg font-semibold transition"
                                    required />
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Fixed amount for this expense</p>
                        </div>

                        {{-- INTRODUCTORY: Fixed Amount Only (No Pay in Full option) --}}
                        @elseif($paymentType === 'introductory')
                        <div>
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 36 36">
                                    <path d="M14.18,13.8V16h9.45a5.26,5.26,0,0,0,.08-.89,4.72,4.72,0,0,0-.2-1.31Z">
                                    </path>
                                    <path d="M14.18,19.7h5.19a4.28,4.28,0,0,0,3.5-1.9H14.18Z"></path>
                                    <path d="M19.37,10.51H14.18V12h8.37A4.21,4.21,0,0,0,19.37,10.51Z"></path>
                                    <path
                                        d="M17.67,2a16,16,0,1,0,16,16A16,16,0,0,0,17.67,2Zm10.5,15.8H25.7a6.87,6.87,0,0,1-6.33,4.4H14.18v6.54a1.25,1.25,0,1,1-2.5,0V17.8H8.76a.9.9,0,1,1,0-1.8h2.92V13.8H8.76a.9.9,0,1,1,0-1.8h2.92V9.26A1.25,1.25,0,0,1,12.93,8h6.44a6.84,6.84,0,0,1,6.15,4h2.65a.9.9,0,0,1,0,1.8H26.09a6.91,6.91,0,0,1,.12,1.3,6.8,6.8,0,0,1-.06.9h2a.9.9,0,0,1,0,1.8Z">
                                    </path>
                                </svg>
                                Payment Amount <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₱</span>
                                <input name="amount" type="number" step="0.01" value="5000" readonly
                                    class="block w-full pl-10 pr-4 py-3 rounded-lg border-gray-300 bg-gray-50 cursor-not-allowed text-lg font-semibold transition"
                                    required />
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Fixed introductory payment amount</p>
                        </div>

                        {{-- DOWNPAYMENT: Options Available --}}
                        @elseif($paymentType === 'downpayment' && $event->billing && $event->billing->remaining_balance
                        > 0)
                        <div x-data="{ 
                                payInFull: '{{ $defaultOption }}',
                                customAmount: {{ $minCustomAmount }},
                                downpaymentAmount: {{ $amount ?? 0 }},
                                remainingBalance: {{ $event->billing ? $event->billing->remaining_balance : 0 }}
                            }">
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 36 36">
                                    <path d="M14.18,13.8V16h9.45a5.26,5.26,0,0,0,.08-.89,4.72,4.72,0,0,0-.2-1.31Z">
                                    </path>
                                    <path d="M14.18,19.7h5.19a4.28,4.28,0,0,0,3.5-1.9H14.18Z"></path>
                                    <path d="M19.37,10.51H14.18V12h8.37A4.21,4.21,0,0,0,19.37,10.51Z"></path>
                                    <path
                                        d="M17.67,2a16,16,0,1,0,16,16A16,16,0,0,0,17.67,2Zm10.5,15.8H25.7a6.87,6.87,0,0,1-6.33,4.4H14.18v6.54a1.25,1.25,0,1,1-2.5,0V17.8H8.76a.9.9,0,1,1,0-1.8h2.92V13.8H8.76a.9.9,0,1,1,0-1.8h2.92V9.26A1.25,1.25,0,0,1,12.93,8h6.44a6.84,6.84,0,0,1,6.15,4h2.65a.9.9,0,0,1,0,1.8H26.09a6.91,6.91,0,0,1,.12,1.3,6.8,6.8,0,0,1-.06.9h2a.9.9,0,0,1,0,1.8Z">
                                    </path>
                                </svg>
                                Payment Amount <span class="text-rose-500">*</span>
                            </label>

                            <div class="space-y-3 mb-4">
                                {{-- Downpayment Option - Only show if not yet approved --}}
                                @if(!$hasApprovedDown)
                                <label
                                    class="flex items-start gap-4 p-4 border-2 rounded-lg cursor-pointer transition-all"
                                    :class="payInFull === '0' ? 'border-violet-500 bg-violet-50' : 'border-gray-200 hover:border-gray-300'">
                                    <input type="radio" name="payment_option" value="0" x-model="payInFull"
                                        class="mt-1 text-violet-600">
                                    <div class="flex-1">
                                        <div class="font-semibold text-gray-900">Downpayment</div>
                                        <div class="text-2xl font-bold text-violet-600 my-1">₱{{ number_format($amount,
                                            2) }}</div>
                                        <p class="text-sm text-gray-600">Pay the downpayment to proceed</p>
                                    </div>
                                </label>
                                @endif

                                {{-- Custom Amount Option --}}
                                <label
                                    class="flex items-start gap-4 p-4 border-2 rounded-lg cursor-pointer transition-all"
                                    :class="payInFull === '2' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300'">
                                    <input type="radio" name="payment_option" value="2" x-model="payInFull"
                                        class="mt-1 text-blue-600">
                                    <div class="flex-1">
                                        <div class="font-semibold text-gray-900">Custom Amount</div>
                                        <div class="text-sm text-gray-600 mb-2">Pay any amount towards balance</div>
                                        <input type="number" step="0.01" x-model="customAmount"
                                            :disabled="payInFull !== '2'" min="{{ $minCustomAmount }}"
                                            max="{{ $event->billing->remaining_balance }}" placeholder="Enter amount"
                                            class="w-full px-3 py-2 border rounded-lg"
                                            :class="payInFull === '2' ? 'border-blue-300' : 'border-gray-300 bg-gray-50'">
                                    </div>
                                </label>

                                {{-- Pay in Full Option --}}
                                <label
                                    class="flex items-start gap-4 p-4 border-2 rounded-lg cursor-pointer transition-all"
                                    :class="payInFull === '1' ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-gray-300'">
                                    <input type="radio" name="payment_option" value="1" x-model="payInFull"
                                        class="mt-1 text-green-600">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <div class="font-semibold text-gray-900">Pay Remaining Balance</div>
                                            <span
                                                class="px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Save
                                                Time</span>
                                        </div>
                                        <div class="text-2xl font-bold text-green-600 my-1">₱{{
                                            number_format($event->billing->remaining_balance, 2) }}</div>
                                        <p class="text-sm text-gray-600">Complete all remaining payments now</p>
                                    </div>
                                </label>
                            </div>

                            <input type="hidden" name="pay_in_full" :value="payInFull === '1' ? 1 : 0">
                            <input type="hidden" name="amount"
                                :value="payInFull === '1' ? remainingBalance : (payInFull === '2' ? customAmount : downpaymentAmount)">
                        </div>

                        {{-- BALANCE: Flexible Amount --}}
                        @elseif($paymentType === 'balance')
                        <div>
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 36 36">
                                    <path d="M14.18,13.8V16h9.45a5.26,5.26,0,0,0,.08-.89,4.72,4.72,0,0,0-.2-1.31Z">
                                    </path>
                                    <path d="M14.18,19.7h5.19a4.28,4.28,0,0,0,3.5-1.9H14.18Z"></path>
                                    <path d="M19.37,10.51H14.18V12h8.37A4.21,4.21,0,0,0,19.37,10.51Z"></path>
                                    <path
                                        d="M17.67,2a16,16,0,1,0,16,16A16,16,0,0,0,17.67,2Zm10.5,15.8H25.7a6.87,6.87,0,0,1-6.33,4.4H14.18v6.54a1.25,1.25,0,1,1-2.5,0V17.8H8.76a.9.9,0,1,1,0-1.8h2.92V13.8H8.76a.9.9,0,1,1,0-1.8h2.92V9.26A1.25,1.25,0,0,1,12.93,8h6.44a6.84,6.84,0,0,1,6.15,4h2.65a.9.9,0,0,1,0,1.8H26.09a6.91,6.91,0,0,1,.12,1.3,6.8,6.8,0,0,1-.06.9h2a.9.9,0,0,1,0,1.8Z">
                                    </path>
                                </svg>
                                Payment Amount <span class="text-rose-500">*</span>
                            </label>

                            {{-- Pay Full Balance Checkbox --}}
                            <div
                                class="mb-3 flex items-center gap-2 p-3 bg-emerald-50 border border-emerald-200 rounded-lg">
                                <input type="checkbox" id="pay_full"
                                    class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500"
                                    onchange="document.getElementById('amount').value = this.checked ? {{ $amount }} : ''; document.getElementById('amount').readOnly = this.checked;">
                                <label for="pay_full" class="text-sm font-medium text-emerald-900 cursor-pointer">
                                    Pay Full Balance (₱{{ number_format($amount, 2) }})
                                </label>
                            </div>

                            <div class="relative">
                                <span
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₱</span>
                                <input id="amount" name="amount" type="number" step="0.01" min="100" max="{{ $amount }}"
                                    value="{{ $amount }}"
                                    class="block w-full pl-10 pr-4 py-3 rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-lg font-semibold transition"
                                    required />
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Enter amount between ₱100 and ₱{{
                                number_format($amount, 2) }} or check "Pay Full Balance" above</p>
                        </div>

                        @else
                        {{-- Fixed Amount (Fallback) --}}
                        <div>
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 36 36">
                                    <path d="M14.18,13.8V16h9.45a5.26,5.26,0,0,0,.08-.89,4.72,4.72,0,0,0-.2-1.31Z">
                                    </path>
                                    <path d="M14.18,19.7h5.19a4.28,4.28,0,0,0,3.5-1.9H14.18Z"></path>
                                    <path d="M19.37,10.51H14.18V12h8.37A4.21,4.21,0,0,0,19.37,10.51Z"></path>
                                    <path
                                        d="M17.67,2a16,16,0,1,0,16,16A16,16,0,0,0,17.67,2Zm10.5,15.8H25.7a6.87,6.87,0,0,1-6.33,4.4H14.18v6.54a1.25,1.25,0,1,1-2.5,0V17.8H8.76a.9.9,0,1,1,0-1.8h2.92V13.8H8.76a.9.9,0,1,1,0-1.8h2.92V9.26A1.25,1.25,0,0,1,12.93,8h6.44a6.84,6.84,0,0,1,6.15,4h2.65a.9.9,0,0,1,0,1.8H26.09a6.91,6.91,0,0,1,.12,1.3,6.8,6.8,0,0,1-.06.9h2a.9.9,0,0,1,0,1.8Z">
                                    </path>
                                </svg>
                                Payment Amount <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₱</span>
                                <input id="amount" name="amount" type="number" step="0.01" value="{{ $amount }}"
                                    readonly
                                    class="block w-full pl-10 pr-4 py-3 rounded-lg border-gray-300 bg-gray-50 cursor-not-allowed text-lg font-semibold transition"
                                    required />
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Amount is fixed for this payment type</p>
                        </div>
                        @endif

                        @error('amount')
                        <p class="mt-2 text-sm text-rose-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror

                        {{-- Payment Method --}}
                        <div>
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-3">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                Payment Method <span class="text-rose-500">*</span>
                            </label>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                {{-- GCash --}}
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="payment_method" value="gcash" x-model="paymentMethod"
                                        class="peer sr-only" required>
                                    <div
                                        class="flex flex-col items-center gap-2 p-4 border-2 border-gray-200 rounded-xl transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:border-gray-300">
                                        <div
                                            class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                                            <span class="text-white font-bold text-sm">G</span>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-700">GCash</span>
                                    </div>
                                </label>

                                {{-- Bank Transfer --}}
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="payment_method" value="bank_transfer"
                                        x-model="paymentMethod" class="peer sr-only">
                                    <div
                                        class="flex flex-col items-center gap-2 p-4 border-2 border-gray-200 rounded-xl transition-all peer-checked:border-emerald-500 peer-checked:bg-emerald-50 hover:border-gray-300">
                                        <div
                                            class="w-12 h-12 bg-emerald-500 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                            </svg>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-700">Bank</span>
                                    </div>
                                </label>

                                {{-- BPI --}}
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="payment_method" value="bpi" x-model="paymentMethod"
                                        class="peer sr-only">
                                    <div
                                        class="flex flex-col items-center gap-2 p-4 border-2 border-gray-200 rounded-xl transition-all peer-checked:border-red-500 peer-checked:bg-red-50 hover:border-gray-300">
                                        <div class="w-12 h-12 bg-red-600 rounded-full flex items-center justify-center">
                                            <span class="text-white font-bold text-xs">BPI</span>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-700">BPI</span>
                                    </div>
                                </label>

                                {{-- Cash --}}
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="payment_method" value="cash" x-model="paymentMethod"
                                        class="peer sr-only">
                                    <div
                                        class="flex flex-col items-center gap-2 p-4 border-2 border-gray-200 rounded-xl transition-all peer-checked:border-amber-500 peer-checked:bg-amber-50 hover:border-gray-300">
                                        <div
                                            class="w-12 h-12 bg-amber-500 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-700">Cash</span>
                                    </div>
                                </label>
                            </div>
                            @error('payment_method')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Reference Number --}}
                        <div x-show="paymentMethod !== 'cash'" x-transition>
                            <label for="reference_number"
                                class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                </svg>
                                Reference Number
                            </label>
                            <input id="reference_number" name="reference_number" type="text"
                                value="{{ old('reference_number') }}"
                                class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
                                placeholder="Enter transaction reference number">
                            @error('reference_number')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Payment Receipt Upload --}}
                        <div>
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Payment Receipt
                                <span x-show="paymentMethod !== 'cash'" class="text-rose-500">*</span>
                            </label>

                            {{-- Upload Area --}}
                            <div id="upload-area"
                                class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-emerald-400 transition cursor-pointer"
                                onclick="document.getElementById('payment_receipt').click()">
                                <div class="space-y-2 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="text-sm text-gray-600">
                                        <label for="payment_receipt"
                                            class="relative cursor-pointer rounded-md font-semibold text-emerald-600 hover:text-emerald-500">
                                            <span>Click to upload</span>
                                        </label>
                                        <span class="text-gray-500"> or drag and drop</span>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 10MB</p>
                                </div>
                            </div>

                            {{-- Preview Area --}}
                            <div id="preview-area"
                                class="hidden mt-2 relative rounded-lg overflow-hidden border-2 border-emerald-200 bg-emerald-50">
                                <img id="image-preview" class="w-full h-48 object-contain rounded-lg" />
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent pointer-events-none">
                                </div>
                                <div class="absolute bottom-0 left-0 right-0 p-3 flex items-center justify-between">
                                    <div class="flex items-center gap-2 text-white text-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="font-medium">Receipt uploaded</span>
                                    </div>
                                    <button type="button" onclick="removeImage()"
                                        class="bg-rose-500 text-white rounded-lg px-3 py-1.5 hover:bg-rose-600 transition text-sm font-medium flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Remove
                                    </button>
                                </div>
                            </div>

                            <input id="payment_receipt" name="payment_receipt" type="file" class="hidden"
                                accept="image/png,image/jpeg,image/jpg" :required="paymentMethod !== 'cash'"
                                onchange="previewImage(event)" />
                            @error('payment_receipt')
                            <p class="mt-2 text-sm text-rose-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="mt-8 flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('customer.events.show', $event) }}"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancel
                        </a>
                        <button type="submit"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r {{ $paymentType === 'expense' ? 'from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700' : 'from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700' }} text-white font-semibold rounded-lg transition shadow-md hover:shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Submit Payment Proof
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        function previewImage(event) {
            const uploadArea = document.getElementById('upload-area');
            const previewArea = document.getElementById('preview-area');
            const imagePreview = document.getElementById('image-preview');
            
            const file = event.target.files[0];
            if (!file) {
                uploadArea.classList.remove('hidden');
                previewArea.classList.add('hidden');
                return;
            }

            const validTypes = ['image/png', 'image/jpeg', 'image/jpg'];
            if (!validTypes.includes(file.type)) {
                alert('Please upload a valid image file (PNG, JPG, or JPEG)');
                event.target.value = '';
                return;
            }

            const maxSize = 10 * 1024 * 1024;
            if (file.size > maxSize) {
                alert('File size must be less than 10MB');
                event.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                uploadArea.classList.add('hidden');
                previewArea.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }

        function removeImage() {
            const fileInput = document.getElementById('payment_receipt');
            const uploadArea = document.getElementById('upload-area');
            const previewArea = document.getElementById('preview-area');
            const imagePreview = document.getElementById('image-preview');
            
            fileInput.value = '';
            imagePreview.src = '';
            uploadArea.classList.remove('hidden');
            previewArea.classList.add('hidden');
        }
    </script>
</x-app-layout>