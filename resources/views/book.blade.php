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
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=Inter:wght@300;400;500;600&family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,400&display=swap"
        rel="stylesheet">
</head>

<body class="bg-white">
    <!-- Toast Notifications -->
    @include('components.toast')

    <!-- Elegant Header -->
    <header class="border-b border-gray-100 bg-white">
        <div class="max-w-screen-xl mx-auto px-6 lg:px-12 py-6">
            <div class="flex items-center justify-between">
                <a href="{{ route('services.show', $package) }}"
                    class="flex items-center gap-2 text-gray-600 hover:text-black transition-colors text-xs uppercase tracking-wider">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span>Back to Package</span>
                </a>

                <a href="{{ url('/') }}">
                    <img src="{{ asset('images/favicon.png') }}" alt="Logo" class="h-12">
                </a>

                <div class="w-32"></div> <!-- Spacer for centering -->
            </div>
        </div>
    </header>

    <!-- Page Header -->
    <section class="py-16 bg-gradient-to-b from-gray-50 to-white border-b border-gray-100">
        <div class="max-w-screen-xl mx-auto px-6 lg:px-12 text-center">
            <h1 class="text-4xl font-light mb-4" style="font-family: 'Playfair Display', serif;">
                Complete Your Booking
            </h1>
            <p class="text-lg text-gray-600 mb-2" style="font-family: 'Cormorant Garamond', serif;">
                {{ $package->name }}
            </p>
            <div class="flex items-center justify-center gap-4 mt-6">
                <div class="h-px w-24 bg-gray-900"></div>
                <div class="mx-3 w-1.5 h-1.5 bg-gray-900"></div>
                <div class="h-px w-24 bg-gray-900"></div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-16">
        <div class="max-w-screen-xl mx-auto px-6 lg:px-12">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

                <!-- LEFT COLUMN - Event Summary -->
                <div class="space-y-8">

                    <!-- Event Details Card -->
                    <div class="border border-gray-200 p-8">
                        <h2 class="text-xs uppercase tracking-widest text-gray-600 mb-6">Event Summary</h2>

                        <div class="space-y-4">
                            <div class="pb-4 border-b border-gray-100">
                                <p class="text-xs uppercase tracking-wider text-gray-500 mb-1">Package</p>
                                <p class="text-base text-gray-900">{{ $package->name }}</p>
                            </div>

                            <div class="pb-4 border-b border-gray-100">
                                <p class="text-xs uppercase tracking-wider text-gray-500 mb-1">Event Name</p>
                                <p class="text-base text-gray-900">{{ $eventData['event_name'] }}</p>
                            </div>

                            <div class="pb-4 border-b border-gray-100">
                                <p class="text-xs uppercase tracking-wider text-gray-500 mb-1">Date</p>
                                <p class="text-base text-gray-900">
                                    {{ \Carbon\Carbon::parse($eventData['event_date'])->format('F d, Y') }}
                                </p>
                            </div>

                            <div class="pb-4 border-b border-gray-100">
                                <p class="text-xs uppercase tracking-wider text-gray-500 mb-1">Venue</p>
                                <p class="text-base text-gray-900">{{ $eventData['venue'] }}</p>
                            </div>

                            @if(!empty($eventData['theme']))
                            <div class="pb-4 border-b border-gray-100">
                                <p class="text-xs uppercase tracking-wider text-gray-500 mb-1">Theme</p>
                                <p class="text-base text-gray-900">{{ $eventData['theme'] }}</p>
                            </div>
                            @endif

                            @if(!empty($eventData['notes']))
                            <div class="pb-4 border-b border-gray-100">
                                <p class="text-xs uppercase tracking-wider text-gray-500 mb-1">Additional Notes</p>
                                <p class="text-base text-gray-900">{{ $eventData['notes'] }}</p>
                            </div>
                            @endif

                            <div class="pt-4">
                                <p class="text-xs uppercase tracking-wider text-gray-500 mb-2">Starting Price</p>
                                <p class="text-3xl font-light text-black">₱{{ number_format($package->price, 0, ',',
                                    ',') }}</p>
                                <p class="text-xs text-gray-500 mt-2">Final pricing determined after consultation</p>
                            </div>
                        </div>
                    </div>

                    <!-- Information Note -->
                    <div class="border border-gray-200 p-8 bg-gray-50">
                        <h3 class="text-xs uppercase tracking-widest text-gray-600 mb-4">What Happens Next?</h3>
                        <ul class="space-y-3 text-sm text-gray-700">
                            <li class="flex items-start gap-3">
                                <span class="w-1 h-1 bg-black mt-2 flex-shrink-0"></span>
                                <span>We'll review your booking request within 24 hours</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="w-1 h-1 bg-black mt-2 flex-shrink-0"></span>
                                <span>Our team will contact you to discuss details</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="w-1 h-1 bg-black mt-2 flex-shrink-0"></span>
                                <span>Schedule a consultation at your convenience</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="w-1 h-1 bg-black mt-2 flex-shrink-0"></span>
                                <span>Receive a detailed quote tailored to your needs</span>
                            </li>
                        </ul>
                    </div>

                </div>

                <!-- RIGHT COLUMN - Customer Information Form -->
                <div class="space-y-8">
                    <div class="border border-gray-900 p-8">
                        <h2 class="text-2xl font-light mb-2" style="font-family: 'Playfair Display', serif;">
                            Your Information
                        </h2>
                        <p class="text-sm text-gray-600 mb-8">Please provide your contact details to proceed</p>

                        <form action="{{ route('book.store', $package) }}" method="POST">
                            @csrf

                            <!-- Hidden fields for event data -->
                            <input type="hidden" name="event_name" value="{{ $eventData['event_name'] }}">
                            <input type="hidden" name="event_date" value="{{ $eventData['event_date'] }}">
                            <input type="hidden" name="venue" value="{{ $eventData['venue'] }}">
                            <input type="hidden" name="theme" value="{{ $eventData['theme'] ?? '' }}">
                            <input type="hidden" name="notes" value="{{ $eventData['notes'] ?? '' }}">

                            <div class="space-y-6">

                                <!-- Customer Name -->
                                <div>
                                    <label for="customer_name"
                                        class="block text-xs uppercase tracking-wider text-gray-600 mb-2">
                                        Full Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="customer_name" name="customer_name"
                                        value="{{ old('customer_name') }}"
                                        class="w-full px-4 py-3 border border-gray-200 text-sm focus:outline-none focus:border-black transition-colors @error('customer_name') border-red-500 @enderror"
                                        placeholder="Enter your full name">
                                    @error('customer_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Gender -->
                                <div>
                                    <label for="gender"
                                        class="block text-xs uppercase tracking-wider text-gray-600 mb-2">
                                        Gender <span class="text-red-500">*</span>
                                    </label>
                                    <select id="gender" name="gender"
                                        class="w-full px-4 py-3 border border-gray-200 bg-white text-sm focus:outline-none focus:border-black transition-colors @error('gender') border-red-500 @enderror">
                                        <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select gender
                                        </option>
                                        <option value="Male" {{ old('gender')=='Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender')=='Female' ? 'selected' : '' }}>Female
                                        </option>
                                        <option value="Other" {{ old('gender')=='Other' ? 'selected' : '' }}>Other
                                        </option>
                                    </select>
                                    @error('gender')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email"
                                        class="block text-xs uppercase tracking-wider text-gray-600 mb-2">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                                        class="w-full px-4 py-3 border border-gray-200 text-sm focus:outline-none focus:border-black transition-colors @error('email') border-red-500 @enderror"
                                        placeholder="your.email@example.com">
                                    @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone Number -->
                                <div>
                                    <label for="phone"
                                        class="block text-xs uppercase tracking-wider text-gray-600 mb-2">
                                        Phone Number <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" id="phone" name="phone" maxlength="12" value="{{ old('phone') }}"
                                        class="w-full px-4 py-3 border border-gray-200 text-sm focus:outline-none focus:border-black transition-colors @error('phone') border-red-500 @enderror"
                                        placeholder="+63 912 345 6789">
                                    @error('phone')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Address -->
                                <div>
                                    <label for="address"
                                        class="block text-xs uppercase tracking-wider text-gray-600 mb-2">
                                        Address
                                    </label>
                                    <input type="text" id="address" name="address" value="{{ old('address') }}"
                                        class="w-full px-4 py-3 border border-gray-200 text-sm focus:outline-none focus:border-black transition-colors"
                                        placeholder="Complete address (minimum 10 characters)">
                                </div>

                                <!-- Number of Guests -->
                                <div>
                                    <label for="guests"
                                        class="block text-xs uppercase tracking-wider text-gray-600 mb-2">
                                        Number of Guests
                                    </label>
                                    <input type="number" id="guests" name="guests" min="1" value="{{ old('guests') }}"
                                        class="w-full px-4 py-3 border border-gray-200 text-sm focus:outline-none focus:border-black transition-colors"
                                        placeholder="Estimated number of guests">
                                </div>

                                <!-- Submit Button -->
                                <div class="pt-4">
                                    <button type="submit"
                                        class="w-full px-8 py-4 bg-black text-white text-xs uppercase tracking-widest hover:bg-gray-900 transition-colors flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Submit Booking Request
                                    </button>
                                    <p class="text-xs text-center text-gray-500 mt-3">
                                        By submitting, you agree to be contacted regarding your event
                                    </p>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Footer CTA -->
    <section class="py-16 bg-gray-50 border-t border-gray-100">
        <div class="max-w-screen-xl mx-auto px-6 lg:px-12 text-center">
            <h3 class="text-2xl font-light mb-4" style="font-family: 'Playfair Display', serif;">
                Questions About Your Booking?
            </h3>
            <p class="text-gray-600 mb-8">
                Our team is here to help you every step of the way
            </p>
            <div class="flex items-center justify-center gap-4">
                <a href="tel:+639173062531"
                    class="px-8 py-3 bg-black text-white text-xs uppercase tracking-widest hover:bg-gray-900 transition-colors">
                    Call Us
                </a>
                <a href="/cdn-cgi/l/email-protection#620f0b010a03070e0a0d0714070c161122050f030b0e4c010d0f"
                    class="px-8 py-3 border border-black text-black text-xs uppercase tracking-widest hover:bg-black hover:text-white transition-all duration-300">
                    Email Us
                </a>
            </div>
        </div>
        </sec