<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ ucfirst($category) }} Packages - {{ config('app.name') }}</title>
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

                        @foreach($categories as $cat)
                        <a href="{{ route('services.category', $cat) }}"
                            class="hover:text-gray-600 transition-colors capitalize {{ $cat === $category ? 'text-gray-900 font-bold' : '' }}">
                            {{ $cat }}
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
    <div class="bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl sm:text-5xl font-bold mb-3 font-libre capitalize">{{ $category }} Packages</h1>
            <p class="text-lg sm:text-xl font-style-script text-gray-300">
                Perfect packages for your {{ $category }} event
            </p>
            <div class="w-24 h-1 bg-white mx-auto mt-4"></div>
        </div>
    </div>

    <!-- PACKAGES GRID -->
    <section class="pb-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            @if($packages->count() > 0)
            <!-- Package Count -->
            <div class="text-center " style="padding-block: 2rem;">
                <p class="text-sm text-gray-600">{{ $packages->count() }} package{{ $packages->count() > 1 ? 's' : '' }}
                    available</p>
            </div>

            <!-- Packages Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($packages as $package)
                <div
                    class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">

                    <!-- Package Image -->
                    <div class="relative aspect-[4/3] overflow-hidden bg-gray-200">
                        @if($package->images->isNotEmpty())
                        <img src="{{ asset('storage/' . $package->images->first()->path) }}" alt="{{ $package->name }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @else
                        <div
                            class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                            <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent">
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
                    <div class="p-5">
                        <h3
                            class="text-xl font-bold text-gray-900 mb-2 font-libre group-hover:text-gray-700 transition-colors">
                            {{ $package->name }}
                        </h3>

                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                            {{ $package->description ?? 'Complete event planning and coordination services tailored to
                            your needs.' }}
                        </p>

                        <!-- Features Preview -->
                        @if($package->features)
                        <div class="mb-3 space-y-1">
                            @foreach(array_slice(explode("\n", $package->features), 0, 2) as $feature)
                            <div class="flex items-center gap-2 text-xs text-gray-600">
                                <svg class="w-3 h-3 text-green-500 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
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
                @endforeach
            </div>
            @else
            <!-- Empty State -->
            <div class="text-center py-20">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-6">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2 font-libre">No {{ ucfirst($category) }} Packages
                    Available</h3>
                <p class="text-gray-600 mb-6">Check back soon or explore our other packages.</p>
                <a href="{{ route('services.index') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 text-white font-semibold rounded-xl hover:bg-black transition-all">
                    View All Services
                </a>
            </div>
            @endif

        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-gray-950 text-gray-400 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-8">
                <!-- Column 1: About -->
                <div class="animate-on-scroll from-left">
                    <img src="{{ asset('images/favicon.png') }}" alt="Logo" class="h-16 mb-6">
                    <p class="text-sm leading-relaxed mb-4">
                        Creating unforgettable moments for weddings, birthdays, corporate events, and more. Your vision,
                        our expertise.
                    </p>
                    <div class="flex items-center gap-4">
                        <a href="https://www.facebook.com/MichaelHoEventsPlanningandCoordinating/" target="_blank"
                            class="hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M22.675 0H1.325C.593 0 0 .593 0 1.325v21.351C0 23.406.593 24 1.325 24H12.82v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.464.099 2.795.143v3.24l-1.918.001c-1.504 0-1.794.716-1.794 1.764v2.312h3.587l-.467 3.622h-3.12V24h6.116C23.406 24 24 23.406 24 22.676V1.325C24 .593 23.406 0 22.675 0z" />
                            </svg>
                        </a>
                        <a href="https://www.instagram.com/michaelhoevents/?hl=en" target="_blank"
                            class="hover:text-white transition-colors">
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
                <div class="animate-on-scroll from-bottom">
                    <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-3 text-sm">
                        <li>
                            <a href="#" class="hover:text-white transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('services.index') }}"
                                class="hover:text-white transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                                Services
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('events-showcase.index') }}"
                                class="hover:text-white transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                                Events
                            </a>
                        </li>
                        <li>
                            <a href="{{ Route::has('login') ? route('login') : '#' }}"
                                class="hover:text-white transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                                Log in
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Column 3: Contact Info -->
                <div class="animate-on-scroll from-right">
                    <h4 class="text-lg font-semibold mb-4">Get In Touch</h4>
                    <ul class="space-y-3 text-gray-400 text-sm">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <a href="mailto:michaelhoevents@gmail.com" class="hover:text-white transition-colors">
                                michaelhoevents@gmail.com
                            </a>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <a href="tel:+639173062531" class="hover:text-white transition-colors">
                                +63 917 306 2531
                            </a>
                        </li>
                    </ul>
                </div>

            </div>

            <!-- Bottom Bar -->
            <div class="pt-8 border-t border-gray-800">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-400">
                    <p>© {{ date('Y') }} Michael Ho Events Styling & Coordination. All rights reserved.</p>
                    <p class="font-style-script text-base">Creating memories, one event at a time</p>
                </div>
            </div>
        </div>
    </footer>

    <script>

    </script>

</body>

</html>