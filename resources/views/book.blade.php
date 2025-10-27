<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Book {{ $package->name }} - Michael Ho Events</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
    </style>
</head>

<body>
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
                <a href="{{ route('services.show', $package) }}" class="inline-block mb-4">
                    <div class="flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        <span class="text-sm font-medium">Back to Package Details</span>
                    </div>
                </a>
                <a href="{{ url('/') }}">
                    <img src="{{ asset('images/favicon.png') }}" alt="Logo" class="h-16 mx-auto mb-4">
                </a>
                <h1 class="text-3xl font-bold font-libre mb-2">Complete Your Booking</h1>
                <p class="text-gray-600">{{ $package->name }}</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Left: Event Summary (1/3) -->
                <div class="lg:col-span-1">
                    <div
                        class="bg-white/90 backdrop-blur-sm rounded-lg shadow-md p-6 border border-gray-100 sticky top-8">
                        <h2 class="text-lg font-bold font-libre mb-4">Event Summary</h2>

                        <div class="space-y-3 text-sm">
                            <div>
                                <p class="text-gray-600 text-xs mb-1">Package</p>
                                <p class="font-semibold text-gray-900">{{ $package->name }}</p>
                            </div>

                            <div>
                                <p class="text-gray-600 text-xs mb-1">Event Name</p>
                                <p class="font-semibold text-gray-900">{{ $eventData['event_name'] }}</p>
                            </div>

                            <div>
                                <p class="text-gray-600 text-xs mb-1">Date</p>
                                <p class="font-semibold text-gray-900">{{
                                    \Carbon\Carbon::parse($eventData['event_date'])->format('F d, Y') }}</p>
                            </div>

                            <div>
                                <p class="text-gray-600 text-xs mb-1">Venue</p>
                                <p class="font-semibold text-gray-900">{{ $eventData['venue'] }}</p>
                            </div>

                            @if(!empty($eventData['theme']))
                            <div>
                                <p class="text-gray-600 text-xs mb-1">Theme</p>
                                <p class="font-semibold text-gray-900">{{ $eventData['theme'] }}</p>
                            </div>
                            @endif

                            <div class="pt-3 border-t border-gray-200">
                                <p class="text-gray-600 text-xs mb-1">Starting Price</p>
                                <p class="text-2xl font-bold text-slate-900">₱{{ number_format($package->price, 2) }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">Final pricing after consultation</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Booking Form (2/3) -->
                <div class="lg:col-span-2">
                    <div class="bg-white/90 backdrop-blur-sm rounded-lg shadow-md p-8 border border-gray-100">
                        <h2 class="text-2xl font-bold font-libre mb-2">Your Information</h2>
                        <p class="text-sm text-gray-600 mb-6">Please provide your contact details</p>

                        @if(session('error'))
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-sm text-red-800">{{ session('error') }}</p>
                            </div>
                        </div>
                        @endif

                        <form action="{{ route('book.store', $package) }}" method="POST" class="space-y-6">
                            @csrf

                            <!-- Hidden fields for event data -->
                            <input type="hidden" name="event_name" value="{{ $eventData['event_name'] }}">
                            <input type="hidden" name="event_date" value="{{ $eventData['event_date'] }}">
                            <input type="hidden" name="venue" value="{{ $eventData['venue'] }}">
                            <input type="hidden" name="theme" value="{{ $eventData['theme'] ?? '' }}">

                            <!-- Customer Name -->
                            <div>
                                <label for="customer_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="customer_name" id="customer_name" required
                                    value="{{ old('customer_name') }}"
                                    class="block w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-slate-500 focus:ring-2 focus:ring-slate-200 transition bg-white @error('customer_name') border-red-500 @enderror"
                                    placeholder="Enter your full name">
                                @error('customer_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" id="email" required value="{{ old('email') }}"
                                    class="block w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-slate-500 focus:ring-2 focus:ring-slate-200 transition bg-white @error('email') border-red-500 @enderror"
                                    placeholder="your.email@example.com">
                                @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone Number -->
                            <div>
                                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Phone Number <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" name="phone" id="phone" required maxlength="12"
                                    value="{{ old('phone') }}"
                                    class="block w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-slate-500 focus:ring-2 focus:ring-slate-200 transition bg-white @error('phone') border-red-500 @enderror"
                                    placeholder="+63 912 345 6789">
                                @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div>
                                <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Address
                                </label>
                                <input type="text" name="address" id="address" minlength="10"
                                    value="{{ old('address') }}"
                                    class="block w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-slate-500 focus:ring-2 focus:ring-slate-200 transition bg-white"
                                    placeholder="Complete address (minimum 10 characters)">
                            </div>

                            <!-- Number of Guests -->
                            <div>
                                <label for="guests" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Number of Guests
                                </label>
                                <input type="number" name="guests" id="guests" min="1" value="{{ old('guests') }}"
                                    class="block w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-slate-500 focus:ring-2 focus:ring-slate-200 transition bg-white"
                                    placeholder="Estimated number of guests">
                            </div>

                            <!-- Additional Notes -->
                            <div>
                                <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Additional Notes
                                </label>
                                <textarea name="notes" id="notes" rows="4"
                                    class="block w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-slate-500 focus:ring-2 focus:ring-slate-200 transition bg-white resize-none"
                                    placeholder="Any special requests or additional information...">{{ old('notes') }}</textarea>
                                <p class="mt-1 text-xs text-gray-500">Tell us about any special requirements or
                                    preferences</p>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-4">
                                <button type="submit"
                                    class="w-full px-6 py-4 bg-slate-800 text-white font-bold rounded-lg hover:bg-slate-900 transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Submit Booking Request
                                </button>
                                <p class="text-xs text-center text-gray-500 mt-3">By submitting, you agree to be
                                    contacted regarding your event booking</p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>