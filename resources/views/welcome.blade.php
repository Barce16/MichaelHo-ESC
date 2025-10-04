<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Michael Ho Events Styling And Coordination') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Style+Script&family=Dancing+Script:wght@400..700&family=Libre+Caslon+Display&family=Noto+Serif:ital,wght@0,100..900;1,100..900&family=Niconne&display=swap"
        rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="min-h-screen text-neutral-900 antialiased selection:bg-black selection:text-white">

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
                <div class="flex items-center gap-6">
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
                        <a href="#">Home</a>
                        <a href="{{ route('events.index') }}">Events</a>
                        <a href="{{ Route::has('login') ? route('login') : '#' }}">Log in</a>
                    </div>
                </div>
            </nav>
        </header>
    </div>

    <!-- Spacer for sticky navbar -->
    <div id="navbar-spacer" class="h-0"></div>

    <!-- HERO SECTION -->
    <div class="relative min-h-screen flex flex-col items-center justify-center"
        style="background-image: url('{{ asset('images/hero.jpg') }}'); background-size: cover; background-position: center;">

        <!-- Overlay -->
        <div class="absolute inset-0 bg-black opacity-70"></div>

        <div class="relative text-center text-white px-4">
            <h1 class="text-4xl sm:text-7xl font-bold mb-4 font-libre animate-on-scroll from-bottom">
                Michael Ho Events Styling & Coordination
            </h1>
            <p class="text-lg sm:text-4xl mb-6 font-style-script animate-on-scroll from-bottom"
                style="transition-delay: 0.2s;">
                Making your special moments unforgettable.
            </p>
            <a href="{{ route('events.index') }}" class="group relative inline-flex items-center bg-white text-black font-medium px-6 py-3 rounded-xl shadow-lg 
          hover:bg-gray-900 hover:text-white hover:pl-10 duration-300 overflow-hidden animate-on-scroll scale-up"
                style="transition-delay: 0.4s;">
                <svg class="absolute left-3 w-5 h-5 transform -translate-x-10 opacity-0 transition-all duration-300 group-hover:translate-x-0 group-hover:opacity-100"
                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
                <span class="transition-transform duration-300">View Events</span>
            </a>

        </div>
    </div>

    <!-- EVENTS SHOWCASE SECTION -->
    <section class="py-20 bg-gradient-to-b from-white via-gray-50 to-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-16 animate-on-scroll from-bottom">
                <h2 class="text-5xl font-bold mb-4 font-libre text-gray-900">Our Recent Events</h2>
                <p class="text-xl text-gray-600 font-style-script">Creating memories that last a lifetime</p>
                <div class="w-24 h-1 bg-gray-900 mx-auto mt-6"></div>
            </div>

            <!-- Events Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                <!-- Event Card 1: Wedding -->
                <div
                    class="group relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 animate-on-scroll from-left">

                    <div class="aspect-[4/5] overflow-hidden">
                        <img src="https://scontent.fcgy2-4.fna.fbcdn.net/v/t39.30808-6/557960014_1337283081741390_2253853673411969444_n.jpg?_nc_cat=110&ccb=1-7&_nc_sid=f727a1&_nc_eui2=AeG4xAq3SH-mjyJNoIBmhA3NyeRUzR5CNSjJ5FTNHkI1KLm0-2G39P6ZarAk0mg0sasCbGF1lCahrtGGYg4DTg8G&_nc_ohc=9S8Po-69JWEQ7kNvwE0dnYT&_nc_oc=AdlffZWMrY6wE-669a9_VxnA-bOlIUA1K7ZhuBO04Hv3Qw5Cu0jwOJg2DV9IAntzb9w&_nc_zt=23&_nc_ht=scontent.fcgy2-4.fna&_nc_gid=m7OsQSV2bGmYJ7GtEZmb6w&oh=00_AfaxE4mMdYH56Fw3V7W-K0FBTV2GpSnB66KABXXHDkEUKQ&oe=68E3080B"
                            alt="Elegant Wedding"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <div class="mb-2">
                            <span
                                class="inline-block px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-medium uppercase tracking-wider">WEDDING</span>
                        </div>
                        <h3 class="text-2xl font-bold mb-2 font-libre">BRYAN
                            + CARN Wedding</h3>
                        <p class="text-sm text-gray-200 mb-3">When the world blurs, love stays clear.</p>
                        <div class="flex items-center text-sm text-gray-300">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>Cagayan de Oro City</span>
                        </div>
                    </div>
                </div>

                <!-- Event Card 2: Birthday -->
                <div
                    class="group relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 animate-on-scroll scale-up">
                    <div class="aspect-[4/5] overflow-hidden">
                        <img src="https://scontent.fcgy2-2.fna.fbcdn.net/v/t39.30808-6/536059669_1302846215185077_727055652988052994_n.jpg?_nc_cat=109&ccb=1-7&_nc_sid=f727a1&_nc_eui2=AeE4q377-eRjQPeJrRMBR5mhSV2TsuhQQ4dJXZOy6FBDh3ogSa4TuAdJWYgJoXqhq2a_0okDiaE7ueRQczPN3Ra1&_nc_ohc=JYj_PdF31QUQ7kNvwHqMAsh&_nc_oc=AdmP2U5c0e4Befi33-34iPwASBH5eOClJqXCXuZiEds3A-ZfQnvB17GtewKCBz3xvoo&_nc_zt=23&_nc_ht=scontent.fcgy2-2.fna&_nc_gid=CgDExkW0GX9jRCyZLcRHRA&oh=00_AfYGmL9Z8YU17J0-uMjH00ZG8b36AUKiKzTJ_fXIkojMLQ&oe=68E302B8"
                            alt="Birthday Celebration"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <div class="mb-2">
                            <span
                                class="inline-block px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-medium uppercase tracking-wider">Birthday</span>
                        </div>
                        <h3 class="text-2xl font-bold mb-2 font-libre">Teresa's 80th Birthday</h3>
                        <p class="text-sm text-gray-200 mb-3">80 years of love, laughter, and a life beautifully lived.
                            Here's to celebrating a true milestone!</p>
                        <div class="flex items-center text-sm text-gray-300">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>Valencia, Bukidnon</span>
                        </div>
                    </div>
                </div>

                <!-- Event Card 3: Corporate Event -->
                <div
                    class="group relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 animate-on-scroll from-right">
                    <div class="aspect-[4/5] overflow-hidden">
                        <img src="https://scontent.fcgy2-4.fna.fbcdn.net/v/t39.30808-6/554959532_1335480161921682_8710209505454407090_n.jpg?stp=cp6_dst-jpg_tt6&_nc_cat=110&ccb=1-7&_nc_sid=833d8c&_nc_eui2=AeEEhQ1wIa3cQs6wMInRzvot1PHwAZGckU7U8fABkZyRTuFJiKFQkHe9wAFk8u5Nbi9XU8YP6H4tm374HMPUwItH&_nc_ohc=AtFj8EJhfHYQ7kNvwEzUBn-&_nc_oc=Adk7f2DowLhqIEn6ipOsUZvCb8_FBjrUdKyuRP08NGLmNRJRB4VxRqTAmOpQcj3HzKc&_nc_zt=23&_nc_ht=scontent.fcgy2-4.fna&_nc_gid=YfmP2CII6E8DRjmYGc5U2w&oh=00_AfZyn0dqzbgd845AljvJiwuCKujdprDHkCvyAAOdDCDISw&oe=68E300C5"
                            alt="Corporate Event"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <div class="mb-2">
                            <span
                                class="inline-block px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-medium uppercase tracking-wider">Corporate</span>
                        </div>
                        <h3 class="text-2xl font-bold mb-2 font-libre">BMS Senior Night</h3>
                        <p class="text-sm text-gray-200 mb-3">An evening of gratitude and recognition, beautifully set
                            for the esteemed senior doctors of the Bukidnon Medical Society.</p>
                        <div class="flex items-center text-sm text-gray-300">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>Valencia, Bukidnon</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- REVIEWS SECTION -->
    <section class="py-20 bg-gray-900 text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <h2 class="text-5xl font-bold mb-4 font-libre">What Our Clients Say</h2>
                <p class="text-xl text-gray-300 font-style-script">Testimonials from our wonderful clients</p>
                <div class="w-24 h-1 bg-white mx-auto mt-6"></div>
            </div>

            <!-- Reviews Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                <!-- Review Card 1 -->
                <div
                    class="bg-white/5 backdrop-blur-sm rounded-2xl p-8 border border-white/10 hover:bg-white/10 hover:border-white/20 transition-all duration-300 animate-on-scroll from-left">
                    <svg class="w-12 h-12 text-white/20 mb-4" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                    </svg>
                    <p class="text-gray-200 mb-6 leading-relaxed">
                        I would like to reply to this review with two of many reasons I would recommend Michael Ho. 1.
                        Professionalism. It's clear to me that he cares about his craft and the satisfaction of his
                        customers. 2. Talent. Michael has clearly found his calling in life. His arrangements are beyond
                        what I can explain. Proportional designs for the occasions, symmetrical (or not when
                        appropriate) color, depth. He should be teaching at the university level.
                    </p>
                    <div class="border-t border-white/10 pt-4">
                        <h4 class="font-bold text-lg mb-1">Matt Hickman, Virginia, USA</h4>
                        <a href="https://www.facebook.com/share/17jdrX6DQp/" target="_blank"
                            class="text-sm text-gray-400 hover:text-white transition-colors inline-flex items-center gap-1">
                            View Review
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Review Card 2 -->
                <div
                    class="bg-white/5 backdrop-blur-sm rounded-2xl p-8 border border-white/10 hover:bg-white/10 hover:border-white/20 transition-all duration-300 animate-on-scroll scale-up">
                    <svg class="w-12 h-12 text-white/20 mb-4" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                    </svg>
                    <p class="text-gray-200 mb-6 leading-relaxed">
                        Attended a friend's wedding at Dahilayan Bukidnon last 23Feb2019. Exceptional wedding
                        coordination done by Michael and his team. Very organized and the program was smooth and on
                        time... The place was fantastic that guests never had any boring moment. I recommend this group
                        100%.
                    </p>
                    <div class="border-t border-white/10 pt-4">
                        <h4 class="font-bold text-lg mb-1">Jonah M. Severa</h4>
                        <a href="https://www.facebook.com/share/1J86B2Q1ox/" target="_blank"
                            class="text-sm text-gray-400 hover:text-white transition-colors inline-flex items-center gap-1">
                            View Review
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Review Card 3 -->
                <div
                    class="bg-white/5 backdrop-blur-sm rounded-2xl p-8 border border-white/10 hover:bg-white/10 hover:border-white/20 transition-all duration-300 animate-on-scroll from-right">
                    <svg class="w-12 h-12 text-white/20 mb-4" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                    </svg>
                    <p class="text-gray-200 mb-6 leading-relaxed">
                        Hi Mic! Thank you so much to your awesome team. Very accommodating and easy to get along with.
                        All request granted! Very flexible in terms sa ginagmay na hassle, aws hehe! Professional
                        indeed! I'm more than satisfied sa result sa photoshoot namo ni Niall, one day we'll both
                        look back on our photos and reminisce good memories. Extra credit to sir Adi (Adrian Flores) and
                        to sir Cleve. Sa uulitin hap. More power and God bless.
                    </p>
                    <div class="border-t border-white/10 pt-4">
                        <h4 class="font-bold text-lg mb-1">Honey Hazel L. Doydora</h4>
                        <a href="https://www.facebook.com/share/19rfzd27RN/" target="_blank"
                            class="text-sm text-gray-400 hover:text-white transition-colors inline-flex items-center gap-1">
                            View Review
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Review Card 4 -->
                <div
                    class="bg-white/5 backdrop-blur-sm rounded-2xl p-8 border border-white/10 hover:bg-white/10 hover:border-white/20 transition-all duration-300 animate-on-scroll from-left">
                    <svg class="w-12 h-12 text-white/20 mb-4" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                    </svg>
                    <p class="text-gray-200 mb-6 leading-relaxed">
                        Very professional, systematic,<br>
                        Hands on and the team is great.<br>
                        My son's wedding was so organized.<br>
                        Michael Ho is a pro.
                    </p>
                    <div class="border-t border-white/10 pt-4">
                        <h4 class="font-bold text-lg mb-1">Jona Fe</h4>
                        <a href="https://www.facebook.com/share/1JUYBzKQEJ/" target="_blank"
                            class="text-sm text-gray-400 hover:text-white transition-colors inline-flex items-center gap-1">
                            View Review
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Review Card 5 -->
                <div
                    class="bg-white/5 backdrop-blur-sm rounded-2xl p-8 border border-white/10 hover:bg-white/10 hover:border-white/20 transition-all duration-300 animate-on-scroll scale-up">
                    <svg class="w-12 h-12 text-white/20 mb-4" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                    </svg>
                    <p class="text-gray-200 mb-6 leading-relaxed">
                        One of the top Event Stylists & Coordinators in the Philippines... Bukidnon's Pride..
                    </p>
                    <div class="border-t border-white/10 pt-4">
                        <h4 class="font-bold text-lg mb-1">Adrian Flores</h4>
                        <a href="https://www.facebook.com/share/1E7L1zm3tg/" target="_blank"
                            class="text-sm text-gray-400 hover:text-white transition-colors inline-flex items-center gap-1">
                            View Review
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Review Card 6 -->
                <div
                    class="bg-white/5 backdrop-blur-sm rounded-2xl p-8 border border-white/10 hover:bg-white/10 hover:border-white/20 transition-all duration-300 animate-on-scroll from-right">
                    <svg class="w-12 h-12 text-white/20 mb-4" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                    </svg>
                    <p class="text-gray-200 mb-6 leading-relaxed">
                        As a supplier, Michael Ho Events Styling & Coordination team is so much fun to work with. Their
                        efficiency in managing the schedules is just right, and with a whole lot of love. If you're
                        looking out for a wedding coordination team for your big day, do make sure to consider this
                        team.
                    </p>
                    <div class="border-t border-white/10 pt-4">
                        <h4 class="font-bold text-lg mb-1">Jaybee Yaba</h4>
                        <a href="https://www.facebook.com/share/1777W5x3B9/" target="_blank"
                            class="text-sm text-gray-400 hover:text-white transition-colors inline-flex items-center gap-1">
                            View Review
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-gray-950 text-white py-16 relative overflow-hidden">
        <!-- Decorative background elements -->
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
            <div class="pt-8 border-t border-gray-800">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-400">
                    <p>© {{ date('Y') }} Michael Ho Events Styling & Coordination. All rights reserved.</p>
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

    // Scroll Animation Observer - triggers every time
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            } else {
                // Remove the class when element leaves viewport
                entry.target.classList.remove('animate-in');
            }
        });
    }, observerOptions);

    // Observe all elements with animate class
    document.querySelectorAll('.animate-on-scroll').forEach(el => {
        observer.observe(el);
    });
    </script>

    <style>
        .animate-on-scroll {
            opacity: 0;
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .animate-on-scroll.from-left {
            transform: translateX(-50px);
        }

        .animate-on-scroll.from-right {
            transform: translateX(50px);
        }

        .animate-on-scroll.from-bottom {
            transform: translateY(50px);
        }

        .animate-on-scroll.scale-up {
            transform: scale(0.9);
        }

        .animate-on-scroll.animate-in {
            opacity: 1;
            transform: translateX(0) translateY(0) scale(1);
        }
    </style>

</body>

</html>