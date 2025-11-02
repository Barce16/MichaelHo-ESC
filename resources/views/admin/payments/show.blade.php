<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Payment Details</h2>
            <a href="{{ route('admin.payments.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Payments
            </a>
        </div>
    </x-slot>

    @php
    $event = $payment->billing->event ?? $payment->event;
    $customer = $event->customer;

    $typeBadge = match($payment->payment_type) {
    'introductory' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-700', 'border' => 'border-orange-200'],
    'downpayment' => ['bg' => 'bg-violet-50', 'text' => 'text-violet-700', 'border' => 'border-violet-200'],
    'balance' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200'],
    default => ['bg' => 'bg-gray-50', 'text' => 'text-gray-700', 'border' => 'border-gray-200'],
    };

    $statusBadge = match($payment->status) {
    'pending' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200'],
    'approved' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200'],
    'rejected' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'border' => 'border-rose-200'],
    default => ['bg' => 'bg-gray-50', 'text' => 'text-gray-700', 'border' => 'border-gray-200'],
    };
    @endphp

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Header Card --}}
            <div
                class="bg-gradient-to-r {{ $typeBadge['bg'] }} border {{ $typeBadge['border'] }} rounded-xl shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-2xl font-bold {{ $typeBadge['text'] }}">
                                {{ ucfirst($payment->payment_type) }} Payment
                            </h3>
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border {{ $statusBadge['bg'] }} {{ $statusBadge['text'] }} {{ $statusBadge['border'] }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                {{ ucfirst($payment->status) }}
                            </span>
                        </div>
                        <p class="{{ $typeBadge['text'] }} opacity-80">Payment ID: #{{ str_pad($payment->id, 6, '0',
                            STR_PAD_LEFT) }}</p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm {{ $typeBadge['text'] }} opacity-80 mb-1">Amount</div>
                        <div class="text-3xl font-bold {{ $typeBadge['text'] }}">₱{{ number_format($payment->amount, 2)
                            }}</div>
                    </div>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">

                {{-- Payment Information --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h4>

                    <div class="space-y-4">
                        <div>
                            <div class="text-xs font-medium text-gray-500 mb-1">Payment Method</div>
                            <div class="text-sm font-medium text-gray-900">{{ $payment->getMethodLabel() }}</div>
                        </div>

                        @if($payment->reference_number)
                        <div>
                            <div class="text-xs font-medium text-gray-500 mb-1">Reference Number</div>
                            <div
                                class="text-sm font-mono font-medium text-gray-900 bg-gray-50 px-3 py-2 rounded border border-gray-200">
                                {{ $payment->reference_number }}
                            </div>
                        </div>
                        @endif

                        <div>
                            <div class="text-xs font-medium text-gray-500 mb-1">Payment Date</div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ $payment->payment_date?->format('F d, Y') ?? 'N/A' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs font-medium text-gray-500 mb-1">Submitted</div>
                            <div class="text-sm font-medium text-gray-900">{{ $payment->created_at->format('M d, Y h:i
                                A') }}</div>
                        </div>

                        @if($payment->notes)
                        <div>
                            <div class="text-xs font-medium text-gray-500 mb-1">Notes</div>
                            <div class="text-sm text-gray-700 bg-gray-50 px-3 py-2 rounded border border-gray-200">
                                {{ $payment->notes }}
                            </div>
                        </div>
                        @endif

                        @if($payment->rejection_reason)
                        <div>
                            <div class="text-xs font-medium text-rose-500 mb-1">Rejection Reason</div>
                            <div class="text-sm text-rose-700 bg-rose-50 px-3 py-2 rounded border border-rose-200">
                                {{ $payment->rejection_reason }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Event & Customer Information --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Event & Customer</h4>

                    <div class="space-y-4">
                        <div>
                            <div class="text-xs font-medium text-gray-500 mb-1">Event Name</div>
                            <a href="{{ route('admin.events.show', $event) }}"
                                class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                                {{ $event->name }}
                            </a>
                        </div>

                        <div>
                            <div class="text-xs font-medium text-gray-500 mb-1">Event Date</div>
                            <div class="text-sm text-gray-900">{{ $event->event_date->format('F d, Y') }}</div>
                        </div>

                        <div>
                            <div class="text-xs font-medium text-gray-500 mb-1">Venue</div>
                            <div class="text-sm text-gray-700">{{ $event->venue ?? 'N/A' }}</div>
                        </div>

                        <div class="pt-4 border-t border-gray-200">
                            <div class="text-xs font-medium text-gray-500 mb-2">Customer Information</div>

                            <div class="space-y-2">
                                <div class="flex items-center gap-2 text-sm">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="font-medium text-gray-900">{{ $customer->customer_name }}</span>
                                </div>

                                @if($customer->email)
                                <div class="flex items-center gap-2 text-sm">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-gray-700">{{ $customer->email }}</span>
                                </div>
                                @endif

                                @if($customer->contact_number)
                                <div class="flex items-center gap-2 text-sm">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <span class="text-gray-700">{{ $customer->contact_number }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Payment Proof --}}
            @if($payment->payment_image)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Payment Proof</h4>

                <a href="{{ $payment->getImageUrlAttribute() }}" target="_blank" class="block">
                    <div
                        class="border border-gray-200 rounded-lg overflow-hidden bg-gray-50 hover:border-blue-400 transition cursor-pointer">
                        <img src="{{ $payment->getImageUrlAttribute() }}" alt="Payment Proof"
                            class="w-full h-auto max-h-80 object-contain">
                    </div>
                    <p class="mt-3 text-sm text-gray-600 text-center">
                        <span class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            Click to view full size
                        </span>
                    </p>
                </a>
            </div>
            @endif

            {{-- Action Buttons for Pending Payments --}}
            @if($payment->status === 'pending')
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                x-data="{ showApprove: false, showReject: false }">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Actions</h4>

                <div class="flex gap-3">
                    <button @click="showApprove = true"
                        class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Approve Payment
                    </button>

                    <button @click="showReject = true"
                        class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-rose-600 text-white font-semibold rounded-lg hover:bg-rose-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Reject Payment
                    </button>
                </div>

                {{-- Approve Modal --}}
                <div x-show="showApprove" x-cloak @click.self="showApprove=false"
                    class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
                    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
                        <form method="POST" action="{{ route('admin.payments.approve', $payment->id) }}">
                            @csrf

                            <div class="bg-emerald-50 border-b border-emerald-100 p-6 rounded-t-2xl">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-emerald-900">Approve Payment</h3>
                                            <p class="text-sm text-emerald-700">Confirm payment verification</p>
                                        </div>
                                    </div>
                                    <button type="button" @click="showApprove=false"
                                        class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 text-center">
                                    <div class="text-sm text-gray-600 mb-1">Payment Amount</div>
                                    <div class="text-2xl font-bold text-gray-900">₱{{ number_format($payment->amount, 2)
                                        }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ ucfirst($payment->payment_type) }}
                                        Payment</div>
                                </div>
                            </div>

                            <div class="bg-gray-50 border-t border-gray-200 p-6 rounded-b-2xl flex justify-end gap-3">
                                <button type="button" @click="showApprove=false"
                                    class="px-5 py-2.5 border border-gray-300 rounded-lg font-medium hover:bg-gray-100 transition">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="px-6 py-2.5 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition">
                                    Approve Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Reject Modal --}}
                <div x-show="showReject" x-cloak @click.self="showReject=false"
                    class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
                    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
                        <form method="POST" action="{{ route('admin.payments.reject', $payment->id) }}">
                            @csrf

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
                                            <h3 class="text-lg font-semibold text-rose-900">Reject Payment</h3>
                                            <p class="text-sm text-rose-700">Provide reason for rejection</p>
                                        </div>
                                    </div>
                                    <button type="button" @click="showReject=false"
                                        class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="p-6">
                                <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                    Rejection Reason <span class="text-rose-500">*</span>
                                </label>
                                <textarea id="rejection_reason" name="rejection_reason" rows="4" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-rose-500 focus:ring-2 focus:ring-rose-200"
                                    placeholder="E.g., Proof of payment is unclear, wrong amount, etc."></textarea>
                            </div>

                            <div class="bg-gray-50 border-t border-gray-200 p-6 rounded-b-2xl flex justify-end gap-3">
                                <button type="button" @click="showReject=false"
                                    class="px-5 py-2.5 border border-gray-300 rounded-lg font-medium hover:bg-gray-100 transition">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="px-6 py-2.5 bg-rose-600 text-white font-medium rounded-lg hover:bg-rose-700 transition">
                                    Reject Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>