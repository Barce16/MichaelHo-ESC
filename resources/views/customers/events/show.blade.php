<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Event Details</h2>
            <div class="flex items-center gap-2">
                @if($event->status === 'requested')
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
            'approved' => ['bg' => 'from-emerald-500 to-teal-600', 'icon_bg' => 'bg-emerald-500'],
            'meeting' => ['bg' => 'from-blue-500 to-indigo-600', 'icon_bg' => 'bg-blue-500'],
            'request_meeting' => ['bg' => 'from-violet-500 to-purple-600', 'icon_bg' => 'bg-violet-500'],
            'scheduled' => ['bg' => 'from-sky-500 to-cyan-600', 'icon_bg' => 'bg-sky-500'],
            'completed' => ['bg' => 'from-emerald-600 to-green-700', 'icon_bg' => 'bg-emerald-600'],
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
                                    $event->package?->type ?? 'Event' }}</div>
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
                        @php
                        $statusBadgeConfig = match($status) {
                        'requested' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'dot' => 'bg-amber-500'],
                        'approved' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'dot' =>
                        'bg-emerald-500'],
                        'meeting' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'dot' => 'bg-blue-500'],
                        'request_meeting' => ['bg' => 'bg-violet-100', 'text' => 'text-violet-700', 'dot' =>
                        'bg-violet-500'],
                        'scheduled' => ['bg' => 'bg-sky-100', 'text' => 'text-sky-700', 'dot' => 'bg-sky-500'],
                        'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'dot' => 'bg-green-500'],
                        default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'dot' => 'bg-gray-500'],
                        };
                        @endphp
                        <span
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-full {{ $statusBadgeConfig['bg'] }} {{ $statusBadgeConfig['text'] }} font-semibold">
                            <span class="w-2 h-2 rounded-full {{ $statusBadgeConfig['dot'] }}"></span>
                            {{ ucwords(str_replace('_', ' ', $event->status)) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Action Alerts --}}
            @if($event->status === 'approved')
            <div
                class="bg-gradient-to-r from-amber-50 to-orange-50 border-l-4 border-amber-500 rounded-xl shadow-sm overflow-hidden">
                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-amber-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 36 36">
                                <path d="M14.18,13.8V16h9.45a5.26,5.26,0,0,0,.08-.89,4.72,4.72,0,0,0-.2-1.31Z"></path>
                                <path d="M14.18,19.7h5.19a4.28,4.28,0,0,0,3.5-1.9H14.18Z"></path>
                                <path d="M19.37,10.51H14.18V12h8.37A4.21,4.21,0,0,0,19.37,10.51Z"></path>
                                <path
                                    d="M17.67,2a16,16,0,1,0,16,16A16,16,0,0,0,17.67,2Zm10.5,15.8H25.7a6.87,6.87,0,0,1-6.33,4.4H14.18v6.54a1.25,1.25,0,1,1-2.5,0V17.8H8.76a.9.9,0,1,1,0-1.8h2.92V13.8H8.76a.9.9,0,1,1,0-1.8h2.92V9.26A1.25,1.25,0,0,1,12.93,8h6.44a6.84,6.84,0,0,1,6.15,4h2.65a.9.9,0,0,1,0,1.8H26.09a6.91,6.91,0,0,1,.12,1.3,6.8,6.8,0,0,1-.06.9h2a.9.9,0,0,1,0,1.8Z">
                                </path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-amber-900 mb-2">🎉 Booking Approved - Payment Required
                            </h3>
                            <p class="text-amber-800 mb-4">Congratulations! Your event has been approved. Please submit
                                your downpayment to proceed with scheduling.</p>

                            @if($event->billing)
                            <div class="bg-white rounded-lg p-4 mb-4 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">Downpayment Amount</span>
                                    <span class="text-3xl font-bold text-amber-600">₱{{
                                        number_format($event->billing->downpayment_amount, 2) }}</span>
                                </div>
                            </div>
                            @endif

                            <a href="{{ route('customer.payments.create', $event) }}"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-amber-600 text-white font-semibold rounded-lg hover:bg-amber-700 transition shadow-md hover:shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Submit Payment Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($event->status === 'meeting')
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
                            <h3 class="text-lg font-bold text-blue-900 mb-2">✅ Payment Confirmed - Schedule Your Meeting
                            </h3>
                            <p class="text-blue-800 mb-4">Your downpayment has been confirmed! Let's schedule a meeting
                                to finalize your event details.</p>

                            <div class="bg-white rounded-lg p-4 mb-4 shadow-sm">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <div>
                                        <div class="text-sm text-gray-700 font-medium">Contact us to schedule</div>
                                        <a href="tel:09173062531"
                                            class="text-lg font-bold text-blue-600 hover:text-blue-700">0917 306
                                            2531</a>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-3">
                                <a href="tel:09173062531"
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition shadow-md">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    Call Now
                                </a>
                                <a href="mailto:michaelhoevents@gmail.com"
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-white text-blue-700 font-semibold rounded-lg hover:bg-blue-50 transition border-2 border-blue-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    Email Us
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($event->billing && $event->billing->downpayment_amount > 0 &&
            $event->billing->payment()->where('status', 'rejected')->exists())
            <div
                class="bg-gradient-to-r from-rose-50 to-red-50 border-l-4 border-rose-500 rounded-xl shadow-sm overflow-hidden">
                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-rose-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-rose-900 mb-2">⚠️ Payment Verification Issue</h3>
                            <p class="text-rose-800 mb-4">We couldn't verify your payment. Please check the details and
                                resubmit.</p>

                            @if($event->billing)
                            <div class="bg-white rounded-lg p-4 mb-4 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">Required Amount</span>
                                    <span class="text-3xl font-bold text-rose-600">₱{{
                                        number_format($event->billing->downpayment_amount, 2) }}</span>
                                </div>
                            </div>
                            @endif

                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('customer.payments.create', $event) }}"
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-rose-600 text-white font-semibold rounded-lg hover:bg-rose-700 transition shadow-md">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Resubmit Payment
                                </a>
                                <a href="tel:09173062531"
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-white text-rose-700 font-semibold rounded-lg hover:bg-rose-50 transition border-2 border-rose-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    Contact Support
                                </a>
                            </div>
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
                            <div class="text-sm text-gray-700 whitespace-pre-line">{{ $event->guests ?? 'Not specified'
                                }}</div>
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
                            <div class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-2">Special Notes
                            </div>
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
                                    <div class="text-lg font-bold text-blue-600">₱{{ number_format($coordPrice, 2) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Event Styling --}}
                        <div
                            class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg p-4 border border-purple-200">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1">
                                    <div class="text-xs text-purple-700 font-semibold uppercase tracking-wide mb-2">
                                        Event Styling</div>
                                    @if(empty($sty))
                                    <div class="text-sm text-gray-500">—</div>
                                    @else
                                    <ul class="text-xs text-gray-700 space-y-1">
                                        @foreach($sty as $item)
                                        <li class="flex items-start gap-1">
                                            <svg class="w-3 h-3 mt-0.5 text-purple-500" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
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
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
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
                        <div
                            class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-emerald-300 transition">
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
                                    <span class="text-lg font-semibold">Estimated Total</span>
                                    <span class="text-3xl font-bold">₱{{ number_format($grand, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>