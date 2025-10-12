<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Book {{ $package->name }} - Michael Ho Events</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Caslon+Display&family=Style+Script&display=swap"
        rel="stylesheet">
    <style>
        .font-libre {
            font-family: 'Libre Caslon Display', serif;
        }

        .font-style-script {
            font-family: 'Style Script', cursive;
        }

        body {
            background-color: #b3bac19f;
            position: relative;
            min-height: 100vh;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset("images/background.png") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.1;
            z-index: -1;
        }

        input[type="radio"] {
            accent-color: #1f2937;
        }

        /* Smooth step transitions */
        #step-1,
        #step-2 {
            will-change: opacity, transform;
        }

        /* Prevent layout shift during transitions */
        .min-h-screen {
            overflow-x: hidden;
        }

        /* Custom Checkbox Styling */
        .inclusion-card {
            position: relative;
        }

        .inclusion-checkbox:checked~div {
            background-color: #334155;
            border-color: #334155;
        }

        .inclusion-checkbox:checked~div svg {
            opacity: 1;
        }

        .inclusion-card:has(.inclusion-checkbox:checked) {
            background-color: #f8fafc;
            border-color: #475569;
        }

        /* Line clamp for long notes */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>

<body>
    {{-- Toasts --}}
    @if (session('success'))
    <x-toast type="success" :message="session('success')" />
    @endif
    @if (session('error'))
    <x-toast type="error" :message="session('error')" />
    @endif

    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <!-- Decorative elements -->
        <div
            class="absolute top-0 left-0 w-64 h-64 bg-gradient-to-br from-slate-100 to-transparent rounded-full blur-3xl opacity-40">
        </div>
        <div
            class="absolute bottom-0 right-0 w-96 h-96 bg-gradient-to-tl from-slate-100 to-transparent rounded-full blur-3xl opacity-40">
        </div>

        <div class="max-w-4xl mx-auto z-10">
            <!-- Header -->
            <div class="text-center mb-8">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('images/favicon.png') }}" alt="Logo" class="h-16 mx-auto mb-4">
                </a>
                <h1 class="text-3xl font-bold font-libre mb-2">Book {{ $package->name }}</h1>
                <div class="text-gray-600">
                    <p class="text-sm">Base Package Price</p>
                    <p class="text-xl font-semibold">₱{{ number_format($package->price, 2) }}</p>
                </div>
            </div>

            <form action="{{ route('book.store', $package) }}" method="POST" id="booking-form" class="space-y-8">
                @csrf

                <!-- Step 1: Event Details & Inclusions -->
                <div id="step-1"
                    class="bg-white/90 backdrop-blur-sm rounded-lg shadow-md p-8 space-y-6 border border-gray-100 overflow-hidden">
                    <h2 class="text-2xl font-bold font-libre mb-6">Event Details</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="event_name" class="block text-sm font-semibold text-gray-700 mb-2">Event Name
                                *</label>
                            <input type="text" name="event_name" id="event_name" required
                                value="{{ old('event_name') }}"
                                class="block w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition bg-white">
                            @error('event_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Event Date <span class="text-rose-500">*</span>
                            </label>

                            <x-calendar-picker name="event_date" :value="old('event_date')" required />

                            @error('event_date')
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

                        <div>
                            <label for="venue" class="block text-sm font-semibold text-gray-700 mb-2">Venue *</label>
                            <input type="text" name="venue" id="venue" required value="{{ old('venue') }}"
                                class="block w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition bg-white">
                            @error('venue')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="theme" class="block text-sm font-semibold text-gray-700 mb-2">Theme</label>
                            <input type="text" name="theme" id="theme" value="{{ old('theme') }}"
                                class="block w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition bg-white">
                        </div>
                    </div>

                    <!-- Coordination & Event Styling -->
                    <div class="mt-8 bg-white rounded-lg p-6 border-2 border-gray-200 shadow-sm">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900">Package Services</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if($package->coordination)
                            <div class="border-l-4 border-slate-500 bg-slate-50 rounded-r-lg p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-semibold text-gray-900">Coordination</h4>
                                    <span class="text-sm font-bold text-slate-700">₱{{
                                        number_format($package->coordination_price, 2) }}</span>
                                </div>
                                <p class="text-sm text-gray-700">{{ $package->coordination }}</p>
                            </div>
                            @endif

                            @if($package->event_styling && count($package->event_styling) > 0)
                            <div class="border-l-4 border-slate-500 bg-slate-50 rounded-r-lg p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-semibold text-gray-900">Event Styling</h4>
                                    <span class="text-sm font-bold text-slate-700">₱{{
                                        number_format($package->event_styling_price, 2) }}</span>
                                </div>
                                <ul class="text-sm text-gray-700 space-y-1">
                                    @foreach($package->event_styling as $styling)
                                    <li>• {{ $styling }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Inclusions by Category -->
                    @if($allInclusions->isNotEmpty())
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-3">Inclusions</h3>
                        <p class="text-sm text-gray-600 mb-4">Select the services you'd like to include in your package
                        </p>

                        @foreach($allInclusions as $categoryName => $categoryInclusions)
                        <div class="mb-6">
                            <h4 class="text-md font-semibold text-gray-800 mb-3 pb-2 border-b flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                {{ $categoryName }}
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($categoryInclusions as $inclusion)
                                @php
                                $isInPackage = $package->inclusions->contains('id', $inclusion->id);
                                $categorySlug = Str::slug($categoryName);
                                @endphp

                                <label
                                    class="inclusion-card group flex items-start gap-3 p-4 rounded-lg border-2 border-gray-200 hover:border-slate-400 hover:bg-slate-50 cursor-pointer transition-all bg-white relative"
                                    data-price="{{ $inclusion->price }}" data-category="{{ $categorySlug }}">

                                    <!-- Custom Checkbox -->
                                    <div class="relative flex items-center justify-center flex-shrink-0">
                                        <input type="checkbox" name="inclusions[]" value="{{ $inclusion->id }}" {{
                                            $isInPackage || collect(old('inclusions', []))->contains($inclusion->id) ?
                                        'checked' : '' }}
                                        class="inclusion-checkbox peer sr-only"
                                        onchange="updateTotal()">

                                        <!-- Checkbox Background -->
                                        <div
                                            class="w-6 h-6 border-2 border-gray-300 rounded-md bg-white peer-checked:bg-slate-700 peer-checked:border-slate-700 transition-all duration-200 flex items-center justify-center group-hover:border-slate-400">
                                            <!-- Checkmark Icon -->
                                            <svg class="w-4 h-4 text-white opacity-0 peer-checked:opacity-100 transition-opacity duration-200"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </div>

                                    @if($inclusion->image)
                                    <div
                                        class="w-16 h-16 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100 border border-gray-200 group-hover:border-slate-300 transition-all">
                                        <img src="{{ $inclusion->image_url }}" alt="{{ $inclusion->name }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                    </div>
                                    @endif

                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-start gap-2">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span
                                                    class="font-semibold text-gray-900 group-hover:text-slate-900 transition-colors">
                                                    {{ $inclusion->name }}
                                                </span>
                                                @if($isInPackage)
                                                <span
                                                    class="inline-flex items-center gap-1 text-xs bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full border border-emerald-200">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    In Package
                                                </span>
                                                @endif
                                            </div>
                                            <span class="text-sm font-bold text-slate-600 whitespace-nowrap">
                                                ₱{{ number_format($inclusion->price, 2) }}
                                            </span>
                                        </div>

                                        @if($inclusion->notes)
                                        <p
                                            class="text-sm text-gray-600 mt-2 leading-relaxed line-clamp-2 group-hover:text-gray-700 transition-colors">
                                            {{ $inclusion->notes }}
                                        </p>
                                        @endif
                                    </div>

                                    <!-- Selected Indicator Overlay -->
                                    <div
                                        class="absolute inset-0 rounded-lg border-2 border-slate-600 opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity duration-200">
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Total Price Display -->
                    <div
                        class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pt-6 border-t-2 border-gray-200">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Total Package Price</p>
                            <p id="total-price" class="text-3xl font-bold text-gray-900">₱{{
                                number_format($package->coordination_price + $package->event_styling_price, 2) }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                <span id="category-count">0</span> inclusion<span id="plural-s">s</span> selected
                            </p>
                        </div>
                        <button type="button" onclick="validateAndShowStep2()"
                            class="px-8 py-4 bg-slate-600 text-white font-semibold rounded-lg hover:bg-slate-700 transition shadow-lg whitespace-nowrap">
                            Continue to Contact Details →
                        </button>
                    </div>
                    @endif
                </div>

                <!-- Step 2: Customer Information -->
                <div id="step-2"
                    class="bg-white/90 backdrop-blur-sm rounded-lg shadow-md p-8 space-y-6 hidden border border-gray-100 overflow-hidden">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold font-libre">Your Contact Information</h2>
                        <button type="button" onclick="showStep1()"
                            class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                            ← Back to Event Details
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="customer_name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name
                                *</label>
                            <input type="text" name="customer_name" id="customer_name" required
                                value="{{ old('customer_name') }}"
                                class="block w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition bg-white">
                            @error('customer_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                            <input type="email" name="email" id="email" required value="{{ old('email') }}"
                                class="block w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition bg-white">
                            @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone Number
                                *</label>
                            <input type="tel" name="phone" id="phone" required value="{{ old('phone') }}"
                                placeholder="+63 917 123 4567"
                                class="block w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition bg-white">
                            @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
                            <input type="text" name="address" id="address" value="{{ old('address') }}"
                                class="block w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition bg-white">
                        </div>
                    </div>

                    <div>
                        <label for="budget" class="block text-sm font-semibold text-gray-700 mb-2">Budget</label>
                        <input type="number" name="budget" id="budget" step="0.01" min="0" value="{{ old('budget') }}"
                            class="block w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition bg-white">
                    </div>

                    <div>
                        <label for="guests" class="block text-sm font-semibold text-gray-700 mb-2">Guest Details</label>
                        <textarea name="guests" id="guests" rows="4"
                            class="block w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition bg-white"
                            placeholder="Enter guest count, names, or special requirements...">{{ old('guests') }}</textarea>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">Additional
                            Notes</label>
                        <textarea name="notes" id="notes" rows="4"
                            class="block w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition bg-white"
                            placeholder="Any special requests or requirements...">{{ old('notes') }}</textarea>
                    </div>

                    <div class="flex justify-between items-center pt-6 border-t-2">
                        <p class="text-sm text-gray-600">
                            By submitting, you agree to be contacted regarding your booking.
                        </p>
                        <button type="submit"
                            class="px-8 py-4 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition shadow-lg">
                            Submit Booking Request
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Base prices from package
    const coordinationPrice = {{ $package->coordination_price ?? 0 }};
    const stylingPrice = {{ $package->event_styling_price ?? 0 }};
    const baseTotal = coordinationPrice + stylingPrice;
    const totalCategories = {{ count($allInclusions) }};

    // Update total price based on selected inclusions
    function updateTotal() {
        const checkboxes = document.querySelectorAll('.inclusion-checkbox:checked');
        let inclusionsTotal = 0;
        let count = 0;
        
        checkboxes.forEach(checkbox => {
            const card = checkbox.closest('.inclusion-card');
            const price = parseFloat(card.dataset.price) || 0;
            inclusionsTotal += price;
            count++;
        });
        
        const total = baseTotal + inclusionsTotal;
        
        // Update display with animation
        const priceElement = document.getElementById('total-price');
        priceElement.style.transform = 'scale(1.05)';
        priceElement.textContent = '₱' + total.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        
        setTimeout(() => {
            priceElement.style.transform = 'scale(1)';
        }, 200);
        
        // Update count
        document.getElementById('category-count').textContent = count;
        document.getElementById('plural-s').textContent = count === 1 ? '' : 's';
    }

    // Initialize total on page load
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('total-price').style.transition = 'transform 0.2s ease';
        updateTotal();
    });

    function validateAndShowStep2() {
        const eventName = document.getElementById('event_name').value.trim();
        const eventDateInput = document.querySelector('input[name="event_date"]');
        const eventDate = eventDateInput ? eventDateInput.value.trim() : '';
        const venue = document.getElementById('venue').value.trim();
        
        // Validation
        if (!eventName) {
            alert('⚠️ Please enter an Event Name');
            document.getElementById('event_name').focus();
            return;
        }
        
        if (!eventDate) {
            alert('⚠️ Please select an Event Date');
            return;
        }
        
        if (!venue) {
            alert('⚠️ Please enter a Venue');
            document.getElementById('venue').focus();
            return;
        }
        
        // All validations passed
        showStep2();
    }

    function showStep2() {
        const step1 = document.getElementById('step-1');
        const step2 = document.getElementById('step-2');
        
        // Scroll to top first
        window.scrollTo({ top: 0, behavior: 'smooth' });
        
        // Simple fade and slide animation
        step1.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        step1.style.opacity = '0';
        step1.style.transform = 'translateX(-30px)';
        
        setTimeout(() => {
            step1.classList.add('hidden');
            step1.style.opacity = '1';
            step1.style.transform = 'translateX(0)';
            
            step2.classList.remove('hidden');
            step2.style.opacity = '0';
            step2.style.transform = 'translateX(30px)';
            step2.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            
            setTimeout(() => {
                step2.style.opacity = '1';
                step2.style.transform = 'translateX(0)';
            }, 50);
        }, 300);
    }

    function showStep1() {
        const step1 = document.getElementById('step-1');
        const step2 = document.getElementById('step-2');
        
        // Scroll to top first
        window.scrollTo({ top: 0, behavior: 'smooth' });
        
        // Simple fade and slide animation
        step2.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        step2.style.opacity = '0';
        step2.style.transform = 'translateX(30px)';
        
        setTimeout(() => {
            step2.classList.add('hidden');
            step2.style.opacity = '1';
            step2.style.transform = 'translateX(0)';
            
            step1.classList.remove('hidden');
            step1.style.opacity = '0';
            step1.style.transform = 'translateX(-30px)';
            step1.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            
            setTimeout(() => {
                step1.style.opacity = '1';
                step1.style.transform = 'translateX(0)';
            }, 50);
        }, 300);
    }

    // If there are validation errors on step 2, show step 2
    @if($errors->has('customer_name') || $errors->has('email') || $errors->has('phone'))
        document.addEventListener('DOMContentLoaded', function() {
            // Show step 2 immediately without animation on page load
            document.getElementById('step-1').classList.add('hidden');
            document.getElementById('step-2').classList.remove('hidden');
        });
    @endif
    </script>
</body>

</html>