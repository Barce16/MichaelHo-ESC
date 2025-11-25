<x-guest-layout>
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-gray-50 to-white py-20 border-b border-gray-100">
        <div class="max-w-screen-xl mx-auto px-6 lg:px-12 text-center">
            <div class="text-xs uppercase tracking-widest text-gray-400 mb-4">Get in Touch</div>
            <h1 class="text-5xl lg:text-6xl font-serif text-gray-900 mb-6">Contact Us</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                We'd love to hear from you. Let's create something beautiful together.
            </p>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-20">
        <div class="max-w-screen-xl mx-auto px-6 lg:px-12">
            <div class="grid lg:grid-cols-2 gap-16">

                <!-- Contact Form -->
                <div>
                    <div class="mb-8">
                        <div class="text-xs uppercase tracking-widest text-gray-400 mb-2">Send us a message</div>
                        <h2 class="text-3xl font-serif text-gray-900 mb-4">Let's Start a Conversation</h2>
                        <p class="text-gray-600">Fill out the form below and we'll get back to you within 24 hours.</p>
                    </div>

                    @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-800">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                    </div>
                    @endif

                    <form action="{{ route('contact.send') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm uppercase tracking-wider text-gray-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-3 border border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition-colors duration-200 @error('name') border-red-500 @enderror">
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm uppercase tracking-wider text-gray-700 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-4 py-3 border border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition-colors duration-200 @error('email') border-red-500 @enderror">
                            @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm uppercase tracking-wider text-gray-700 mb-2">
                                Phone Number
                            </label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                class="w-full px-4 py-3 border border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition-colors duration-200 @error('phone') border-red-500 @enderror">
                            @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-sm uppercase tracking-wider text-gray-700 mb-2">
                                Your Message <span class="text-red-500">*</span>
                            </label>
                            <textarea id="message" name="message" rows="6" required
                                class="w-full px-4 py-3 border border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition-colors duration-200 @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                            @error('message')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="w-full bg-gray-900 text-white py-4 px-8 uppercase tracking-widest text-sm font-medium hover:bg-gray-800 transition-colors duration-300">
                            Send Message
                        </button>
                    </form>
                </div>

                <!-- Contact Information -->
                <div class="lg:pl-8">
                    <div class="mb-12">
                        <div class="text-xs uppercase tracking-widest text-gray-400 mb-2">Visit Us</div>
                        <h2 class="text-3xl font-serif text-gray-900 mb-8">Our Office</h2>

                        <!-- Address -->
                        <div class="mb-8">
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm uppercase tracking-wider text-gray-900 font-medium mb-2">Address
                                    </h3>
                                    <p class="text-gray-600 leading-relaxed">
                                        Malaybalay City, Bukidnon 8700, Philippines
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="mb-8">
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm uppercase tracking-wider text-gray-900 font-medium mb-2">Phone
                                    </h3>
                                    <p class="text-gray-600">+639173062531</p>
                                    <p class="text-gray-600">+639979026039</p>
                                </div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-8">
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm uppercase tracking-wider text-gray-900 font-medium mb-2">Email
                                    </h3>
                                    <p class="text-gray-600">michaelhoevents@gmail.com</p>
                                </div>
                            </div>
                        </div>

                        <!-- Social Media -->
                        <div>
                            <h3 class="text-sm uppercase tracking-wider text-gray-900 font-medium mb-4">Follow Us</h3>
                            <div class="flex gap-4">
                                <a href="https://www.facebook.com/MichaelHoEventsPlanningandCoordinating/"
                                    class="w-10 h-10 bg-gray-100 hover:bg-gray-900 text-gray-700 hover:text-white rounded-full flex items-center justify-center transition-colors duration-300">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                    </svg>
                                </a>
                                <a href="https://www.instagram.com/michaelhoevents/?hl=en"
                                    class="w-10 h-10 bg-gray-100 hover:bg-gray-900 text-gray-700 hover:text-white rounded-full flex items-center justify-center transition-colors duration-300">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Map Section (Optional) -->
    <section class="border-t border-gray-100">
        <div class="w-full h-96 bg-gray-100">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63401.8!2d125.1278!3d8.1531!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x32fff3c8e87e9f1d%3A0x70e93cf85e038!2sMalaybalay%2C%20Bukidnon!5e0!3m2!1sen!2sph!4v1732544094000"
                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" class="grayscale">
            </iframe>
        </div>
    </section>

</x-guest-layout>