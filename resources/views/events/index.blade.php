<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Event Showcase - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Style+Script&family=Dancing+Script:wght@400..700&family=Libre+Caslon+Display&family=Noto+Serif:ital,wght@0,100..900;1,100..900&family=Niconne&display=swap"
        rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .font-libre {
            font-family: 'Libre Caslon Display', serif;
        }

        .font-style-script {
            font-family: 'Style Script', cursive;
        }

        /* Masonry Grid */
        .masonry-grid {
            column-count: 1;
            column-gap: 1.5rem;
        }

        @media (min-width: 768px) {
            .masonry-grid {
                column-count: 2;
            }
        }

        @media (min-width: 1024px) {
            .masonry-grid {
                column-count: 3;
            }
        }

        .masonry-item {
            break-inside: avoid;
            margin-bottom: 1.5rem;
        }
    </style>
</head>

<body class="min-h-screen bg-gray-50 text-neutral-900 antialiased">

    <!-- HEADER CONTAINER (Top Bar + Navbar) -->
    <div id="header-container" class="relative z-50">

        <!-- Top Bar -->
        <div id="top-bar" class="bg-gray-950 text-white text-sm">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex items-center justify-between h-10">
                <!-- Left: Social Media -->
                <div class="flex items-center gap-4">
                    <a href="https://www.facebook.com/MichaelHoEventsPlanningandCoordinating/" target="_blank"
                        class="hover:text-gray-400" aria-label="Facebook">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M22.675 0H1.325C.593 0 0 .593 0 1.325v21.351C0 23.406.593 24 1.325 24H12.82v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.464.099 2.795.143v3.24l-1.918.001c-1.504 0-1.794.716-1.794 1.764v2.312h3.587l-.467 3.622h-3.12V24h6.116C23.406 24 24 23.406 24 22.676V1.325C24 .593 23.406 0 22.675 0z" />
                        </svg>
                    </a>
                    <a href="https://www.instagram.com/michaelhoevents/?hl=en" target="_blank"
                        class="hover:text-gray-400" aria-label="Instagram">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2.163c3.204 0 3.584.012 4.85.07 1.17.056 1.97.24 2.43.403a4.92 4.92 0 011.675 1.087 4.92 4.92 0 011.087 1.675c.163.46.347 1.26.403 2.43.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.056 1.17-.24 1.97-.403 2.43a4.918 4.918 0 01-1.087 1.675 4.918 4.918 0 01-1.675 1.087c-.46.163-1.26.347-2.43.403-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.17-.056-1.97-.24-2.43-.403a4.918 4.918 0 01-1.675-1.087 4.918 4.918 0 01-1.087-1.675c-.163-.46-.347-1.26-.403-2.43C2.175 15.747 2.163 15.367 2.163 12s.012-3.584.07-4.85c.056-1.17.24-1.97.403-2.43a4.92 4.92 0 011.087-1.675A4.92 4.92 0 015.398 2.636c.46-.163 1.26-.347 2.43-.403C8.416 2.175 8.796 2.163 12 2.163zm0-2.163C8.741 0 8.332.013 7.052.072 5.775.131 4.772.348 3.95.692a6.918 6.918 0 00-2.53 1.656A6.918 6.918 0 00.692 4.878c-.344.822-.561 1.825-.62 3.102C.013 8.332 0 8.741 0 12c0 3.259.013 3.668.072 4.948.059 1.277.276 2.28.62 3.102a6.918 6.918 0 001.656 2.53 6.918 6.918 0 002.53 1.656c.822.344 1.825.561 3.102.62C8.332 23.987 8.741 24 12 24s3.668-.013 4.948-.072c1.277-.059 2.28-.276 3.102-.62a6.918 6.918 0 002.53-1.656 6.918 6.918 0 001.656-2.53c.344-.822.561-1.825.62-3.102.059-1.28.072-1.689.072-4.948s-.013-3.668-.072-4.948c-.059-1.277-.276-2.28-.62-3.102a6.918 6.918 0 00-1.656-2.53A6.918 6.918 0 0019.05.692c-.822-.344-1.825-.561-3.102-.62C15.668.013 15.259 0 12 0z" />
                            <path
                                d="M12 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0 10.162a3.999 3.999 0 110-7.998 3.999 3.999 0 010 7.998z" />
                            <circle cx="18.406" cy="5.594" r="1.44" />
                        </svg>
                    </a>
                </div>

                <!-- Right: Email & Phone -->
                <div class="hidden sm:flex items-center gap-6">
                    <span>michaelhoevents@gmail.com</span>
                    <span>+639173062531</span>
                </div>
            </div>
        </div>

        <!-- Navbar -->
        <header id="navbar" class="bg-white/80 shadow-sm">
            <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="py-5 flex items-center justify-between">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('images/favicon.png') }}" alt="Logo" class="h-16">
                    </a>
                    <div class="flex items-center gap-3 sm:gap-5 text-sm font-medium">
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

    <!-- PAGE HEADER -->
    <div class="bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl sm:text-5xl font-bold mb-3 font-libre">Event Portfolio</h1>
            <p class="text-lg sm:text-xl font-style-script text-gray-300">
                Celebrating life's most beautiful moments
            </p>
            <div class="w-24 h-1 bg-white mx-auto mt-4"></div>
        </div>
    </div>

    <!-- FILTER TABS -->
    <div class="bg-white border-b border-gray-200 sticky top-0 z-40 shadow-sm">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex gap-4 py-4 overflow-x-auto">
                <a href="{{ route('events-showcase.index') }}"
                    class="px-4 py-2 rounded-lg font-medium text-sm whitespace-nowrap transition-colors {{ !request('type') ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    All Events
                </a>
                @foreach(['wedding', 'birthday', 'corporate', 'debut'] as $type)
                <a href="{{ route('events-showcase.index', ['type' => $type]) }}"
                    class="px-4 py-2 rounded-lg font-medium text-sm capitalize whitespace-nowrap transition-colors {{ request('type') === $type ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ $type }}
                </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- EVENTS GRID -->
    <section class="py-12 bg-gray-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            @if($eventShowcases->count() > 0)
            <!-- Masonry Grid -->
            <div class="masonry-grid">
                @foreach($eventShowcases as $showcase)
                <div class="masonry-item group">
                    <div
                        class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-2xl transition-all duration-500">
                        <!-- Image -->
                        <div class="relative aspect-[4/3] overflow-hidden">
                            <img src="{{ $showcase->image_url }}" alt="{{ $showcase->event_name }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>

                            <!-- Type Badge -->
                            <div class="absolute top-3 left-3">
                                <span
                                    class="inline-block px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-xs font-bold uppercase tracking-wider text-gray-900">
                                    {{ $showcase->type }}
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <h3 class="text-xl font-bold text-gray-900 mb-2 font-libre">{{ $showcase->event_name }}</h3>
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $showcase->description }}</p>

                            <!-- Location -->
                            <div class="flex items-center text-xs text-gray-500">
                                <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span>{{ $showcase->location }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($eventShowcases->hasPages())
            <div class="mt-10">
                {{ $eventShowcases->links() }}
            </div>
            @endif

            @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No Events Yet</h3>
                <p class="text-gray-600 mb-6">Check back soon for our amazing event showcases!</p>
                <a href="{{ route('services.index') }}"
                    class="inline-flex items-center gap-2 bg-gray-900 text-white px-6 py-3 rounded-lg font-semibold hover:bg-black transition-all">
                    Browse Our Services
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
            </div>
            @endif

        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-gray-900 text-white py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-sm text-gray-400">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

</body>

</html>