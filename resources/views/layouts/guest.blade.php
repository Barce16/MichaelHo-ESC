<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Michael Ho Events Styling Aand Coordination') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col-reverse gap-y-5 sm:flex-row items-center justify-center bg-gray-100 p-6">
        <!-- Content -->
        @if (request()->routeIs('contact'))
        <div class="w-lg bg-white shadow-lg rounded-lg p-8 py-16">
            {{ $slot }}
        </div>
        @else
        <div class="w-[40rem] bg-white shadow-lg rounded-lg px-10 py-16">
            {{ $slot }}
        </div>
        @endif
    </div>
</body>

</html>