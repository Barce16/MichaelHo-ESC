<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">My Billings & Payments</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Stats Overview --}}
            @php
            $totalOutstanding = $eventsWithBillings->sum(fn($e) => $e->billing->total_amount ?? 0);
            $totalPaid = $eventsWithBillings->sum(function($e) {
            return $e->billing->payment()->where('status', 'approved')->sum('amount');
            });
            $pendingPayments = $eventsWithBillings->sum(function($e) {
            return $e->billing->payment()->where('status', 'pending')->count();
            });
            $completedBillings = $eventsWithBillings->filter(fn($e) => ($e->billing->total_amount ?? 0) <= 0)->count();
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-gradient-to-br from-rose-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-rose-100 uppercase tracking-wide">Outstanding</p>
                                <p class="text-3xl font-bold mt-1">₱{{ number_format($totalOutstanding, 0) }}</p>
                                <p class="text-xs text-rose-100 mt-1">Total due</p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-emerald-100 uppercase tracking-wide">Paid</p>
                                <p class="text-3xl font-bold mt-1">₱{{ number_format($totalPaid, 0) }}</p>
                                <p class="text-xs text-emerald-100 mt-1">Total paid</p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Pending</p>
                                <p class="text-3xl font-bold text-amber-600 mt-1">{{ $pendingPayments }}</p>
                                <p class="text-xs text-gray-500 mt-1">Awaiting approval</p>
                            </div>
                            <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Completed</p>
                                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $completedBillings }}</p>
                                <p class="text-xs text-gray-500 mt-1">Fully paid</p>
                            </div>
                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Billing Cards --}}
                @forelse($eventsWithBillings as $event)
                @php
                $billing = $event->billing;
                $payments = $billing->payment ?? null;
                $outstandingAmount = $billing->total_amount ?? 0;
                $downpayment = $billing->downpayment_amount ?? 0;
                $isPaid = $outstandingAmount <= 0; $date=\Carbon\Carbon::parse($event->event_date);

                    // Get payment history for this billing
                    $paymentHistory = \App\Models\Payment::where('billing_id', $billing->id)->orderBy('created_at',
                    'desc')->get();
                    $totalPaidAmount = $paymentHistory->where('status', 'approved')->sum('amount');
                    $pendingAmount = $paymentHistory->where('status', 'pending')->sum('amount');
                    @endphp

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        {{-- Header --}}
                        <div
                            class="bg-gradient-to-r {{ $isPaid ? 'from-emerald-50 to-teal-50' : 'from-violet-50 to-purple-50' }} border-b border-gray-200 px-6 py-4">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div
                                            class="w-10 h-10 {{ $isPaid ? 'bg-emerald-100' : 'bg-violet-100' }} rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 {{ $isPaid ? 'text-emerald-600' : 'text-violet-600' }}"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-900">{{ $event->name }}</h3>
                                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                {{ $date->format('F d, Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($isPaid)
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Fully Paid
                                </span>
                                @else
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Payment Due
                                </span>
                                @endif
                            </div>
                        </div>

                        {{-- Billing Details --}}
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                {{-- Total Amount --}}
                                <div
                                    class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-lg p-4 border border-blue-200">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 36 36">
                                            <path
                                                d="M14.18,13.8V16h9.45a5.26,5.26,0,0,0,.08-.89,4.72,4.72,0,0,0-.2-1.31Z">
                                            </path>
                                            <path d="M14.18,19.7h5.19a4.28,4.28,0,0,0,3.5-1.9H14.18Z"></path>
                                            <path d="M19.37,10.51H14.18V12h8.37A4.21,4.21,0,0,0,19.37,10.51Z"></path>
                                            <path
                                                d="M17.67,2a16,16,0,1,0,16,16A16,16,0,0,0,17.67,2Zm10.5,15.8H25.7a6.87,6.87,0,0,1-6.33,4.4H14.18v6.54a1.25,1.25,0,1,1-2.5,0V17.8H8.76a.9.9,0,1,1,0-1.8h2.92V13.8H8.76a.9.9,0,1,1,0-1.8h2.92V9.26A1.25,1.25,0,0,1,12.93,8h6.44a6.84,6.84,0,0,1,6.15,4h2.65a.9.9,0,0,1,0,1.8H26.09a6.91,6.91,0,0,1,.12,1.3,6.8,6.8,0,0,1-.06.9h2a.9.9,0,0,1,0,1.8Z">
                                            </path>
                                        </svg>
                                        <div class="text-xs text-blue-700 font-semibold uppercase tracking-wide">
                                            Downpayment</div>
                                    </div>
                                    <div class="text-2xl font-bold text-blue-900">₱{{ number_format($downpayment, 2) }}
                                    </div>
                                </div>

                                {{-- Paid Amount --}}
                                <div
                                    class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-lg p-4 border border-emerald-200">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div class="text-xs text-emerald-700 font-semibold uppercase tracking-wide">Paid
                                        </div>
                                    </div>
                                    <div class="text-2xl font-bold text-emerald-900">₱{{ number_format($totalPaidAmount,
                                        2) }}</div>
                                </div>

                                {{-- Outstanding --}}
                                <div
                                    class="bg-gradient-to-br {{ $isPaid ? 'from-gray-50 to-slate-50' : 'from-rose-50 to-red-50' }} rounded-lg p-4 border {{ $isPaid ? 'border-gray-200' : 'border-rose-200' }}">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-4 h-4 {{ $isPaid ? 'text-gray-600' : 'text-rose-600' }}"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div
                                            class="text-xs {{ $isPaid ? 'text-gray-700' : 'text-rose-700' }} font-semibold uppercase tracking-wide">
                                            Outstanding</div>
                                    </div>
                                    <div class="text-2xl font-bold {{ $isPaid ? 'text-gray-900' : 'text-rose-900' }}">
                                        ₱{{ number_format($outstandingAmount, 2) }}</div>
                                </div>
                            </div>

                            {{-- Payment History --}}
                            @if($paymentHistory->isNotEmpty())
                            <div class="border-t border-gray-200 pt-4">
                                <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Payment History
                                </h4>
                                <div class="space-y-2">
                                    @foreach($paymentHistory as $payment)
                                    @php
                                    $statusConfig = match($payment->status) {
                                    'approved' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'border' =>
                                    'border-emerald-200', 'dot' => 'bg-emerald-500'],
                                    'rejected' => ['bg' => 'bg-rose-100', 'text' => 'text-rose-700', 'border' =>
                                    'border-rose-200', 'dot' => 'bg-rose-500'],
                                    default => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'border' =>
                                    'border-amber-200', 'dot' => 'bg-amber-500'],
                                    };
                                    @endphp
                                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                                        <div class="flex items-center justify-between gap-4">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 bg-white rounded-lg flex items-center justify-center border border-gray-200">
                                                    @if($payment->payment_method === 'gcash' || $payment->payment_method
                                                    === 'paymaya')
                                                    <span class="text-lg">💳</span>
                                                    @elseif($payment->payment_method === 'bank_transfer')
                                                    <span class="text-lg">🏦</span>
                                                    @else
                                                    <span class="text-lg">💵</span>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">₱{{
                                                        number_format($payment->amount, 2) }}</div>
                                                    <div class="text-xs text-gray-500">{{
                                                        \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y')
                                                        }} • {{ ucwords(str_replace('_', ' ', $payment->payment_method))
                                                        }}</div>
                                                </div>
                                            </div>
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-full {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} {{ $statusConfig['border'] }} border">
                                                <span
                                                    class="w-1.5 h-1.5 rounded-full {{ $statusConfig['dot'] }}"></span>
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- Action Button --}}
                            @if(!$isPaid)
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <a href="{{ route('customer.payments.create', $event) }}"
                                        class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold rounded-lg hover:from-emerald-600 hover:to-teal-700 transition shadow-md hover:shadow-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        Submit Payment
                                    </a>
                                    <a href="{{ route('customer.events.show', $event) }}"
                                        class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View Event
                                    </a>
                                </div>
                            </div>
                            @else
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <a href="{{ route('customer.events.show', $event) }}"
                                    class="inline-flex items-center justify-center gap-2 w-full px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View Event Details
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-16 text-center">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">No Billings Yet</h3>
                        <p class="text-gray-500 mb-6">You don't have any billing records at the moment.</p>
                        <a href="{{ route('customer.events.create') }}"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-violet-500 to-purple-600 text-white font-semibold rounded-lg hover:from-violet-600 hover:to-purple-700 transition shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Create Your First Event
                        </a>
                    </div>
                    @endforelse

        </div>
    </div>
</x-app-layout>