<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">{{ $event->name }}</h2>

            @if($event->status === 'requested')
            <a href="{{ route('customer.events.edit', $event) }}"
                class="px-3 py-2 bg-gray-800 text-white rounded">Edit</a>
            @endif
        </div>
    </x-slot>


    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Summary --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                {{-- Display Downpayment Request if Event is Approved --}}
                {{-- Approved - Awaiting Downpayment --}}
                @if($event->status === 'approved')
                <div
                    class="bg-gradient-to-r from-amber-50 to-yellow-50 border-l-4 border-amber-500 rounded-lg shadow-sm overflow-hidden mb-6">
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-amber-500 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 36 36"
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
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-amber-900 mb-2">Booking Approved - Payment Required
                                </h3>
                                <p class="text-amber-800 mb-3">Your event has been approved! Please submit your
                                    downpayment to proceed with scheduling your meeting.</p>

                                @if($event->billing)
                                <div class="bg-white/60 rounded-lg p-4 mb-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-amber-900">Downpayment Amount:</span>
                                        <span class="text-2xl font-bold text-amber-900">₱{{
                                            number_format($event->billing->downpayment_amount, 2) }}</span>
                                    </div>
                                </div>
                                @else
                                <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                                    <p class="text-sm text-red-700">⚠️ No billing information available. Please contact
                                        us.</p>
                                </div>
                                @endif

                                <a href="{{ route('customer.payments.create', ['event' => $event->id]) }}"
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-amber-600 text-white font-semibold rounded-lg hover:bg-amber-700 transition-colors shadow-md hover:shadow-lg">
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

                {{-- Meeting Status - Downpayment Confirmed --}}
                @if($event->status === 'meeting')
                <div
                    class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 rounded-lg shadow-sm overflow-hidden mb-6">
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-blue-900 mb-2">Payment Confirmed - Schedule Your
                                    Meeting</h3>
                                <p class="text-blue-800 mb-3">Your downpayment has been confirmed. Let's
                                    schedule a meeting to finalize the details of your event.</p>

                                <div class="bg-white/60 rounded-lg p-4 mb-4">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        <div>
                                            <p class="text-sm text-blue-900 font-medium">Contact us to schedule:</p>
                                            <a href="tel:09173062531"
                                                class="text-lg font-bold text-blue-700 hover:text-blue-800">0917 306
                                                2531</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex gap-3">
                                    <a href="tel:09173062531"
                                        class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-md hover:shadow-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        Call Now
                                    </a>
                                    <a href="mailto:michaelhoevents@gmail.com"
                                        class="inline-flex items-center gap-2 px-6 py-3 bg-white text-blue-700 font-semibold rounded-lg hover:bg-blue-50 transition-colors border-2 border-blue-200">
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

                {{-- Payment Rejected --}}
                @if($event->billing && $event->billing->downpayment_amount > 0 &&
                $event->billing->payment()->where('status', 'rejected')->exists())
                <div
                    class="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 rounded-lg shadow-sm overflow-hidden mb-6">
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-red-900 mb-2">Payment Verification Issue</h3>
                                <p class="text-red-800 mb-3">We couldn't verify your payment. Please check the details
                                    and resubmit, or contact us for assistance.</p>

                                @if($event->billing)
                                <div class="bg-white/60 rounded-lg p-4 mb-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-red-900">Required Amount:</span>
                                        <span class="text-2xl font-bold text-red-900">₱{{
                                            number_format($event->billing->downpayment_amount, 2) }}</span>
                                    </div>
                                </div>
                                @else
                                <div class="bg-red-100 border border-red-200 rounded-lg p-3 mb-4">
                                    <p class="text-sm text-red-700">⚠️ No billing information available. Please contact
                                        us.</p>
                                </div>
                                @endif

                                <div class="flex gap-3">
                                    <a href="{{ route('customer.payments.create', ['event' => $event->id]) }}"
                                        class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors shadow-md hover:shadow-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Resubmit Payment
                                    </a>
                                    <a href="tel:09173062531"
                                        class="inline-flex items-center gap-2 px-6 py-3 bg-white text-red-700 font-semibold rounded-lg hover:bg-red-50 transition-colors border-2 border-red-200">
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


                @php
                $status = strtolower($event->status ?? 'unknown');
                $statusClasses = match($status) {
                'requested' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                'approved' => 'bg-amber-100 text-amber-800 border border-amber-200',
                'meeting' => 'bg-blue-100 text-blue-800 border border-blue-200',
                'completed' => 'bg-green-100 text-green-800 border border-green-200',
                'cancelled', 'canceled' => 'bg-rose-100 text-rose-800 border border-rose-200',
                default => 'bg-gray-100 text-gray-800 border border-gray-200',
                };

                $date = \Illuminate\Support\Carbon::parse($event->event_date);
                @endphp

                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Date --}}
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                        <dt class="text-xs uppercase tracking-wide text-gray-500">Date</dt>
                        <dd class="mt-1 font-medium text-gray-900">
                            {{ $date->format('M d, Y') }}
                            <span class="ml-2 text-xs text-gray-500">({{ $date->format('D') }})</span>
                        </dd>
                    </div>

                    {{-- Status --}}
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                        <dt class="text-xs uppercase tracking-wide text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span
                                class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold {{ $statusClasses }}">
                                {{-- tiny dot --}}
                                <span class="inline-block w-1.5 h-1.5 rounded-full bg-current"></span>
                                {{ ucfirst($event->status) }}
                            </span>
                        </dd>
                    </div>

                    {{-- Package --}}
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                        <dt class="text-xs uppercase tracking-wide text-gray-500">Package</dt>
                        <dd class="mt-1 font-medium text-gray-900">
                            {{ $event->package?->name ?? '—' }}
                        </dd>
                        @if(!empty($event->package?->short_description))
                        <dd class="mt-0.5 text-xs text-gray-500">{{ $event->package->short_description }}</dd>
                        @endif
                    </div>

                    {{-- Venue --}}
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                        <dt class="text-xs uppercase tracking-wide text-gray-500">Venue</dt>
                        <dd class="mt-1 font-medium text-gray-900">
                            {{ $event->venue ?: '—' }}
                        </dd>
                    </div>

                    {{-- Theme --}}
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                        <dt class="text-xs uppercase tracking-wide text-gray-500">Theme</dt>
                        <dd class="mt-1">
                            @if(!empty($event->theme))
                            <span
                                class="inline-flex items-center px-2 py-0.5 text-[11px] font-semibold rounded-full bg-gray-100 text-gray-700 border border-gray-200">
                                {{ $event->theme }}
                            </span>
                            @else
                            <span class="text-gray-500">—</span>
                            @endif
                        </dd>
                    </div>

                    {{-- Guests --}}
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                        <dt class="text-xs uppercase tracking-wide text-gray-500">Guests</dt>
                        <dd class="mt-1 text-gray-700 whitespace-pre-line text-sm">
                            {{ $event->guests ?? 'Not specified' }}
                        </dd>
                    </div>

                    {{-- Notes (full width on md) --}}
                    <div class="md:col-span-2 rounded-lg border border-gray-200 bg-white p-4">
                        <dt class="text-xs uppercase tracking-wide text-gray-500">Notes</dt>
                        <dd class="mt-1 font-medium text-gray-900 whitespace-pre-line">
                            {{ $event->notes ?: '—' }}
                        </dd>
                    </div>
                </dl>

            </div>

            {{-- ======================== PACKAGE DETAILS ======================== --}}
            @php
            $pkg = $event->package;
            $sty = is_array($pkg?->event_styling) ? array_values(array_filter($pkg->event_styling, fn($v) =>
            trim((string)$v) !== '')) : [];
            $coordPrice = (float)($pkg->coordination_price ?? 25000);
            $stylePrice = (float)($pkg->event_styling_price ?? 55000);
            $pkgSubtotal = $coordPrice + $stylePrice;
            @endphp

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 7h18M3 12h18M7 17h10" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg text-gray-900">Package Details</h3>
                            @if($pkg?->name)
                            <span
                                class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700 border border-gray-200">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        d="M5 12h14M5 6h14M5 18h7" />
                                </svg>
                                {{ $pkg->name }}
                            </span>
                            @endif
                        </div>
                    </div>

                    @if($pkg)
                    <div class="text-right shrink-0">
                        <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Package Subtotal</div>
                        <div class="text-2xl font-bold text-gray-900">
                            <span class="text-emerald-600">₱</span>{{ number_format($pkgSubtotal, 2) }}
                        </div>
                        <div class="text-xs text-gray-500">Coordination + Styling</div>
                    </div>
                    @endif
                </div>

                @if($pkg)
                {{-- Description (collapsible when long) --}}
                @if(!empty($pkg->description))
                @php $isLong = mb_strlen($pkg->description) > 180; @endphp
                <div class="mt-4">
                    <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Description</div>

                    @if($isLong)
                    <details class="group mt-1 rounded-lg border border-gray-200 bg-gray-50 p-4">
                        <summary class="list-none cursor-pointer select-none flex items-center justify-between">
                            <span class="text-gray-700">Preview</span>
                            <svg class="w-4 h-4 text-gray-500 transition-transform group-open:rotate-180" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <div class="mt-2 text-gray-800 whitespace-pre-line">{{ $pkg->description }}</div>
                    </details>
                    @else
                    <div
                        class="mt-1 rounded-lg border border-gray-200 bg-gray-50 p-4 text-gray-800 whitespace-pre-line">
                        {{ $pkg->description }}
                    </div>
                    @endif
                </div>
                @endif

                {{-- Feature tiles --}}
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Coordination --}}
                    <div class="rounded-lg border border-gray-200 bg-white p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Coordination
                                </div>
                                <div class="mt-1 text-sm text-gray-800 whitespace-pre-line">
                                    {{ $pkg->coordination ?: '—' }}
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <div class="text-sm text-gray-500">Price</div>
                                <div class="text-lg font-semibold text-gray-900">
                                    <span class="text-emerald-600">₱</span>{{ number_format($coordPrice, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Event Styling --}}
                    <div class="rounded-lg border border-gray-200 bg-white p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Event Styling
                                </div>

                                @if(empty($sty))
                                <div class="mt-1 text-sm text-gray-500">—</div>
                                @else
                                <ul class="mt-2 text-sm text-gray-800 space-y-1">
                                    @foreach($sty as $item)
                                    @if(trim($item) !== '')
                                    <li class="flex items-start gap-2">
                                        <svg class="w-4 h-4 mt-0.5 text-gray-500 shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span class="break-words">{{ $item }}</span>
                                    </li>
                                    @endif
                                    @endforeach
                                </ul>
                                @endif
                            </div>

                            <div class="text-right shrink-0">
                                <div class="text-sm text-gray-500">Price</div>
                                <div class="text-lg font-semibold text-gray-900">
                                    <span class="text-emerald-600">₱</span>{{ number_format($stylePrice, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Helper note --}}
                <div class="mt-4 flex items-start gap-2 text-xs text-gray-500">
                    <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            d="M13 16h-1v-4h-1m1-4h.01M12 19a7 7 0 110-14 7 7 0 010 14z" />
                    </svg>
                    <span>Package Subtotal is for Coordination + Styling only. Full estimate includes selected
                        inclusions below.</span>
                </div>
                @else
                <div class="mt-3 rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <div class="text-sm text-gray-500">No package information.</div>
                </div>
                @endif
            </div>


            {{-- ======================== SELECTED INCLUSIONS ======================== --}}
            @php
            $incs = $event->inclusions ?? collect();
            $incSubtotal = $incs->sum(fn($i) => (float)($i->pivot->price_snapshot ?? $i->price ?? 0));
            $grand = $incSubtotal + ($pkg ? $coordPrice : 0) + ($pkg ? $stylePrice : 0);
            @endphp

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                aria-hidden="true">
                                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 7h18M3 12h18M3 17h18" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg text-gray-900">Selected Inclusions</h3>
                            <div class="text-sm text-gray-600">
                                @if($incs->isEmpty())
                                No inclusions selected
                                @else
                                {{ $incs->count() }} item{{ $incs->count() > 1 ? 's' : '' }}
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="text-right shrink-0">
                        <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Inclusions Subtotal
                        </div>
                        <div class="text-2xl font-bold text-gray-900">
                            <span class="text-emerald-600">₱</span>{{ number_format($incSubtotal, 2) }}
                        </div>
                    </div>
                </div>

                @if($incs->isEmpty())
                <div class="mt-4 rounded-lg border border-dashed border-gray-300 bg-gray-50 p-6 text-center">
                    <div
                        class="mx-auto w-10 h-10 rounded-full bg-white flex items-center justify-center border border-gray-200">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <p class="mt-2 text-sm text-gray-600">No inclusions selected for this event.</p>
                </div>
                @else
                <ul class="mt-4 divide-y divide-gray-200 rounded-lg border border-gray-200 overflow-hidden">
                    @foreach($incs as $inc)
                    @php
                    $price = $inc->pivot->price_snapshot ?? $inc->price;
                    $notes = trim((string)($inc->notes ?? ''));
                    $lines = $notes !== '' ? preg_split('/\r\n|\r|\n/', $notes) : [];
                    @endphp

                    <li class="bg-white p-4 hover:bg-gray-50 transition">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <div class="font-medium text-gray-900">{{ $inc->name }}</div>
                                    @if($inc->category)
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 text-[11px] font-semibold rounded-full bg-gray-100 text-gray-700 border border-gray-200">
                                        {{ $inc->category }}
                                    </span>
                                    @endif
                                </div>

                                @if(!empty($lines))
                                <ul class="mt-2 text-xs text-gray-700 space-y-1">
                                    @foreach($lines as $line)
                                    @if(trim($line) !== '')
                                    <li class="flex items-start gap-2">
                                        <svg class="w-3.5 h-3.5 mt-0.5 text-gray-400 shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                d="M5 12h14" />
                                        </svg>
                                        <span class="break-words">{{ $line }}</span>
                                    </li>
                                    @endif
                                    @endforeach
                                </ul>
                                @endif
                            </div>

                            @if(!is_null($price))
                            <div class="shrink-0 text-right">
                                <div class="text-xs text-gray-500">Price</div>
                                <div class="text-sm font-semibold text-gray-900">
                                    <span class="text-emerald-600">₱</span>{{ number_format($price, 2) }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </li>
                    @endforeach
                </ul>

                {{-- Totals panel --}}
                <div class="mt-4 rounded-lg border bg-gray-50 p-4 text-sm text-gray-800">
                    <div class="flex items-center justify-between">
                        <span>Inclusions Subtotal</span>
                        <span class="font-semibold">
                            <span class="text-emerald-600">₱</span>{{ number_format($incSubtotal, 2) }}
                        </span>
                    </div>

                    @if($pkg)
                    <div class="flex items-center justify-between mt-1">
                        <span>Coordination</span>
                        <span class="font-semibold">
                            <span class="text-emerald-600">₱</span>{{ number_format($coordPrice, 2) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between mt-1">
                        <span>Event Styling</span>
                        <span class="font-semibold">
                            <span class="text-emerald-600">₱</span>{{ number_format($stylePrice, 2) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between mt-3 border-t pt-3">
                        <span class="font-medium">Estimated Total</span>
                        <span class="font-extrabold text-2xl text-gray-900">
                            <span class="text-emerald-600">₱</span>{{ number_format($grand, 2) }}
                        </span>
                    </div>
                    @endif

                    <div class="mt-2 text-xs text-gray-500 flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8v4m0 4h.01M21 12A9 9 0 113 12a9 9 0 0118 0z" />
                        </svg>
                        <span>These figures are estimates based on current selections and package components.</span>
                    </div>
                </div>
                @endif
            </div>

            {{-- ======================== BACK LINK ======================== --}}
            <div class="flex items-center">
                <a href="{{ route('customer.events.index') }}"
                    class="inline-flex items-center gap-2 text-gray-700 hover:text-gray-900 font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to events
                </a>
            </div>

        </div>
    </div>
</x-app-layout>