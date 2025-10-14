<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Submit Feedback</h2>
            <a href="{{ route('customer.events.show', $event) }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Event
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <!-- Event Info -->
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white mb-6">
                <h3 class="text-2xl font-bold mb-2">{{ $event->name }}</h3>
                <p class="opacity-90">{{ $event->event_date->format('F d, Y') }}</p>
            </div>

            <!-- Feedback Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        How was your experience?
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">Your feedback helps us improve our services</p>
                </div>

                <form method="POST" action="{{ route('customer.feedback.store', $event) }}" class="p-6">
                    @csrf

                    <!-- Rating -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Rating <span class="text-rose-500">*</span>
                        </label>
                        <div x-data="{ rating: {{ old('rating', 0) }} }" class="flex items-center gap-2">
                            <template x-for="star in 5" :key="star">
                                <button type="button" @click="rating = star"
                                    class="text-4xl transition-transform hover:scale-110 focus:outline-none">
                                    <span x-show="star <= rating" class="text-yellow-400">⭐</span>
                                    <span x-show="star > rating" class="text-gray-300">☆</span>
                                </button>
                            </template>
                            <input type="hidden" name="rating" :value="rating">
                            <span x-show="rating > 0"
                                x-text="['Poor', 'Fair', 'Good', 'Very Good', 'Excellent'][rating - 1]"
                                class="ml-4 text-sm font-semibold text-gray-700"></span>
                        </div>
                        @error('rating')
                        <p class="mt-2 text-sm text-rose-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Comment -->
                    <div class="mb-6">
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                            Your Feedback <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="comment" name="comment" rows="6"
                            class="px-3 py-2 block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                            placeholder="Tell us about your experience... What did you like? What could be improved?"
                            required>{{ old('comment') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Maximum 1000 characters</p>
                        @error('comment')
                        <p class="mt-2 text-sm text-rose-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <div class="flex gap-3">
                        <a href="{{ route('customer.events.show', $event) }}"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancel
                        </a>
                        <button type="submit"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-indigo-700 transition shadow-md hover:shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Submit Feedback
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>