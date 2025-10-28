<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Michael Ho Events Styling And Coordination') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=Inter:wght@300;400;500;600&family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,400&display=swap"
        rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        /* Custom elegant styles */
        .elegant-shadow {
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.08);
        }

        .elegant-shadow-hover {
            box-shadow: 0 10px 60px rgba(0, 0, 0, 0.12);
        }

        /* Elegant animations */
        .animate-on-scroll {
            opacity: 0;
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .animate-on-scroll.from-left {
            transform: translateX(-30px);
        }

        .animate-on-scroll.from-right {
            transform: translateX(30px);
        }

        .animate-on-scroll.from-bottom {
            transform: translateY(30px);
        }

        .animate-on-scroll.scale-up {
            transform: scale(0.95);
        }

        .animate-on-scroll.animate-in {
            opacity: 1;
            transform: translateX(0) translateY(0) scale(1);
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

        <!-- Navbar -  -->
        <header id="navbar" class="bg-white border-b border-gray-100">
            <nav class="mx-auto max-w-screen-xl px-6 lg:px-12">
                <div class="py-6 flex items-center justify-between">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('images/favicon.png') }}" alt="Logo" class="h-14">
                    </a>
                    <div class="flex items-center gap-10 text-xs uppercase tracking-widest font-medium">
                        <a href="{{ url('/') }}" class="text-gray-700 hover:text-black transition-colors duration-300">
                            Home
                        </a>

                        <!-- Services Dropdown -->
                        <div class="relative" x-data="{ open: false }" @mouseenter="open = true"
                            @mouseleave="open = false">
                            <button @click="open = !open"
                                class="flex items-center text-xs uppercase gap-1 text-black border-b-2 border-black pb-1 transition-colors duration-300">
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
                                class="absolute top-full left-0 mt-2 w-56 bg-white border border-gray-200 shadow-lg"
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

                        <a href="{{ Route::has('login') ? route('login') : '#' }}"
                            class="text-gray-700 hover:text-black transition-colors duration-300">
                            Account
                        </a>
                    </div>
                </div>
            </nav>
        </header>
    </div>

    <!-- Spacer for sticky navbar -->
    <div id="navbar-spacer" class="h-0"></div>

    <!-- HERO SECTION - Elegant minimal -->
    <div class="relative min-h-screen flex flex-col items-center justify-center overflow-hidden"
        style="background-image: url('{{ asset('images/hero.jpg') }}'); background-size: cover; background-position: center;">

        <!-- Sophisticated overlay gradient -->
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/50 to-black/70"></div>

        <div class="relative text-center text-white px-6 max-w-screen-xl mx-auto">
            <h1 class="text-5xl sm:text-7xl font-light mb-6 tracking-tight animate-on-scroll from-bottom"
                style="font-family: 'Playfair Display', serif;">
                Michael Ho Events
            </h1>
            <p class="text-xl sm:text-2xl mb-3 font-light italic animate-on-scroll from-bottom"
                style="font-family: 'Playfair Display', serif; transition-delay: 0.2s;">
                Styling & Coordination
            </p>
            <p class="text-base sm:text-lg mb-12 font-light tracking-wide animate-on-scroll from-bottom"
                style="font-family: 'Cormorant Garamond', serif; transition-delay: 0.3s;">
                Creating extraordinary celebrations with timeless elegance
            </p>

            <!-- Elegant CTA button -->
            <div class="animate-on-scroll scale-up" style="transition-delay: 0.4s;">
                <a href="{{ route('services.index') }}"
                    class="inline-block px-10 py-4 border-2 border-white text-white text-xs uppercase tracking-widest hover:bg-white hover:text-black transition-all duration-500">
                    Explore Our Services
                </a>
            </div>
        </div>

        <!-- Elegant scroll indicator -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 text-white animate-bounce">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M19 14l-7 7m0 0l-7-7m7 7V3" />
            </svg>
        </div>
    </div>

    <!-- PORTFOLIO SECTION - Elegant grid -->
    <section class="py-24 bg-white">
        <div class="mx-auto max-w-screen-xl px-6 lg:px-12">
            <!-- Section Header -->
            <div class="text-center mb-16 animate-on-scroll from-bottom">
                <h2 class="text-5xl font-light mb-4" style="font-family: 'Playfair Display', serif;">
                    Our <span class="italic">Portfolio</span>
                </h2>
                <p class="text-lg text-gray-600 font-light" style="font-family: 'Cormorant Garamond', serif;">
                    A curated collection of unforgettable celebrations
                </p>
                <!-- Elegant divider -->
                <div class="mt-8 flex items-center justify-center">
                    <div class="h-px w-24 bg-gray-900"></div>
                    <div class="mx-3 w-1.5 h-1.5 bg-gray-900"></div>
                    <div class="h-px w-24 bg-gray-900"></div>
                </div>
            </div>

            <!-- Events Grid - No rounded corners -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                @if($eventShowcases->count() > 0)
                @foreach($eventShowcases as $index => $showcase)
                <div
                    class="group relative overflow-hidden animate-on-scroll {{ $index === 0 ? 'from-left' : ($index === 1 ? 'scale-up' : 'from-right') }}">
                    <div class="aspect-[3/4] overflow-hidden bg-gray-100">
                        <img src="{{ $showcase->image_url }}" alt="{{ $showcase->event_name }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    </div>
                    <!-- Elegant overlay -->
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>

                    <!-- Content overlay -->
                    <div
                        class="absolute bottom-0 left-0 right-0 p-6 text-white transform translate-y-full group-hover:translate-y-0 transition-transform duration-500">
                        <div class="mb-2">
                            <span
                                class="inline-block px-3 py-1 border border-white/50 text-[10px] font-medium uppercase tracking-wider">
                                {{ $showcase->type }}
                            </span>
                        </div>
                        <h3 class="text-xl font-light mb-2" style="font-family: 'Playfair Display', serif;">
                            {{ $showcase->event_name }}
                        </h3>
                        {{-- <p class="text-sm opacity-90 font-light">
                            {{ $showcase->date->format('F Y') }}
                        </p> --}}
                    </div>
                </div>
                @endforeach
                @else
                {{-- Fallback showcases with elegant design --}}
                @foreach(['Birthday Celebration', 'Wedding Reception', 'Corporate Event'] as $index => $event)
                <div
                    class="group relative overflow-hidden animate-on-scroll {{ $index === 0 ? 'from-left' : ($index === 1 ? 'scale-up' : 'from-right') }}">
                    <div class="aspect-[3/4] overflow-hidden bg-gradient-to-b from-gray-100 to-gray-200">
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <div
                        class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-black/80 to-transparent text-white">
                        <h3 class="text-xl font-light" style="font-family: 'Playfair Display', serif;">
                            {{ $event }}
                        </h3>
                        <p class="text-sm opacity-75">Premium Event Experience</p>
                    </div>
                </div>
                @endforeach
                @endif

            </div>

            <!-- View All Button -->
            <div class="text-center mt-16 animate-on-scroll from-bottom">
                <a href="{{ route('events-showcase.index') }}"
                    class="inline-block px-8 py-3 border border-black text-black text-xs uppercase tracking-widest hover:bg-black hover:text-white transition-all duration-300">
                    View Full Portfolio
                </a>
            </div>
        </div>
    </section>

    <!-- SERVICES SECTION - Elegant cards -->
    <section class="py-24 bg-gray-50">
        <div class="mx-auto max-w-screen-xl px-6 lg:px-12">
            <div class="text-center mb-16 animate-on-scroll from-bottom">
                <h2 class="text-5xl font-light mb-4" style="font-family: 'Playfair Display', serif;">
                    Our <span class="italic">Services</span>
                </h2>
                <p class="text-lg text-gray-600 font-light" style="font-family: 'Cormorant Garamond', serif;">
                    Comprehensive event planning tailored to your vision
                </p>
                <!-- Elegant divider -->
                <div class="mt-8 flex items-center justify-center">
                    <div class="h-px w-24 bg-gray-900"></div>
                    <div class="mx-3 w-1.5 h-1.5 bg-gray-900"></div>
                    <div class="h-px w-24 bg-gray-900"></div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach(['Full Planning', 'Partial Planning', 'Day Coordination'] as $service)
                <div class="text-center animate-on-scroll from-bottom"
                    style="transition-delay: {{ $loop->index * 0.1 }}s;">
                    <div class="mb-6">
                        <div class="w-20 h-20 mx-auto border border-gray-300 flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-light mb-3" style="font-family: 'Playfair Display', serif;">
                        {{ $service }}
                    </h3>
                    <p class="text-sm text-gray-600 mb-6 leading-relaxed">
                        Professional event {{ strtolower($service) }} services designed to bring your vision to life
                        with elegance and precision.
                    </p>
                    <a href="{{ route('services.index') }}"
                        class="text-xs uppercase tracking-wider text-black hover:tracking-widest transition-all duration-300">
                        Learn More →
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- PACKAGES SECTION - Elegant carousel -->
    <section class="py-24 bg-white">
        <div class="mx-auto max-w-screen-xl px-6 lg:px-12">
            <div class="text-center mb-16 animate-on-scroll from-bottom">
                <h2 class="text-5xl font-light mb-4" style="font-family: 'Playfair Display', serif;">
                    Featured <span class="italic">Packages</span>
                </h2>
                <p class="text-lg text-gray-600 font-light" style="font-family: 'Cormorant Garamond', serif;">
                    Curated collections for every celebration
                </p>
                <!-- Elegant divider -->
                <div class="mt-8 flex items-center justify-center">
                    <div class="h-px w-24 bg-gray-900"></div>
                    <div class="mx-3 w-1.5 h-1.5 bg-gray-900"></div>
                    <div class="h-px w-24 bg-gray-900"></div>
                </div>
            </div>

            <!-- Packages Carousel -->
            <div x-data="{
                currentSlide: 0,
                totalSlides: {{ $packages->count() }},
                itemsPerView: window.innerWidth >= 1024 ? 3 : (window.innerWidth >= 768 ? 2 : 1),
                get maxSlide() {
                    return Math.max(0, this.totalSlides - this.itemsPerView);
                },
                next() {
                    if (this.currentSlide < this.maxSlide) {
                        this.currentSlide++;
                    }
                },
                prev() {
                    if (this.currentSlide > 0) {
                        this.currentSlide--;
                    }
                },
                init() {
                    window.addEventListener('resize', () => {
                        this.itemsPerView = window.innerWidth >= 1024 ? 3 : (window.innerWidth >= 768 ? 2 : 1);
                        if (this.currentSlide > this.maxSlide) {
                            this.currentSlide = this.maxSlide;
                        }
                    });
                }
            }" class="relative animate-on-scroll scale-up">

                <div class="overflow-hidden">
                    <div class="flex transition-transform duration-700 ease-out"
                        :style="`transform: translateX(-${currentSlide * (100 / itemsPerView)}%)`">
                        @foreach($packages as $package)
                        <div class="w-full md:w-1/2 lg:w-1/3 flex-shrink-0 px-4">
                            <div class="group">
                                <!-- Package Image - No rounded corners -->
                                <div class="relative aspect-[3/4] overflow-hidden bg-gray-100 mb-6">
                                    @if($package->images->isNotEmpty())
                                    <img src="{{ asset('storage/' . $package->images->first()->path) }}"
                                        alt="{{ $package->name }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                    @else
                                    <div
                                        class="w-full h-full flex items-center justify-center bg-gradient-to-b from-gray-50 to-gray-100">
                                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    @endif

                                    <!-- Elegant overlay -->
                                    <div
                                        class="absolute inset-0 bg-black opacity-0 group-hover:opacity-20 transition-opacity duration-500">
                                    </div>
                                </div>

                                <!-- Package Content -->
                                <div class="text-center">
                                    <h3 class="text-2xl font-light mb-2"
                                        style="font-family: 'Playfair Display', serif;">
                                        {{ $package->name }}
                                    </h3>

                                    <p class="text-3xl font-light text-black mb-4">
                                        ₱{{ number_format($package->price, 0, ',', ',') }}
                                    </p>

                                    <p class="text-sm text-gray-600 mb-6 line-clamp-2">
                                        {{ $package->description ?? 'Exclusive package for your special celebration' }}
                                    </p>

                                    <a href="{{ route('services.show', $package->id) }}"
                                        class="inline-block px-8 py-3 border border-black text-black text-xs uppercase tracking-widest hover:bg-black hover:text-white transition-all duration-300">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Navigation Arrows -->
                @if($packages->count() > 1)
                <button @click="prev" :disabled="currentSlide === 0"
                    :class="{ 'opacity-30 cursor-not-allowed': currentSlide === 0 }"
                    class="absolute left-0 top-1/3 -translate-y-1/2 -translate-x-4 bg-white border border-gray-200 p-3 hover:border-black transition-all z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <button @click="next" :disabled="currentSlide >= maxSlide"
                    :class="{ 'opacity-30 cursor-not-allowed': currentSlide >= maxSlide }"
                    class="absolute right-0 top-1/3 -translate-y-1/2 translate-x-4 bg-white border border-gray-200 p-3 hover:border-black transition-all z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
                @endif

                <!-- Dots Indicator -->
                @if($packages->count() > 1)
                <div class="flex justify-center gap-3 mt-10">
                    <template x-for="i in (maxSlide + 1)" :key="i">
                        <button @click="currentSlide = i - 1"
                            :class="{ 'bg-black w-8': currentSlide === i - 1, 'bg-gray-300 w-2': currentSlide !== i - 1 }"
                            class="h-2 transition-all duration-300"></button>
                    </template>
                </div>
                @endif
            </div>
        </div>
    </section>

    <!-- REVIEWS SECTION - Elegant testimonials -->
    <section class="py-24 bg-gray-50">
        <div class="mx-auto max-w-screen-xl px-6 lg:px-12">
            <div class="text-center mb-16 animate-on-scroll from-bottom">
                <h2 class="text-5xl font-light mb-4" style="font-family: 'Playfair Display', serif;">
                    Client <span class="italic">Testimonials</span>
                </h2>
                <p class="text-lg text-gray-600 font-light" style="font-family: 'Cormorant Garamond', serif;">
                    Words from our cherished clients
                </p>
                <!-- Elegant divider -->
                <div class="mt-8 flex items-center justify-center">
                    <div class="h-px w-24 bg-gray-900"></div>
                    <div class="mx-3 w-1.5 h-1.5 bg-gray-900"></div>
                    <div class="h-px w-24 bg-gray-900"></div>
                </div>
            </div>

            <!-- Reviews Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($publishedFeedback as $feedback)
                <div class="bg-white p-8 border border-gray-200 animate-on-scroll from-bottom"
                    style="transition-delay: {{ $loop->index * 0.1 }}s;">
                    <!-- Stars Rating -->
                    <div class="flex items-center justify-center mb-6">
                        @for($i = 1; $i <= 5; $i++) <svg
                            class="w-5 h-5 {{ $i <= $feedback->rating ? 'text-black' : 'text-gray-300' }}"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            @endfor
                    </div>

                    <!-- Review Content -->
                    <blockquote class="text-center mb-6">
                        <p class="text-gray-700 italic leading-relaxed"
                            style="font-family: 'Cormorant Garamond', serif; font-size: 1.1rem;">
                            "{{ $feedback->message }}"
                        </p>
                    </blockquote>

                    <!-- Reviewer Info -->
                    <div class="text-center border-t border-gray-100 pt-6">
                        <p class="font-medium text-sm uppercase tracking-wider text-gray-900">
                            {{ $feedback->customer->name ?? 'Guest' }}
                        </p>
                        @if($feedback->event)
                        <p class="text-xs text-gray-500 mt-1 uppercase tracking-wider">
                            {{ $feedback->event->type ?? '' }} Event
                        </p>
                        @endif
                        <p class="text-xs text-gray-400 mt-2">
                            {{ $feedback->created_at->format('F Y') }}
                        </p>
                    </div>
                </div>
                @empty
                <!-- Fallback reviews if no database reviews -->
                @php
                $fallbackReviews = [
                [
                'rating' => 5,
                'message' => 'Michael Ho Events transformed our wedding into a fairytale. Every detail was perfect, and
                the coordination was flawless.',
                'name' => 'Sarah & James',
                'event' => 'Wedding',
                'date' => 'December 2023'
                ],
                [
                'rating' => 5,
                'message' => 'Professional, creative, and absolutely wonderful to work with. They made our corporate
                event truly memorable.',
                'name' => 'Robert Chen',
                'event' => 'Corporate',
                'date' => 'November 2023'
                ],
                [
                'rating' => 5,
                'message' => 'The attention to detail and personal touch they brought to our celebration was beyond our
                expectations.',
                'name' => 'Maria Santos',
                'event' => 'Birthday',
                'date' => 'October 2023'
                ]
                ];
                @endphp

                @foreach($fallbackReviews as $index => $review)
                <div class="bg-white p-8 border border-gray-200 animate-on-scroll from-bottom"
                    style="transition-delay: {{ $index * 0.1 }}s;">
                    <!-- Stars Rating -->
                    <div class="flex items-center justify-center mb-6">
                        @for($i = 1; $i <= 5; $i++) <svg
                            class="w-5 h-5 {{ $i <= $review['rating'] ? 'text-black' : 'text-gray-300' }}"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            @endfor
                    </div>

                    <!-- Review Content -->
                    <blockquote class="text-center mb-6">
                        <p class="text-gray-700 italic leading-relaxed"
                            style="font-family: 'Cormorant Garamond', serif; font-size: 1.1rem;">
                            "{{ $review['message'] }}"
                        </p>
                    </blockquote>

                    <!-- Reviewer Info -->
                    <div class="text-center border-t border-gray-100 pt-6">
                        <p class="font-medium text-sm uppercase tracking-wider text-gray-900">
                            {{ $review['name'] }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1 uppercase tracking-wider">
                            {{ $review['event'] }} Event
                        </p>
                        <p class="text-xs text-gray-400 mt-2">
                            {{ $review['date'] }}
                        </p>
                    </div>
                </div>
                @endforeach
                @endforelse
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-white border-t border-gray-100">
        <div class="mx-auto max-w-screen-xl px-6 lg:px-12 text-center">
            <h2 class="text-4xl font-light mb-4" style="font-family: 'Playfair Display', serif;">
                Let's Create Something <span class="italic">Extraordinary</span>
            </h2>
            <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto font-light">
                Begin your journey to an unforgettable celebration
            </p>
            <div class="flex items-center justify-center gap-4">
                <a href="tel:+639173062531"
                    class="px-8 py-3 bg-black text-white text-xs uppercase tracking-widest hover:bg-gray-900 transition-colors">
                    Schedule Consultation
                </a>
                <a href="{{ route('services.index') }}"
                    class="px-8 py-3 border border-black text-black text-xs uppercase tracking-widest hover:bg-black hover:text-white transition-all duration-300">
                    View Services
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
                        Creating extraordinary celebrations with impeccable attention to detail and timeless elegance.
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
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 1.17.056 1.97.24 2.43.403a4.92 4.92 0 011.675 1.087 4.92 4.92 0 011.087 1.675c.163.46.347 1.26.403 2.43.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.056 1.17-.24 1.97-.403 2.43a4.918 4.918 0 01-1.087 1.675 4.918 4.918 0 01-1.675 1.087c-.46.163-1.26.347-2.43.403-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.17-.056-1.97-.24-2.43-.403a4.918 4.918 0 01-1.675-1.087 4.918 4.918 0 01-1.087-1.675c-.163-.46-.347-1.26-.403-2.43C2.175 15.747 2.163 15.367 2.163 12s.012-3.584.07-4.85c.056-1.17.24-1.97.403-2.43a4.92 4.92 0 011.087-1.675A4.92 4.92 0 015.398 2.636c.46-.163 1.26-.347 2.43-.403C8.416 2.175 8.796 2.163 12 2.163zm0-2.163C8.741 0 8.332.013 7.052.072 5.775.131 4.772.348 3.95.692a6.918 6.918 0 00-2.53 1.656A6.918 6.918 0 00.692 4.878c-.344.822-.561 1.825-.62 3.102C.013 8.332 0 8.741 0 12c0 3.259.013 3.668.072 4.948.059 1.277.276 2.28.62 3.102a6.918 6.918 0 001.656 2.53 6.918 6.918 0 002.53 1.656c.822.344 1.825.561 3.102.62C8.332 23.987 8.741 24 12 24s3.668-.013 4.948-.072c1.277-.059 2.28-.276 3.102-.62a6.918 6.918 0 002.53-1.656 6.918 6.918 0 001.656-2.53c.344-.822.561-1.825.62-3.102.059-1.28.072-1.689.072-4.948s-.013-3.668-.072-4.948c-.059-1.277-.276-2.28-.62-3.102a6.918 6.918 0 00-1.656-2.53A6.918 6.918 0 0020.05.692c-.822-.344-1.825-.561-3.102-.62C15.668.013 15.259 0 12 0z" />
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

    <script>
        const topBar = document.getElementById('top-bar');
        const navbar = document.getElementById('navbar');
        const spacer = document.getElementById('navbar-spacer');
        
        const topBarHeight = topBar.offsetHeight;

        window.addEventListener('scroll', () => {
            const scrollY = window.scrollY || window.pageYOffset;

            if (scrollY >= topBarHeight) {
                navbar.classList.add('fixed', 'top-0', 'left-0', 'w-full', 'z-50');
                topBar.classList.add('hidden');
                spacer.style.height = navbar.offsetHeight + 'px';
            } else {
                navbar.classList.remove('fixed', 'top-0', 'left-0', 'w-full', 'z-50');
                topBar.classList.remove('hidden');
                spacer.style.height = '0px';
            }
        });

        // Scroll Animation Observer
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });
    </script>

</body>

</html>