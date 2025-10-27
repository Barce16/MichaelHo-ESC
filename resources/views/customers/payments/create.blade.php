<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">
                Submit
                @if($paymentType === 'introductory')
                Introductory Payment
                @elseif($paymentType === 'downpayment')
                Downpayment
                @else
                Balance Payment
                @endif
            </h2>
            <a href="{{ route('customer.events.show', $event) }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Event
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Event Summary Card --}}
            @php
            $headerColor = match($paymentType) {
            'introductory' => 'from-orange-500 to-red-600',
            'downpayment' => 'from-violet-500 to-purple-600',
            'balance' => 'from-emerald-500 to-teal-600',
            default => 'from-gray-500 to-slate-600',
            };

            $paymentLabel = match($paymentType) {
            'introductory' => 'Introductory Payment',
            'downpayment' => 'Downpayment',
            'balance' => 'Balance Payment',
            default => 'Payment',
            };
            @endphp

            <div class="bg-gradient-to-r {{ $headerColor }} rounded-xl shadow-lg p-8 text-white">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-sm font-semibold opacity-90 uppercase tracking-wide">
                                {{ $paymentLabel }}
                            </span>
                        </div>
                        <h3 class="text-2xl font-bold mb-1">{{ $event->name }}</h3>
                        <p class="opacity-90">{{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm opacity-90 mb-1">{{ $paymentType === 'balance' ? 'Remaining Balance' : 'Amount
                            Due' }}</p>
                        <p class="text-4xl font-bold">₱{{ number_format($amount, 2) }}</p>
                    </div>
                </div>
            </div>

            {{-- Payment Instructions --}}
            @if($paymentType === 'introductory')
            <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-sm text-blue-900">
                        <p class="font-semibold mb-1">About Introductory Payment</p>
                        <p>This ₱15,000 payment secures your event booking and allows us to schedule your planning
                            meeting. This amount will be deducted from your total downpayment later.</p>
                    </div>
                </div>
            </div>
            @elseif($paymentType === 'downpayment')
            <div class="bg-violet-50 border-l-4 border-violet-500 rounded-lg p-4">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-violet-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-sm text-violet-900">
                        <p class="font-semibold mb-1">About Downpayment</p>
                        <p>The ₱15,000 introductory payment has been deducted from your downpayment. You only need to
                            pay ₱{{ number_format($amount, 2) }} now to complete your downpayment and schedule your
                            event.</p>
                    </div>
                </div>
            </div>
            @elseif($paymentType === 'balance')
            <div class="bg-emerald-50 border-l-4 border-emerald-500 rounded-lg p-4">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-sm text-emerald-900">
                        <p class="font-semibold mb-1">Balance Payment</p>
                        <p>You can pay any amount towards your remaining balance of ₱{{ number_format($amount, 2) }}.
                            Enter the amount you wish to pay below (minimum ₱100).</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Payment Form --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Payment Information
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">Please provide your payment details and upload proof of
                        payment</p>
                </div>

                <form method="POST" action="{{ route('customer.payments.store', $event) }}"
                    enctype="multipart/form-data" class="p-6">
                    @csrf
                    <input type="hidden" name="payment_type" value="{{ $paymentType }}">

                    <div class="space-y-6">
                        {{-- Payment Amount --}}
                        <div>
                            <label for="amount" class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 36 36">
                                    <path d="M14.18,13.8V16h9.45a5.26,5.26,0,0,0,.08-.89,4.72,4.72,0,0,0-.2-1.31Z">
                                    </path>
                                    <path d="M14.18,19.7h5.19a4.28,4.28,0,0,0,3.5-1.9H14.18Z"></path>
                                    <path d="M19.37,10.51H14.18V12h8.37A4.21,4.21,0,0,0,19.37,10.51Z"></path>
                                    <path
                                        d="M17.67,2a16,16,0,1,0,16,16A16,16,0,0,0,17.67,2Zm10.5,15.8H25.7a6.87,6.87,0,0,1-6.33,4.4H14.18v6.54a1.25,1.25,0,1,1-2.5,0V17.8H8.76a.9.9,0,1,1,0-1.8h2.92V13.8H8.76a.9.9,0,1,1,0-1.8h2.92V9.26A1.25,1.25,0,0,1,12.93,8h6.44a6.84,6.84,0,0,1,6.15,4h2.65a.9.9,0,0,1,0,1.8H26.09a6.91,6.91,0,0,1,.12,1.3,6.8,6.8,0,0,1-.06.9h2a.9.9,0,0,1,0,1.8Z">
                                    </path>
                                </svg>
                                Payment Amount <span class="text-rose-500">*</span>
                            </label>

                            {{-- Pay in Full Option --}}
                            <div
                                class="mb-3 flex items-center gap-2 p-3 bg-emerald-50 border border-emerald-200 rounded-lg">
                                <input type="checkbox" id="pay_full"
                                    class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500"
                                    onchange="document.getElementById('amount').value = this.checked ? {{ $amount }} : ''; document.getElementById('amount').readOnly = this.checked;">
                                <label for="pay_full" class="text-sm font-medium text-emerald-900 cursor-pointer">
                                    Pay Full Balance (₱{{ number_format($amount, 2) }})
                                </label>
                            </div>

                            <div class="relative">
                                <span
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₱</span>
                                <input id="amount" name="amount" type="number" step="0.01"
                                    min="{{ $paymentType === 'balance' ? '100' : '0' }}"
                                    max="{{ $paymentType === 'balance' ? $amount : '' }}" value="{{ $amount }}" {{
                                    $paymentType !=='balance' ? 'readonly' : '' }}
                                    class="block w-full pl-10 pr-4 py-3 rounded-lg border-gray-300 {{ $paymentType !== 'balance' ? 'bg-gray-50 cursor-not-allowed' : 'focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200' }} text-lg font-semibold transition"
                                    required />
                            </div>
                            @if($paymentType === 'balance')
                            <p class="mt-1 text-xs text-gray-500">Enter amount between ₱100 and ₱{{
                                number_format($amount, 2) }} or check "Pay Full Balance" above</p>
                            @else
                            <p class="mt-1 text-xs text-gray-500">Amount is fixed for this payment type</p>
                            @endif
                            @error('amount')
                            <p class="mt-2 text-sm text-rose-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        {{-- Payment Method --}}
                        <div>
                            <label for="payment_method"
                                class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                Payment Method <span class="text-rose-500">*</span>
                            </label>
                            <select id="payment_method" name="payment_method"
                                class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
                                required>
                                <option value="">Select payment method</option>
                                <option value="bank_transfer" {{ old('payment_method')=='bank_transfer' ? 'selected'
                                    : '' }}>🏦 Bank Transfer</option>
                                <option value="bpi" {{ old('payment_method')=='bpi' ? 'selected' : '' }}>🏦 BPI</option>
                                <option value="gcash" {{ old('payment_method')=='gcash' ? 'selected' : '' }}>💳 GCash
                                </option>
                                <option value="cash" {{ old('payment_method')=='cash' ? 'selected' : '' }}>💵 Cash
                                </option>
                            </select>
                            @error('payment_method')
                            <p class="mt-2 text-sm text-rose-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        {{-- Reference Number --}}
                        <div>
                            <label for="reference_number"
                                class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                </svg>
                                Reference Number
                            </label>
                            <input id="reference_number" name="reference_number" type="text"
                                value="{{ old('reference_number') }}"
                                class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
                                placeholder="e.g., TXN123456789, REF-2024-001" />
                            <p class="mt-1 text-xs text-gray-500">Enter transaction or reference number if available
                                (GCash Ref No., Bank Ref, etc.)</p>
                            @error('reference_number')
                            <p class="mt-2 text-sm text-rose-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        {{-- File Upload --}}
                        <div>
                            <label for="payment_receipt"
                                class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Payment Proof / Receipt <span class="text-rose-500">*</span>
                            </label>
                            <div class="mt-2 flex justify-center px-6 pt-8 pb-8 border-2 border-gray-300 border-dashed rounded-lg hover:border-emerald-400 transition-colors cursor-pointer bg-gray-50 hover:bg-gray-100"
                                onclick="document.getElementById('payment_receipt').click()">
                                <div class="space-y-3 text-center">
                                    <svg class="mx-auto h-16 w-16 text-gray-400" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="text-sm text-gray-600">
                                        <label for="payment_receipt"
                                            class="relative cursor-pointer rounded-md font-semibold text-emerald-600 hover:text-emerald-500">
                                            <span>Click to upload</span>
                                        </label>
                                        <p class="mt-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 10MB</p>
                                </div>
                            </div>
                            <input id="payment_receipt" name="payment_receipt" type="file" class="hidden"
                                accept="image/png,image/jpeg,image/jpg" required onchange="previewImage(event)" />
                            @error('payment_receipt')
                            <p class="mt-2 text-sm text-rose-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        {{-- Image Preview --}}
                        <div id="image-preview-container" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Preview</label>
                            <div
                                class="relative rounded-lg overflow-hidden border-2 border-emerald-200 bg-emerald-50 p-4">
                                <img id="image-preview" class="max-w-full h-auto rounded-lg shadow-lg mx-auto" />
                                <button type="button" onclick="removeImage()"
                                    class="absolute top-6 right-6 bg-rose-500 text-white rounded-full p-2 hover:bg-rose-600 transition shadow-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="mt-8 flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('customer.events.show', $event) }}"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancel
                        </a>
                        <button type="submit"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold rounded-lg hover:from-emerald-600 hover:to-teal-700 transition shadow-md hover:shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
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