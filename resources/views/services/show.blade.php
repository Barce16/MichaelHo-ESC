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

        /* Image Gallery Styles */
        .gallery-slide {
            display: none;
            animation: fadeIn 0.7s ease-in-out;
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
            background-color: #000000;
            width: 2rem;
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
    </style>
</head>

<body class="bg-white">
    <!-- Elegant Header -->
    <header class="border-b border-gray-100 bg-white">
        <div class="max-w-screen-xl mx-auto px-6 lg:px-12 py-6">
            <div class="flex items-center justify-between">
                <a href="{{ route('services.category', $package->type) }}"
                    class="flex items-center gap-2 text-gray-600 hover:text-black transition-colors text-xs uppercase tracking-wider">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span>Back to {{ ucfirst($package->type) }}</span>
                </a>

                <a href="{{ url('/') }}">
                    <img src="{{ asset('images/favicon.png') }}" alt="Logo" class="h-12">
                </a>

                <div class="w-32"></div> <!-- Spacer for centering -->
            </div>
        </div>
    </header>

    <!-- Package Header -->
    <section class="py-16 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-screen-xl mx-auto px-6 lg:px-12 text-center">
            <h1 class="text-5xl font-light mb-4" style="font-family: 'Playfair Display', serif;">
                {{ $package->name }}
            </h1>
            <p class="text-lg text-gray-600 mb-6" style="font-family: 'Cormorant Garamond', serif;">
                {{ ucfirst($package->type) }} Package
            </p>
            <div class="text-center">
                <p class="text-xs uppercase tracking-wider text-gray-500 mb-2">Starting from</p>
                <p class="text-4xl font-light text-black">₱{{ number_format($package->price, 0, ',', ',') }}</p>
            </div>
            <!-- Elegant divider -->
            <div class="mt-8 flex items-center justify-center">
                <div class="h-px w-24 bg-gray-900"></div>
                <div class="mx-3 w-1.5 h-1.5 bg-gray-900"></div>
                <div class="h-px w-24 bg-gray-900"></div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-16">
        <div class="max-w-screen-xl mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

                <!-- LEFT COLUMN - Image Gallery & Details -->
                <div class="space-y-8">

                    <!-- Image Gallery -->
                    @php
                    $galleryImages = $package->images ?? collect();
                    $hasImages = $galleryImages->count() > 0;
                    @endphp

                    <div class="relative aspect-[4/5] w-full overflow-hidden bg-gray-100" id="imageGallery">
                        @if($hasImages)
                        @foreach($galleryImages as $index => $image)
                        <div class="gallery-slide {{ $index === 0 ? 'active' : '' }} absolute inset-0">
                            <img src="{{ asset('storage/' . $image->path) }}"
                                alt="{{ $package->name }} - Image {{ $index + 1 }}" class="w-full h-full object-cover">
                        </div>
                        @endforeach

                        <!-- Gallery Controls -->
                        @if($galleryImages->count() > 1)
                        <!-- Previous Button -->
                        <button type="button" onclick="changeSlide(-1)"
                            class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/90 backdrop-blur-sm border border-gray-200 p-3 hover:border-black transition-all">
                            <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>

                        <!-- Next Button -->
                        <button type="button" onclick="changeSlide(1)"
                            class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/90 backdrop-blur-sm border border-gray-200 p-3 hover:border-black transition-all">
                            <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                        <!-- Indicators -->
                        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                            @foreach($galleryImages as $index => $image)
                            <button type="button" onclick="goToSlide({{ $index }})"
                                class="gallery-indicator h-2 bg-white/50 hover:bg-white/80 transition-all {{ $index === 0 ? 'active w-8' : 'w-2' }}"
                                data-slide="{{ $index }}">
                            </button>
                            @endforeach
                        </div>
                        @endif
                        @else
                        <!-- Placeholder if no images -->
                        <div
                            class="w-full h-full flex items-center justify-center bg-gradient-to-b from-gray-50 to-gray-100">
                            <div class="text-center">
                                <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-gray-400 text-sm">No images available</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Package Description -->
                    @if($package->description)
                    <div>
                        <h3 class="text-xs uppercase tracking-widest text-gray-600 mb-4">About This Package</h3>
                        <p class="text-gray-700 leading-relaxed"
                            style="font-family: 'Cormorant Garamond', serif; font-size: 1.1rem;">
                            {{ $package->description }}
                        </p>
                    </div>
                    @endif

                    <!-- Package Inclusions -->
                    @if($package->inclusions)
                    <div class="border border-gray-200 p-8">
                        <h3 class="text-xs uppercase tracking-widest text-gray-600 mb-6">Package Includes</h3>
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
                        $inclusions = array_values($inclusions);
                        @endphp
                        <ul class="space-y-3">
                            @foreach($inclusions as $inclusion)
                            <li class="flex items-start gap-3">
                                <span class="w-1 h-1 bg-black mt-2 flex-shrink-0"></span>
                                <span class="text-sm text-gray-700">
                                    {{ is_array($inclusion) ? ($inclusion['name'] ?? $inclusion[0] ?? '') : $inclusion
                                    }}
                                </span>
                            </li>
                            @endforeach
                        </ul>
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
                                <svg class="w-5 h-5 text-black flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
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
                                    <input type="text" id="event_name" name="event_name" required
                                        class="w-full px-4 py-3 border border-gray-200 text-sm focus:outline-none focus:border-black transition-colors"
                                        placeholder="e.g., John & Jane Wedding">
                                </div>

                                <!-- Event Date -->
                                <div>
                                    <label for="event_date"
                                        class="block text-xs uppercase tracking-wider text-gray-600 mb-2">
                                        Event Date
                                    </label>
                                    <x-calendar-picker name="event_date" :value="old('event_date')" required />
                                    @error('event_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Venue -->
                                <div>
                                    <label for="venue"
                                        class="block text-xs uppercase tracking-wider text-gray-600 mb-2">
                                        Venue Address
                                    </label>
                                    <textarea id="venue" name="venue" rows="3" required
                                        class="w-full px-4 py-3 border border-gray-200 text-sm focus:outline-none focus:border-black transition-colors"
                                        placeholder="Complete venue name and address"></textarea>
                                </div>

                                <!-- Additional Details -->
                                <div>
                                    <label for="additional_details"
                                        class="block text-xs uppercase tracking-wider text-gray-600 mb-2">
                                        Additional Details (Optional)
                                    </label>
                                    <textarea id="additional_details" name="additional_details" rows="3"
                                        class="w-full px-4 py-3 border border-gray-200 text-sm focus:outline-none focus:border-black transition-colors"
                                        placeholder="Special requests or additional information"></textarea>
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
                        @if($related->images->isNotEmpty())
                        <img src="{{ asset('storage/' . $related->images->first()->path) }}" alt="{{ $related->name }}"
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
                        <p class="text-gray-700 italic leading-relaxed"
                            style="font-family: 'Cormorant Garamond', serif;">
                            "{{ $review->message }}"
                        </p>
                    </blockquote>
                    <div class="border-t border-gray-100 pt-4">
                        <p class="text-sm font-medium text-gray-900">{{ $review->customer->name ?? 'Anonymous' }}</p>
                        <p class="text-xs text-gray-500">{{ $review->created_at->format('F Y') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Footer CTA -->
    <section class="py-16 bg-gray-50 border-t border-gray-100">
        <div class="max-w-screen-xl mx-auto px-6 lg:px-12 text-center">
            <h3 class="text-3xl font-light mb-4" style="font-family: 'Playfair Display', serif;">
                Need More Information?
            </h3>
            <p class="text-gray-600 mb-8">
                Contact us for a personalized consultation
            </p>
            <div class="flex items-center justify-center gap-4">
                <a href="tel:+639173062531"
                    class="px-8 py-3 bg-black text-white text-xs uppercase tracking-widest hover:bg-gray-900 transition-colors">
                    Call Us
                </a>
                <a href="mailto:michaelhoevents@gmail.com"
                    class="px-8 py-3 border border-black text-black text-xs uppercase tracking-widest hover:bg-black hover:text-white transition-all duration-300">
                    Email Us
                </a>
            </div>
        </div>
    </section>

    <!-- Gallery JavaScript -->
    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.gallery-slide');
        const indicators = document.querySelectorAll('.gallery-indicator');
        let autoSlideInterval;

        function showSlide(index) {
            slides.forEach(slide => slide.classList.remove('active'));
            indicators.forEach(indicator => {
                indicator.classList.remove('active', 'w-8');
                indicator.classList.add('w-2');
            });

            if (slides[index]) {
                slides[index].classList.add('active');
                if (indicators[index]) {
                    indicators[index].classList.add('active', 'w-8');
                    indicators[index].classList.remove('w-2');
                }
            }
            currentSlide = index;
        }

        function changeSlide(direction) {
            let newSlide = currentSlide + direction;
            if (newSlide >= slides.length) newSlide = 0;
            if (newSlide < 0) newSlide = slides.length - 1;
            showSlide(newSlide);
            resetAutoSlide();
        }

        function goToSlide(index) {
            showSlide(index);
            resetAutoSlide();
        }

        function startAutoSlide() {
            if (slides.length > 1) {
                autoSlideInterval = setInterval(() => {
                    changeSlide(1);
                }, 5000);
            }
        }

        function resetAutoSlide() {
            clearInterval(autoSlideInterval);
            startAutoSlide();
        }

        // Start auto-slide on load
        startAutoSlide();

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') changeSlide(-1);
            if (e.key === 'ArrowRight') changeSlide(1);
        });

        // Pause auto-slide when hovering
        const gallery = document.getElementById('imageGallery');
        if (gallery) {
            gallery.addEventListener('mouseenter', () => clearInterval(autoSlideInterval));
            gallery.addEventListener('mouseleave', startAutoSlide);
        }

        // Form Validation
        document.addEventListener('DOMContentLoaded', function() {
            const bookingForm = document.querySelector('form[action*="book"]');
            
            if (bookingForm) {
                bookingForm.addEventListener('submit', function(e) {
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
                        showError(venue, 'Please enter a complete venue address');
                        hasError = true;
                        if (!firstErrorField) firstErrorField = venue;
                    }

                    if (hasError) {
                        e.preventDefault();
                        if (firstErrorField) {
                            firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            firstErrorField.focus();
                        }
                        showAlert('Please fill in all required fields correctly', 'error');
                        return false;
                    }

                    // Show loading state
                    const submitBtn = bookingForm.querySelector('button[type="submit"]');
                    const originalContent = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = 'Processing...';

                    showAlert('Proceeding to booking...', 'success');
                });
            }

            function showError(field, message) {
                const errorDiv = document.createElement('p');
                errorDiv.className = 'validation-error text-red-500 text-xs mt-1';
                errorDiv.textContent = message;
                
                field.classList.add('border-red-500');
                field.classList.remove('border-gray-200');
                field.parentElement.appendChild(errorDiv);
            }

            function showAlert(message, type = 'error') {
                const existingAlert = document.querySelector('.validation-alert');
                if (existingAlert) existingAlert.remove();

                const alert = document.createElement('div');
                alert.className = `validation-alert fixed top-4 right-4 z-50 px-6 py-4 border shadow-lg flex items-center gap-3 animate-slide-in ${
                    type === 'error' ? 'bg-red-50 border-red-200' : 'bg-green-50 border-green-200'
                }`;
                
                alert.innerHTML = `
                    <span class="text-sm font-medium ${type === 'error' ? 'text-red-800' : 'text-green-800'}">${message}</span>
                `;
                
                document.body.appendChild(alert);
                setTimeout(() => alert.remove(), 4000);
            }

            // Remove error styling on input
            document.querySelectorAll('input, textarea').forEach(field => {
                field.addEventListener('input', function() {
                    this.classList.remove('border-red-500');
                    this.classList.add('border-gray-200');
                    const error = this.parentElement.querySelector('.validation-error');
                    if (error) error.remove();
                });
            });
        });
    </script>
</body>

</html>