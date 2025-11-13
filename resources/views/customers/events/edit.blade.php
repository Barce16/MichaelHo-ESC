<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Edit Event</h2>
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

    {{-- Expose package data for Alpine --}}
    <script>
        window.__pkgData = {
    @foreach($packages as $p)
        {{ $p->id }}: @js([
            'id'   => $p->id,
            'name' => $p->name,
            'type' => $p->type,
            'coordination' => $p->coordination,
            'coordination_price' => $p->coordination_price ?? 25000,
            'event_styling' => is_array($p->event_styling) ? array_values($p->event_styling) : [],
            'event_styling_price' => $p->event_styling_price ?? 55000,
            'inclusions' => $p->inclusions->map(fn($i) => $i->id),
        ]),
    @endforeach
    };

    window.__allInclusions = @js($allInclusions->map(function($categoryInclusions, $categoryName) {
        return [
            'category' => $categoryName,
            'items' => $categoryInclusions->map(fn($i) => [
                'id' => $i->id,
                'name' => $i->name,
                'price' => $i->price,
                'notes' => $i->notes,
                'image' => $i->image,
                'category' => $i->category,
                'package_type' => $i->package_type,
            ])->values()
        ];
    })->values());
    </script>

    @php
    // Event's currently selected inclusions grouped by category
    $eventInclusionsByCategory = $event->inclusions->groupBy('category')->map(function($incs) {
    return $incs->first()->id;
    })->toArray();
    @endphp

    <div class="py-6" x-data="editEventForm()" x-init="init()">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- IMPORTANT: Remove @submit handler from form, we'll handle it programmatically -->
            <form method="POST" action="{{ route('customer.events.update', $event) }}" class="space-y-6"
                id="eventEditForm">
                @csrf
                @method('PUT')

                {{-- Event Information & Additional Details Combined --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-violet-50 to-purple-50 border-b border-gray-200 px-6 py-4">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Event Information & Details
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Update your event details</p>
                    </div>

                    <div class="p-6 space-y-6">
                        {{-- Basic Event Information --}}
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">Basic
                                Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Event Name --}}
                                <div>
                                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        Event Name <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" required
                                        value="{{ old('name', $event->name) }}"
                                        class="block w-full px-4 py-3 rounded-lg border-[1px] border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition"
                                        placeholder="e.g., Sarah's 18th Birthday">
                                    @error('name')
                                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Contact Number --}}
                                <div>
                                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        Contact Number
                                    </label>
                                    <input type="text" name="phone" id="phone"
                                        value="{{ old('phone', $customer->phone) }}"
                                        class="block w-full px-4 py-3 rounded-lg border-[1px] border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition"
                                        placeholder="e.g., 09171234567">
                                    @error('phone')
                                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Event Date --}}
                                <div>
                                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Event Date <span class="text-rose-500">*</span>
                                    </label>
                                    <x-calendar-picker name="event_date"
                                        :value="old('event_date', \Illuminate\Support\Carbon::parse($event->event_date)->format('Y-m-d'))"
                                        required />
                                    @error('event_date')
                                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Package Selection --}}
                                <div>
                                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                        Package <span class="text-rose-500">*</span>
                                    </label>
                                    <select name="package_id" id="package_id" required
                                        class="block w-full px-4 py-3 rounded-lg border-[1px] border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition"
                                        x-model.number="selectedPackage" @change="loadPackage(selectedPackage)">
                                        <option value="">Select a package</option>
                                        @foreach($packages as $p)
                                        <option value="{{ $p->id }}" @selected(old('package_id', $event->package_id) ==
                                            $p->id)>
                                            {{ $p->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('package_id')
                                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Venue --}}
                                <div>
                                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Venue
                                    </label>
                                    <input type="text" name="venue" id="venue" value="{{ old('venue', $event->venue) }}"
                                        class="block w-full px-4 py-3 rounded-lg border-[1px] border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition"
                                        placeholder="e.g., Grand Ballroom, City Hotel">
                                    @error('venue')
                                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Theme --}}
                                <div>
                                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                        </svg>
                                        Theme
                                    </label>
                                    <input type="text" name="theme" id="theme" value="{{ old('theme', $event->theme) }}"
                                        class="block w-full px-4 py-3 rounded-lg border-[1px] border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition"
                                        placeholder="e.g., Enchanted Garden">
                                    @error('theme')
                                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Number of Guests --}}
                                <div>
                                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        Number of Guests
                                    </label>
                                    <input type="number" name="guests" id="guests"
                                        value="{{ old('guests', $event->guests) }}" min="1"
                                        class="block w-full px-4 py-3 rounded-lg border-[1px] border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition"
                                        placeholder="e.g., 150">
                                    @error('guests')
                                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Notes --}}
                                <div class="md:col-span-2">
                                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Special Requests or Notes
                                    </label>
                                    <textarea name="notes" id="notes" rows="3"
                                        class="block w-full px-4 py-3 rounded-lg border-[1px] border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition"
                                        placeholder="Any special requests, dietary restrictions, or additional information...">{{ old('notes', $event->notes) }}</textarea>
                                    @error('notes')
                                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Inclusions Selection --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div
                        class="bg-gradient-to-r from-violet-50 to-purple-50 border-b border-gray-200 px-6 py-4 flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Select Inclusions
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">Choose the services you want for your event</p>
                        </div>

                        {{-- Price Summary Badge --}}
                        <div class="text-right">
                            <div class="inline-flex items-center px-3 py-1 rounded-full bg-violet-100 text-violet-700">
                                <span class="text-sm font-medium">Total:</span>
                                <span class="text-lg font-bold ml-2">₱<span
                                        x-text="grandTotal().toLocaleString()"></span></span>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        {{-- Category Tabs --}}
                        <div class="border-b border-gray-200 mb-6">
                            <nav class="-mb-px flex space-x-8 overflow-x-auto" aria-label="Inclusion categories">
                                <template x-for="cat in categories" :key="cat.category">
                                    <button type="button" @click="activeTab = cat.category" :class="activeTab === cat.category ? 
                                    'border-violet-500 text-violet-600' : 
                                    'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition">
                                        <span x-text="cat.category"></span>
                                        <span class="ml-2 px-2 py-0.5 text-xs rounded-full"
                                            :class="activeTab === cat.category ? 'bg-violet-100 text-violet-600' : 'bg-gray-100 text-gray-600'"
                                            x-text="cat.items.length"></span>
                                    </button>
                                </template>
                            </nav>
                        </div>

                        {{-- Inclusions Grid --}}
                        <template x-for="cat in categories" :key="cat.category">
                            <div x-show="activeTab === cat.category" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <template x-for="item in cat.items" :key="item.id">
                                    <div class="relative border rounded-lg overflow-hidden transition hover:shadow-md"
                                        :class="selectedIncs.has(item.id) ? 
                                    'bg-violet-50 border-violet-300 ring-2 ring-violet-200' : 
                                    'bg-white border-gray-200 hover:border-gray-300'">

                                        {{-- Inclusion Image --}}
                                        <template x-if="item.image">
                                            <div class="h-32 w-full bg-gray-100 overflow-hidden">
                                                <img :src="'/storage/' + item.image" :alt="item.name"
                                                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                            </div>
                                        </template>

                                        <div class="p-4">
                                            <label :for="'inc_' + item.id"
                                                class="flex items-start cursor-pointer select-none">
                                                <div class="flex items-center h-5">
                                                    <input :id="'inc_' + item.id" type="checkbox" :name="'inclusions[]'"
                                                        :value="item.id" :checked="selectedIncs.has(item.id)"
                                                        @change="toggleInclusion(item.id)"
                                                        class="w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500">
                                                </div>

                                                <div class="ml-3 flex-1">
                                                    <div class="flex justify-between items-start">
                                                        <div class="flex-1">
                                                            <p class="text-sm font-semibold text-gray-900"
                                                                x-text="item.name"></p>
                                                            <p class="text-xs text-gray-500 mt-1" x-text="item.notes">
                                                            </p>
                                                        </div>
                                                        <span class="text-sm font-bold text-violet-600 ml-2">
                                                            ₱<span x-text="Number(item.price).toLocaleString()"></span>
                                                        </span>
                                                    </div>

                                                    {{-- Notes Input --}}
                                                    <div x-show="selectedIncs.has(item.id)" class="mt-3"
                                                        x-transition:enter="transition ease-out duration-200"
                                                        x-transition:enter-start="opacity-0 transform scale-95"
                                                        x-transition:enter-end="opacity-100 transform scale-100">
                                                        <label :for="'notes_' + item.id"
                                                            class="block text-xs font-medium text-gray-600 mb-1">
                                                            Special instructions (optional)
                                                        </label>
                                                        <input type="text" :id="'notes_' + item.id"
                                                            :name="'inclusion_notes[' + item.id + ']'"
                                                            x-model="inclusionNotes[item.id]"
                                                            class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:border-violet-300 focus:ring focus:ring-violet-200 focus:ring-opacity-50"
                                                            placeholder="e.g., Specific color, style, or quantity">
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>

                        {{-- Price Breakdown --}}
                        <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Price Breakdown</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Inclusions Subtotal:</span>
                                    <span class="font-medium">₱<span
                                            x-text="inclusionsSubtotal().toLocaleString()"></span></span>
                                </div>
                                <div class="flex justify-between" x-show="pkg && pkg.coordination_price">
                                    <span class="text-gray-600">Event Coordination:</span>
                                    <span class="font-medium">₱<span
                                            x-text="Number(pkg?.coordination_price || 0).toLocaleString()"></span></span>
                                </div>
                                <div class="flex justify-between" x-show="pkg && pkg.event_styling_price">
                                    <span class="text-gray-600">Event Styling:</span>
                                    <span class="font-medium">₱<span
                                            x-text="Number(pkg?.event_styling_price || 0).toLocaleString()"></span></span>
                                </div>
                                <div class="border-t pt-2 flex justify-between">
                                    <span class="font-semibold text-gray-900">Grand Total:</span>
                                    <span class="font-bold text-violet-600 text-lg">₱<span
                                            x-text="grandTotal().toLocaleString()"></span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit Buttons --}}
                <div class="flex justify-between items-center">
                    <a href="{{ route('customer.events.show', $event) }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancel
                    </a>

                    <button type="submit"
                        class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-violet-600 to-purple-600 text-white rounded-lg hover:from-violet-700 hover:to-purple-700 transition font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Event
                    </button>
                </div>
            </form>
        </div>

        {{-- Confirmation Modal --}}
        <div x-show="showConfirmModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                {{-- Background overlay --}}
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    @click="showConfirmModal = false"></div>

                {{-- Modal panel --}}
                <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Confirm Inclusion Changes
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        You've made changes to your event inclusions. These changes will need admin
                                        approval.
                                    </p>

                                    {{-- Show changes summary --}}
                                    <div class="mt-4 space-y-3">
                                        {{-- Added items --}}
                                        <div x-show="changesSummary.added.length > 0">
                                            <p class="text-sm font-medium text-gray-700">Items to be added:</p>
                                            <ul class="mt-1 text-sm text-gray-600 list-disc list-inside">
                                                <template x-for="item in changesSummary.added" :key="item.id">
                                                    <li>
                                                        <span x-text="item.name"></span>
                                                        <span class="text-green-600 font-medium">+₱<span
                                                                x-text="Number(item.price).toLocaleString()"></span></span>
                                                    </li>
                                                </template>
                                            </ul>
                                        </div>

                                        {{-- Removed items --}}
                                        <div x-show="changesSummary.removed.length > 0">
                                            <p class="text-sm font-medium text-gray-700">Items to be removed:</p>
                                            <ul class="mt-1 text-sm text-gray-600 list-disc list-inside">
                                                <template x-for="item in changesSummary.removed" :key="item.id">
                                                    <li>
                                                        <span x-text="item.name"></span>
                                                        <span class="text-red-600 font-medium">-₱<span
                                                                x-text="Number(item.price).toLocaleString()"></span></span>
                                                    </li>
                                                </template>
                                            </ul>
                                        </div>

                                        {{-- Total change --}}
                                        <div class="pt-3 border-t border-gray-200">
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-600">Previous Total:</span>
                                                <span class="font-medium">₱<span
                                                        x-text="Number(changesSummary.oldTotal).toLocaleString()"></span></span>
                                            </div>
                                            <div class="flex justify-between text-sm mt-1">
                                                <span class="text-gray-600">New Total:</span>
                                                <span class="font-bold text-violet-600">₱<span
                                                        x-text="Number(changesSummary.newTotal).toLocaleString()"></span></span>
                                            </div>
                                            <div class="flex justify-between text-sm mt-2 pt-2 border-t">
                                                <span class="font-medium">Difference:</span>
                                                <span class="font-bold"
                                                    :class="changesSummary.difference > 0 ? 'text-green-600' : 'text-red-600'">
                                                    <span
                                                        x-text="changesSummary.difference > 0 ? '+' : ''"></span>₱<span
                                                        x-text="Math.abs(changesSummary.difference).toLocaleString()"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="confirmAndSubmit()"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-violet-600 text-base font-medium text-white hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Confirm Changes
                        </button>
                        <button type="button" @click="showConfirmModal = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function editEventForm() {
    const eventSelections = @json($event->inclusions->pluck('id'));
    const oldSelections = @json(old('inclusions', []));
    const existingNotes = @json($existingNotes ?? []);
    const originalInclusions = new Set(eventSelections.map(id => Number(id)));
    const originalTotal = Number(@json($event->inclusions->sum(fn($i) => $i->pivot->price_snapshot ?? 0))) + 
                         Number(@json($event->package?->coordination_price ?? 25000)) + 
                         Number(@json($event->package?->event_styling_price ?? 55000));

    return {
        selectedPackage: Number(@json(old('package_id', $event->package_id))) || null,
        pkg: null,
        packageInclusions: [],
        categories: [],
        activeTab: '',
        selectedIncs: new Set(),
        inclusionNotes: {},
        showConfirmModal: false,
        formSubmitted: false,
        changesSummary: {
            added: [],
            removed: [],
            oldTotal: originalTotal,
            newTotal: originalTotal,
            difference: 0
        },

        loadPackage(id){
            if (!id) {
                this.pkg = null;
                this.packageInclusions = [];
                this.categories = [];
                this.activeTab = '';
                return;
            }

            id = Number(id);
            const p = window.__pkgData[id];
            if (!p) return;

            this.pkg = p;
            this.packageInclusions = p.inclusions || [];

            // Filter inclusions based on package type
            const filteredInclusions = window.__allInclusions
                .map(cat => ({
                    category: cat.category,
                    items: cat.items.filter(item => 
                        !item.package_type || 
                        item.package_type === 'all' || 
                        item.package_type === p.type
                    )
                }))
                .filter(cat => cat.items.length > 0);

            this.categories = filteredInclusions;
            if (this.categories.length > 0) {
                this.activeTab = this.categories[0].category;
            }

            // Restore old selections if validation failed
            if (Array.isArray(oldSelections) && oldSelections.length > 0) {
                oldSelections.forEach(id => {
                    const idNum = Number(id);
                    const isVisible = this.categories.some(cat => 
                        cat.items.some(item => item.id === idNum)
                    );
                    if (isVisible) {
                        this.selectedIncs.add(idNum);
                    }
                });
            } 
            // Otherwise restore event's current selections
            else if (Array.isArray(eventSelections) && eventSelections.length > 0 && Number(@json($event->package_id)) === Number(id)) {
                eventSelections.forEach(id => {
                    const idNum = Number(id);
                    const isVisible = this.categories.some(cat => 
                        cat.items.some(item => item.id === idNum)
                    );
                    if (isVisible) {
                        this.selectedIncs.add(idNum);
                    }
                });
            }
            // Or pre-select package inclusions
            else if (p && this.categories.length) {
                this.categories.forEach(cat => {
                    cat.items.forEach(item => {
                        if (this.packageInclusions.includes(item.id)) {
                            this.selectedIncs.add(item.id);
                        }
                    });
                });
            }
        },

        toggleInclusion(id){
            id = Number(id);
            if (this.selectedIncs.has(id)) {
                this.selectedIncs.delete(id);
            } else {
                this.selectedIncs.add(id);
            }
        },

        inclusionsSubtotal(){
            let total = 0;
            this.selectedIncs.forEach(incId => {
                this.categories.forEach(cat => {
                    const item = cat.items.find(i => i.id === incId);
                    if (item) total += Number(item.price || 0);
                });
            });
            return total;
        },

        grandTotal(){
            const coord = Number(this.pkg ? this.pkg.coordination_price : 0);
            const styl  = Number(this.pkg ? this.pkg.event_styling_price : 0);
            return this.inclusionsSubtotal() + coord + styl;
        },

        detectChanges() {
            // Get added inclusions
            const added = [];
            this.selectedIncs.forEach(incId => {
                if (!originalInclusions.has(incId)) {
                    this.categories.forEach(cat => {
                        const item = cat.items.find(i => i.id === incId);
                        if (item) {
                            added.push({
                                id: item.id,
                                name: item.name,
                                price: item.price
                            });
                        }
                    });
                }
            });

            // Get removed inclusions
            const removed = [];
            originalInclusions.forEach(incId => {
                if (!this.selectedIncs.has(incId)) {
                    this.categories.forEach(cat => {
                        const item = cat.items.find(i => i.id === incId);
                        if (item) {
                            removed.push({
                                id: item.id,
                                name: item.name,
                                price: item.price
                            });
                        }
                    });
                }
            });

            // Calculate totals
            const newTotal = this.grandTotal();
            const difference = newTotal - originalTotal;

            this.changesSummary = {
                added,
                removed,
                oldTotal: originalTotal,
                newTotal: newTotal,
                difference: difference
            };

            return added.length > 0 || removed.length > 0;
        },

        handleSubmit(event) {
            event.preventDefault();
            
            // If already confirmed, let the form submit normally
            if (this.formSubmitted) {
                return true;
            }

            // Check if inclusions changed
            const hasChanges = this.detectChanges();
            
            if (hasChanges) {
                // Show confirmation modal
                this.showConfirmModal = true;
                return false; // Prevent form submission
            } else {
                // No changes, submit directly
                return true; // Allow form submission
            }
        },

        confirmAndSubmit() {
            this.formSubmitted = true;
            this.showConfirmModal = false;
            
            // Get the form and submit it
            const form = document.getElementById('eventEditForm');
            if (form) {
                form.submit();
            }
        },

        init() {
            // Load existing notes into inclusionNotes
            Object.keys(existingNotes).forEach(id => {
                this.inclusionNotes[id] = existingNotes[id];
            });
            
            if (this.selectedPackage) {
                this.loadPackage(this.selectedPackage);
            }

            // Attach submit handler to form using the form's built-in onsubmit
            const form = document.getElementById('eventEditForm');
            if (form) {
                form.onsubmit = (e) => {
                    // Only prevent default if handleSubmit returns false
                    if (!this.handleSubmit(e)) {
                        e.preventDefault();
                        return false;
                    }
                    return true;
                };
            }
        }
    }
}
    </script>
</x-app-layout>