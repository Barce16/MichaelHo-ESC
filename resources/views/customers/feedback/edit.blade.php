<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Edit Feedback</h2>
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
                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                        Edit Your Feedback
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">Update your rating and comments</p>
                </div>

                <form method="POST" action="{{ route('customer.feedback.update', $event) }}" class="p-6">
                    @csrf
                    @method('PUT')

                    <!-- Rating -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Rating <span class="text-rose-500">*</span>
                        </label>
                        <div x-data="{ rating: {{ old('rating', $feedback->rating) }} }"
                            class="flex items-center gap-2">
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
                            class="block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                            placeholder="Tell us about your experience... What did you like? What could be improved?"
                            required>{{ old('comment', $feedback->comment) }}</textarea>
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

                    <!-- Info -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex gap-3">
                            <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div class="text-sm text-blue-800">
                                <p class="font-medium">Originally submitted {{ $feedback->created_at->diffForHumans() }}
                                </p>
                                @if($feedback->updated_at != $feedback->created_at)
                                <p class="text-xs mt-1">Last edited {{ $feedback->updated_at->diffForHumans() }}</p>
                                @endif
                            </div>
                        </div>
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
                            Update Feedback
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>