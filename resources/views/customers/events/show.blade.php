<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Event Details</h2>
            <div class="flex items-center gap-2">
                @if(in_array($event->status, ['requested', 'request_meeting', 'meeting']))
                <a href="{{ route('customer.events.edit', $event) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Event
                </a>
                @endif
                <a href="{{ route('customer.events.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Events
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Event Header Card --}}
            @php
            $status = strtolower($event->status ?? 'unknown');
            $statusConfig = match($status) {
            'requested' => ['bg' => 'from-amber-500 to-orange-600', 'icon_bg' => 'bg-amber-500'],
            'request_meeting' => ['bg' => 'from-orange-500 to-red-600', 'icon_bg' => 'bg-orange-500'],
            'meeting' => ['bg' => 'from-blue-500 to-indigo-600', 'icon_bg' => 'bg-blue-500'],
            'scheduled' => ['bg' => 'from-violet-500 to-purple-600', 'icon_bg' => 'bg-violet-500'],
            'ongoing' => ['bg' => 'from-teal-500 to-cyan-600', 'icon_bg' => 'bg-teal-500'],
            'completed' => ['bg' => 'from-emerald-600 to-green-700', 'icon_bg' => 'bg-emerald-600'],
            'rejected' => ['bg' => 'from-rose-500 to-red-600', 'icon_bg' => 'bg-rose-500'],
            default => ['bg' => 'from-gray-500 to-slate-600', 'icon_bg' => 'bg-gray-500'],
            };
            $date = \Illuminate\Support\Carbon::parse($event->event_date);
            @endphp

            <div class="bg-gradient-to-r {{ $statusConfig['bg'] }} rounded-xl shadow-lg p-8 text-white">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <div
                                class="h-12 {{ $statusConfig['icon_bg'] }}/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                            </div>
                            <div>
                                <div class="text-sm font-semibold opacity-90 uppercase tracking-wide">{{
                                    $event->package?->name ?? 'Event' }}</div>
                                <h3 class="text-3xl font-bold">{{ $event->name }}</h3>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <div>
                                    <div class="text-xs opacity-75">Event Date</div>
                                    <div class="font-semibold">{{ $date->format('M d, Y') }}</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <div>
                                    <div class="text-xs opacity-75">Venue</div>
                                    <div class="font-semibold">{{ $event->venue ?: 'TBD' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex-shrink-0">
                        <span
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/20 backdrop-blur-sm font-semibold">
                            <span class="w-2 h-2 rounded-full bg-white"></span>
                            {{ $event->status_label }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- ========================================
            PAYMENT ACTION ALERTS (Organized by Status)
            ======================================== --}}

            {{-- 1. REQUEST_MEETING: Need to Pay Intro --}}
            @if($event->status === 'request_meeting' && !$pendingIntroPayment)
            <div
                class="bg-gradient-to-r from-orange-50 to-red-50 border-l-4 border-orange-500 rounded-xl shadow-sm overflow-hidden">
                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                                class="w-6 h-6 text-white">
                                <path d="M11,15H8V3h3a6,6,0,0,1,6,6h0A6,6,0,0,1,11,15ZM8,3V21"
                                    style="fill: none; stroke: #ffffff; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;">
                                </path>
                                <path d="M4,7H20M4,11H20"
                                    style="fill: none; stroke: #ffffff; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;">
                                </path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-orange-900 mb-2">🎉 Booking Approved - Payment Required
                            </h3>
                            <p class="text-orange-800 mb-4">Congratulations! Your event has been approved. Submit your
                                payment to proceed.</p>

                            <div class="bg-white rounded-lg p-4 mb-4 shadow-sm">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">Introductory Payment</span>
                                    <span class="text-3xl font-bold text-orange-600">₱{{ number_format($introAmount, 2)
                                        }}</span>
                                </div>
                                @if($event->billing && $event->billing->total_amount > 0)
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <div class="flex items-center gap-2 text-sm text-green-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                        <span class="font-semibold">Option to pay in full available!</span>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-1">You can choose to pay the full amount (₱{{
                                        number_format($event->billing->total_amount, 2) }}) in the payment form.</p>
                                </div>
                                @endif
                            </div>

                            <a href="{{ route('customer.payments.createIntro', $event) }}"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-orange-600 text-white font-semibold rounded-lg hover:bg-orange-700 transition shadow-md hover:shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Proceed to Payment
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- 2. Pending Intro Payment Alert --}}
            @if($pendingIntroPayment)
            <div
                class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 rounded-xl shadow-sm overflow-hidden">
                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0 animate-pulse">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-blue-900 mb-2">⏳ Payment Under Review</h3>
                            <p class="text-blue-800 mb-4">Your introductory payment of ₱{{
                                number_format($pendingIntroPayment->amount, 2) }} is being verified. We'll notify you
                                once approved!</p>
                            <div class="text-xs text-blue-600">Submitted: {{
                                $pendingIntroPayment->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- 3. MEETING: Waiting for Admin to Request Downpayment --}}
            @if($event->status === 'meeting' && (!$event->billing || $event->billing->downpayment_amount == 0) &&
            !$pendingDownpayment)
            <div
                class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 rounded-xl shadow-sm overflow-hidden">
                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-blue-900 mb-2">✅ Intro Payment Confirmed!</h3>
                            <p class="text-blue-800 mb-4">Great! Your introductory payment has been confirmed. Our team
                                will contact you soon to finalize event details and request the downpayment.</p>

                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <div>
                                        <div class="text-sm text-gray-700 font-medium">Need to reach us?</div>
                                        <a href="tel:09173062531"
                                            class="text-lg font-bold text-blue-600 hover:text-blue-700">0917 306
                                            2531</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- 4. MEETING: Downpayment Requested (Not Yet Paid) --}}
            @if(($event->status !== 'requested') && $event->billing && $event->billing->downpayment_amount <= 0 &&
                !$pendingDownpayment && !$event->
                hasDownpaymentPaid())
                <div
                    class="bg-gradient-to-r from-violet-50 to-purple-50 border-l-4 border-violet-500 rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 bg-violet-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-violet-900 mb-2">💰 Downpayment Requested</h3>
                                <p class="text-violet-800 mb-4">Please submit your downpayment to finalize your event
                                    booking and schedule.</p>

                                <div class="bg-white rounded-lg p-4 mb-4 shadow-sm space-y-2">
                                    <div class="flex items-center justify-between pb-2 border-b border-gray-200">
                                        <span class="text-sm font-medium text-gray-700">Total Downpayment</span>
                                        <span class="text-2xl font-bold text-violet-600">₱{{
                                            number_format($event->billing->downpayment_amount, 2) }}</span>
                                    </div>
                                    @if($event->billing->introductory_payment_status === 'paid')
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-600">Introductory Payment (Paid)</span>
                                        <span class="text-emerald-600 font-semibold">- ₱5,000.00</span>
                                    </div>
                                    @endif
                                    <div class="flex items-center justify-between pt-2 border-t border-gray-200">
                                        <span class="text-sm font-bold text-gray-900">Amount to Pay Now</span>
                                        <span class="text-3xl font-bold text-violet-600">₱{{
                                            number_format($downpaymentAmount, 2) }}</span>
                                    </div>
                                </div>

                                <a href="{{ route('customer.payments.createDownpayment', $event) }}"
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-violet-600 text-white font-semibold rounded-lg hover:bg-violet-700 transition shadow-md hover:shadow-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Submit Downpayment Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- 5. Pending Downpayment Alert --}}
                @if($pendingDownpayment)
                @php
                $isFullPayment = false;
                if ($event->billing && $event->billing->remaining_balance > 0) {
                $isFullPayment = abs($pendingDownpayment->amount - $event->billing->remaining_balance) < 0.01; } @endphp
                    <div
                    class="bg-gradient-to-r from-{{ $isFullPayment ? 'green' : 'blue' }}-50 to-{{ $isFullPayment ? 'emerald' : 'indigo' }}-50 border-l-4 border-{{ $isFullPayment ? 'green' : 'blue' }}-500 rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 bg-{{ $isFullPayment ? 'green' : 'blue' }}-500 rounded-full flex items-center justify-center flex-shrink-0 animate-pulse">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($isFullPayment)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    @endif
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3
                                    class="text-lg font-bold {{ $isFullPayment ? 'text-green-900' : 'text-blue-900' }} mb-2">
                                    ⏳ {{ $isFullPayment ? 'Full Payment' : 'Downpayment' }} Under Review
                                </h3>
                                <p class="{{ $isFullPayment ? 'text-green-800' : 'text-blue-800' }} mb-4">
                                    @if($isFullPayment)
                                    Your full payment of ₱{{ number_format($pendingDownpayment->amount, 2) }} is being
                                    verified. Your event will be fully confirmed once approved!
                                    @else
                                    Your downpayment of ₱{{ number_format($pendingDownpayment->amount, 2) }} is being
                                    verified. Your event will be scheduled once approved!
                                    @endif
                                </p>
                                <div class="text-xs {{ $isFullPayment ? 'text-green-600' : 'text-blue-600' }}">
                                    Submitted: {{ $pendingDownpayment->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </div>
        </div>
        @endif

        {{-- 6. SCHEDULED/ONGOING/COMPLETED: Balance Payment Available --}}
        @if(in_array($event->status, ['scheduled', 'ongoing', 'completed']) && $canPayBalance)
        <div
            class="bg-gradient-to-r from-emerald-50 to-teal-50 border-l-4 border-emerald-500 rounded-xl shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-emerald-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-emerald-900 mb-2">💰 Balance Payment Available</h3>
                        <p class="text-emerald-800 mb-4">
                            Your remaining balance is ₱{{ number_format($event->billing->remaining_balance, 2)
                            }}.
                            You can make partial payments anytime.
                        </p>

                        <a href="{{ route('customer.payments.create', $event) }}"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition shadow-md hover:shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Pay Balance Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- 7. SCHEDULED/ONGOING/COMPLETED: Downpayment Not Yet Paid --}}
        @if(in_array($event->status, ['scheduled', 'ongoing', 'completed']) && !$isDownpaymentPaid &&
        $event->billing && $event->billing->downpayment_amount > 0)
        <div
            class="bg-gradient-to-r from-amber-50 to-orange-50 border-l-4 border-amber-500 rounded-xl shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-amber-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-amber-900 mb-2">⚠️ Downpayment Required First</h3>
                        <p class="text-amber-800 mb-4">
                            Please complete your downpayment of ₱{{ number_format($downpaymentAmount, 2) }}
                            before
                            making balance payments.
                        </p>

                        @if(!$pendingDownpayment)
                        <a href="{{ route('customer.payments.createDownpayment', $event) }}"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-amber-600 text-white font-semibold rounded-lg hover:bg-amber-700 transition shadow-md hover:shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Submit Downpayment Now
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- 8. SCHEDULED/ONGOING/COMPLETED: Fully Paid --}}
        @if(in_array($event->status, ['scheduled', 'ongoing', 'completed']) && $event->billing &&
        $event->billing->remaining_balance <= 0) <div
            class="bg-gradient-to-r from-emerald-50 to-teal-50 border-l-4 border-emerald-500 rounded-xl shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-emerald-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-emerald-900 mb-2">✅ Fully Paid!</h3>
                        <p class="text-emerald-800">Your event is fully paid. Thank you!</p>
                    </div>
                </div>
            </div>
    </div>
    @endif

    {{-- Show feedback section for completed events --}}
    @if($event->status === 'completed')
    @if($event->hasFeedback())
    {{-- Show existing feedback --}}
    <div
        class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-xl shadow-sm overflow-hidden">
        <div class="p-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-green-900 mb-2">✅ Thank You for Your Feedback!</h3>
                    <div class="bg-white rounded-lg p-4 mb-3">
                        <div class="text-2xl mb-2">{{ $event->feedback->stars_html }}</div>
                        <p class="text-sm text-gray-700">{{ $event->feedback->comment }}</p>
                        <p class="text-xs text-gray-500 mt-2">Submitted {{
                            $event->feedback->created_at->diffForHumans() }}</p>
                    </div>
                    <a href="{{ route('customer.feedback.edit', $event) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white text-green-700 font-medium rounded-lg hover:bg-green-50 transition border border-green-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Feedback
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else
    {{-- Prompt to submit feedback --}}
    <div
        class="bg-gradient-to-r from-yellow-50 to-amber-50 border-l-4 border-yellow-500 rounded-xl shadow-sm overflow-hidden">
        <div class="p-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-yellow-900 mb-2">⭐ Share Your Experience!</h3>
                    <p class="text-yellow-800 mb-4">Your event is complete! We'd love to hear about your experience.
                        Your feedback helps us improve our services.</p>
                    <a href="{{ route('customer.feedback.create', $event) }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-yellow-500 text-white font-semibold rounded-lg hover:bg-yellow-600 transition shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        Submit Feedback
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endif

    {{-- 9. REJECTED Status --}}
    @if($event->status === 'rejected')
    <div
        class="bg-gradient-to-r from-rose-50 to-red-50 border-l-4 border-rose-500 rounded-xl shadow-sm overflow-hidden">
        <div class="p-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-rose-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-rose-900 mb-2">Event Request Declined</h3>
                    @if($event->rejection_reason)
                    <div class="bg-white rounded-lg p-4 text-sm text-gray-700 mb-4">
                        <strong class="text-rose-800">Reason:</strong> {{ $event->rejection_reason }}
                    </div>
                    @endif
                    <p class="text-rose-800 text-sm">Please contact us if you have questions or would like to submit
                        a new request.</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Event Information Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-violet-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div>
                    <div class="text-xs text-gray-500 font-medium uppercase tracking-wide">Package</div>
                    <div class="font-bold text-gray-900">{{ $event->package?->name ?? 'No Package' }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-sky-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                    </svg>
                </div>
                <div>
                    <div class="text-xs text-gray-500 font-medium uppercase tracking-wide">Theme</div>
                    <div class="font-bold text-gray-900">{{ $event->theme ?: 'Not Set' }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 md:col-span-2">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-2">Guest List</div>
                    <div class="text-sm text-gray-700 whitespace-pre-line">{{ $event->guests ?? 'Not specified' }}
                    </div>
                </div>
            </div>
        </div>

        @if($event->notes)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 md:col-span-2">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-2">Special Notes</div>
                    <div class="text-sm text-gray-700 whitespace-pre-line">{{ $event->notes }}</div>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Package Details --}}
    @php
    $pkg = $event->package;
    $sty = is_array($pkg?->event_styling) ? array_values(array_filter($pkg->event_styling, fn($v) =>
    trim((string)$v) !== '')) : [];
    $coordPrice = (float)($pkg->coordination_price ?? 25000);
    $stylePrice = (float)($pkg->event_styling_price ?? 55000);
    $pkgSubtotal = $coordPrice + $stylePrice;
    @endphp

    @if($pkg)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-violet-50 to-purple-50 border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    Package Details
                </h3>
                <div class="text-right">
                    <div class="text-xs text-gray-500">Subtotal</div>
                    <div class="text-xl font-bold text-violet-600">₱{{ number_format($pkgSubtotal, 2) }}</div>
                </div>
            </div>
        </div>

        <div class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Coordination --}}
                <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-lg p-4 border border-blue-200">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="text-xs text-blue-700 font-semibold uppercase tracking-wide mb-1">
                                Coordination</div>
                            <div class="text-sm text-gray-700">{{ $pkg->coordination ?: '—' }}</div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <div class="text-lg font-bold text-blue-600">₱{{ number_format($coordPrice, 2) }}</div>
                        </div>
                    </div>
                </div>

                {{-- Event Styling --}}
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg p-4 border border-purple-200">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1">
                            <div class="text-xs text-purple-700 font-semibold uppercase tracking-wide mb-2">Event
                                Styling</div>
                            @if(empty($sty))
                            <div class="text-sm text-gray-500">—</div>
                            @else
                            <ul class="text-xs text-gray-700 space-y-1">
                                @foreach($sty as $item)
                                <li class="flex items-start gap-1">
                                    <svg class="w-3 h-3 mt-0.5 text-purple-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>{{ $item }}</span>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </div>
                        <div class="text-right flex-shrink-0">
                            <div class="text-lg font-bold text-purple-600">₱{{ number_format($stylePrice, 2) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Selected Inclusions --}}
    @php
    $incs = $event->inclusions ?? collect();
    $incSubtotal = $incs->sum(fn($i) => (float)($i->pivot->price_snapshot ?? $i->price ?? 0));
    $grand = $incSubtotal + ($pkg ? $pkgSubtotal : 0);
    @endphp

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-50 to-teal-50 border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Selected Inclusions
                    <span class="text-sm font-normal text-gray-600">({{ $incs->count() }} items)</span>
                </h3>
                <div class="text-right">
                    <div class="text-xs text-gray-500">Subtotal</div>
                    <div class="text-xl font-bold text-emerald-600">₱{{ number_format($incSubtotal, 2) }}</div>
                </div>
            </div>
        </div>

        @if($incs->isEmpty())
        <div class="p-12 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <p class="text-gray-500 font-medium">No inclusions selected</p>
        </div>
        @else
        <div class="p-6">
            <div class="space-y-3">
                @foreach($incs as $inc)
                @php
                $price = $inc->pivot->price_snapshot ?? $inc->price;
                @endphp
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-emerald-300 transition">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <div class="font-semibold text-gray-900">{{ $inc->name }}</div>
                                @if($inc->category)
                                <span
                                    class="px-2 py-0.5 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">
                                    {{ $inc->category }}
                                </span>
                                @endif
                            </div>
                            @if($inc->notes)
                            <div class="text-xs text-gray-600 mt-1">{{ $inc->notes }}</div>
                            @endif
                        </div>
                        @if(!is_null($price))
                        <div class="text-right flex-shrink-0">
                            <div class="text-lg font-bold text-gray-900">₱{{ number_format($price, 2) }}</div>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Grand Total --}}
            <div class="mt-6 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl p-6 text-white">
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span>Inclusions</span>
                        <span class="font-semibold">₱{{ number_format($incSubtotal, 2) }}</span>
                    </div>
                    @if($pkg)
                    <div class="flex items-center justify-between text-sm">
                        <span>Package (Coordination + Styling)</span>
                        <span class="font-semibold">₱{{ number_format($pkgSubtotal, 2) }}</span>
                    </div>
                    @endif
                    <div class="border-t border-white/20 pt-3 mt-3">
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-semibold">Total Event Cost</span>
                            <span class="text-3xl font-bold">₱{{ number_format($grand, 2) }}</span>
                        </div>
                    </div>

                    {{-- Payment Progress --}}
                    @if($event->billing)
                    <div class="border-t border-white/20 pt-3 mt-3 space-y-2">
                        <div class="flex items-center justify-between text-sm">
                            <span>Intro Payment {{ $event->billing->introductory_payment_status === 'paid' ?
                                '(Paid)' : '(Pending)' }}</span>
                            <span>{{ $event->billing->introductory_payment_status === 'paid' ? '- ₱5,000.00' :
                                '₱5,000.00' }}</span>
                        </div>
                        @if($event->billing->downpayment_amount > 0)
                        <div class="flex items-center justify-between text-sm">
                            <span>Downpayment {{ $event->hasDownpaymentPaid() ? '(Paid)' : '(Pending)' }}</span>
                            <span>{{ $event->hasDownpaymentPaid() ? '- ₱' .
                                number_format($event->billing->downpayment_amount - 5000, 2) : '₱' .
                                number_format($event->billing->downpayment_amount - 5000, 2) }}</span>
                        </div>
                        @endif
                        @if($event->billing->remaining_balance > 0)
                        <div class="flex items-center justify-between pt-2 border-t border-white/20">
                            <span class="font-semibold">Remaining Balance</span>
                            <span class="text-xl font-bold">₱{{ number_format($event->billing->remaining_balance, 2)
                                }}</span>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    </div>
    </div>
</x-app-layout>