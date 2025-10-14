<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-800">Add Event Showcase</h2>
            <a href="{{ route('admin.management.showcases.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <form method="POST" action="{{ route('admin.management.showcases.store') }}"
                    enctype="multipart/form-data" class="p-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                Event Type <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="type" name="type" value="{{ old('type') }}"
                                placeholder="e.g., Wedding, Birthday, Corporate"
                                class="block w-full px-3 py-2 rounded-lg border-gray-300 border-2 focus:border-rose-500 focus:ring-2 focus:ring-rose-200 transition"
                                required>
                            @error('type')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                                Location <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="location" name="location" value="{{ old('location') }}"
                                placeholder="e.g., Cagayan de Oro City"
                                class="block w-full px-3 py-2 rounded-lg border-gray-300 border-2 focus:border-rose-500 focus:ring-2 focus:ring-rose-200 transition"
                                required>
                            @error('location')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Event Name -->
                    <div class="mt-6">
                        <label for="event_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Event Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="event_name" name="event_name" value="{{ old('event_name') }}"
                            placeholder="e.g., BRYAN + CARN Wedding"
                            class="block w-full px-3 py-2 rounded-lg border-gray-300 border-2 focus:border-rose-500 focus:ring-2 focus:ring-rose-200 transition"
                            required>
                        @error('event_name')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mt-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description / Quote <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="description" name="description" rows="3"
                            placeholder="e.g., When the world blurs, love stays clear."
                            class="block w-full px-3 py-2 rounded-lg border-gray-300 border-2 focus:border-rose-500 focus:ring-2 focus:ring-rose-200 transition"
                            required>{{ old('description') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Maximum 500 characters</p>
                        @error('description')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Image -->
                    <div class="mt-6">
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                            Event Image <span class="text-rose-500">*</span>
                        </label>
                        <input type="file" id="image" name="image" accept="image/*"
                            class="block w-full px-3 py-2 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100"
                            required>
                        <p class="mt-1 text-xs text-gray-500">Best size: 800x1000px (4:5 ratio). Max 5MB.</p>
                        @error('image')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Display Order -->
                        <div>
                            <label for="display_order" class="block text-sm font-medium text-gray-700 mb-2">
                                Display Order
                            </label>
                            <input type="number" id="display_order" name="display_order"
                                value="{{ old('display_order', 0) }}" min="0"
                                class="block w-full px-3 py-2 rounded-lg border-gray-300 border-2 focus:border-rose-500 focus:ring-2 focus:ring-rose-200 transition">
                            <p class="mt-1 text-xs text-gray-500">Lower numbers appear first</p>
                        </div>

                        <!-- Published -->
                        <div class="flex items-center h-full">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="is_published" value="1" {{ old('is_published', true)
                                    ? 'checked' : '' }}
                                    class="rounded border-gray-300 border-2 text-rose-600 focus:ring-2 focus:ring-rose-200">
                                <span class="ml-2 text-sm font-medium text-gray-700">Publish immediately</span>
                            </label>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="mt-8 flex gap-3">
                        <a href="{{ route('admin.management.showcases.index') }}"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                            Cancel
                        </a>
                        <button type="submit"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-rose-500 to-pink-600 text-white font-semibold rounded-lg hover:from-rose-600 hover:to-pink-700 transition shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Create Showcase
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>