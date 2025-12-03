<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800">{{ $event->name }}</h2>
                <p class="text-sm text-gray-500 mt-1">Event ID: #{{ str_pad($event->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>

            @php
            $statusColors = [
            'requested' => 'bg-amber-50 text-amber-700 border-amber-200',
            'request_meeting' => 'bg-orange-50 text-orange-700 border-orange-200',
            'meeting' => 'bg-blue-50 text-blue-700 border-blue-200',
            'scheduled' => 'bg-violet-50 text-violet-700 border-violet-200',
            'ongoing' => 'bg-teal-50 text-teal-700 border-teal-200',
            'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
            ];
            $badge = $statusColors[$event->status] ?? 'bg-slate-50 text-slate-700 border-slate-200';
            @endphp
            <span
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold border {{ $badge }}">
                <span class="w-2 h-2 rounded-full bg-current"></span>
                {{ $event->status_label }}
            </span>
        </div>
    </x-slot>

    @php
    $coord = (float) optional($event->package)->coordination_price ?? 25000;
    $styl = (float) optional($event->package)->event_styling_price ?? 55000;
    $incSubtotal = (float) $event->inclusions->sum(fn($i) => (float) ($i->pivot->price_snapshot ?? 0));
    $grandTotal = $coord + $styl + $incSubtotal;
    @endphp

    <div class="py-8" x-data="{
        grandTotal: {{ json_encode($grandTotal) }},
        status: @js($event->status),
        showImageModal: false,
        modalImageSrc: '',
        showRejectIntro: false,
        showApprove: false,
        showReject: false,
        showRejectIntro: false,
        showRejectDown: false,
        showRequestDownpayment: false,
        showSchedules: false,
        downpaymentAmount: 0,

        fmt(n) {
            return Number(n || 0).toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        },

        openApprove() {
            this.showReject = false;
            this.showApprove = true;
        },

        openReject() {
            this.showApprove = false;
            this.showReject = true;
        },

        openRequestDownpayment() {
            this.downpaymentAmount = Math.max(this.grandTotal * 0.5, 0);
            this.showRequestDownpayment = true;
        },
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

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

            {{-- Intro Payment Pending Alert --}}
            @if($event->status === 'request_meeting' && $pendingIntroPayment)
            <div class="bg-orange-50 border border-orange-200 rounded-xl shadow-sm"
                x-data="{ showImageModal: false, modalImageSrc: '', modalPaymentType: '', modalPaymentAmount: '' }">
                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                                <svg viewBox="0 0 36 36" version="1.1" preserveAspectRatio="xMidYMid meet"
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    class="w-6 h-6 fill-orange-600">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <title>peso-solid</title>
                                        <path d="M14.18,13.8V16h9.45a5.26,5.26,0,0,0,.08-.89,4.72,4.72,0,0,0-.2-1.31Z"
                                            class="clr-i-solid clr-i-solid-path-1"></path>
                                        <path d="M14.18,19.7h5.19a4.28,4.28,0,0,0,3.5-1.9H14.18Z"
                                            class="clr-i-solid clr-i-solid-path-2"></path>
                                        <path d="M19.37,10.51H14.18V12h8.37A4.21,4.21,0,0,0,19.37,10.51Z"
                                            class="clr-i-solid clr-i-solid-path-3"></path>
                                        <path
                                            d="M17.67,2a16,16,0,1,0,16,16A16,16,0,0,0,17.67,2Zm10.5,15.8H25.7a6.87,6.87,0,0,1-6.33,4.4H14.18v6.54a1.25,1.25,0,1,1-2.5,0V17.8H8.76a.9.9,0,1,1,0-1.8h2.92V13.8H8.76a.9.9,0,1,1,0-1.8h2.92V9.26A1.25,1.25,0,0,1,12.93,8h6.44a6.84,6.84,0,0,1,6.15,4h2.65a.9.9,0,0,1,0,1.8H26.09a6.91,6.91,0,0,1,.12,1.3,6.8,6.8,0,0,1-.06.9h2a.9.9,0,0,1,0,1.8Z"
                                            class="clr-i-solid clr-i-solid-path-4"></path>
                                        <rect x="0" y="0" width="36" height="36" fill-opacity="0"></rect>
                                    </g>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-orange-900 mb-1">Introductory Payment Submitted</h3>
                            <p class="text-sm text-orange-700 mb-3">
                                Customer has submitted proof of ₱5,000 introductory payment
                            </p>

                            {{-- Payment Details --}}
                            <div class="bg-white/50 rounded-lg p-3 mb-4 space-y-2 text-sm">
                                <div class="flex items-center justify-between">
                                    <span class="text-orange-800 font-medium">Amount:</span>
                                    <span class="text-orange-900 font-semibold">₱{{
                                        number_format($pendingIntroPayment->amount, 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-orange-800 font-medium">Payment Method:</span>
                                    <span class="text-orange-900 font-semibold">{{
                                        $pendingIntroPayment->getMethodLabel() }}</span>
                                </div>
                                @if($pendingIntroPayment->reference_number)
                                <div class="flex items-center justify-between">
                                    <span class="text-orange-800 font-medium">Reference Number:</span>
                                    <span class="text-orange-900 font-semibold font-mono">{{
                                        $pendingIntroPayment->reference_number }}</span>
                                </div>
                                @endif
                                <div class="flex items-center justify-between">
                                    <span class="text-orange-800 font-medium">Payment Date:</span>
                                    <span class="text-orange-900 font-semibold">{{
                                        $pendingIntroPayment->payment_date->format('M d, Y') }}</span>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-3">
                                <form method="POST" action="{{ route('admin.events.approveIntroPayment', $event) }}">
                                    @csrf
                                    <button
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Approve Payment
                                    </button>
                                </form>
                                <button @click="showRejectIntro = true"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-rose-600 text-white font-medium rounded-lg hover:bg-rose-700 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Reject Payment
                                </button>
                                @if($pendingIntroPayment->payment_image)
                                <button
                                    @click="showImageModal = true; modalImageSrc = '{{ asset('storage/' . $pendingIntroPayment->payment_image) }}'; modalPaymentType = 'Introductory Payment'; modalPaymentAmount = '5,000.00'"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    View Payment Proof
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Downpayment Pending Alert --}}
            @if($event->status === 'meeting' && $pendingDownpayment)
            @php
            $isFullPayment = false;
            if ($event->billing && $event->billing->remaining_balance > 0) {
            $isFullPayment = abs($pendingDownpayment->amount - $event->billing->remaining_balance) < 0.01; } @endphp
                <div class="bg-blue-50 border border-blue-200 rounded-xl shadow-sm">
                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 {{ $isFullPayment ? 'bg-green-100' : 'bg-blue-100' }} rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 {{ $isFullPayment ? 'text-green-600' : 'text-blue-600' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($isFullPayment)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    @endif
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3
                                class="text-lg font-semibold {{ $isFullPayment ? 'text-green-900' : 'text-blue-900' }} mb-1">
                                {{ $isFullPayment ? 'Full Payment Submitted' : 'Downpayment Submitted' }}
                            </h3>
                            <p class="text-sm {{ $isFullPayment ? 'text-green-700' : 'text-blue-700' }} mb-3">
                                @if($isFullPayment)
                                Customer has submitted full payment proof of ₱{{
                                number_format($pendingDownpayment->amount, 2) }}
                                (remaining balance)
                                @else
                                Customer has submitted proof of ₱{{ number_format($pendingDownpayment->amount, 2) }}
                                downpayment
                                @endif
                            </p>

                            {{-- Payment Details --}}
                            <div class="bg-white/50 rounded-lg p-3 mb-4 space-y-2 text-sm">
                                <div class="flex items-center justify-between">
                                    <span
                                        class="{{ $isFullPayment ? 'text-green-800' : 'text-blue-800' }} font-medium">Amount:</span>
                                    <span
                                        class="{{ $isFullPayment ? 'text-green-900' : 'text-blue-900' }} font-semibold">₱{{
                                        number_format($pendingDownpayment->amount, 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span
                                        class="{{ $isFullPayment ? 'text-green-800' : 'text-blue-800' }} font-medium">Payment
                                        Method:</span>
                                    <span
                                        class="{{ $isFullPayment ? 'text-green-900' : 'text-blue-900' }} font-semibold">{{
                                        $pendingDownpayment->getMethodLabel() }}</span>
                                </div>
                                @if($pendingDownpayment->reference_number)
                                <div class="flex items-center justify-between">
                                    <span
                                        class="{{ $isFullPayment ? 'text-green-800' : 'text-blue-800' }} font-medium">Reference
                                        Number:</span>
                                    <span
                                        class="{{ $isFullPayment ? 'text-green-900' : 'text-blue-900' }} font-semibold font-mono">{{
                                        $pendingDownpayment->reference_number }}</span>
                                </div>
                                @endif
                                <div class="flex items-center justify-between">
                                    <span
                                        class="{{ $isFullPayment ? 'text-green-800' : 'text-blue-800' }} font-medium">Payment
                                        Date:</span>
                                    <span
                                        class="{{ $isFullPayment ? 'text-green-900' : 'text-blue-900' }} font-semibold">{{
                                        $pendingDownpayment->payment_date->format('M d, Y') }}</span>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-3">
                                <form method="POST" action="{{ route('admin.events.approveDownpayment', $event) }}">
                                    @csrf
                                    <button
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Approve {{ $isFullPayment ? 'Full Payment' : 'Downpayment' }}
                                    </button>
                                </form>
                                <button @click="showRejectDown = true"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-rose-600 text-white font-medium rounded-lg hover:bg-rose-700 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Reject Payment
                                </button>
                                @if($pendingDownpayment->payment_image)
                                <button
                                    @click="showImageModal = true; modalImageSrc = '{{ asset('storage/' . $pendingDownpayment->payment_image) }}'; modalPaymentType = '{{ $isFullPayment ? 'Full Payment' : 'Downpayment' }}'; modalPaymentAmount = '{{ number_format($pendingDownpayment->amount, 2) }}'"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    View Payment Proof
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
        </div>

        {{-- Image Modal --}}
        <div x-show="showImageModal" x-cloak @keydown.escape.window="showImageModal = false"
            class="fixed inset-0 z-50 overflow-y-auto" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">

            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-black/80 transition-opacity" @click="showImageModal = false"></div>

            {{-- Modal Content --}}
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative max-w-5xl w-full" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95" @click.stop>

                    {{-- Close Button --}}
                    <button @click="showImageModal = false"
                        class="absolute -top-4 -right-4 z-10 bg-white rounded-full p-2 shadow-lg hover:bg-gray-100 transition">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    {{-- Image --}}
                    <div class="bg-white rounded-xl shadow-2xl overflow-hidden">
                        <div class="bg-gray-100 px-6 py-4 border-b">
                            <h3 class="text-lg font-semibold text-gray-900">Payment Proof</h3>
                            <p class="text-sm text-gray-500 mt-1"
                                x-text="modalPaymentType + ' - ₱' + modalPaymentAmount"></p>
                        </div>
                        <div class="p-4 bg-gray-50">
                            <img :src="modalImageSrc" alt="Payment Proof" class="w-full h-auto rounded-lg shadow-md"
                                style="max-height: 70vh; object-fit: contain;">
                        </div>
                        <div class="bg-gray-100 px-6 py-4 border-t flex justify-end gap-3">
                            <a :href="modalImageSrc" download
                                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download
                            </a>
                            <button @click="showImageModal = false"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>
        @endif
        {{-- Main Event Information Grid --}}
        <div class="grid lg:grid-cols-3 gap-6">

            {{-- Left Column: Event Details --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Event Key Information with Inline Editing --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200" x-data="{ 
                    isEditing: false,
                    saving: false,
                }">
                    <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Event Information
                            </h3>

                            {{-- Edit Toggle Button --}}
                            <button type="button" @click="isEditing = !isEditing" x-show="!isEditing"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-violet-600 hover:bg-violet-50 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                Edit
                            </button>

                            {{-- Cancel Button (when editing) --}}
                            <button type="button" @click="isEditing = false" x-show="isEditing" x-cloak
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Cancel
                            </button>
                        </div>
                    </div>

                    {{-- Display Mode --}}
                    <div x-show="!isEditing" class="p-6 space-y-6">
                        {{-- Event Date --}}
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-sky-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Event Date
                                </div>
                                <div class="text-xl font-semibold text-gray-900">
                                    {{ $event->event_date->format('F d, Y') }}
                                </div>
                            </div>
                        </div>

                        {{-- Venue --}}
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
                                <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Venue</div>
                                @if($event->venue)
                                <div class="text-base font-medium text-gray-900">{{ $event->venue }}</div>
                                @else
                                <div class="text-sm text-gray-400 italic">Not specified</div>
                                @endif
                            </div>
                        </div>

                        {{-- Theme --}}
                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 bg-violet-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Theme</div>
                                @if($event->theme)
                                <div class="text-base font-medium text-gray-900">{{ $event->theme }}</div>
                                @else
                                <div class="text-sm text-gray-400 italic">Not specified</div>
                                @endif
                            </div>
                        </div>

                        {{-- Guests --}}
                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Guest
                                    Details</div>
                                @if($event->guests)
                                <div class="text-sm text-gray-700 leading-relaxed">{{ $event->guests }}</div>
                                @else
                                <div class="text-sm text-gray-400 italic">Not specified</div>
                                @endif
                            </div>
                        </div>

                        {{-- Notes --}}
                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-12 h-12 bg-gray-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Notes
                                    </div>
                                    @if($event->notes)
                                    <div class="text-sm text-gray-700 leading-relaxed bg-slate-50 rounded-lg p-4">{{
                                        $event->notes }}</div>
                                    @else
                                    <div class="text-sm text-gray-400 italic">No notes</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Edit Mode Form --}}
                    <form x-show="isEditing" x-cloak action="{{ route('admin.events.update-info', $event) }}"
                        method="POST" @submit="saving = true" class="p-6 space-y-6">
                        @csrf
                        @method('PATCH')

                        {{-- Event Date with Calendar Picker --}}
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-sky-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <label
                                    class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2 block">Event
                                    Date *</label>
                                <x-calendar-picker name="event_date" :value="old('event_date')" required />
                                @error('event_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Venue --}}
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
                                <label for="venue"
                                    class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2 block">Venue</label>
                                <input type="text" id="venue" name="venue" value="{{ old('venue', $event->venue) }}"
                                    placeholder="Enter venue"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                                @error('venue')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Theme --}}
                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 bg-violet-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <label for="theme"
                                    class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2 block">Theme</label>
                                <input type="text" id="theme" name="theme" value="{{ old('theme', $event->theme) }}"
                                    placeholder="Enter theme"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                                @error('theme')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Guests --}}
                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <label for="guests"
                                    class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2 block">Guest
                                    Details</label>
                                <textarea id="guests" name="guests" rows="2"
                                    placeholder="Enter guest details (e.g., 100 pax, VIP guests, etc.)"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500">{{ old('guests', $event->guests) }}</textarea>
                                @error('guests')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Notes --}}
                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-12 h-12 bg-gray-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <label for="notes"
                                        class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2 block">Notes</label>
                                    <textarea id="notes" name="notes" rows="4"
                                        placeholder="Enter any additional notes..."
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500">{{ old('notes', $event->notes) }}</textarea>
                                    @error('notes')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Save Button --}}
                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-end gap-3">
                                <button type="button" @click="isEditing = false"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                    Cancel
                                </button>
                                <button type="submit" :disabled="saving"
                                    class="inline-flex items-center gap-2 px-5 py-2 text-sm font-semibold text-white bg-violet-600 rounded-lg hover:bg-violet-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg x-show="!saving" class="w-4 h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <svg x-show="saving" x-cloak class="w-4 h-4 animate-spin" fill="none"
                                        viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    <span x-text="saving ? 'Saving...' : 'Save Changes'"></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Package Details (keeping your existing section) --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                                {{-- Edit Button - Only show if event is approved (not requested or rejected) --}}
                                @if(!in_array($event->status, ['requested', 'rejected', 'completed']))
                                <a href="{{ route('admin.events.editInclusions', $event) }}"
                                    class="ml-2 inline-flex items-center gap-1 px-3 py-1.5 bg-violet-600 text-white text-sm font-medium rounded-lg hover:bg-violet-700 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </a>
                                @endif
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
                                            @if(!empty($inc->pivot->notes))
                                            <div
                                                class="mt-2 flex items-start gap-2 text-xs text-gray-600 bg-white rounded px-2 py-1.5 border border-gray-200">
                                                <svg class="w-3.5 h-3.5 text-gray-400 mt-0.5 flex-shrink-0" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                                </svg>
                                                <span class="flex-1">{{ $inc->pivot->notes }}</span>
                                            </div>
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
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            <img src="{{ $avatar }}" class="h-12 w-12 rounded-full object-cover ring-2 ring-gray-200"
                                alt="Avatar">
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
                @php
                $pricingLines = [
                ['label' => 'Coordination', 'amount' => $coord ?? 0],
                ['label' => 'Event Styling', 'amount' => $styl ?? 0],
                ['label' => 'Inclusions', 'amount' => $incSubtotal ?? 0],
                ];
                @endphp

                <section aria-labelledby="pricing-heading" class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <header id="pricing-heading" class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                        <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M11 15H8V3h3a6 6 0 016 6 6 6 0 01-6 6zM8 3v18" stroke="#6b7280"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M4 7H20M4 11H20" stroke="#6b7280" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            Pricing
                        </h3>
                    </header>

                    <div class="p-6 space-y-3">
                        @foreach($pricingLines as $line)
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600">{{ $line['label'] }}</span>
                            <span class="font-medium text-gray-900">₱{{ number_format((float)$line['amount'], 2)
                                }}</span>
                        </div>
                        @endforeach

                        <div class="border-t border-gray-200 pt-3 mt-3">
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-gray-900">Grand Total</span>
                                <span class="text-xl font-bold text-emerald-600">₱{{ number_format($grandTotal ?? 0, 2)
                                    }}</span>
                            </div>
                        </div>

                        @if($event->billing ?? false)
                        <div class="border-t border-gray-200 pt-3 mt-3 space-y-2">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">Amount Paid</span>
                                <span
                                    class="font-medium {{ (float)($totalPaid ?? 0) > 0 ? 'text-emerald-600' : 'text-gray-500' }}">
                                    ₱{{ number_format((float)($totalPaid ?? 0), 2) }}
                                </span>
                            </div>

                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">Remaining Balance</span>
                                @php $remaining = $remainingBalance ?? max(0, ($grandTotal ?? 0) - ($totalPaid ?? 0));
                                @endphp
                                @if($remaining > 0)
                                <span class="font-semibold text-red-600">₱{{ number_format($remaining, 2) }}</span>
                                @else
                                <span class="font-semibold text-emerald-600">₱0.00 — Paid in full</span>
                                @endif
                            </div>

                            {{-- Optional progress indicator --}}
                            @php
                            $paid = (float)($totalPaid ?? 0);
                            $total = max(1, (float)($grandTotal ?? 0)); // avoid division by zero
                            $percent = min(100, round(($paid / $total) * 100));
                            @endphp
                            <div class="w-full pt-2">
                                <div class="text-xs text-gray-500 mb-1">{{ $percent }}% paid</div>
                                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-2 rounded-full"
                                        style="width: {{ $percent }}%; background-color: rgba(16,185,129,0.85)"></div>
                                </div>
                            </div>

                            {{-- Payment status indicators --}}
                            <div class="pt-2 space-y-2">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Intro Payment</span>
                                    <span
                                        class="font-medium {{ ($event->billing->introductory_payment_status ?? '') === 'paid' ? 'text-emerald-600' : 'text-gray-500' }}">
                                        {{ ($event->billing->introductory_payment_status ?? '') === 'paid' ? '✓ Paid' :
                                        'Pending' }}
                                    </span>
                                </div>

                                @if(($event->billing->downpayment_amount ?? 0) > 0)
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Downpayment</span>
                                    <span
                                        class="font-medium {{ $event->hasDownpaymentPaid() ? 'text-emerald-600' : 'text-gray-500' }}">
                                        {{ $event->hasDownpaymentPaid() ? '✓ Paid' : 'Pending' }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </section>


                {{-- Action Buttons Based on Status --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                        <h4 class="text-base font-semibold text-gray-800">Actions</h4>
                    </div>
                    <div class="p-6">

                        {{-- REQUESTED Status - Approve/Reject --}}
                        @if($event->status === 'requested')
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
                        @endif

                        {{-- REQUEST_MEETING Status - Waiting for intro payment --}}
                        @if($event->status === 'request_meeting' && !$pendingIntroPayment)
                        <div class="text-center py-4 bg-orange-50 rounded-lg border border-orange-200">
                            <svg class="w-8 h-8 text-orange-500 mx-auto mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="text-sm font-medium text-orange-900 mb-1">Awaiting Payment</div>
                            <div class="text-xs text-orange-700">Customer needs to pay ₱5,000 intro payment</div>
                        </div>
                        @endif

                        {{-- MEETING Status - Request downpayment or complete meeting --}}
                        @if($event->status === 'meeting')
                        <div class="space-y-3">
                            @if(!$event->billing->downpayment_amount && !$pendingDownpayment)
                            <button type="button" @click="openRequestDownpayment()"
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Request Downpayment
                            </button>
                            @elseif($event->billing->downpayment_amount && !$pendingDownpayment &&
                            !$hasDownpaymentPaid)
                            <div class="text-center py-4 bg-blue-50 rounded-lg border border-blue-200">
                                <svg class="w-8 h-8 text-blue-500 mx-auto mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="text-sm font-medium text-blue-900 mb-1">Awaiting Downpayment</div>
                                <div class="text-xs text-blue-700">Customer needs to pay ₱{{
                                    number_format($event->billing->downpayment_amount - 5000, 2) }}</div>
                            </div>
                            @endif

                            {{-- Complete Meeting Button - Always show in meeting status --}}
                            @if($hasDownpaymentPaid)
                            <form method="POST" action="{{ route('admin.events.completeMeeting', $event) }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-lg bg-violet-600 text-white font-medium hover:bg-violet-700 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Mark Meeting Complete
                                </button>
                            </form>
                            @endif
                        </div>
                        @endif

                        {{-- SCHEDULED Status - Assign staff --}}
                        @if($event->status === 'scheduled')
                        <div class="space-y-3">
                            <a href="{{ route('admin.events.assignStaffPage', $event) }}"
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-lg bg-violet-600 text-white font-medium hover:bg-violet-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                Assign Staff
                            </a>

                            <button @click="showSchedules = true"
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-lg bg-amber-600 text-white font-medium hover:bg-amber-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Manage Schedules
                            </button>
                        </div>
                        @endif

                        {{-- ONGOING Status --}}
                        @if($event->status === 'ongoing')
                        <div class="text-center py-4 bg-teal-50 rounded-lg border border-teal-200">
                            <svg class="w-8 h-8 text-teal-500 mx-auto mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="text-sm font-medium text-teal-900 mb-1">Event is Ongoing</div>
                            <div class="text-xs text-teal-700">Event is currently in progress</div>
                        </div>
                        @endif

                        {{-- COMPLETED Status --}}
                        @if($event->status === 'completed')
                        <div class="text-center py-4 bg-emerald-50 rounded-lg border border-emerald-200">
                            <svg class="w-8 h-8 text-emerald-500 mx-auto mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="text-sm font-medium text-emerald-900 mb-1">Event Completed</div>
                            <div class="text-xs text-emerald-700">This event has been completed successfully</div>
                        </div>
                        @endif

                        {{-- REJECTED Status --}}
                        @if($event->status === 'rejected')
                        <div class="text-center py-4 bg-rose-50 rounded-lg border border-rose-200">
                            <svg class="w-8 h-8 text-rose-500 mx-auto mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="text-sm font-medium text-rose-900 mb-1">Event Rejected</div>
                            @if($event->rejection_reason)
                            <div class="text-xs text-rose-700 mt-2">{{ $event->rejection_reason }}</div>
                            @endif
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
        {{-- Approve Event Modal --}}
        <div x-show="showApprove" x-cloak @click.self="showApprove=false"
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-auto"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-90">

                <form method="POST" action="{{ route('admin.events.approve', $event) }}">
                    @csrf

                    {{-- Modal Header --}}
                    <div class="bg-emerald-50 border-b border-emerald-100 p-6 rounded-t-2xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-emerald-900">Approve Event</h3>
                                    <p class="text-sm text-emerald-700">Customer will be asked to pay ₱5,000
                                        introductory payment</p>
                                </div>
                            </div>
                            <button type="button" @click="showApprove=false"
                                class="text-gray-400 hover:text-gray-600 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-6 space-y-6">
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <div class="grid grid-cols-2 gap-4 text-center">
                                <div>
                                    <div class="text-xs text-gray-500 mb-1">Total Event Cost</div>
                                    <div class="text-xl font-bold text-gray-900">₱<span x-text="fmt(grandTotal)"></span>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 mb-1">Intro Payment Required</div>
                                    <div class="text-xl font-bold text-emerald-600">₱5,000.00</div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex gap-3">
                                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="text-sm text-blue-900">
                                    <p class="font-medium mb-1">What happens next?</p>
                                    <ul class="text-xs text-blue-800 space-y-1">
                                        <li>• Customer account will be created automatically</li>
                                        <li>• Customer will receive login credentials via email</li>
                                        <li>• Customer must pay ₱5,000 introductory payment</li>
                                        <li>• After payment verification, you can request downpayment</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="bg-gray-50 border-t border-gray-200 p-6 rounded-b-2xl flex justify-end gap-3">
                        <button type="button" @click="showApprove=false"
                            class="px-5 py-2.5 border border-gray-300 rounded-lg font-medium hover:bg-gray-100 transition">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-2.5 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition shadow-lg shadow-emerald-200">
                            Approve & Notify Customer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Reject Event Modal --}}
        <div x-show="showReject" x-cloak @click.self="showReject=false"
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-auto"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-90">

                <form method="POST" action="{{ route('admin.events.reject', $event) }}">
                    @csrf

                    {{-- Modal Header --}}
                    <div class="bg-rose-50 border-b border-rose-100 p-6 rounded-t-2xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-rose-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-rose-900">Reject Event</h3>
                                    <p class="text-sm text-rose-700">Provide reason for rejection</p>
                                </div>
                            </div>
                            <button type="button" @click="showReject=false"
                                class="text-gray-400 hover:text-gray-600 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-6">
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                Rejection Reason (Optional)
                            </label>
                            <textarea id="rejection_reason" name="rejection_reason" rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-rose-500 focus:ring-2 focus:ring-rose-200"
                                placeholder="Explain why this event is being rejected..."></textarea>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="bg-gray-50 border-t border-gray-200 p-6 rounded-b-2xl flex justify-end gap-3">
                        <button type="button" @click="showReject=false"
                            class="px-5 py-2.5 border border-gray-300 rounded-lg font-medium hover:bg-gray-100 transition">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-2.5 bg-rose-600 text-white font-medium rounded-lg hover:bg-rose-700 transition shadow-lg shadow-rose-200">
                            Confirm Rejection
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Request Downpayment Modal --}}
        <div x-show="showRequestDownpayment" x-cloak @click.self="showRequestDownpayment=false"
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-auto"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-90">

                <form method="POST" action="{{ route('admin.events.requestDownpayment', $event) }}">
                    @csrf

                    {{-- Modal Header --}}
                    <div class="bg-blue-50 border-b border-blue-100 p-6 rounded-t-2xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-blue-900">Request Downpayment</h3>
                                    <p class="text-sm text-blue-700">Set the downpayment amount for this event</p>
                                </div>
                            </div>
                            <button type="button" @click="showRequestDownpayment=false"
                                class="text-gray-400 hover:text-gray-600 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="text-xs text-gray-500 mb-1">Total Cost</div>
                                <div class="text-xl font-bold text-gray-900">₱<span x-text="fmt(grandTotal)"></span>
                                </div>
                            </div>

                            <div class="bg-emerald-50 rounded-lg p-4 border border-emerald-200">
                                <div class="text-xs text-emerald-600 mb-1">Intro Paid</div>
                                <div class="text-xl font-bold text-emerald-600">₱5,000.00</div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="text-xs text-gray-500 mb-1">Remaining</div>
                                <div class="text-xl font-bold text-gray-700">₱<span
                                        x-text="fmt(grandTotal - 5000)"></span></div>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <label for="downpayment_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                Downpayment Amount <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span
                                    class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-medium">₱</span>
                                <input type="number" step="0.01" min="0" x-model.number="downpaymentAmount"
                                    name="downpayment_amount" id="downpayment_amount" required
                                    class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200" />
                            </div>
                            <p class="text-xs text-gray-500 mt-2">
                                Customer will pay: <span class="font-semibold text-gray-700">₱<span
                                        x-text="fmt(Math.max(downpaymentAmount - 5000, 0))"></span></span>
                                (₱5,000 already paid as intro payment)
                            </p>
                            <p class="text-xs text-gray-500 mt-1">Default: 50% of total (₱<span
                                    x-text="fmt(grandTotal * 0.5)"></span>)</p>
                        </div>

                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                            <div class="flex gap-3">
                                <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="text-sm text-amber-900">
                                    <p class="font-medium mb-1">Important Note:</p>
                                    <p class="text-xs text-amber-800">
                                        The downpayment amount you enter should include the ₱5,000 introductory
                                        payment.
                                        The customer will only pay the difference.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="bg-gray-50 border-t border-gray-200 p-6 rounded-b-2xl flex justify-end gap-3">
                        <button type="button" @click="showRequestDownpayment=false"
                            class="px-5 py-2.5 border border-gray-300 rounded-lg font-medium hover:bg-gray-100 transition">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                            Request Downpayment
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Reject Intro Payment Modal --}}
        <div x-show="showRejectIntro" x-cloak @click.self="showRejectIntro=false"
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-auto"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-90">

                <form method="POST" action="{{ route('admin.events.rejectIntroPayment', $event) }}">
                    @csrf

                    {{-- Modal Header --}}
                    <div class="bg-rose-50 border-b border-rose-100 p-6 rounded-t-2xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-rose-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-rose-900">Reject Introductory Payment</h3>
                                    <p class="text-sm text-rose-700">Provide reason for rejection</p>
                                </div>
                            </div>
                            <button type="button" @click="showRejectIntro=false"
                                class="text-gray-400 hover:text-gray-600 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-6">
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <label for="rejection_reason_intro" class="block text-sm font-medium text-gray-700 mb-2">
                                Rejection Reason
                            </label>
                            <textarea id="rejection_reason_intro" name="rejection_reason" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-rose-500 focus:ring-2 focus:ring-rose-200"
                                placeholder="E.g., Proof of payment is unclear, wrong amount, etc."></textarea>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="bg-gray-50 border-t border-gray-200 p-6 rounded-b-2xl flex justify-end gap-3">
                        <button type="button" @click="showRejectIntro=false"
                            class="px-5 py-2.5 border border-gray-300 rounded-lg font-medium hover:bg-gray-100 transition">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-2.5 bg-rose-600 text-white font-medium rounded-lg hover:bg-rose-700 transition shadow-lg shadow-rose-200">
                            Reject Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Reject Downpayment Modal --}}
        <div x-show="showRejectDown" x-cloak @click.self="showRejectDown=false"
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-auto"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-90">

                <form method="POST" action="{{ route('admin.events.rejectDownpayment', $event) }}">
                    @csrf

                    {{-- Modal Header --}}
                    <div class="bg-rose-50 border-b border-rose-100 p-6 rounded-t-2xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-rose-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-rose-900">Reject Downpayment</h3>
                                    <p class="text-sm text-rose-700">Provide reason for rejection</p>
                                </div>
                            </div>
                            <button type="button" @click="showRejectDown=false"
                                class="text-gray-400 hover:text-gray-600 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-6">
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <label for="rejection_reason_down" class="block text-sm font-medium text-gray-700 mb-2">
                                Rejection Reason
                            </label>
                            <textarea id="rejection_reason_down" name="rejection_reason" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-rose-500 focus:ring-2 focus:ring-rose-200"
                                placeholder="E.g., Proof of payment is unclear, wrong amount, etc."></textarea>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="bg-gray-50 border-t border-gray-200 p-6 rounded-b-2xl flex justify-end gap-3">
                        <button type="button" @click="showRejectDown=false"
                            class="px-5 py-2.5 border border-gray-300 rounded-lg font-medium hover:bg-gray-100 transition">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-2.5 bg-rose-600 text-white font-medium rounded-lg hover:bg-rose-700 transition shadow-lg shadow-rose-200">
                            Reject Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Schedules Modal --}}
        <div x-show="showSchedules" x-cloak @keydown.escape.window="showSchedules = false"
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div @click.stop class="bg-white rounded-xl shadow-2xl max-w-5xl w-full mx-auto max-h-[90vh] flex flex-col"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-90">

                {{-- Modal Header --}}
                <div
                    class="bg-amber-600 text-white px-6 py-4 flex items-center justify-between rounded-t-xl flex-shrink-0">
                    <div>
                        <h3 class="text-lg font-bold">Inclusion Schedules</h3>
                        <p class="text-sm text-amber-100">{{ $event->event_date->format('M d, Y') }} • {{
                            $event->inclusions->count() }} inclusions</p>
                    </div>
                    <button @click="showSchedules = false" class="text-white/80 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Form --}}
                <form action="/admin/events/{{ $event->id }}/save-schedules" method="POST"
                    class="flex flex-col flex-1 overflow-hidden">
                    @csrf

                    {{-- Table Header --}}
                    <div class="bg-gray-50 border-b border-gray-200 px-6 py-3 flex-shrink-0">
                        <div
                            class="grid grid-cols-12 gap-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                            <div class="col-span-4">Inclusion</div>
                            <div class="col-span-3">Date</div>
                            <div class="col-span-2">Time</div>
                            <div class="col-span-3">Remarks</div>
                        </div>
                    </div>

                    {{-- Table Body --}}
                    <div class="flex-1 overflow-y-auto">
                        @forelse($event->inclusions as $index => $inclusion)
                        @php
                        $schedule = $event->schedules?->where('inclusion_id', $inclusion->id)->first();
                        $hasSchedule = $schedule && $schedule->scheduled_date;
                        $rowBg = $hasSchedule ? 'bg-emerald-50 border-l-4 border-l-emerald-500' : 'bg-white border-l-4
                        border-l-gray-200';
                        @endphp
                        <div class="px-6 py-3 border-b border-gray-100 {{ $rowBg }} hover:bg-gray-50 transition">
                            <input type="hidden" name="schedules[{{ $index }}][inclusion_id]"
                                value="{{ $inclusion->id }}">

                            <div class="grid grid-cols-12 gap-3 items-center">
                                {{-- Inclusion Info --}}
                                <div class="col-span-4 flex items-center gap-3">
                                    {{-- Image --}}
                                    <div class="w-10 h-10 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                        @if($inclusion->image)
                                        <img src="{{ Storage::url($inclusion->image) }}" alt="{{ $inclusion->name }}"
                                            class="w-full h-full object-cover">
                                        @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        @endif
                                    </div>
                                    {{-- Name & Status --}}
                                    <div class="min-w-0">
                                        <div class="font-medium text-gray-900 text-sm truncate">{{ $inclusion->name }}
                                        </div>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            @if($inclusion->category)
                                            <span class="text-xs text-violet-600">{{ $inclusion->category }}</span>
                                            @endif
                                            @if($hasSchedule)
                                            <span class="inline-flex items-center gap-1 text-xs text-emerald-600">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Scheduled
                                            </span>
                                            @else
                                            <span class="text-xs text-gray-400">Not set</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Date --}}
                                <div class="col-span-3">
                                    <input type="date" name="schedules[{{ $index }}][scheduled_date]"
                                        value="{{ $schedule?->scheduled_date?->format('Y-m-d') }}"
                                        class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                                </div>

                                {{-- Time --}}
                                <div class="col-span-2">
                                    <input type="time" name="schedules[{{ $index }}][scheduled_time]"
                                        value="{{ $schedule?->scheduled_time ? \Carbon\Carbon::parse($schedule->scheduled_time)->format('H:i') : '' }}"
                                        class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                                </div>

                                {{-- Remarks --}}
                                <div class="col-span-3">
                                    <input type="text" name="schedules[{{ $index }}][remarks]"
                                        value="{{ $schedule?->remarks }}" placeholder="Optional"
                                        class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p>No inclusions in this event</p>
                        </div>
                        @endforelse
                    </div>

                    {{-- Modal Footer --}}
                    <div
                        class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between flex-shrink-0 rounded-b-xl">
                        @php
                        $scheduledCount = $event->schedules?->count() ?? 0;
                        $totalCount = $event->inclusions->count();
                        @endphp
                        <div class="text-sm text-gray-500">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                {{ $scheduledCount }}/{{ $totalCount }} scheduled
                            </span>
                        </div>
                        <div class="flex items-center gap-3">
                            <button type="button" @click="showSchedules = false"
                                class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition font-medium">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-6 py-2 bg-amber-600 text-white font-medium rounded-lg hover:bg-amber-700 transition">
                                Save Schedules
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>