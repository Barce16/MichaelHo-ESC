<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800">{{ $event->name }}</h2>
                <p class="text-sm text-gray-500 mt-1">Event ID: #{{ str_pad($event->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>

            @php
            $statusKey = strtolower((string) $event->status);
            $badge = match($statusKey) {
            'requested' => 'bg-amber-50 text-amber-700 border-amber-200',
            'approved' => 'bg-sky-50 text-sky-700 border-sky-200',
            'scheduled' => 'bg-violet-50 text-violet-700 border-violet-200',
            'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
            'request_meeting' => 'bg-orange-50 text-orange-700 border-orange-200',
            default => 'bg-slate-50 text-slate-700 border-slate-200',
            };
            @endphp
            <span
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold border {{ $badge }}">
                <span class="w-2 h-2 rounded-full bg-current"></span>
                {{ ucfirst(str_replace('_', ' ', $event->status)) }}
            </span>
        </div>
    </x-slot>

    @php
    // --- Compute grand total safely (server-side) ---
    $coord = (float) optional($event->package)->coordination_price ?? 25000;
    $styl = (float) optional($event->package)->event_styling_price ?? 55000;
    $incSubtotal = (float) $event->inclusions->sum(fn($i) => (float) ($i->pivot->price_snapshot ?? 0));
    $grandTotal = $coord + $styl + $incSubtotal;
    @endphp

    <div class="py-8" x-data="{
        grandTotal: {{ json_encode($grandTotal) }},
        status: @js($event->status),
        showApprove: false,
        showReject: false,
        downpayment: 0,
        selected: new Set(),
        query: '',
        isDownpaymentPending: @js($isDownpaymentPending),
        details: {},

        fmt(n) {
            return Number(n || 0).toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        },

        statusLabel() {
            return formatStatus(this.status);
        },

        openApprove() {
            this.downpayment = Math.max(this.grandTotal * 0.5, 0);
            this.showReject = false;
            this.showApprove = true;
        },

        openReject() {
            this.showApprove = false;
            this.showReject = true;
        },

        toggle(p) {
            const id = p.id;
            if (this.selected.has(id)) {
                this.selected.delete(id);
            } else {
                this.selected.add(id);
            }
        },
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Payment Verification Alert --}}
            <template x-if="status === 'request_meeting' && isDownpaymentPending">
                <div class="bg-amber-50 border border-amber-200 rounded-xl shadow-sm">
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-amber-900 mb-1">Payment Awaiting Verification</h3>
                                <p class="text-sm text-amber-700 mb-4">
                                    Customer has submitted a downpayment of
                                    <span class="font-bold">₱{{ number_format($paymentAmount, 2) }}</span>
                                </p>
                                <a href="{{ route('admin.payment.verification', $event) }}"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 text-white font-medium rounded-lg hover:bg-amber-700 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Verify Payment
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Main Event Information Grid --}}
            <div class="grid lg:grid-cols-3 gap-6">

                {{-- Left Column: Event Details --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Event Key Information --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Event Information
                            </h3>
                        </div>

                        <div class="p-6 space-y-6">
                            {{-- Event Date & Time --}}
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-12 h-12 bg-sky-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Event
                                        Date</div>
                                    <div class="text-xl font-semibold text-gray-900">
                                        {{ \Illuminate\Support\Carbon::parse($event->event_date)->format('F d, Y') }}
                                    </div>
                                    @if($event->event_time)
                                    <div class="text-sm text-gray-600 mt-1">
                                        Time: {{ \Illuminate\Support\Carbon::parse($event->event_time)->format('g:i A')
                                        }}
                                    </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Location --}}
                            @if($event->event_location)
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-12 h-12 bg-emerald-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">
                                        Location</div>
                                    <div class="text-base font-medium text-gray-900">{{ $event->event_location }}</div>
                                </div>
                            </div>
                            @endif

                            {{-- Guest Count --}}
                            @if($event->guest_count)
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-12 h-12 bg-violet-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">
                                        Expected Guests</div>
                                    <div class="text-xl font-semibold text-gray-900">{{
                                        number_format($event->guest_count) }}</div>
                                </div>
                            </div>
                            @endif

                            {{-- Special Requests --}}
                            @if($event->special_requests)
                            <div class="pt-4 border-t border-gray-200">
                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">
                                            Special Requests</div>
                                        <div class="text-sm text-gray-700 leading-relaxed bg-slate-50 rounded-lg p-4">{{
                                            $event->special_requests }}</div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Package Details --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                Package: {{ $event->package->name ?? 'Custom' }}
                            </h3>
                        </div>

                        <div class="p-6">
                            <div class="grid md:grid-cols-2 gap-6">
                                {{-- Coordination --}}
                                <div>
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="w-8 h-8 bg-sky-50 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                        </div>
                                        <h4 class="font-semibold text-gray-800">Coordination</h4>
                                    </div>
                                    <div class="text-sm text-gray-700 leading-relaxed bg-slate-50 rounded-lg p-4">
                                        {{ $event->package->coordination ?? '—' }}
                                    </div>
                                    <div class="mt-3 flex items-center justify-between">
                                        <span class="text-xs text-gray-500 uppercase tracking-wider">Price</span>
                                        <span class="text-base font-semibold text-gray-800">₱{{ number_format($coord, 2)
                                            }}</span>
                                    </div>
                                </div>

                                {{-- Event Styling --}}
                                <div>
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="w-8 h-8 bg-rose-50 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                            </svg>
                                        </div>
                                        <h4 class="font-semibold text-gray-800">Event Styling</h4>
                                    </div>
                                    @if(is_array(optional($event->package)->event_styling) &&
                                    count($event->package->event_styling))
                                    <ul class="space-y-2 bg-slate-50 rounded-lg p-4">
                                        @foreach($event->package->event_styling as $item)
                                        <li class="flex items-start gap-2 text-sm text-gray-700">
                                            <svg class="w-4 h-4 text-rose-400 flex-shrink-0 mt-0.5" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span>{{ $item }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                    @else
                                    <div class="text-gray-500 text-sm bg-slate-50 rounded-lg p-4">No styling items</div>
                                    @endif
                                    <div class="mt-3 flex items-center justify-between">
                                        <span class="text-xs text-gray-500 uppercase tracking-wider">Price</span>
                                        <span class="text-base font-semibold text-gray-800">₱{{ number_format($styl, 2)
                                            }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Selected Inclusions --}}
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="w-8 h-8 bg-violet-50 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                        </svg>
                                    </div>
                                    <h4 class="font-semibold text-gray-800">Selected Inclusions</h4>
                                    <span class="ml-auto text-xs text-gray-500">{{ $event->inclusions->count() }}
                                        items</span>
                                </div>

                                @if($event->inclusions->isEmpty())
                                <div class="text-center py-8 bg-slate-50 rounded-lg">
                                    <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="text-gray-500 text-sm">No inclusions selected</p>
                                </div>
                                @else
                                <div class="space-y-2">
                                    @foreach($event->inclusions as $inc)
                                    <div
                                        class="bg-slate-50 border border-gray-200 rounded-lg p-4 hover:bg-slate-100 transition">
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <h5 class="font-medium text-gray-900">{{ $inc->name }}</h5>
                                                    @if($inc->category)
                                                    <span
                                                        class="px-2 py-0.5 text-xs font-medium rounded-full bg-violet-50 text-violet-700">
                                                        {{ $inc->category }}
                                                    </span>
                                                    @endif
                                                </div>
                                                @if(trim($inc->notes))
                                                <p class="text-xs text-gray-600 mt-1">{{ $inc->notes }}</p>
                                                @endif
                                            </div>
                                            @if(!is_null(optional($inc->pivot)->price_snapshot))
                                            <div class="text-right">
                                                <div class="text-xs text-gray-500 mb-1">Price</div>
                                                <div class="text-base font-semibold text-gray-800">
                                                    ₱{{ number_format((float)$inc->pivot->price_snapshot, 2) }}
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Customer & Actions --}}
                <div class="space-y-6">

                    {{-- Customer Card --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                            <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Customer
                            </h3>
                        </div>

                        <div class="p-6">
                            <div class="flex items-center gap-3 mb-4">
                                @php
                                $avatar = optional(optional($event->customer)->user)->profile_photo_url
                                ?? 'https://ui-avatars.com/api/?name=' . urlencode($event->customer->customer_name ??
                                'Unknown') . '&background=E5E7EB&color=6B7280&size=200';
                                @endphp
                                <img src="{{ $avatar }}"
                                    class="h-12 w-12 rounded-full object-cover ring-2 ring-gray-200" alt="Avatar">
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $event->customer->customer_name ??
                                        'Unknown' }}</div>
                                    <div class="text-xs text-gray-500">ID: #{{ str_pad($event->customer->id ?? 0, 4,
                                        '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                @if($event->customer->email)
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <span>{{ $event->customer->email }}</span>
                                </div>
                                @endif
                                @if($event->customer->phone)
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <span>{{ $event->customer->phone }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Pricing Summary --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                            <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Pricing
                            </h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">Coordination</span>
                                <span class="font-medium text-gray-900">₱{{ number_format($coord, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">Event Styling</span>
                                <span class="font-medium text-gray-900">₱{{ number_format($styl, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">Inclusions</span>
                                <span class="font-medium text-gray-900">₱{{ number_format($incSubtotal, 2) }}</span>
                            </div>
                            <div class="border-t border-gray-200 pt-3 mt-3">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-gray-900">Grand Total</span>
                                    <span class="text-xl font-bold text-emerald-600">₱{{ number_format($grandTotal, 2)
                                        }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                            <h4 class="text-base font-semibold text-gray-800">Actions</h4>
                        </div>
                        <div class="p-6">

                            {{-- Requested Status Actions --}}
                            <template x-if="status === 'requested'">
                                <div class="space-y-3">
                                    <button type="button" @click="openApprove()"
                                        class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-lg bg-emerald-600 text-white font-medium hover:bg-emerald-700 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Approve Event
                                    </button>
                                    <button type="button" @click="openReject()"
                                        class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-lg bg-rose-600 text-white font-medium hover:bg-rose-700 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Reject Event
                                    </button>
                                </div>
                            </template>

                            {{-- Meeting Status Actions --}}
                            <template x-if="statusLabel() === 'Meeting'">
                                <div class="space-y-3">
                                    <a href="{{ route('admin.events.assignStaffPage', $event) }}"
                                        class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-lg bg-violet-600 text-white font-medium hover:bg-violet-700 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        Assign Staff
                                    </a>
                                    <form method="POST" action="{{ route('admin.events.confirm', $event) }}">
                                        @csrf
                                        <button
                                            class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-lg bg-sky-600 text-white font-medium hover:bg-sky-700 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Confirm Event
                                        </button>
                                    </form>
                                </div>
                            </template>

                            {{-- Scheduled Status Actions --}}
                            <template x-if="statusLabel() === 'Scheduled'">
                                <div class="grid grid-cols-2 gap-2">
                                    <a href="{{ route('admin.event.guests', $event) }}"
                                        class="flex flex-col items-center justify-center gap-1 px-3 py-3 bg-slate-50 text-gray-700 font-medium rounded-lg hover:bg-slate-100 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <span class="text-xs">Guests</span>
                                    </a>
                                    <a href="{{ route('admin.event.staffs', $event) }}"
                                        class="flex flex-col items-center justify-center gap-1 px-3 py-3 bg-slate-50 text-gray-700 font-medium rounded-lg hover:bg-slate-100 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <span class="text-xs">Staff</span>
                                    </a>
                                </div>
                            </template>

                            {{-- Other Status Display --}}
                            <template
                                x-if="status !== 'requested' && statusLabel() !== 'Meeting' && statusLabel() !== 'Scheduled'">
                                <div class="text-center py-4 bg-slate-50 rounded-lg">
                                    <div class="text-xs text-gray-500 mb-1">Current Status</div>
                                    <div class="text-base font-semibold text-gray-900" x-text="statusLabel()"></div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Approve Panel --}}
            <div x-show="showApprove" x-transition x-cloak
                class="bg-emerald-50 border border-emerald-200 rounded-xl shadow-sm">
                <form method="POST" action="{{ route('admin.events.approve', $event) }}" class="p-6">
                    @csrf
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-emerald-900">Approve Event</h3>
                            <p class="text-sm text-emerald-700">Set downpayment and confirm</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-white rounded-lg p-4 border border-emerald-200">
                            <div class="text-xs text-gray-500 mb-1">Grand Total</div>
                            <div class="text-xl font-bold text-gray-900">₱<span x-text="fmt(grandTotal)"></span></div>
                        </div>

                        <div class="bg-white rounded-lg p-4 border border-emerald-200">
                            <label for="downpayment_amount" class="text-xs text-gray-500 block mb-2">
                                Downpayment
                            </label>
                            <div class="relative">
                                <span
                                    class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-medium">₱</span>
                                <input type="number" step="0.01" min="0" x-model.number="downpayment"
                                    name="downpayment_amount" id="downpayment_amount"
                                    class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200" />
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Default: 50%</p>
                        </div>

                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <div class="text-xs text-gray-500 mb-1">Balance</div>
                            <div class="text-xl font-bold text-gray-700">₱<span
                                    x-text="fmt(Math.max(grandTotal - (downpayment || 0), 0))"></span></div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" @click="showApprove=false"
                            class="px-4 py-2 border border-gray-300 rounded-lg font-medium hover:bg-gray-50 transition">
                            Cancel
                        </button>
                        <button
                            class="px-6 py-2 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition">
                            Confirm Approval
                        </button>
                    </div>
                </form>
            </div>

            {{-- Reject Panel --}}
            <div x-show="showReject" x-transition x-cloak
                class="bg-rose-50 border border-rose-200 rounded-xl shadow-sm">
                <form method="POST" action="{{ route('admin.events.reject', $event) }}" class="p-6">
                    @csrf
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-rose-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-rose-900">Reject Event</h3>
                            <p class="text-sm text-rose-700">Provide reason for rejection</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg p-4 border border-rose-200 mb-6">
                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Rejection Reason (Optional)
                        </label>
                        <textarea id="rejection_reason" name="rejection_reason" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-rose-500 focus:ring-2 focus:ring-rose-200"
                            placeholder="Explain why this event is being rejected..."></textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" @click="showReject=false"
                            class="px-4 py-2 border border-gray-300 rounded-lg font-medium hover:bg-gray-50 transition">
                            Cancel
                        </button>
                        <button
                            class="px-6 py-2 bg-rose-600 text-white font-medium rounded-lg hover:bg-rose-700 transition">
                            Confirm Rejection
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function formatStatus(status) {
            return status.replace(/_/g, ' ')  
                         .toLowerCase()  
                         .replace(/\b\w/g, function(char) { return char.toUpperCase(); }); 
        }
    </script>
</x-app-layout>