<x-guest-layout>
    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-white px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">

            <!-- Logo Section -->
            <div class="text-center mb-8">
                <a href="{{ url('/') }}" class="inline-block">
                    <img src="{{ asset('images/favicon.png') }}" alt="Logo" class="h-20 mx-auto mb-6">
                </a>
                <div class="text-xs uppercase tracking-widest text-gray-400 mb-2">Reset Password</div>
                <h2 class="text-3xl font-serif text-gray-900">Forgot Password?</h2>
                <p class="mt-2 text-sm text-gray-600">No problem. We'll send you a reset link.</p>
            </div>

            <!-- Reset Card -->
            <div class="bg-white rounded-lg shadow-lg border border-gray-100 p-8">

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm uppercase tracking-wider text-gray-700 mb-2">
                            Email Address
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all duration-200 @error('email') border-red-500 @enderror"
                                placeholder="Enter your email address">
                        </div>
                        @error('email')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-gray-900 text-white py-3 px-4 rounded-lg uppercase tracking-widest text-sm font-medium hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-all duration-200 shadow-md hover:shadow-lg">
                        Email Password Reset Link
                    </button>
                </form>
            </div>

            <!-- Back to Login -->
            <div class="text-center mt-6">
                <a href="{{ route('login') }}"
                    class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Login
                </a>
            </div>

        </div>
    </div>
</x-guest-layout>