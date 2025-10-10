<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">
                Event: {{ $event->name }}
            </h2>

            @php
            $statusKey = strtolower((string) $event->status);
            $badge = match($statusKey) {
            'requested' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-blue-100 text-blue-800',
            'scheduled' => 'bg-indigo-100 text-indigo-800',
            'completed' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'request_meeting' => 'bg-orange-100 text-orange-800',
            default => 'bg-gray-100 text-gray-800',
            };
            @endphp
            <span class="px-2 py-1 rounded text-xs {{ $badge }}">
                {{ ucfirst($event->status) }}
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

    <div class="py-6" x-data="{
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
        this.downpayment = Math.max(this.grandTotal * 0.5, 0); // default 50%
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
        <!-- Main container -->
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Summary + Actions --}}

            {{-- Payment Verification Alert --}}
            <template x-if="status === 'request_meeting' && isDownpaymentPending">
                <div
                    class="bg-gradient-to-r from-amber-50 to-yellow-50 border-l-4 border-amber-500 rounded-lg shadow-sm p-6 mb-6">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-amber-900 mb-1">Payment Awaiting Verification</h4>
                            <p class="text-sm text-amber-800">Customer has submitted downpayment of <span
                                    class="font-bold">₱{{ number_format($paymentAmount, 2) }}</span></p>
                            <a href="{{ route('admin.payment.verification', $event) }}"
                                class="inline-flex items-center gap-2 mt-3 px-4 py-2 bg-amber-600 text-white font-medium rounded-lg hover:bg-amber-700 transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Verify Payment
                            </a>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Event Details Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-wrap items-start justify-between gap-6">
                        {{-- Event Information --}}
                        <div class="grid md:grid-cols-3 gap-6 flex-1">
                            <div>
                                <div
                                    class="flex items-center gap-2 text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Event Date
                                </div>
                                <div class="text-lg font-semibold text-gray-900">
                                    {{ \Illuminate\Support\Carbon::parse($event->event_date)->format('M d, Y') }}
                                </div>
                            </div>

                            <div>
                                <div
                                    class="flex items-center gap-2 text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Customer
                                </div>
                                <div class="flex items-center gap-3">
                                    @php
                                    $avatar = optional(optional($event->customer)->user)->profile_photo_url
                                    ?? 'https://ui-avatars.com/api/?name=' . urlencode($event->customer->customer_name
                                    ?? 'Unknown') . '&background=E5E7EB&color=111827';
                                    @endphp
                                    <img src="{{ $avatar }}"
                                        class="h-9 w-9 rounded-full object-cover ring-2 ring-gray-200" alt="Avatar">
                                    <div class="font-semibold text-gray-900">
                                        {{ $event->customer->customer_name ?? 'Unknown' }}
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div
                                    class="flex items-center gap-2 text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    Package
                                </div>
                                <div class="font-semibold text-gray-900">{{ $event->package->name ?? '—' }}</div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex flex-col gap-3">
                            {{-- Requested Status Actions --}}
                            <template x-if="status === 'requested'">
                                <div class="flex items-center gap-2">
                                    <button type="button"
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white font-medium hover:bg-emerald-700 transition shadow-sm"
                                        @click="openApprove()">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Approve
                                    </button>
                                    <button type="button"
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-600 text-white font-medium hover:bg-red-700 transition shadow-sm"
                                        @click="openReject()">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Reject
                                    </button>
                                </div>
                            </template>

                            {{-- Status Display --}}
                            <template x-if="status !== 'requested'">
                                <div class="px-4 py-2 bg-gray-100 rounded-lg text-center">
                                    <span class="text-xs text-gray-500">Status:</span>
                                    <span class="font-bold text-gray-900 ml-1" x-text="statusLabel()"></span>
                                </div>
                            </template>

                            {{-- Meeting Status - Assign Staff & Confirm --}}
                            <template x-if="statusLabel() === 'Meeting'">
                                <div class="flex flex-col gap-2">
                                    <a href="{{ route('admin.events.assignStaffPage', $event) }}"
                                        class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        Assign Staff
                                    </a>
                                    <form method="POST" action="{{ route('admin.events.confirm', $event) }}">
                                        @csrf
                                        <button
                                            class="w-full px-6 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm">
                                            Confirm Event
                                        </button>
                                    </form>
                                </div>
                            </template>

                            {{-- Scheduled Status - Quick Links --}}
                            <template x-if="statusLabel() === 'Scheduled'">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.event.guests', $event) }}"
                                        class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 text-center font-medium rounded-lg hover:bg-gray-200 transition">
                                        Guests
                                    </a>
                                    <a href="{{ route('admin.event.staffs', $event) }}"
                                        class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 text-center font-medium rounded-lg hover:bg-gray-200 transition">
                                        Staffs
                                    </a>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Approve Panel --}}
                <div x-show="showApprove" x-transition x-cloak
                    class="border-t border-gray-200 bg-gradient-to-br from-emerald-50 to-green-50 p-6">
                    <form method="POST" action="{{ route('admin.events.approve', $event) }}" class="space-y-4">
                        @csrf
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-8 h-8 bg-emerald-600 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-emerald-900">Approve Event</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-white rounded-lg p-4 border border-emerald-200">
                                <div class="text-xs uppercase tracking-wide text-gray-500 mb-1">Grand Total</div>
                                <div class="text-2xl font-bold text-emerald-700">₱<span x-text="fmt(grandTotal)"></span>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg p-4 border border-emerald-200">
                                <label for="downpayment_amount"
                                    class="text-xs uppercase tracking-wide text-gray-500 block mb-2">Downpayment</label>
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₱</span>
                                    <input type="number" step="0.01" min="0" x-model.number="downpayment"
                                        name="downpayment_amount" id="downpayment_amount"
                                        class="w-full pl-8 pr-3 py-2 border-2 border-gray-300 rounded-lg focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200" />
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Default: 50% of total</p>
                            </div>

                            <div class="bg-white rounded-lg p-4 border border-emerald-200">
                                <div class="text-xs uppercase tracking-wide text-gray-500 mb-1">Remaining Balance</div>
                                <div class="text-2xl font-bold text-gray-700">₱<span
                                        x-text="fmt(Math.max(grandTotal - (downpayment || 0), 0))"></span></div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button"
                                class="px-5 py-2 border-2 border-gray-300 rounded-lg font-medium hover:bg-gray-50 transition"
                                @click="showApprove=false">Cancel</button>
                            <button
                                class="px-6 py-2 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition shadow-md">
                                Confirm Approval
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Reject Panel --}}
                <div x-show="showReject" x-transition x-cloak
                    class="border-t border-gray-200 bg-gradient-to-br from-red-50 to-rose-50 p-6">
                    <form method="POST" action="{{ route('admin.events.reject', $event) }}" class="space-y-4">
                        @csrf
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-8 h-8 bg-red-600 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-red-900">Reject Event</h3>
                        </div>

                        <div class="bg-white rounded-lg p-4 border border-red-200">
                            <label for="rejection_reason" class="block text-sm font-semibold text-gray-700 mb-2">
                                Rejection Reason (Optional)
                            </label>
                            <textarea id="rejection_reason" name="rejection_reason" rows="4"
                                class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:border-red-500 focus:ring-2 focus:ring-red-200"
                                placeholder="Please provide a reason for rejecting this event..."></textarea>
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button"
                                class="px-5 py-2 border-2 border-gray-300 rounded-lg font-medium hover:bg-gray-50 transition"
                                @click="showReject=false">Cancel</button>
                            <button
                                class="px-6 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition shadow-md">
                                Confirm Rejection
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Package details & inclusions --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="font-semibold mb-3">Package Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-gray-600">Package</div>
                        <div class="font-medium">{{ $event->package->name ?? '—' }}</div>

                        <div class="mt-3 text-sm text-gray-600">Coordination</div>
                        <div class="whitespace-pre-line text-sm">
                            {{ $event->package->coordination ?? '—' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Event Styling</div>
                        @if(is_array(optional($event->package)->event_styling) && count($event->package->event_styling))
                        <ul class="list-disc pl-5 text-sm space-y-0.5">
                            @foreach($event->package->event_styling as $item)
                            <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                        @else
                        <div class="text-gray-500 text-sm">—</div>
                        @endif

                    </div>
                </div>

                <div class="mt-4">
                    <div class="text-sm text-gray-600 mb-1">Selected Inclusions</div>
                    @if($event->inclusions->isEmpty())
                    <div class="text-gray-500 text-sm">—</div>
                    @else
                    <ul class="space-y-2">
                        @foreach($event->inclusions as $inc)
                        <li class="border rounded p-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="font-medium text-gray-900">
                                        {{ $inc->name }}
                                        @if($inc->category)
                                        <span class="text-xs text-gray-500">• {{ $inc->category }}</span>
                                        @endif
                                    </div>

                                    {{-- Inclusion Notes --}}
                                    @if(trim($inc->notes))
                                    <div class="text-xs text-gray-500 mt-1">
                                        Notes: {{ $inc->notes }}
                                    </div>
                                    @endif
                                </div>

                                {{-- Inclusion Price --}}
                                @if(!is_null(optional($inc->pivot)->price_snapshot))
                                <div class="text-sm font-semibold whitespace-nowrap">
                                    ₱{{ number_format((float)$inc->pivot->price_snapshot, 2) }}
                                </div>
                                @endif
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <div class="text-gray-600">Coordination Price</div>
                        <div class="font-medium">
                            ₱{{ number_format($coord, 2) }}
                        </div>
                    </div>
                    <div>
                        <div class="text-gray-600">Event Styling Price</div>
                        <div class="font-medium">
                            ₱{{ number_format($styl, 2) }}
                        </div>
                    </div>
                    <div>
                        <div class="text-gray-600">Grand Total</div>
                        <div class="font-semibold">
                            ₱{{ number_format($grandTotal, 2) }}
                        </div>
                    </div>
                </div>
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