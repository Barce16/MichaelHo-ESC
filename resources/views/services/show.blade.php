<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $package->name }} - Michael Ho Events</title>
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

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Image Gallery Styles */
        .gallery-slide {
            display: none;
            animation: fadeIn 0.5s ease-in-out;
        }

        .gallery-slide.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .gallery-indicator {
            transition: all 0.3s ease;
        }

        .gallery-indicator.active {
            background-color: #334155;
            width: 2rem;
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

        <div class="max-w-7xl mx-auto z-10">
            <!-- Header -->
            <div class="text-center mb-8">
                <a href="{{ route('services.category', $package->type) }}" class="inline-block mb-4">
                    <div class="flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        <span class="text-sm font-medium">Back to {{ ucfirst($package->type) }} Packages</span>
                    </div>
                </a>
                <a href="{{ url('/') }}">
                    <img src="{{ asset('images/favicon.png') }}" alt="Logo" class="h-16 mx-auto mb-4">
                </a>
                <h1 class="text-3xl font-bold font-libre mb-2">{{ $package->name }}</h1>
                <div class="text-gray-600">
                    <p class="text-sm mb-1">Starting Package Price</p>
                    <p class="text-2xl font-semibold">₱{{ number_format($package->price, 2) }}</p>
                </div>
            </div>

            <!-- Two Column Layout - 50/50 Split -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <!-- LEFT COLUMN - Package Details -->
                <div class="space-y-6">

                    <!-- Image Gallery -->
                    @php
                    // Get package images from relationship
                    $galleryImages = $package->images ?? collect();
                    $hasImages = $galleryImages->count() > 0;
                    @endphp

                    <div
                        class="bg-white/90 backdrop-blur-sm rounded-lg shadow-md overflow-hidden border border-gray-100">
                        <div class="relative aspect-[16/10] w-full overflow-hidden bg-gray-200" id="imageGallery">
                            @if($hasImages)
                            @foreach($galleryImages as $index => $image)
                            <div class="gallery-slide {{ $index === 0 ? 'active' : '' }} absolute inset-0">
                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                    alt="{{ $package->name }} - Image {{ $index + 1 }}"
                                    class="w-full h-full object-cover">
                            </div>
                            @endforeach

                            <!-- Gallery Controls -->
                            @if($galleryImages->count() > 1)
                            <!-- Previous Button -->
                            <button type="button" onclick="changeSlide(-1)"
                                class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white rounded-full p-2 shadow-lg transition-all">
                                <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>

                            <!-- Next Button -->
                            <button type="button" onclick="changeSlide(1)"
                                class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white rounded-full p-2 shadow-lg transition-all">
                                <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>

                            <!-- Indicators -->
                            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                                @foreach($galleryImages as $index => $image)
                                <button type="button" onclick="goToSlide({{ $index }})"
                                    class="gallery-indicator h-2 rounded-full bg-white/50 hover:bg-white/80 transition-all {{ $index === 0 ? 'active w-8' : 'w-2' }}"
                                    data-slide="{{ $index }}">
                                </button>
                                @endforeach
                            </div>
                            @endif
                            @else
                            <!-- Placeholder if no images -->
                            <div
                                class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                <div class="text-center">
                                    <svg class="w-24 h-24 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-gray-500 text-sm">No images available</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Package Details Container -->
                    <div class="bg-white/90 backdrop-blur-sm rounded-lg shadow-md p-6 border border-gray-100 space-y-6">

                        <!-- Description -->
                        @if($package->description)
                        <div>
                            <h2 class="text-xl font-bold font-libre mb-3 flex items-center gap-2">
                                <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                About This Package
                            </h2>
                            <p class="text-gray-700 leading-relaxed">{{ $package->description }}</p>
                        </div>
                        @endif

                        <!-- Coordination & Event Styling -->
                        @if($package->coordination || ($package->event_styling && count($package->event_styling) > 0))
                        <div>
                            <h3 class="text-md font-semibold mb-3 text-gray-800">
                                Package Services
                            </h3>
                            <div class="space-y-3">
                                @if($package->coordination)
                                <div class="bg-slate-50 rounded-lg p-3 border-l-2 border-slate-400">
                                    <h4 class="font-semibold text-sm text-gray-900 mb-1">Coordination</h4>
                                    <p class="text-xs text-gray-700 leading-relaxed">{{ $package->coordination }}</p>
                                </div>
                                @endif

                                @if($package->event_styling && count($package->event_styling) > 0)
                                <div class="bg-slate-50 rounded-lg p-3 border-l-2 border-slate-400">
                                    <h4 class="font-semibold text-sm text-gray-900 mb-2">Event Styling</h4>
                                    <ul class="text-xs text-gray-700 space-y-1">
                                        @foreach($package->event_styling as $styling)
                                        <li class="flex items-start gap-1.5">
                                            <span class="text-slate-500 mt-0.5">•</span>
                                            <span>{{ $styling }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Package Features -->
                        @if($package->features)
                        <div>
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 flex items-center gap-2">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Package Highlights
                            </h3>
                            <div class="space-y-3">
                                @foreach(explode("\n", $package->features) as $feature)
                                @if(trim($feature))
                                <div
                                    class="flex items-start gap-3 p-3 rounded-lg bg-emerald-50 border border-emerald-100">
                                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-800 leading-relaxed">{{ trim($feature) }}</span>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Package Inclusions -->
                        @if(isset($packageInclusions) && $packageInclusions->isNotEmpty())
                        <div>
                            <h3 class="text-lg font-semibold mb-3 flex items-center gap-2">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                                What's Included
                            </h3>

                            @foreach($packageInclusions as $categoryName => $categoryInclusions)
                            <div class="mb-4 last:mb-0" data-category="{{ $categoryName }}">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="text-sm font-semibold text-gray-700">
                                        {{ ucfirst(str_replace('_', ' ', $categoryName)) }}
                                    </h4>
                                    <button type="button" onclick="openCustomizeModal('{{ $categoryName }}')"
                                        class="text-slate-600 hover:text-slate-900 transition-colors p-1.5 rounded hover:bg-slate-100 flex items-center gap-1"
                                        title="Customize {{ ucfirst(str_replace('_', ' ', $categoryName)) }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        <span class="text-xs">Customize</span>
                                    </button>
                                </div>
                                <ul class="space-y-1.5 ml-4 inclusion-list"
                                    id="inclusion-list-{{ Str::slug($categoryName) }}">
                                    @foreach($categoryInclusions as $inclusion)
                                    <li class="flex items-start gap-2 text-sm text-gray-700"
                                        data-inclusion-id="{{ $inclusion->id }}">
                                        <svg class="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>{{ $inclusion->name }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <!-- Additional Info -->
                    @if($package->additional_info)
                    <div class="bg-amber-50/90 backdrop-blur-sm rounded-lg shadow-md p-6 border border-amber-200">
                        <div class="flex items-start gap-3">
                            <svg class="w-7 h-7 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <h4 class="font-semibold text-amber-900 mb-2">Important Information</h4>
                                <p class="text-sm text-amber-800 leading-relaxed">{{ $package->additional_info }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- RIGHT COLUMN - Booking Form (Sticky) -->
                <div class="lg:sticky lg:top-8 h-fit">
                    <div class="bg-white/90 backdrop-blur-sm rounded-lg shadow-md p-6 border border-gray-100">
                        <h2 class="text-2xl font-bold font-libre mb-2 text-center">Book This Package</h2>
                        <p class="text-sm text-gray-600 text-center mb-6">Fill in your event details</p>

                        <form action="{{ route('book.form', $package) }}" method="POST" class="space-y-4">
                            @csrf

                            <!-- Hidden inputs for selected inclusions -->
                            <div id="selected-inclusions-inputs"></div>

                            <!-- Event Name -->
                            <div>
                                <label for="event_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Event Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="event_name" id="event_name" required
                                    value="{{ old('event_name') }}"
                                    class="block w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-slate-500 focus:ring-2 focus:ring-slate-200 transition bg-white"
                                    placeholder="Enter event name">
                                @error('event_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Event Date -->
                            <div>
                                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Event Date <span class="text-red-500">*</span>
                                </label>
                                <x-calendar-picker name="event_date" :value="old('event_date')" required />
                                @error('event_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Venue -->
                            <div>
                                <label for="venue" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Venue <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="venue" id="venue" required value="{{ old('venue') }}"
                                    minlength="10" maxlength="255"
                                    class="block w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-slate-500 focus:ring-2 focus:ring-slate-200 transition bg-white"
                                    placeholder="Enter full venue address (minimum 10 characters)">
                                @error('venue')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">Please provide complete venue address</p>
                            </div>

                            <!-- Theme -->
                            <div>
                                <label for="theme" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Theme
                                </label>
                                <input type="text" name="theme" id="theme" value="{{ old('theme') }}"
                                    class="block w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-slate-500 focus:ring-2 focus:ring-slate-200 transition bg-white"
                                    placeholder="Optional theme">
                                @error('theme')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <button type="submit"
                                class="w-full px-6 py-4 bg-slate-800 text-white font-bold rounded-lg hover:bg-slate-900 transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                                Continue to Book
                            </button>

                            <p class="text-xs text-center text-gray-500 leading-relaxed mt-3">Next step: Enter your
                                contact information</p>
                        </form>

                        <!-- Contact Info -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <p class="text-sm font-semibold text-gray-700 mb-3 text-center">Need Assistance?</p>
                            <div class="space-y-2">
                                <a href="mailto:michaelhoevents@gmail.com"
                                    class="flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors p-2 rounded-lg hover:bg-gray-50">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-sm">michaelhoevents@gmail.com</span>
                                </a>
                                <a href="tel:+639173062531"
                                    class="flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors p-2 rounded-lg hover:bg-gray-50">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <span class="text-sm">+63 917 306 2531</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Customization Modals -->
    @if(isset($allInclusions) && $allInclusions->isNotEmpty())
    @foreach($allInclusions as $categoryName => $categoryInclusions)
    <div id="modal-{{ Str::slug($categoryName) }}"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-[90vh] overflow-hidden">
            <!-- Modal Header -->
            <div class="bg-slate-800 text-white px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-bold">Customize {{ ucfirst(str_replace('_', ' ', $categoryName)) }}</h3>
                <button type="button" onclick="closeCustomizeModal('{{ $categoryName}}')"
                    class="text-white hover:text-gray-300 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-140px)]">
                <p class="text-sm text-gray-600 mb-4">Select the inclusions you want for this category</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($categoryInclusions as $inclusion)
                    <label
                        class="flex items-start gap-3 p-4 border-2 rounded-lg cursor-pointer hover:border-slate-300 transition-all inclusion-checkbox"
                        data-inclusion-id="{{ $inclusion->id }}" data-inclusion-name="{{ $inclusion->name }}"
                        data-category="{{ $categoryName }}">
                        <input type="checkbox" name="inclusions[{{ $categoryName }}][]" value="{{ $inclusion->id }}"
                            class="mt-1 w-5 h-5 text-slate-600 rounded border-gray-300 focus:ring-slate-500"
                            onchange="handleInclusionChange(this)">

                        <div class="flex-1 min-w-0">
                            @if($inclusion->image)
                            <div class="w-full h-32 rounded-lg overflow-hidden bg-gray-100 border border-gray-200 mb-2">
                                <img src="{{ $inclusion->image_url }}" alt="{{ $inclusion->name }}"
                                    class="w-full h-full object-cover">
                            </div>
                            @endif
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">{{ $inclusion->name }}</p>
                                <p class="text-xs text-slate-600 font-bold mt-1">₱{{ number_format($inclusion->price, 2)
                                    }}</p>
                                @if($inclusion->notes)
                                <p class="text-xs text-gray-600 mt-1 leading-relaxed">{{ Str::limit($inclusion->notes,
                                    80) }}</p>
                                @endif
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t">
                <span class="text-sm text-gray-600">
                    <span id="selected-count-{{ Str::slug($categoryName) }}" class="font-bold">0</span> selected
                </span>
                <div class="flex gap-3">
                    <button type="button" onclick="closeCustomizeModal('{{ $categoryName }}')"
                        class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="button" onclick="saveCustomization('{{ $categoryName }}')"
                        class="px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-900 transition-colors">
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @endif

    <script>
        // Store selected inclusions
        let selectedInclusions = {};

        // Initialize with package inclusions
        document.addEventListener('DOMContentLoaded', function() {
            // Load initial package inclusions
            @if(isset($packageInclusions))
            @foreach($packageInclusions as $categoryName => $categoryInclusions)
            selectedInclusions['{{ $categoryName }}'] = [
                @foreach($categoryInclusions as $inclusion)
                {
                    id: {{ $inclusion->id }},
                    name: '{{ addslashes($inclusion->name) }}'
                },
                @endforeach
            ];
            @endforeach
            @endif
            
            // Initialize hidden inputs
            updateHiddenInputs();
        });

        // Open customization modal
        function openCustomizeModal(categoryName) {
            const modalId = 'modal-' + categoryName.toLowerCase().replace(/\s+/g, '-').replace(/_/g, '-');
            const modal = document.getElementById(modalId);
            
            if (modal) {
                // Check currently selected inclusions
                const selected = selectedInclusions[categoryName] || [];
                const checkboxes = modal.querySelectorAll('input[type="checkbox"]');
                
                checkboxes.forEach(checkbox => {
                    const inclusionId = parseInt(checkbox.value);
                    checkbox.checked = selected.some(item => item.id === inclusionId);
                });
                
                updateSelectedCount(categoryName);
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        // Close customization modal
        function closeCustomizeModal(categoryName) {
            const modalId = 'modal-' + categoryName.toLowerCase().replace(/\s+/g, '-').replace(/_/g, '-');
            const modal = document.getElementById(modalId);
            
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        // Handle inclusion checkbox change
        function handleInclusionChange(checkbox) {
            const categorySlug = checkbox.closest('.inclusion-checkbox').getAttribute('data-category');
            updateSelectedCount(categorySlug);
        }

        // Update selected count
        function updateSelectedCount(categoryName) {
            const modalId = 'modal-' + categoryName.toLowerCase().replace(/\s+/g, '-').replace(/_/g, '-');
            const modal = document.getElementById(modalId);
            
            if (modal) {
                const checkedBoxes = modal.querySelectorAll('input[type="checkbox"]:checked');
                const countElement = document.getElementById('selected-count-' + categoryName.toLowerCase().replace(/\s+/g, '-').replace(/_/g, '-'));
                
                if (countElement) {
                    countElement.textContent = checkedBoxes.length;
                }
            }
        }

        // Save customization
        function saveCustomization(categoryName) {
            const modalId = 'modal-' + categoryName.toLowerCase().replace(/\s+/g, '-').replace(/_/g, '-');
            const modal = document.getElementById(modalId);
            
            if (modal) {
                const checkedBoxes = modal.querySelectorAll('input[type="checkbox"]:checked');
                const newSelections = [];
                
                checkedBoxes.forEach(checkbox => {
                    const label = checkbox.closest('.inclusion-checkbox');
                    newSelections.push({
                        id: parseInt(checkbox.value),
                        name: label.getAttribute('data-inclusion-name')
                    });
                });
                
                // Update stored selections
                selectedInclusions[categoryName] = newSelections;
                
                // Update UI
                updateInclusionList(categoryName);
                
                // Close modal
                closeCustomizeModal(categoryName);
                
                // Show success message
                showAlert('Customization saved! Your selections will be included in the booking.', 'success');
            }
        }

        // Update inclusion list in UI
        function updateInclusionList(categoryName) {
            const listId = 'inclusion-list-' + categoryName.toLowerCase().replace(/\s+/g, '-').replace(/_/g, '-');
            const list = document.getElementById(listId);
            
            if (list && selectedInclusions[categoryName]) {
                const inclusions = selectedInclusions[categoryName];
                
                // Clear current list
                list.innerHTML = '';
                
                // Add new items
                if (inclusions.length === 0) {
                    list.innerHTML = '<li class="text-sm text-gray-500 italic ml-4">No inclusions selected</li>';
                } else {
                    inclusions.forEach(inclusion => {
                        const li = document.createElement('li');
                        li.className = 'flex items-start gap-2 text-sm text-gray-700';
                        li.setAttribute('data-inclusion-id', inclusion.id);
                        li.innerHTML = `
                            <svg class="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>${inclusion.name}</span>
                        `;
                        list.appendChild(li);
                    });
                }
            }
            
            // Update hidden form inputs
            updateHiddenInputs();
        }

        // Update hidden form inputs with selected inclusions
        function updateHiddenInputs() {
            const container = document.getElementById('selected-inclusions-inputs');
            if (!container) return;
            
            // Clear existing inputs
            container.innerHTML = '';
            
            // Add hidden inputs for all selected inclusions
            Object.keys(selectedInclusions).forEach(categoryName => {
                const inclusions = selectedInclusions[categoryName];
                inclusions.forEach(inclusion => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'inclusions[]';
                    input.value = inclusion.id;
                    container.appendChild(input);
                });
            });
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('bg-opacity-50')) {
                const modals = document.querySelectorAll('[id^="modal-"]');
                modals.forEach(modal => {
                    if (!modal.classList.contains('hidden')) {
                        modal.classList.add('hidden');
                        document.body.style.overflow = 'auto';
                    }
                });
            }
        });

        // Image Gallery Auto-Slide
        let currentSlide = 0;
        const slides = document.querySelectorAll('.gallery-slide');
        const indicators = document.querySelectorAll('.gallery-indicator');
        const totalSlides = slides.length;
        let autoSlideInterval;

        function showSlide(index) {
            // Remove active class from all
            slides.forEach(slide => slide.classList.remove('active'));
            indicators.forEach(indicator => {
                indicator.classList.remove('active', 'w-8');
                indicator.classList.add('w-2');
            });

            // Add active class to current
            if (slides[index]) {
                slides[index].classList.add('active');
            }
            if (indicators[index]) {
                indicators[index].classList.add('active', 'w-8');
                indicators[index].classList.remove('w-2');
            }
        }

        function changeSlide(direction) {
            currentSlide = (currentSlide + direction + totalSlides) % totalSlides;
            showSlide(currentSlide);
            resetAutoSlide();
        }

        function goToSlide(index) {
            currentSlide = index;
            showSlide(currentSlide);
            resetAutoSlide();
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            showSlide(currentSlide);
        }

        function startAutoSlide() {
            if (totalSlides > 1) {
                autoSlideInterval = setInterval(nextSlide, 4000); // Change slide every 4 seconds
            }
        }

        function resetAutoSlide() {
            clearInterval(autoSlideInterval);
            startAutoSlide();
        }

        // Start auto-slide when page loads
        document.addEventListener('DOMContentLoaded', function() {
            if (totalSlides > 0) {
                startAutoSlide();
            }
        });

        // Pause auto-slide when hovering over gallery
        const gallery = document.getElementById('imageGallery');
        if (gallery) {
            gallery.addEventListener('mouseenter', function() {
                clearInterval(autoSlideInterval);
            });

            gallery.addEventListener('mouseleave', function() {
                startAutoSlide();
            });
        }

        // Form Validation
        document.addEventListener('DOMContentLoaded', function() {
            const bookingForm = document.querySelector('form[action*="book"]');
            
            if (bookingForm) {
                bookingForm.addEventListener('submit', function(e) {
                    // Clear previous error messages
                    document.querySelectorAll('.validation-error').forEach(el => el.remove());
                    
                    let hasError = false;
                    let firstErrorField = null;

                    // Validate Event Name
                    const eventName = document.getElementById('event_name');
                    if (!eventName.value.trim()) {
                        showError(eventName, 'Event name is required');
                        hasError = true;
                        if (!firstErrorField) firstErrorField = eventName;
                    }

                    // Validate Event Date
                    const eventDate = document.querySelector('input[name="event_date"]');
                    if (!eventDate || !eventDate.value.trim()) {
                        showError(eventDate, 'Event date is required');
                        hasError = true;
                        if (!firstErrorField) firstErrorField = eventDate;
                    } else {
                        // Check if date is in the future
                        const selectedDate = new Date(eventDate.value);
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);
                        
                        if (selectedDate <= today) {
                            showError(eventDate, 'Event date must be in the future');
                            hasError = true;
                            if (!firstErrorField) firstErrorField = eventDate;
                        }
                    }

                    // Validate Venue
                    const venue = document.getElementById('venue');
                    if (!venue.value.trim()) {
                        showError(venue, 'Venue is required');
                        hasError = true;
                        if (!firstErrorField) firstErrorField = venue;
                    } else if (venue.value.trim().length < 10) {
                        showError(venue, 'Please enter a complete venue address (minimum 10 characters)');
                        hasError = true;
                        if (!firstErrorField) firstErrorField = venue;
                    }

                    // If there are errors, prevent form submission
                    if (hasError) {
                        e.preventDefault();
                        
                        // Scroll to first error
                        if (firstErrorField) {
                            firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            firstErrorField.focus();
                        }
                        
                        // Show alert
                        showAlert('Please fill in all required fields correctly', 'error');
                        return false;
                    }

                    // Show loading state
                    const submitBtn = bookingForm.querySelector('button[type="submit"]');
                    const originalContent = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `
                        <svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    `;

                    // Show success message
                    showAlert('Proceeding to booking form...', 'success');
                });
            }

            function showError(field, message) {
                const errorDiv = document.createElement('p');
                errorDiv.className = 'validation-error text-red-500 text-xs mt-1';
                errorDiv.textContent = message;
                
                // Add red border to field
                field.classList.add('border-red-500');
                field.classList.remove('border-gray-300');
                
                // Insert error message after field
                field.parentElement.appendChild(errorDiv);
            }

            function showAlert(message, type = 'error') {
                // Remove existing alert if any
                const existingAlert = document.querySelector('.validation-alert');
                if (existingAlert) {
                    existingAlert.remove();
                }

                // Create alert
                const alert = document.createElement('div');
                alert.className = `validation-alert fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 animate-slide-in ${
                    type === 'error' ? 'bg-red-50 border border-red-200' : 'bg-emerald-50 border border-emerald-200'
                }`;
                
                const icon = type === 'error' 
                    ? `<svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                       </svg>`
                    : `<svg class="w-6 h-6 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                       </svg>`;
                
                alert.innerHTML = `
                    ${icon}
                    <span class="text-sm font-medium ${type === 'error' ? 'text-red-800' : 'text-emerald-800'}">${message}</span>
                `;
                
                document.body.appendChild(alert);
                
                // Auto remove after 4 seconds
                setTimeout(() => {
                    alert.remove();
                }, 4000);
            }

            // Remove error styling when user starts typing
            document.querySelectorAll('input, textarea').forEach(field => {
                field.addEventListener('input', function() {
                    this.classList.remove('border-red-500');
                    this.classList.add('border-gray-300');
                    const error = this.parentElement.querySelector('.validation-error');
                    if (error) {
                        error.remove();
                    }
                });
            });
        });
    </script>
    <style>
        @keyframes slide-in {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .animate-slide-in {
            animation: slide-in 0.3s ease-out;
        }
    </style>
</body>

</html>