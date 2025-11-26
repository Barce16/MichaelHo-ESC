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
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=Inter:wght@300;400;500;600&family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,400&display=swap"
        rel="stylesheet">
    <style>
        body {
            background-color: #ffffff;
        }

        [x-cloak] {
            display: none !important;
        }

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

        /* Lightbox Styles */
        .lightbox-backdrop {
            backdrop-filter: blur(8px);
        }

        @keyframes zoom-in {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-zoom-in {
            animation: zoom-in 0.3s ease-out;
        }
    </style>
</head>

<body class="bg-white">
    <!-- Toast Notifications -->
    @include('components.toast')

    <div x-data="{ 
        showLightbox: false,
        openLightbox() {
            this.showLightbox = true;
            document.body.style.overflow = 'hidden';
        },
        closeLightbox() {
            this.showLightbox = false;
            document.body.style.overflow = '';
        }
    }" @keydown.escape.window="closeLightbox()">
        <!-- Elegant Header -->
        <header class="border-b border-gray-100 bg-white">
            <div class="max-w-screen-xl mx-auto px-6 lg:px-12 py-6">
                <div class="flex items-center justify-between">
                    <a href="{{ route('services.category', $package->type) }}"
                        class="flex items-center gap-2 text-gray-600 hover:text-black transition-colors text-xs uppercase tracking-wider">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M15 19l-7-7 7-7" />
                        </svg>
                        Back to {{ ucfirst($package->type) }} Packages
                    </a>

                    <a href="{{ url('/') }}" class="text-2xl font-light tracking-wider"
                        style="font-family: 'Playfair Display', serif;">
                        Michael Ho <span class="italic">Events</span>
                    </a>
                </div>
            </div>
        </header>

        <!-- Package Hero Section -->
        <section class="py-20 bg-white">
            <div class="max-w-screen-xl mx-auto px-6 lg:px-12">
                <!-- Package Title & Type -->
                <div class="text-center mb-12">
                    <p class="text-xs uppercase tracking-widest text-gray-600 mb-4">{{ ucfirst($package->type) }}
                        Package</p>
                    <h1 class="text-5xl md:text-6xl font-light mb-6" style="font-family: 'Playfair Display', serif;">
                        {{ $package->name }}
                    </h1>
                    <div class="text-3xl font-light text-black">
                        ₱{{ number_format($package->price, 0, ',', ',') }}
                    </div>
                </div>

                <!-- TWO COLUMN LAYOUT -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mt-16">

                    <!-- LEFT COLUMN - Banner Image -->
                    <div class="space-y-8 min-h-[600px] lg:min-h-[700px]">
                        @if($package->banner)
                        <!-- Banner Display with Click to Enlarge -->
                        <div class="flex justify-center lg:justify-center h-full">
                            <div @click="openLightbox()"
                                class="relative rounded-xl overflow-hidden shadow-2xl border-2 border-gray-200 w-full max-w-md group cursor-pointer hover:border-gray-400 transition-colors h-full">
                                <img src="{{ $package->banner_url }}" alt="{{ $package->name }} banner"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                    loading="lazy">

                                <!-- Gradient Overlay -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent pointer-events-none">
                                </div>

                                <!-- Package Badge -->
                                <div
                                    class="absolute top-4 left-4 bg-white/95 backdrop-blur-sm px-4 py-2 border border-gray-200">
                                    <p class="text-xs uppercase tracking-widest text-black font-medium">{{
                                        ucfirst($package->type) }}</p>
                                </div>

                                <!-- Click to Enlarge Hint -->
                                <div
                                    class="absolute bottom-4 right-4 bg-black/80 backdrop-blur-sm px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div class="flex items-center gap-2 text-white">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                        </svg>
                                        <span class="text-xs font-medium">Click to enlarge</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <!-- Fallback if no banner -->
                        <div class="flex justify-center lg:justify-center  h-full">
                            <div
                                class="relative w-full max-w-md aspect-[2/3] bg-gradient-to-b from-gray-50 to-gray-100 rounded-xl border-2 border-gray-200 flex items-center justify-center">
                                <div class="text-center">
                                    <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-gray-400 text-sm">{{ $package->name }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- RIGHT COLUMN - Booking Form & Package Info -->
                    <div class="space-y-8">

                        <!-- Package Features -->
                        @if($package->features)
                        <div class="border border-gray-200 p-8">
                            <h3 class="text-xs uppercase tracking-widest text-gray-600 mb-6">Special Features</h3>
                            <ul class="space-y-3">
                                @foreach(explode("\n", $package->features) as $feature)
                                @if(trim($feature))
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-black flex-shrink-0 mt-0.5" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-700">{{ trim($feature) }}</span>
                                </li>
                                @endif
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <!-- Booking Form -->
                        <div class="border border-gray-900 p-8">
                            <h3 class="text-2xl font-light mb-6" style="font-family: 'Playfair Display', serif;">
                                Book This Package
                            </h3>
                            <form action="{{ route('book.form', $package) }}" method="POST">
                                @csrf
                                <div class="space-y-6">
                                    <!-- Event Name -->
                                    <div>
                                        <label for="event_name"
                                            class="block text-xs uppercase tracking-wider text-gray-600 mb-2">
                                            Event Name
                                        </label>
                                        <input type="text" id="event_name" name="event_name"
                                            value="{{ old('event_name') }}"
                                            class="w-full px-4 py-3 border text-sm focus:outline-none focus:border-black transition-colors @error('event_name') border-red-500 @else border-gray-200 @enderror"
                                            placeholder="e.g., John & Jane Wedding">
                                        @error('event_name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Event Date -->
                                    <div>
                                        <label for="event_date"
                                            class="block text-xs uppercase tracking-wider text-gray-600 mb-2">
                                            Event Date
                                        </label>
                                        <x-calendar-picker name="event_date" :value="old('event_date')" />
                                        @error('event_date')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Theme -->
                                    <div>
                                        <label for="theme"
                                            class="block text-xs uppercase tracking-wider text-gray-600 mb-2">
                                            Theme
                                        </label>
                                        <input type="text" id="theme" name="theme" value="{{ old('theme') }}"
                                            class="w-full px-4 py-3 border text-sm focus:outline-none focus:border-black transition-colors @error('theme') border-red-500 @else border-gray-200 @enderror"
                                            placeholder="e.g., Modern Minimalist">
                                        @error('theme')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Venue -->
                                    <div>
                                        <label for="venue"
                                            class="block text-xs uppercase tracking-wider text-gray-600 mb-2">
                                            Venue Address
                                        </label>
                                        <textarea id="venue" name="venue" rows="3"
                                            class="w-full px-4 py-3 border text-sm focus:outline-none focus:border-black transition-colors @error('venue') border-red-500 @else border-gray-200 @enderror"
                                            placeholder="Complete venue name and address">{{ old('venue') }}</textarea>
                                        @error('venue')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Additional Details -->
                                    <div>
                                        <label for="notes"
                                            class="block text-xs uppercase tracking-wider text-gray-600 mb-2">
                                            Additional Details (Optional)
                                        </label>
                                        <textarea id="notes" name="notes" rows="3"
                                            class="w-full px-4 py-3 border border-gray-200 text-sm focus:outline-none focus:border-black transition-colors"
                                            placeholder="Special requests or additional information">{{ old('notes') }}</textarea>
                                    </div>

                                    <!-- Submit Button -->
                                    <button type="submit"
                                        class="w-full px-6 py-4 bg-black text-white text-xs uppercase tracking-widest hover:bg-gray-900 transition-colors">
                                        Proceed to Booking
                                    </button>

                                    <p class="text-xs text-gray-500 text-center">
                                        By proceeding, you agree to our terms and conditions
                                    </p>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <!-- Related Packages -->
        @if(isset($relatedPackages) && $relatedPackages->count() > 0)
        <section class="py-20 bg-gray-50 border-t border-gray-100">
            <div class="max-w-screen-xl mx-auto px-6 lg:px-12">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-light mb-4" style="font-family: 'Playfair Display', serif;">
                        Related <span class="italic">Packages</span>
                    </h2>
                    <p class="text-lg text-gray-600" style="font-family: 'Cormorant Garamond', serif;">
                        Explore similar options for your celebration
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($relatedPackages as $related)
                    <div class="group">
                        <div class="relative aspect-[3/4] overflow-hidden bg-gray-100 mb-6">
                            @if($related->banner)
                            <img src="{{ $related->banner_url }}" alt="{{ $related->name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            @elseif($related->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $related->images->first()->path) }}"
                                alt="{{ $related->name }}"
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
                            <div
                                class="absolute inset-0 bg-black opacity-0 group-hover:opacity-20 transition-opacity duration-500">
                            </div>
                        </div>

                        <div class="text-center">
                            <h3 class="text-2xl font-light mb-2" style="font-family: 'Playfair Display', serif;">
                                {{ $related->name }}
                            </h3>
                            <p class="text-2xl font-light text-black mb-4">
                                ₱{{ number_format($related->price, 0, ',', ',') }}
                            </p>
                            <a href="{{ route('services.show', $related->id) }}"
                                class="inline-block px-6 py-2 border border-black text-black text-xs uppercase tracking-widest hover:bg-black hover:text-white transition-all duration-300">
                                View Details
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        <!-- Reviews Section -->
        @if(isset($feedback) && $feedback->count() > 0)
        <section class="py-20 bg-white">
            <div class="max-w-screen-xl mx-auto px-6 lg:px-12">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-light mb-4" style="font-family: 'Playfair Display', serif;">
                        Client <span class="italic">Reviews</span>
                    </h2>
                    <p class="text-lg text-gray-600" style="font-family: 'Cormorant Garamond', serif;">
                        What our clients say about this package
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @foreach($feedback->take(4) as $review)
                    <div class="border border-gray-200 p-8">
                        <div class="flex items-center mb-4">
                            @for($i = 1; $i <= 5; $i++) <svg
                                class="w-5 h-5 {{ $i <= $review->rating ? 'text-black' : 'text-gray-300' }}"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                @endfor
                        </div>
                        <blockquote class="mb-4">
                            <p class="text-gray-700 italic" style="font-family: 'Cormorant Garamond', serif;">
                                "{{ $review->comment }}"
                            </p>
                        </blockquote>
                        <cite class="text-sm text-gray-600 not-italic">
                            — {{ $review->event->customer->customer_name ?? 'Anonymous' }}
                        </cite>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        <!-- Banner Lightbox Modal -->
        @if($package->banner)
        <div x-show="showLightbox" x-cloak @click="closeLightbox()"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 lightbox-backdrop"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <!-- Lightbox Container -->
            <div @click.stop class="relative max-w-5xl w-full mx-auto animate-zoom-in"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100">

                <!-- Close Button -->
                <button @click="closeLightbox()"
                    class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors z-10">
                    <div class="flex items-center gap-2 text-sm uppercase tracking-wider">
                        <span>Close</span>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                </button>

                <!-- Banner Image -->
                <div class="relative rounded-lg overflow-hidden shadow-2xl bg-white">
                    <img src="{{ $package->banner_url }}" alt="{{ $package->name }} banner"
                        class="w-full h-auto max-h-[90vh] object-contain">

                    <!-- Image Info Overlay -->
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-6">
                        <h3 class="text-white text-2xl font-light mb-2" style="font-family: 'Playfair Display', serif;">
                            {{ $package->name }}
                        </h3>
                        <div class="flex items-center gap-4 text-white/90 text-sm">
                            <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded">{{ ucfirst($package->type)
                                }}</span>
                            <span>₱{{ number_format($package->price, 0, ',', ',') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Keyboard Hint -->
                <p class="text-white/60 text-xs text-center mt-4">
                    Press ESC or click outside to close
                </p>
            </div>
        </div>
        @endif

    </div>
</body>

</html>