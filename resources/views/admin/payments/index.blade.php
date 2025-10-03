<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Payments</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @foreach($payments as $payment)
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="font-semibold text-xl">{{ $payment->billing->event->name }} ({{
                    $payment->billing->event->event_date
                    }})</h3>

                <div class="mt-4">
                    <strong class="text-gray-700">Amount Paid:</strong>
                    ₱{{ number_format($payment->amount, 2) }}
                </div>

                <div class="mt-2">
                    <strong class="text-gray-700">Payment Method:</strong>
                    {{ ucfirst($payment->payment_method) }}
                </div>

                <div class="mt-4">
                    <strong class="text-gray-700">Payment Proof:</strong>
                    <div class="mt-2">
                        <!-- Image click to open modal -->
                        <img src="{{ Storage::url($payment->payment_image) }}" alt="Payment Proof"
                            class="w-64 h-48 object-cover rounded-lg shadow-md cursor-pointer"
                            onclick="openModal('{{ Storage::url($payment->payment_image) }}')">
                    </div>
                </div>

                <div class="mt-4 flex justify-end gap-5">
                    <form action="{{ route('admin.payments.approve', $payment->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-400">
                            Approve Payment
                        </button>
                    </form>

                    <form action="{{ route('admin.payments.reject', $payment->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-500">
                            Reject Payment
                        </button>
                    </form>
                </div>
            </div>
            @endforeach

            @if($payments->isEmpty())
            <div class="bg-white p-4 rounded-lg shadow-md">
                <p class="text-center text-gray-500">No pending payments.</p>
            </div>
            @endif

        </div>
    </div>

    <!-- Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-md max-w-lg w-full" onclick="event.stopPropagation()">
            <span class="absolute top-2 right-2 text-gray-600 cursor-pointer" onclick="closeModal()">×</span>
            <img id="modalImage" src="" alt="Payment Proof" class="w-full h-auto object-cover rounded-lg">
        </div>
    </div>

    <script>
        function openModal(imageUrl) {
            document.getElementById('imageModal').classList.remove('hidden');
            document.getElementById('modalImage').src = imageUrl;
        }

        function closeModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }

        // Close modal when clicking outside of the modal content
        document.getElementById('imageModal').addEventListener('click', function(event) {
            if (event.target === document.getElementById('imageModal')) {
                closeModal();
            }
        });
    </script>
</x-app-layout>