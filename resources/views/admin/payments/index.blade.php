<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Payment Management</h2>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-sm font-medium">
                    {{ $payments->total() }} Total
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if($payments->isEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Payments Yet</h3>
                <p class="text-gray-500">Payment submissions will appear here</p>
            </div>
            @else

            @foreach($payments as $payment)
            @php
            $event = $payment->billing->event;
            $customer = $event->customer;

            $typeColors = [
            'introductory' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-700', 'border' => 'border-orange-200',
            'badge' => 'bg-orange-100'],
            'downpayment' => ['bg' => 'bg-violet-50', 'text' => 'text-violet-700', 'border' => 'border-violet-200',
            'badge' => 'bg-violet-100'],
            'balance' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200',
            'badge' => 'bg-emerald-100'],
            ];

            $statusColors = [
            'pending' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200'],
            'approved' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200'],
            'rejected' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'border' => 'border-rose-200'],
            ];

            $typeConfig = $typeColors[$payment->payment_type] ?? $typeColors['balance'];
            $statusConfig = $statusColors[$payment->status] ?? $statusColors['pending'];
            @endphp

            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                <!-- Header -->
                <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <a href="{{ route('admin.events.show', $event) }}"
                                    class="text-lg font-semibold text-gray-900 hover:text-blue-600 transition">
                                    {{ $event->name }}
                                </a>
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold {{ $typeConfig['badge'] }} {{ $typeConfig['text'] }}">
                                    {{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}
                                </span>
                            </div>
                            <div class="flex items-center gap-4 text-sm text-gray-600">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>{{ $customer->customer_name }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>{{ $event->event_date->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <span
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-semibold border {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} {{ $statusConfig['border'] }}">
                                <span class="w-2 h-2 rounded-full bg-current"></span>
                                {{ ucfirst($payment->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <div class="grid md:grid-cols-3 gap-6">
                        <!-- Payment Details -->
                        <div class="md:col-span-2 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <div class="text-xs text-gray-500 uppercase tracking-wider mb-1">Amount</div>
                                    <div class="text-2xl font-bold text-gray-900">₱{{ number_format($payment->amount, 2)
                                        }}</div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 uppercase tracking-wider mb-1">Payment Method
                                    </div>
                                    <div class="text-base font-semibold text-gray-700">{{ ucfirst(str_replace('_', ' ',
                                        $payment->payment_method)) }}</div>
                                </div>
                            </div>

                            <div>
                                <div class="text-xs text-gray-500 uppercase tracking-wider mb-1">Submitted</div>
                                <div class="text-sm text-gray-700">{{ $payment->created_at->format('M d, Y h:i A') }}
                                    ({{ $payment->created_at->diffForHumans() }})</div>
                            </div>

                            @if($payment->status === 'rejected' && $payment->rejection_reason)
                            <div class="bg-rose-50 border border-rose-200 rounded-lg p-3">
                                <div class="text-xs text-rose-700 font-semibold uppercase tracking-wider mb-1">Rejection
                                    Reason</div>
                                <div class="text-sm text-rose-800">{{ $payment->rejection_reason }}</div>
                            </div>
                            @endif

                            @if($payment->status === 'pending')
                            <div class="flex gap-3 pt-2">
                                @if($payment->payment_type === 'introductory')
                                <form action="{{ route('admin.events.approveIntroPayment', $event) }}" method="POST"
                                    class="flex-1">
                                    @csrf
                                    <button type="submit"
                                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Approve Intro
                                    </button>
                                </form>

                                @elseif($payment->payment_type === 'downpayment')
                                <form action="{{ route('admin.events.approveDownpayment', $event) }}" method="POST"
                                    class="flex-1">
                                    @csrf
                                    <button type="submit"
                                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Approve Downpayment
                                    </button>
                                </form>

                                @elseif($payment->payment_type === 'balance')
                                <button type="button"
                                    onclick="openApproveBalanceModal({{ $payment->id }}, {{ $event->id }})"
                                    class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Approve Balance
                                </button>
                                @endif

                                <button type="button" onclick="openRejectModal({{ $payment->id }})"
                                    class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-rose-600 text-white font-medium rounded-lg hover:bg-rose-700 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Reject
                                </button>
                            </div>
                            @else
                            <div class="pt-2">
                                <a href="{{ route('admin.events.show', $event) }}"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View Event Details
                                </a>
                            </div>
                            @endif
                        </div>

                        <!-- Payment Proof -->
                        <div>
                            <div class="text-xs text-gray-500 uppercase tracking-wider mb-2">Payment Proof</div>
                            @if($payment->payment_image)
                            <div class="relative group">
                                <img src="{{ Storage::url($payment->payment_image) }}" alt="Payment Proof"
                                    class="w-full h-48 object-cover rounded-lg shadow-sm cursor-pointer border-2 border-gray-200 group-hover:border-blue-400 transition"
                                    onclick="openModal('{{ Storage::url($payment->payment_image) }}')">
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 rounded-lg transition flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 text-center">Click to view full size</p>
                            @else
                            <div class="w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center">
                                <span class="text-gray-400 text-sm">No image</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Pagination -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                {{ $payments->links() }}
            </div>

            @endif
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 flex justify-center items-center hidden z-50"
        onclick="closeModal()">
        <div class="relative max-w-5xl max-h-screen p-4" onclick="event.stopPropagation()">
            <button onclick="closeModal()"
                class="absolute -top-2 -right-2 bg-white rounded-full p-2 shadow-lg hover:bg-gray-100 transition z-10">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <img id="modalImage" src="" alt="Payment Proof"
                class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl">
        </div>
    </div>

    <!-- Reject Payment Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4" onclick="event.stopPropagation()">
            <div class="bg-rose-600 text-white px-6 py-4 rounded-t-xl">
                <h3 class="text-lg font-bold">Reject Payment</h3>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="p-6">
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Rejection Reason <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="rejection_reason" name="rejection_reason" rows="4"
                        class="block w-full rounded-lg border-gray-300 focus:border-rose-500 focus:ring-2 focus:ring-rose-200"
                        placeholder="Please explain why this payment is being rejected..." required></textarea>
                </div>
                <div class="bg-gray-50 px-6 py-4 rounded-b-xl flex gap-3">
                    <button type="button" onclick="closeRejectModal()"
                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-rose-600 text-white font-medium rounded-lg hover:bg-rose-700 transition">
                        Reject Payment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Approve Balance Payment Modal -->
    <div id="approveBalanceModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4" onclick="event.stopPropagation()">
            <div class="bg-emerald-600 text-white px-6 py-4 rounded-t-xl">
                <h3 class="text-lg font-bold">Approve Balance Payment</h3>
            </div>
            <form id="approveBalanceForm" method="POST">
                @csrf
                <div class="p-6">
                    <p class="text-gray-700 mb-4">Are you sure you want to approve this balance payment?</p>
                    <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                        <div class="text-sm text-emerald-800">
                            <strong>Note:</strong> This will update the remaining balance and payment history.
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 rounded-b-xl flex gap-3">
                    <button type="button" onclick="closeApproveBalanceModal()"
                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition">
                        Approve Payment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(imageUrl) {
        document.getElementById('imageModal').classList.remove('hidden');
        document.getElementById('modalImage').src = imageUrl;
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function openRejectModal(paymentId) {
        const form = document.getElementById('rejectForm');
        form.action = `/admin/payments/${paymentId}/reject`;
        document.getElementById('rejectModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.getElementById('rejection_reason').value = '';
        document.body.style.overflow = 'auto';
    }

    function openApproveBalanceModal(paymentId, eventId) {
        const form = document.getElementById('approveBalanceForm');
        form.action = `/admin/payments/${paymentId}/approve`;
        document.getElementById('approveBalanceModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeApproveBalanceModal() {
        document.getElementById('approveBalanceModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modals with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
            closeRejectModal();
            closeApproveBalanceModal();
        }
    });
    </script>
</x-app-layout>