<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ ucfirst($category) }} Packages - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=Inter:wght@300;400;500;600&family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,400&display=swap"
        rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Custom elegant styles */
        .elegant-shadow {
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.08);
        }

        .elegant-shadow-hover {
            box-shadow: 0 10px 60px rgba(0, 0, 0, 0.12);
        }

        .text-elegant {
            letter-spacing: 0.02em;
        }

        .border-elegant {
            border: 1px solid #e5e5e5;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="min-h-screen bg-white text-neutral-900 antialiased selection:bg-black selection:text-white">

    <!-- HEADER CONTAINER -->
    <div id="header-container" class="relative z-50">
        <!-- Top Bar - More minimal and elegant -->
        <div id="top-bar" class="bg-black text-white text-xs">
            <div class="mx-auto max-w-screen-xl px-6 lg:px-12 flex items-center justify-between h-9">
                <div class="flex items-center gap-6">
                    <a href="https://www.facebook.com/MichaelHoEventsPlanningandCoordinating/" target="_blank"
                        class="opacity-70 hover:opacity-100 transition-opacity" aria-label="Facebook">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M22.675 0H1.325C.593 0 0 .593 0 1.325v21.351C0 23.406.593 24 1.325 24H12.82v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.464.099 2.795.143v3.24l-1.918.001c-1.504 0-1.794.716-1.794 1.764v2.312h3.587l-.467 3.622h-3.12V24h6.116C23.406 24 24 23.406 24 22.676V1.325C24 .593 23.406 0 22.675 0z" />
                        </svg>
                    </a>
                    <a href="https://www.instagram.com/michaelhoevents/?hl=en" target="_blank"
                        class="opacity-70 hover:opacity-100 transition-opacity" aria-label="Instagram">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2.163c3.204 0 3.584.012 4.85.07 1.17.056 1.97.24 2.43.403a4.92 4.92 0 011.675 1.087 4.92 4.92 0 011.087 1.675c.163.46.347 1.26.403 2.43.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.056 1.17-.24 1.97-.403 2.43a4.918 4.918 0 01-1.087 1.675 4.918 4.918 0 01-1.675 1.087c-.46.163-1.26.347-2.43.403-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.17-.056-1.97-.24-2.43-.403a4.918 4.918 0 01-1.675-1.087 4.918 4.918 0 01-1.087-1.675c-.163-.46-.347-1.26-.403-2.43C2.175 15.747 2.163 15.367 2.163 12s.012-3.584.07-4.85c.056-1.17.24-1.97.403-2.43a4.92 4.92 0 011.087-1.675A4.92 4.92 0 015.398 2.636c.46-.163 1.26-.347 2.43-.403C8.416 2.175 8.796 2.163 12 2.163zm0-2.163C8.741 0 8.332.013 7.052.072 5.775.131 4.772.348 3.95.692a6.918 6.918 0 00-2.53 1.656A6.918 6.918 0 00.692 4.878c-.344.822-.561 1.825-.62 3.102C.013 8.332 0 8.741 0 12c0 3.259.013 3.668.072 4.948.059 1.277.276 2.28.62 3.102a6.918 6.918 0 001.656 2.53 6.918 6.918 0 002.53 1.656c.822.344 1.825.561 3.102.62C8.332 23.987 8.741 24 12 24s3.668-.013 4.948-.072c1.277-.059 2.28-.276 3.102-.62a6.918 6.918 0 002.53-1.656 6.918 6.918 0 001.656-2.53c.344-.822.561-1.825.62-3.102.059-1.28.072-1.689.072-4.948s-.013-3.668-.072-4.948c-.059-1.277-.276-2.28-.62-3.102a6.918 6.918 0 00-1.656-2.53A6.918 6.918 0 0019.05.692c-.822-.344-1.825-.561-3.102-.62C15.668.013 15.259 0 12 0z" />
                            <path
                                d="M12 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0 10.162a3.999 3.999 0 110-7.998 3.999 3.999 0 010 7.998z" />
                            <circle cx="18.406" cy="5.594" r="1.44" />
                        </svg>
                    </a>
                </div>
                <div class="hidden sm:flex items-center gap-8 uppercase tracking-wider font-light">
                    <span>michaelhoevents@gmail.com</span>
                    <span>+639173062531</span>
                </div>
            </div>
        </div>
        <!-- Navbar - Centered Logo Layout with Mobile Support -->
        <header id="navbar" class="bg-white border-b border-gray-100">
            <nav class="mx-auto max-w-screen-xl px-6 lg:px-12">

                <!-- Desktop Navigation -->
                <div class="hidden lg:flex py-6 items-center justify-between">

                    <!-- Left Navigation -->
                    <div class="flex items-center gap-8 flex-1">
                        <div class="text-center">
                            <a href="{{ url('/') }}"
                                class="text-xs uppercase tracking-widest font-medium text-gray-700 hover:text-black transition-colors duration-300">
                                Home
                            </a>
                        </div>

                        <div class="text-center">
                            <a href="{{ url('/#service-section') }}"
                                class="text-xs uppercase tracking-widest font-medium text-gray-700 hover:text-black transition-colors duration-300">
                                About
                            </a>
                        </div>

                        <!-- Services Dropdown -->
                        <div class="relative text-center" x-data="{ open: false }" @mouseenter="open = true"
                            @mouseleave="open = false">
                            <button @click="open = !open"
                                class="flex items-center text-xs uppercase gap-1 font-medium tracking-widest text-gray-700 hover:text-black transition-colors duration-300">
                                Services
                                <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-180': open }" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 transform scale-100"
                                x-transition:leave-end="opacity-0 transform scale-95"
                                class="absolute top-full left-0 mt-2 w-56 bg-white border border-gray-200 shadow-lg z-50"
                                style="display: none;">

                                <a href="{{ route('services.index') }}"
                                    class="block px-6 py-3 bg-gray-50 text-black font-semibold hover:bg-gray-100 transition-colors duration-200">
                                    All Services
                                </a>

                                <div class="border-t border-gray-100"></div>

                                @foreach($categories as $category)
                                <a href="{{ route('services.category', $category) }}"
                                    class="block px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-black transition-colors duration-200">
                                    {{ ucfirst($category) }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Center Logo -->
                    <div class="flex-shrink-0 mx-8">
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('images/favicon.png') }}" alt="Logo" class="h-16">
                        </a>
                    </div>

                    <!-- Right Navigation -->
                    <div class="flex items-center gap-8 flex-1 justify-end">
                        <div class="text-center">
                            <a href="{{ route('events-showcase.index') }}"
                                class="text-xs uppercase tracking-widest font-medium text-gray-700 hover:text-black transition-colors duration-300">
                                Portfolio
                            </a>
                        </div>

                        <div class="text-center">
                            <a href="{{ url('/contact') }}"
                                class="text-xs uppercase tracking-widest font-medium text-gray-700 hover:text-black transition-colors duration-300">
                                Contact Us
                            </a>
                        </div>

                        <div class="text-center">
                            <a href="{{ Route::has('login') ? route('login') : '#' }}"
                                class="text-xs uppercase tracking-widest font-medium text-gray-700 hover:text-black transition-colors duration-300">
                                Account
                            </a>
                        </div>
                    </div>

                </div>

                <!-- Mobile Navigation -->
                <div class="lg:hidden py-4 flex items-center justify-between" x-data="{ mobileOpen: false }">
                    <!-- Mobile Logo -->
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('images/favicon.png') }}" alt="Logo" class="h-12">
                    </a>

                    <!-- Mobile Menu Button -->
                    <button @click="mobileOpen = !mobileOpen" class="text-gray-700 hover:text-black p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Mobile Dropdown Menu -->
                    <div x-show="mobileOpen" x-cloak x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-2"
                        class="absolute top-full left-0 right-0 bg-white border-b border-gray-200 shadow-lg z-50"
                        style="display: none;">

                        <div class="px-6 py-4 space-y-4">
                            <a href="{{ url('/') }}"
                                class="block text-sm uppercase tracking-wider text-gray-700 hover:text-black">Home</a>
                            <a href="{{ url('/about') }}"
                                class="block text-sm uppercase tracking-wider text-gray-700 hover:text-black">About</a>
                            <a href="{{ route('services.index') }}"
                                class="block text-sm uppercase tracking-wider text-gray-700 hover:text-black">Services</a>
                            <a href="{{ url('/portfolio') }}"
                                class="block text-sm uppercase tracking-wider text-gray-700 hover:text-black">Portfolio</a>
                            <a href="{{ url('/blog') }}"
                                class="block text-sm uppercase tracking-wider text-gray-700 hover:text-black">Blog</a>
                            <a href="{{ url('/contact') }}"
                                class="block text-sm uppercase tracking-wider text-gray-700 hover:text-black">Contact
                                Us</a>
                            <a href="{{ Route::has('login') ? route('login') : '#' }}"
                                class="block text-sm uppercase tracking-wider text-gray-700 hover:text-black">Account</a>
                        </div>
                    </div>
                </div>

            </nav>
        </header>
    </div>

    <div id="navbar-spacer" class="h-0"></div>

    <!-- PAGE HEADER -  -->
    <div class="relative bg-gradient-to-b from-gray-50 to-white overflow-hidden">
        <!--  element -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-0 left-0 w-96 h-96 bg-black transform -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-black transform translate-x-1/2 translate-y-1/2"></div>
        </div>

        <div class="relative mx-auto max-w-screen-xl px-6 lg:px-12 py-20 text-center">
            <h1 class="text-5xl sm:text-6xl font-light mb-4 tracking-tight"
                style="font-family: 'Playfair Display', serif;">
                {{ ucfirst($category) }} <span class="italic font-normal">Packages</span>
            </h1>
            <p class="text-lg text-gray-600 font-light tracking-wide" style="font-family: 'Cormorant Garamond', serif;">
                Curated experiences for your extraordinary {{ strtolower($category) }} celebration
            </p>
            <!-- Elegant divider -->
            <div class="mt-8 flex items-center justify-center">
                <div class="h-px w-24 bg-gray-900"></div>
                <div class="mx-3 w-1.5 h-1.5 bg-gray-900"></div>
                <div class="h-px w-24 bg-gray-900"></div>
            </div>
        </div>
    </div>

    <!-- PACKAGES SECTION -->
    <section class="py-20 bg-white">
        <div class="mx-auto max-w-screen-xl px-6 lg:px-12">

            @if($packages->count() > 0)
            <!-- Package Count - More subtle -->
            <div class="text-center mb-16">
                <p class="text-xs uppercase tracking-widest text-gray-500">
                    Showing {{ $packages->count() }} exclusive {{ strtolower($category) }} package{{ $packages->count()
                    > 1 ? 's' : '' }}
                </p>
            </div>

            <!-- Packages Grid - Clean, minimal cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-12">
                @foreach($packages as $package)
                <div class="group">
                    <!-- Package Image Container - No rounded corners -->
                    <div class="relative aspect-[3/4] overflow-hidden bg-gray-100 mb-6">
                        @if($package->images->isNotEmpty())
                        <img src="{{ asset('storage/' . $package->images->first()->path) }}" alt="{{ $package->name }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        @else
                        <div
                            class="w-full h-full flex items-center justify-center bg-gradient-to-b from-gray-50 to-gray-100">
                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        @endif

                        <!-- Elegant overlay on hover -->
                        <div
                            class="absolute inset-0 bg-black opacity-0 group-hover:opacity-20 transition-opacity duration-500">
                        </div>
                    </div>

                    <!-- Package Content -->
                    <div class="text-center">
                        <!-- Package Name -->
                        <h3 class="text-2xl font-light mb-2" style="font-family: 'Playfair Display', serif;">
                            {{ $package->name }}
                        </h3>

                        <!-- Price - More prominent -->
                        {{-- <p class="text-3xl font-light text-black mb-4">
                            ₱{{ number_format($package->price, 0, ',', ',') }}
                        </p> --}}

                        <!-- Description -->
                        @if($package->description)
                        <p class="text-sm text-gray-600 leading-relaxed mb-6 line-clamp-3"
                            style="font-family: 'Inter', sans-serif;">
                            {{ $package->description }}
                        </p>
                        @endif

                        <!-- Inclusions Preview -->
                        @if($package->inclusions)
                        <div class="mb-6">
                            <p class="text-xs uppercase tracking-wider text-gray-500 mb-3">Package Includes</p>
                            @php
                            // Handle different types of inclusions data
                            if (is_string($package->inclusions)) {
                            $inclusions = json_decode($package->inclusions, true) ?? [];
                            } elseif ($package->inclusions instanceof \Illuminate\Support\Collection ||
                            $package->inclusions instanceof \Illuminate\Database\Eloquent\Collection) {
                            $inclusions = $package->inclusions->toArray();
                            } elseif (is_array($package->inclusions)) {
                            $inclusions = $package->inclusions;
                            } else {
                            $inclusions = [];
                            }

                            // Ensure we have an array before using array_slice
                            $inclusions = array_values($inclusions); // Reset array keys
                            $previewInclusions = array_slice($inclusions, 0, 3);
                            @endphp
                            @if(!empty($previewInclusions))
                            <ul class="text-xs text-gray-600 space-y-1">
                                @foreach($previewInclusions as $inclusion)
                                <li>{{ is_array($inclusion) ? ($inclusion['name'] ?? $inclusion[0] ?? '') : $inclusion
                                    }}</li>
                                @endforeach
                                @if(count($inclusions) > 3)
                                <li class="text-gray-400 italic">+{{ count($inclusions) - 3 }} more...</li>
                                @endif
                            </ul>
                            @endif
                        </div>
                        @endif

                        <!-- CTA Button - Minimal, elegant -->
                        <a href="{{ route('services.show', $package->id) }}"
                            class="inline-block px-8 py-3 bg-black text-white text-xs uppercase tracking-widest hover:bg-gray-900 transition-colors duration-300">
                            Inquire
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            @else
            <!-- No Packages Message - More elegant -->
            <div class="text-center py-20">
                <div class="mb-8">
                    <svg class="w-20 h-20 text-gray-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                </div>
                <h2 class="text-3xl font-light mb-3" style="font-family: 'Playfair Display', serif;">
                    No Packages Available
                </h2>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    We're currently updating our {{ strtolower($category) }} packages.
                    Please check back soon or contact us for custom arrangements.
                </p>
                <a href="{{ route('services.index') }}"
                    class="inline-block px-8 py-3 border border-black text-black text-xs uppercase tracking-widest hover:bg-black hover:text-white transition-all duration-300">
                    Explore Other Services
                </a>
            </div>
            @endif

        </div>
    </section>

    <!-- CTA Section - Elegant minimal design -->
    <section class="py-20 bg-gray-50 border-t border-gray-100">
        <div class="mx-auto max-w-screen-xl px-6 lg:px-12 text-center">
            <h2 class="text-4xl font-light mb-4" style="font-family: 'Playfair Display', serif;">
                Ready to Create Something <span class="italic">Extraordinary?</span>
            </h2>
            <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto font-light">
                Let us transform your vision into an unforgettable experience
            </p>
            <div class="flex items-center justify-center gap-4">
                <a href="tel:+639173062531"
                    class="px-8 py-3 bg-black text-white text-xs uppercase tracking-widest hover:bg-gray-900 transition-colors duration-300">
                    Schedule Consultation
                </a>
                <a href="{{ route('services.index') }}"
                    class="px-8 py-3 border border-black text-black text-xs uppercase tracking-widest hover:bg-black hover:text-white transition-all duration-300">
                    View All Services
                </a>
            </div>
        </div>
    </section>

    <!-- FOOTER -  -->
    <footer class="bg-black text-white py-16">
        <div class="mx-auto max-w-screen-xl px-6 lg:px-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">

                <!-- Column 1: Brand -->
                <div>
                    <h3 class="text-2xl font-light mb-4" style="font-family: 'Playfair Display', serif;">
                        Michael Ho Events
                    </h3>
                    <p class="text-sm text-gray-400 leading-relaxed mb-6">
                        Crafting extraordinary celebrations with impeccable attention to detail and timeless elegance.
                    </p>
                    <div class="flex items-center gap-4">
                        <a href="https://www.facebook.com/MichaelHoEventsPlanningandCoordinating/" target="_blank"
                            class="text-gray-400 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M22.675 0H1.325C.593 0 0 .593 0 1.325v21.351C0 23.406.593 24 1.325 24H12.82v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.464.099 2.795.143v3.24l-1.918.001c-1.504 0-1.794.716-1.794 1.764v2.312h3.587l-.467 3.622h-3.12V24h6.116C23.406 24 24 23.406 24 22.676V1.325C24 .593 23.406 0 22.675 0z" />
                            </svg>
                        </a>
                        <a href="https://www.instagram.com/michaelhoevents/?hl=en" target="_blank"
                            class="text-gray-400 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 1.17.056 1.97.24 2.43.403a4.92 4.92 0 011.675 1.087 4.92 4.92 0 011.087 1.675c.163.46.347 1.26.403 2.43.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.056 1.17-.24 1.97-.403 2.43a4.918 4.918 0 01-1.087 1.675 4.918 4.918 0 01-1.675 1.087c-.46.163-1.26.347-2.43.403-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.17-.056-1.97-.24-2.43-.403a4.918 4.918 0 01-1.675-1.087 4.918 4.918 0 01-1.087-1.675c-.163-.46-.347-1.26-.403-2.43C2.175 15.747 2.163 15.367 2.163 12s.012-3.584.07-4.85c.056-1.17.24-1.97.403-2.43a4.92 4.92 0 011.087-1.675A4.92 4.92 0 015.398 2.636c.46-.163 1.26-.347 2.43-.403C8.416 2.175 8.796 2.163 12 2.163zm0-2.163C8.741 0 8.332.013 7.052.072 5.775.131 4.772.348 3.95.692a6.918 6.918 0 00-2.53 1.656A6.918 6.918 0 00.692 4.878c-.344.822-.561 1.825-.62 3.102C.013 8.332 0 8.741 0 12c0 3.259.013 3.668.072 4.948.059 1.277.276 2.28.62 3.102a6.918 6.918 0 001.656 2.53 6.918 6.918 0 002.53 1.656c.822.344 1.825.561 3.102.62C8.332 23.987 8.741 24 12 24s3.668-.013 4.948-.072c1.277-.059 2.28-.276 3.102-.62a6.918 6.918 0 002.53-1.656 6.918 6.918 0 001.656-2.53c.344-.822.561-1.825.62-3.102.059-1.28.072-1.689.072-4.948s-.013-3.668-.072-4.948c-.059-1.277-.276-2.28-.62-3.102a6.918 6.918 0 00-1.656-2.53A6.918 6.918 0 0019.05.692c-.822-.344-1.825-.561-3.102-.62C15.668.013 15.259 0 12 0z" />
                                <path
                                    d="M12 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0 10.162a3.999 3.999 0 110-7.998 3.999 3.999 0 010 7.998z" />
                                <circle cx="18.406" cy="5.594" r="1.44" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Column 2: Quick Links -->
                <div>
                    <h4 class="text-xs uppercase tracking-widest mb-6 text-gray-400">Navigation</h4>
                    <ul class="space-y-3 text-sm">
                        <li>
                            <a href="#" class="text-gray-300 hover:text-white transition-colors">
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('services.index') }}"
                                class="text-gray-300 hover:text-white transition-colors">
                                Services
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('events-showcase.index') }}"
                                class="text-gray-300 hover:text-white transition-colors">
                                Portfolio
                            </a>
                        </li>
                        <li>
                            <a href="{{ Route::has('login') ? route('login') : '#' }}"
                                class="text-gray-300 hover:text-white transition-colors">
                                Account
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Column 3: Contact -->
                <div>
                    <h4 class="text-xs uppercase tracking-widest mb-6 text-gray-400">Contact</h4>
                    <ul class="space-y-4 text-sm">
                        <li>
                            <a href="mailto:michaelhoevents@gmail.com"
                                class="text-gray-300 hover:text-white transition-colors">
                                michaelhoevents@gmail.com
                            </a>
                        </li>
                        <li>
                            <a href="tel:+639173062531" class="text-gray-300 hover:text-white transition-colors">
                                +63 917 306 2531
                            </a>
                        </li>
                    </ul>
                </div>

            </div>

            <!-- Bottom Bar -->
            <div class="pt-8 border-t border-gray-800">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-xs text-gray-400 uppercase tracking-wider">
                        © {{ date('Y') }} Michael Ho Events. All rights reserved.
                    </p>
                    <p class="text-xs text-gray-400 italic" style="font-family: 'Cormorant Garamond', serif;">
                        Creating memories, one event at a time
                    </p>
                </div>
            </div>
        </div>
    </footer>

</body>

</html>