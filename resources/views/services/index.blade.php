<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Our Services - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Style+Script&family=Dancing+Script:wght@400..700&family=Libre+Caslon+Display&family=Noto+Serif:ital,wght@0,100..900;1,100..900&family=Niconne&display=swap"
        rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50 text-neutral-900 antialiased selection:bg-black selection:text-white">

    <!-- HEADER CONTAINER -->
    <div id="header-container" class="relative z-50">
        <!-- Top Bar -->
        <div id="top-bar" class="bg-gray-950 text-white text-sm">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex items-center justify-between h-10">
                <div class="flex items-center gap-4">
                    <a href="https://www.facebook.com/MichaelHoEventsPlanningandCoordinating/" target="_blank"
                        class="hover:text-gray-400">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M22.675 0H1.325C.593 0 0 .593 0 1.325v21.351C0 23.406.593 24 1.325 24H12.82v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.464.099 2.795.143v3.24l-1.918.001c-1.504 0-1.794.716-1.794 1.764v2.312h3.587l-.467 3.622h-3.12V24h6.116C23.406 24 24 23.406 24 22.676V1.325C24 .593 23.406 0 22.675 0z" />
                        </svg>
                    </a>
                    <a href="https://www.instagram.com/michaelhoevents/?hl=en" target="_blank"
                        class="hover:text-gray-400">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2.163c3.204 0 3.584.012 4.85.07 1.17.056 1.97.24 2.43.403a4.92 4.92 0 011.675 1.087 4.92 4.92 0 011.087 1.675c.163.46.347 1.26.403 2.43.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.056 1.17-.24 1.97-.403 2.43a4.918 4.918 0 01-1.087 1.675 4.918 4.918 0 01-1.675 1.087c-.46.163-1.26.347-2.43.403-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.17-.056-1.97-.24-2.43-.403a4.918 4.918 0 01-1.675-1.087 4.918 4.918 0 01-1.087-1.675c-.163-.46-.347-1.26-.403-2.43C2.175 15.747 2.163 15.367 2.163 12s.012-3.584.07-4.85c.056-1.17.24-1.97.403-2.43a4.92 4.92 0 011.087-1.675A4.92 4.92 0 015.398 2.636c.46-.163 1.26-.347 2.43-.403C8.416 2.175 8.796 2.163 12 2.163zm0-2.163C8.741 0 8.332.013 7.052.072 5.775.131 4.772.348 3.95.692a6.918 6.918 0 00-2.53 1.656A6.918 6.918 0 00.692 4.878c-.344.822-.561 1.825-.62 3.102C.013 8.332 0 8.741 0 12c0 3.259.013 3.668.072 4.948.059 1.277.276 2.28.62 3.102a6.918 6.918 0 001.656 2.53 6.918 6.918 0 002.53 1.656c.822.344 1.825.561 3.102.62C8.332 23.987 8.741 24 12 24s3.668-.013 4.948-.072c1.277-.059 2.28-.276 3.102-.62a6.918 6.918 0 002.53-1.656 6.918 6.918 0 001.656-2.53c.344-.822.561-1.825.62-3.102.059-1.28.072-1.689.072-4.948s-.013-3.668-.072-4.948c-.059-1.277-.276-2.28-.62-3.102a6.918 6.918 0 00-1.656-2.53A6.918 6.918 0 0019.05.692c-.822-.344-1.825-.561-3.102-.62C15.668.013 15.259 0 12 0z" />
                            <path
                                d="M12 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0 10.162a3.999 3.999 0 110-7.998 3.999 3.999 0 010 7.998z" />
                            <circle cx="18.406" cy="5.594" r="1.44" />
                        </svg>
                    </a>
                </div>
                <div class="hidden sm:flex items-center gap-6 text-xs">
                    <span>michaelhoevents@gmail.com</span>
                    <span>+639173062531</span>
                </div>
            </div>
        </div>

        <!-- Navbar -->
        <header id="navbar" class="bg-white shadow-sm">
            <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="py-5 flex items-center justify-between">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('images/favicon.png') }}" alt="Logo" class="h-16">
                    </a>
                    <div class="flex items-center gap-5 text-sm font-medium">
                        <a href="{{ url('/') }}" class="hover:text-gray-600 transition-colors">Home</a>

                        @foreach($categories as $category)
                        <a href="{{ route('services.category', $category) }}"
                            class="hover:text-gray-600 transition-colors capitalize">
                            {{ $category }}
                        </a>
                        @endforeach

                        <a href="{{ route('services.index') }}"
                            class="hover:text-gray-600 transition-colors">Services</a>
                        <a href="{{ Route::has('login') ? route('login') : '#' }}"
                            class="hover:text-gray-600 transition-colors">Log in</a>
                    </div>
                </div>
            </nav>
        </header>
    </div>

    <div id="navbar-spacer" class="h-0"></div>

    <!-- PAGE HEADER -->
    <div class="bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl sm:text-6xl font-bold mb-4 font-libre">Our Services</h1>
            <p class="text-xl sm:text-2xl font-style-script text-gray-300">
                Crafting unforgettable moments for every occasion
            </p>
            <div class="w-24 h-1 bg-white mx-auto mt-6"></div>
        </div>
    </div>

    <!-- BUDGET FILTER -->
    <div class="bg-white border-b border-gray-200 sticky top-0 z-40 shadow-sm">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
            <form method="GET" action="{{ route('services.index') }}"
                class="flex flex-col sm:flex-row items-center gap-4">
                <div class="flex items-center gap-2 flex-1">
                    <svg class="w-5 h-5 fill-gray-500" viewBox="0 0 36 36" version="1.1"
                        preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <title>peso-solid</title>
                            <path d="M14.18,13.8V16h9.45a5.26,5.26,0,0,0,.08-.89,4.72,4.72,0,0,0-.2-1.31Z"
                                class="clr-i-solid clr-i-solid-path-1"></path>
                            <path d="M14.18,19.7h5.19a4.28,4.28,0,0,0,3.5-1.9H14.18Z"
                                class="clr-i-solid clr-i-solid-path-2"></path>
                            <path d="M19.37,10.51H14.18V12h8.37A4.21,4.21,0,0,0,19.37,10.51Z"
                                class="clr-i-solid clr-i-solid-path-3"></path>
                            <path
                                d="M17.67,2a16,16,0,1,0,16,16A16,16,0,0,0,17.67,2Zm10.5,15.8H25.7a6.87,6.87,0,0,1-6.33,4.4H14.18v6.54a1.25,1.25,0,1,1-2.5,0V17.8H8.76a.9.9,0,1,1,0-1.8h2.92V13.8H8.76a.9.9,0,1,1,0-1.8h2.92V9.26A1.25,1.25,0,0,1,12.93,8h6.44a6.84,6.84,0,0,1,6.15,4h2.65a.9.9,0,0,1,0,1.8H26.09a6.91,6.91,0,0,1,.12,1.3,6.8,6.8,0,0,1-.06.9h2a.9.9,0,0,1,0,1.8Z"
                                class="clr-i-solid clr-i-solid-path-4"></path>
                            <rect x="0" y="0" width="36" height="36" fill-opacity="0"></rect>
                        </g>
                    </svg>
                    <span class="text-sm font-semibold text-gray-700">Budget Range:</span>
                </div>
                <div class="flex items-center gap-4 flex-wrap">
                    <div class="flex items-center gap-2">
                        <label for="min_budget" class="text-sm text-gray-600">Min:</label>
                        <input type="number" name="min_budget" id="min_budget" value="{{ request('min_budget') }}"
                            placeholder="0"
                            class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                    </div>
                    <span class="text-gray-400">—</span>
                    <div class="flex items-center gap-2">
                        <label for="max_budget" class="text-sm text-gray-600">Max:</label>
                        <input type="number" name="max_budget" id="max_budget" value="{{ request('max_budget') }}"
                            placeholder="500000"
                            class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                    </div>
                    <button type="submit"
                        class="px-6 py-2 bg-gray-900 text-white font-semibold rounded-lg hover:bg-black transition-all">
                        Filter
                    </button>
                    @if(request('min_budget') || request('max_budget'))
                    <a href="{{ route('services.index') }}"
                        class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900 transition-colors">
                        Clear
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- PACKAGES BY TYPE -->
    <section class="py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            @forelse($packagesByType as $type => $packages)
            <div id="{{ Str::slug($type) }}" class="mb-20 scroll-mt-20">
                <!-- Type Header -->
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-gray-900 mb-2 font-libre capitalize">{{ $type }}</h2>
                    <p class="text-gray-600">{{ $packages->count() }} package{{ $packages->count() > 1 ? 's' : '' }}
                        available</p>
                    <div class="w-16 h-1 bg-gray-900 mx-auto mt-4"></div>
                </div>

                <!-- Carousel -->
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
                }" class="relative">

                    <!-- Carousel Container -->
                    <div class="overflow-hidden">
                        <div class="flex transition-transform duration-500 ease-out"
                            :style="`transform: translateX(-${currentSlide * (100 / itemsPerView)}%)`">
                            @foreach($packages as $package)
                            <div class="w-full md:w-1/2 lg:w-1/3 flex-shrink-0 px-4">
                                <div
                                    class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 h-full">

                                    <!-- Package Image -->
                                    <div class="relative aspect-[4/3] overflow-hidden bg-gray-200">
                                        @if($package->image_url)
                                        <img src="{{ $package->image_url }}" alt="{{ $package->name }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                        @else
                                        <div
                                            class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                            <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        </div>
                                        @endif
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent">
                                        </div>

                                        <!-- Type Badge -->
                                        <div class="absolute top-4 left-4">
                                            <span
                                                class="inline-block px-4 py-1.5 bg-white/90 backdrop-blur-sm rounded-full text-xs font-bold uppercase tracking-wider text-gray-900">
                                                {{ $package->type }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Package Content -->
                                    <div class="p-6">
                                        <h3
                                            class="text-2xl font-bold text-gray-900 mb-2 font-libre group-hover:text-gray-700 transition-colors">
                                            {{ $package->name }}
                                        </h3>

                                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                            {{ $package->description ?? 'Complete event planning and coordination
                                            services tailored to your needs.' }}
                                        </p>

                                        <!-- Features Preview -->
                                        @if($package->features)
                                        <div class="mb-4 space-y-1">
                                            @foreach(array_slice(explode("\n", $package->features), 0, 3) as $feature)
                                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span class="line-clamp-1">{{ trim($feature) }}</span>
                                            </div>
                                            @endforeach
                                        </div>
                                        @endif

                                        <!-- View Details Button -->
                                        <a href="{{ route('services.show', $package) }}"
                                            class="group/btn relative w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-900 text-white font-semibold rounded-xl overflow-hidden transition-all hover:bg-black hover:shadow-lg">
                                            <span class="relative z-10">View Details</span>
                                            <svg class="w-5 h-5 relative z-10 transition-transform group-hover/btn:translate-x-1"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                            </svg>
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
                        :class="{ 'opacity-50 cursor-not-allowed': currentSlide === 0 }"
                        class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 bg-white rounded-full p-3 shadow-lg hover:bg-gray-100 transition-all z-10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <button @click="next" :disabled="currentSlide >= maxSlide"
                        :class="{ 'opacity-50 cursor-not-allowed': currentSlide >= maxSlide }"
                        class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 bg-white rounded-full p-3 shadow-lg hover:bg-gray-100 transition-all z-10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    @endif

                    <!-- Dots Indicator -->
                    @if($packages->count() > 1)
                    <div class="flex justify-center gap-2 mt-8">
                        <template x-for="i in (maxSlide + 1)" :key="i">
                            <button @click="currentSlide = i - 1"
                                :class="{ 'bg-gray-900 w-8': currentSlide === i - 1, 'bg-gray-300 w-3': currentSlide !== i - 1 }"
                                class="h-3 rounded-full transition-all duration-300"></button>
                        </template>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <!-- Empty State -->
            <div class="text-center py-20">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-6">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2 font-libre">No Services Found</h3>
                <p class="text-gray-600 mb-6">Try adjusting your budget filter.</p>
                <a href="{{ route('services.index') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 text-white font-semibold rounded-xl hover:bg-black transition-all">
                    Clear Filters
                </a>
            </div>
            @endforelse

        </div>
    </section>

    <!-- CTA SECTION -->
    <section class="bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl sm:text-5xl font-bold mb-4 font-libre">Ready to Plan Your Event?</h2>
            <p class="text-xl text-gray-300 mb-8 font-style-script">
                Let's create something unforgettable together
            </p>
            <a href="{{ Route::has('login') ? route('login') : '#' }}"
                class="inline-flex items-center gap-2 px-8 py-4 bg-white text-gray-900 font-bold rounded-xl hover:bg-gray-100 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                Get Started
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-gray-950 text-gray-400 py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <div>
                    <img src="{{ asset('images/favicon.png') }}" alt="Logo" class="h-16 mb-4">
                    <p class="text-sm leading-relaxed">
                        Creating unforgettable moments for weddings, birthdays, corporate events, and more.
                    </p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ url('/') }}" class="hover:text-white transition-colors">Home</a></li>
                        <li><a href="{{ route('services.index') }}"
                                class="hover:text-white transition-colors">Services</a></li>
                        <li><a href="{{ Route::has('login') ? route('login') : '#' }}"
                                class="hover:text-white transition-colors">Log in</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Get In Touch</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="mailto:michaelhoevents@gmail.com"
                                class="hover:text-white transition-colors">michaelhoevents@gmail.com</a></li>
                        <li><a href="tel:+639173062531" class="hover:text-white transition-colors">+63 917 306 2531</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="pt-8 border-t border-gray-800">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-sm">
                    <p>© {{ date('Y') }} Michael Ho Events. All rights reserved.</p>
                    <p class="font-style-script text-base">Creating memories, one event at a time</p>
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
    </script>

</body>

</html>