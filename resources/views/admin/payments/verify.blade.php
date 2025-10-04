<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800">Payment Verification</h2>
                <p class="text-sm text-gray-600 mt-1">{{ $event->name }}</p>
            </div>
            <a href="{{ route('admin.events.show', $event) }}"
                class="text-sm text-gray-600 hover:text-gray-900 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Event
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Status Banner --}}
            <div
                class="bg-gradient-to-r from-amber-50 to-yellow-50 border-l-4 border-amber-500 rounded-lg shadow-sm p-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-amber-900">Payment Awaiting Verification</h3>
                        <p class="text-sm text-amber-800 mt-0.5">Please review the payment details and approve or reject
                            this submission.</p>
                    </div>
                </div>
            </div>

            {{-- Payment Details Card --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
                {{-- Header --}}
                <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-8 py-6">
                    <h3 class="text-xl font-bold text-white flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Payment Details
                    </h3>
                </div>

                {{-- Content --}}
                <div class="p-8">
                    <div class="grid md:grid-cols-2 gap-8">
                        {{-- Payment Receipt Image --}}
                        <div class="space-y-3">
                            <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wide">Payment
                                Receipt</label>
                            <div class="relative group cursor-pointer"
                                onclick="openImageModal('{{ Storage::url($payment->payment_image) }}')">
                                <img src="{{ Storage::url($payment->payment_image) }}" alt="Payment Proof"
                                    class="w-full h-96 object-contain rounded-xl border-2 border-gray-200 bg-gray-50 shadow-md group-hover:shadow-xl transition-shadow">
                                <div
                                    class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors rounded-xl flex items-center justify-center">
                                    <div
                                        class="bg-white/90 rounded-full p-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg class="w-8 h-8 text-gray-800" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 text-center">Click image to enlarge</p>
                        </div>

                        {{-- Payment Information --}}
                        <div class="space-y-6">
                            {{-- Amount --}}
                            <div
                                class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-6 border-2 border-green-200">
                                <label
                                    class="block text-xs font-semibold text-green-700 uppercase tracking-wide mb-2">Payment
                                    Amount</label>
                                <div class="text-4xl font-bold text-green-700">₱{{ number_format($payment->amount, 2) }}
                                </div>
                            </div>

                            {{-- Payment Method --}}
                            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                                <label
                                    class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">Payment
                                    Method</label>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                    </div>
                                    <div class="text-lg font-semibold text-gray-900">
                                        {{ ucwords(str_replace('_', ' ', strtolower($payment->payment_method))) }}
                                    </div>
                                </div>
                            </div>

                            {{-- Submission Date --}}
                            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                                <label
                                    class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">Submitted
                                    On</label>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="text-lg font-semibold text-gray-900">
                                        {{ $payment->created_at->format('M d, Y g:i A') }}
                                    </div>
                                </div>
                            </div>

                            {{-- Customer Info --}}
                            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                                <label
                                    class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">Customer</label>
                                <div class="flex items-center gap-3">
                                    @php
                                    $avatar = optional(optional($event->customer)->user)->profile_photo_url
                                    ?? 'https://ui-avatars.com/api/?name=' . urlencode($event->customer->customer_name
                                    ?? 'Unknown') . '&background=E5E7EB&color=111827';
                                    @endphp
                                    <img src="{{ $avatar }}"
                                        class="h-10 w-10 rounded-full object-cover ring-2 ring-gray-200" alt="Avatar">
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $event->customer->customer_name }}
                                        </div>
                                        <div class="text-sm text-gray-600">{{ $event->customer->email }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="bg-gray-50 px-8 py-6 border-t border-gray-200">
                    <div class="flex justify-end gap-4">
                        <form method="POST" action="{{ route('admin.payments.reject', $event->id) }}">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-8 py-3 bg-white border-2 border-red-300 text-red-700 font-semibold rounded-lg hover:bg-red-50 hover:border-red-400 transition shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Reject Payment
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.eventPayments.approve', $event->id) }}">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-lg hover:from-green-700 hover:to-emerald-700 transition shadow-md hover:shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Approve Payment
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Image Modal --}}
    <div id="imageModal" class="hidden fixed inset-0 bg-black/95 z-50 flex items-center justify-center p-4">
        <div class="relative max-w-7xl w-full h-full flex items-center justify-center">
            <button onclick="closeImageModal()"
                class="absolute top-4 right-4 text-white hover:text-gray-300 transition z-10 bg-black/50 rounded-full p-2">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <img id="modalImage" src="" alt="Payment Receipt"
                class="max-w-full max-h-full object-contain rounded-lg shadow-2xl" onclick="event.stopPropagation()">
        </div>
        <div class="absolute inset-0" onclick="closeImageModal()"></div>
    </div>

    <script>
        function openImageModal(src) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
</x-app-layout>