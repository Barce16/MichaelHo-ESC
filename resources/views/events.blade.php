<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Michael Ho Events Styling And Coordination') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Google Fonts -->
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
    </style>
</head>

<body class="bg-gray-50 text-gray-900">

    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center py-8">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/favicon.png') }}" alt="Logo" class="h-12">
            </a>
            <nav class="flex gap-6 text-sm font-medium">
                <a href="{{ url('/') }}" class="hover:text-gray-700">Home</a>
                <a href="{{ url('/events') }}" class="hover:text-gray-700">Events</a>
                <a href="{{ route('login') }}" class="hover:text-gray-700">Log in</a>
            </nav>
        </div>
    </header>

    <main class="flex flex-col gap-5">
        @foreach($packages as $type => $typePackages)
        <div>
            <!-- Page Title -->
            <section class="text-center py-12">
                <h1 class="text-4xl sm:text-5xl font-libre mb-4">
                    Our {{ ucfirst(str_replace('_', ' ', $type)) }} Packages
                </h1>
            </section>

            <!-- Events Section -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
                <div class="grid gap-12 md:grid-cols-2">
                    @foreach($typePackages as $package)
                    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                        <!-- Event Image -->
                        <div class="relative">
                            @if($package->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $package->images->first()->path) }}"
                                alt="{{ $package->name }}"
                                class="w-full h-64 object-cover hover:opacity-80 duration-300">
                            @else
                            <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400">No image available</span>
                            </div>
                            @endif

                            <!-- Price Overlay -->
                            <div
                                class="absolute top-4 left-4 bg-white/90 text-black font-semibold px-4 py-2 rounded-lg shadow-md">
                                ₱ {{ number_format($package->price, 2) }}
                            </div>
                        </div>

                        <!-- Event Details -->
                        <div class="py-8 px-0">
                            <h2 class="text-3xl font-libre font-bold mb-6 px-8">{{ $package->name }}</h2>

                            <!-- Services Included -->
                            @php
                            $packageType = $package->type instanceof \App\Enums\PackageType
                            ? $package->type
                            : \App\Enums\PackageType::from($type);
                            @endphp

                            <div
                                class="duration-300 p-6 mb-8 {{ $packageType->color() }} bg-opacity-50 hover:bg-opacity-70">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Coordination -->
                                    @if($package->coordination)
                                    <div>
                                        <h4 class="font-semibold text-lg mb-2 text-gray-950">
                                            Coordination
                                        </h4>
                                        <p class="text-gray-800 text-sm">
                                            {{ $package->coordination }}
                                        </p>
                                        <p class="text-xs mt-1 font-semibold">₱ {{
                                            number_format($package->coordination_price, 2) }}</p>
                                    </div>
                                    @endif

                                    <!-- Event Styling -->
                                    @if($package->event_styling && count($package->event_styling) > 0)
                                    <div>
                                        <h4 class="font-semibold text-lg mb-2 text-gray-950">
                                            Event Styling
                                        </h4>
                                        <ul class="space-y-1 text-sm text-gray-800">
                                            @foreach($package->event_styling as $styling)
                                            <li>• {{ $styling }}</li>
                                            @endforeach
                                        </ul>
                                        <p class="text-xs mt-1 font-semibold">₱ {{
                                            number_format($package->event_styling_price, 2) }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Inclusions Grid -->
                            @if($package->inclusions->isNotEmpty())
                            <div class="px-8 mt-8">
                                <h3 class="text-2xl font-bold mb-6 text-gray-900">Package Inclusions</h3>

                                <div class="grid grid-cols-1 gap-4">
                                    @foreach($package->inclusions as $inclusion)
                                    <div
                                        class="relative bg-white border border-gray-200 rounded-lg p-5 hover:border-gray-300 hover:shadow-sm transition-all duration-200">
                                        <!-- Dot indicator -->
                                        <div
                                            class="absolute top-5 left-0 w-1 h-8 bg-gradient-to-b from-gray-400 to-gray-200 rounded-r">
                                        </div>

                                        <div class="ml-4">
                                            <div class="flex items-start justify-between gap-3 mb-2">
                                                <h4 class="font-semibold text-gray-900 leading-tight">{{
                                                    $inclusion->name }}</h4>

                                                @if($inclusion->price > 0)
                                                <span class="text-sm font-semibold text-gray-700 whitespace-nowrap">
                                                    ₱{{ number_format($inclusion->price, 2) }}
                                                </span>
                                                @endif
                                            </div>

                                            @if($inclusion->category)
                                            <span class="inline-block text-xs text-gray-500 mb-2">{{
                                                $inclusion->category }}</span>
                                            @endif

                                            @if($inclusion->notes)
                                            <p class="text-sm text-gray-600 leading-relaxed">{{ $inclusion->notes }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- CTA Buttons -->
                            <div class="mt-8 flex gap-4 flex-wrap px-8">
                                <a href="{{ route('book.package', $package) }}"
                                    class="bg-slate-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-slate-600 transition shadow-md">
                                    BOOK NOW
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
        </div>
        @endforeach

        @if($packages->isEmpty())
        <div class="text-center py-20">
            <h2 class="text-2xl font-libre text-gray-600">No packages available at the moment.</h2>
            <p class="text-gray-500 mt-2">Please check back later or contact us directly.</p>
        </div>
        @endif
    </main>




    <!-- FOOTER -->
    <footer class="bg-gray-950 text-white py-16 relative overflow-hidden">
        <!--  background elements -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-0 left-0 w-64 h-64 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        </div>

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Main Footer Content -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">

                <!-- Column 1: About -->
                <div class="animate-on-scroll from-left">
                    <h3 class="text-2xl font-bold mb-4 font-libre">Michael Ho Events</h3>
                    <p class="text-gray-400 text-sm leading-relaxed mb-4">
                        Creating unforgettable moments through exceptional event styling and coordination.
                    </p>
                    <div class="flex items-center gap-4">
                        <a href="https://www.facebook.com/MichaelHoEventsPlanningandCoordinating/" target="_blank"
                            class="hover:text-gray-400 transition-colors" aria-label="Facebook">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M22.675 0H1.325C.593 0 0 .593 0 1.325v21.351C0 23.406.593 24 1.325 24H12.82v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.464.099 2.795.143v3.24l-1.918.001c-1.504 0-1.794.716-1.794 1.764v2.312h3.587l-.467 3.622h-3.12V24h6.116C23.406 24 24 23.406 24 22.676V1.325C24 .593 23.406 0 22.675 0z" />
                            </svg>
                        </a>
                        <a href="https://www.instagram.com/michaelhoevents/?hl=en" target="_blank"
                            class="hover:text-gray-400 transition-colors" aria-label="Instagram">
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
                    <ul class="space-y-3 text-gray-400">
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
                            <a href="{{ route('events.index') }}"
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
            <div class="pt-8 border-t border-gray-800 animate-on-scroll from-bottom">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-400">
                    <p>© {{ date('Y') }} Michael Ho Events Styling & Coordination. All rights reserved.</p>
                    <p class="font-style-script text-base">Creating memories, one event at a time</p>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>