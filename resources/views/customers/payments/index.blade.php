<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Payment History</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gradient-to-br from-slate-500 to-gray-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-slate-100 uppercase tracking-wide">Total Paid</p>
                            <p class="text-3xl font-bold mt-1">
                                ₱{{ number_format($payments->where('status', 'approved')->sum('amount'), 2) }}
                            </p>
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
                            <p class="text-3xl font-bold text-amber-600 mt-1">
                                {{ $payments->where('status', 'pending')->count() }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Payments</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1">
                                {{ $payments->count() }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payment History Table --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Payment History
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Event</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Amount</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Method</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Reference</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Date</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($payments as $payment)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $payment->billing->event->name }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $payment->billing->event->venue ?? '—' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">₱{{ number_format($payment->amount, 2)
                                        }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2 text-sm text-gray-700">
                                        @if($payment->payment_method === 'gcash')
                                        <span>💳</span>
                                        @elseif($payment->payment_method === 'bank_transfer')
                                        <span>🏦</span>
                                        @else
                                        <span>💵</span>
                                        @endif
                                        {{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{
                                    $payment->reference_number ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
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
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} {{ $statusConfig['border'] }} border">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $statusConfig['dot'] }}"></span>
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    @if($payment->payment_image)
                                    <div class="flex flex-col items-end gap-2">
                                        <button
                                            onclick="openImageModal('{{ asset('storage/' . $payment->payment_image) }}')"
                                            class="inline-flex items-center gap-1 text-emerald-600 hover:text-emerald-800 font-medium transition">

                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View Proof of Payment
                                        </button>

                                        @if($payment->status === 'approved')
                                        @if($payment->hasReceiptCreated())
                                        {{-- Status 2: Receipt ready - show download button --}}
                                        <a href="{{ route('customer.payments.download-receipt', $payment) }}"
                                            target="_blank"
                                            class="inline-flex items-center gap-1 text-rose-600 hover:text-rose-800 font-medium transition text-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            Download Receipt
                                        </a>
                                        @elseif($payment->hasReceiptRequested())
                                        {{-- Status 1: Receipt requested - show pending status --}}
                                        <span class="inline-flex items-center gap-1 text-sm text-amber-600 font-medium">
                                            <svg class="w-3.5 h-3.5 animate-pulse" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Receipt Requested
                                        </span>
                                        @else
                                        {{-- Status 0: Not requested - show request button --}}
                                        <form id="receipt-form-{{ $payment->id }}"
                                            action="{{ route('customer.payments.request-receipt', $payment) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            <button type="button"
                                                onclick="openReceiptModal({{ $payment->id }}, '{{ $payment->getTypeLabel() }}', {{ number_format($payment->amount, 2, '.', '') }})"
                                                class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 font-medium transition text-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                Request Receipt
                                            </button>
                                        </form>
                                        @endif
                                        @endif
                                    </div>
                                    @else
                                    <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-gray-500 font-medium">No payment history available</p>
                                    <p class="text-gray-400 text-sm mt-1">Your payment records will appear here</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Image Modal --}}
    <div id="imageModal" class="fixed inset-0 z-50 hidden overflow-hidden">
        {{-- Backdrop --}}
        <div onclick="closeImageModal()"
            class="fixed inset-0 bg-black bg-opacity-75 backdrop-blur-sm transition-opacity"></div>

        {{-- Modal Content --}}
        <div class="fixed inset-0 flex items-center justify-center p-4 sm:p-6 lg:p-8">
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-5xl w-full max-h-[90vh] overflow-hidden">

                {{-- Close Button --}}
                <button onclick="closeImageModal()"
                    class="absolute top-4 right-4 z-10 bg-white/90 hover:bg-white text-gray-700 hover:text-gray-900 rounded-full p-2 shadow-lg transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                {{-- Header --}}
                <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Payment Proof
                    </h3>
                </div>

                {{-- Image Container --}}
                <div class="overflow-auto max-h-[calc(90vh-80px)] bg-gray-50 p-6">
                    <div class="flex items-center justify-center">
                        <img id="modalImage" alt="Payment Proof" class="max-w-full h-auto rounded-lg shadow-lg">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Receipt Request Confirmation Modal --}}
    <div id="receiptModal" class="fixed inset-0 z-50 hidden overflow-hidden">
        {{-- Backdrop --}}
        <div onclick="closeReceiptModal()"
            class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>

        {{-- Modal Content --}}
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all">

                {{-- Icon --}}
                <div class="flex items-center justify-center pt-8 pb-4">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>

                {{-- Content --}}
                <div class="px-8 pb-6 text-center">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Request Official Receipt?</h3>
                    <p class="text-gray-600 mb-1">You are requesting a receipt for:</p>
                    <p class="text-sm font-semibold text-gray-900 mb-1" id="receiptPaymentType"></p>
                    <p class="text-2xl font-bold text-blue-600 mb-4" id="receiptAmount"></p>
                    <p class="text-xs text-gray-500">The admin will be notified and will process your receipt request.
                    </p>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3 px-8 pb-8">
                    <button onclick="closeReceiptModal()"
                        class="flex-1 px-4 py-3 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                        Cancel
                    </button>
                    <button onclick="submitReceiptRequest()"
                        class="flex-1 px-4 py-3 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
                        Confirm Request
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openImageModal(imageUrl) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            modalImage.src = imageUrl;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        let currentFormId = null;

        function openReceiptModal(paymentId, paymentType, amount) {
            currentFormId = paymentId;
            document.getElementById('receiptPaymentType').textContent = paymentType;
            document.getElementById('receiptAmount').textContent = '₱' + parseFloat(amount).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            const modal = document.getElementById('receiptModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeReceiptModal() {
            const modal = document.getElementById('receiptModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            currentFormId = null;
        }

        function submitReceiptRequest() {
            if (currentFormId) {
                document.getElementById('receipt-form-' + currentFormId).submit();
            }
        }

        // Close modal on ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeImageModal();
                closeReceiptModal();
            }
        });
    </script>
</x-app-layout>