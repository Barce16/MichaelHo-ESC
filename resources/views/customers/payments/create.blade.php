<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Payment for Event: {{ $event->name }}</h2>
            <a href="{{ route('customer.events.index') }}" class="px-3 py-2 bg-gray-800 text-white rounded">Back to
                Events</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Payment Form --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                {{-- Header --}}
                <div class="bg-gradient-to-r from-emerald-600 to-green-600 px-8 py-6">
                    <h3 class="text-2xl font-bold text-white">Submit Payment Proof</h3>
                    <p class="text-emerald-100 text-sm mt-1">Upload your payment receipt for verification</p>
                </div>

                {{-- Event & Billing Info --}}
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 px-8 py-6 border-b border-gray-200">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="flex items-start gap-3">
                            <div
                                class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Event</p>
                                <p class="text-lg font-bold text-gray-900">{{ $event->name }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div
                                class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 36 36"
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
                            <div>
                                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Downpayment Due
                                    Amount
                                </p>
                                <p class="text-2xl font-bold text-green-600">
                                    @if ($event->billing)
                                    ₱{{ number_format($event->billing->downpayment_amount, 2) }}
                                    @else
                                    ₱0.00
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('customer.payments.store', $event) }}"
                    enctype="multipart/form-data" class="px-8 py-8">
                    @csrf

                    <div class="space-y-6">
                        {{-- Payment Amount --}}
                        <div>
                            <label for="amount" class="block text-sm font-semibold text-gray-700 mb-2">
                                Amount Paid <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₱</span>
                                <input id="amount" name="amount" type="number" step="0.01" min="0"
                                    value="{{ old('amount', $event->billing->downpayment_amount ?? 0) }}"
                                    class="block w-full pl-8 pr-4 py-3 rounded-lg border-2 border-gray-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition bg-white text-lg font-semibold"
                                    required />
                            </div>
                            @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Payment Method --}}
                        <div>
                            <label for="payment_method" class="block text-sm font-semibold text-gray-700 mb-2">
                                Payment Method <span class="text-red-500">*</span>
                            </label>
                            <select id="payment_method" name="payment_method"
                                class="block w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition bg-white"
                                required>
                                <option value="">Select payment method</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="gcash">GCash</option>
                                <option value="paymaya">PayMaya</option>
                                <option value="physical_payment">Physical Payment (Cash)</option>
                            </select>
                            @error('payment_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- File Upload --}}
                        <div>
                            <label for="payment_receipt" class="block text-sm font-semibold text-gray-700 mb-2">
                                Payment Receipt / Proof <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-emerald-400 transition-colors cursor-pointer bg-gray-50"
                                onclick="document.getElementById('payment_receipt').click()">
                                <div class="space-y-2 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="text-sm text-gray-600">
                                        <label for="payment_receipt"
                                            class="relative cursor-pointer rounded-md font-medium text-emerald-600 hover:text-emerald-500">
                                            <span>Upload a file</span>
                                        </label>
                                        <p class="pl-1 inline">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 10MB</p>
                                </div>
                            </div>
                            <input id="payment_receipt" name="payment_receipt" type="file" class="hidden"
                                accept="image/png,image/jpeg,image/jpg" required onchange="previewImage(event)" />
                            @error('payment_receipt')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Image Preview --}}
                        <div id="image-preview-container" class="hidden">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Preview</label>
                            <div class="relative rounded-lg overflow-hidden border-2 border-gray-200 bg-gray-50 p-4">
                                <img id="image-preview" class="max-w-full h-auto rounded-lg shadow-md mx-auto" />
                                <button type="button" onclick="removeImage()"
                                    class="absolute top-6 right-6 bg-red-500 text-white rounded-full p-2 hover:bg-red-600 transition shadow-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="mt-8 flex gap-4">
                        <a href="{{ route('customer.events.show', $event) }}"
                            class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 text-center font-semibold rounded-lg hover:bg-gray-300 transition">
                            Cancel
                        </a>
                        <button type="submit"
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-emerald-600 to-green-600 text-white font-semibold rounded-lg hover:from-emerald-700 hover:to-green-700 transition shadow-md hover:shadow-lg">
                            Submit Payment Proof
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <script>
        function previewImage(event) {
        const previewContainer = document.getElementById('image-preview-container');
        const imagePreview = document.getElementById('image-preview');
        
        const file = event.target.files[0];
        if (!file) {
            previewContainer.classList.add('hidden');
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            previewContainer.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }

    function removeImage() {
        const fileInput = document.getElementById('payment_receipt');
        const previewContainer = document.getElementById('image-preview-container');
        
        fileInput.value = '';
        previewContainer.classList.add('hidden');
    }
    </script>
</x-app-layout>