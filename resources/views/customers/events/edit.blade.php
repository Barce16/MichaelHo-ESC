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

    // Inclusions can NEVER be removed - only new ones can be added
    $canRemoveInclusions = false;

    // All existing inclusions are locked
    $originalInclusionIds = $event->inclusions->pluck('id')->toArray();
    @endphp

    <div class="py-6" x-data="editEventForm()" x-init="init()">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Notice if removal is restricted --}}
            @if(!$canRemoveInclusions)
            <div class="bg-amber-50 border-l-4 border-amber-500 rounded-lg p-4">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div class="text-sm text-amber-900">
                        <p class="font-semibold mb-1">Limited Editing Mode</p>
                        <p>Since your event is already <strong>{{ $event->status_label }}</strong>, you can only
                            <strong>add new inclusions</strong>. Existing inclusions cannot be removed at this stage.
                            Contact the admin if you need to make changes.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- IMPORTANT: Remove @submit handler from form, handle it programmatically -->
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
                    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 border-b border-gray-200 px-6 py-4">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Select Your Inclusions
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Choose the services you'd like to add or remove from your
                            event</p>
                    </div>

                    <div class="p-6">
                        {{-- Tabs Navigation --}}
                        <div class="border-b border-gray-200 mb-6">
                            <div class="flex flex-wrap gap-2">
                                <template x-for="(category, index) in categories" :key="category.category">
                                    <button type="button" @click="activeTab = category.category" :class="{
                            'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === category.category,
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== category.category
                        }" class="px-4 py-2.5 border-b-2 font-medium text-sm transition-colors duration-200 whitespace-nowrap"
                                        x-text="category.category">
                                    </button>
                                </template>
                            </div>
                        </div>

                        {{-- Tab Content --}}
                        <div class="space-y-6">
                            <template x-for="category in categories" :key="category.category">
                                <div x-show="activeTab === category.category"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                    {{-- Category Grid --}}
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                        <template x-for="inc in category.items" :key="inc.id">
                                            <div class="relative border rounded-xl overflow-hidden transition-all duration-300 cursor-pointer hover:shadow-lg"
                                                :class="{
                                    'ring-2 ring-emerald-500 border-emerald-500 shadow-md': selectedIncs.has(inc.id) && !isLocked(inc.id),
                                    'ring-2 ring-slate-400 border-slate-400 shadow-md bg-slate-50': selectedIncs.has(inc.id) && isLocked(inc.id),
                                    'border-gray-300 hover:border-emerald-300': !selectedIncs.has(inc.id),
                                    'cursor-not-allowed': isLocked(inc.id)
                                }" @click="toggleInclusion(inc.id)">

                                                {{-- Lock Badge for locked inclusions --}}
                                                <div x-show="isLocked(inc.id)" class="absolute top-2 right-2 z-10">
                                                    <span
                                                        class="inline-flex items-center gap-1 px-2 py-1 bg-slate-600 text-white text-xs font-medium rounded-full shadow-sm">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                        </svg>
                                                        Locked
                                                    </span>
                                                </div>

                                                {{-- Image --}}
                                                <div class="aspect-video bg-gray-100 overflow-hidden">
                                                    <img :src="'/storage/' + inc.image" :alt="inc.name"
                                                        class="w-full h-full object-cover">
                                                </div>

                                                {{-- Content --}}
                                                <div class="p-4">
                                                    <div class="flex items-start justify-between gap-2 mb-2">
                                                        <h5 class="font-semibold text-gray-900 line-clamp-2 flex-1"
                                                            x-text="inc.name"></h5>
                                                        <span
                                                            class="text-sm font-bold text-emerald-600 whitespace-nowrap">₱<span
                                                                x-text="Number(inc.price).toLocaleString()"></span></span>
                                                    </div>

                                                    <p class="text-xs text-gray-600 line-clamp-2 mb-3"
                                                        x-text="inc.notes"></p>

                                                    {{-- Selection Indicator --}}
                                                    <div class="flex items-center gap-2">
                                                        <div class="flex-shrink-0 w-5 h-5 rounded border-2 flex items-center justify-center transition"
                                                            :class="{
                                                'bg-emerald-500 border-emerald-500': selectedIncs.has(inc.id) && !isLocked(inc.id),
                                                'bg-slate-400 border-slate-400': selectedIncs.has(inc.id) && isLocked(inc.id),
                                                'border-gray-300': !selectedIncs.has(inc.id)
                                            }">
                                                            <svg x-show="selectedIncs.has(inc.id)"
                                                                class="w-3 h-3 text-white" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="3" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        </div>
                                                        <span class="text-xs font-medium" :class="{
                                                'text-emerald-600': selectedIncs.has(inc.id) && !isLocked(inc.id),
                                                'text-slate-500': selectedIncs.has(inc.id) && isLocked(inc.id),
                                                'text-gray-500': !selectedIncs.has(inc.id)
                                            }" x-text="isLocked(inc.id) ? 'Locked' : (selectedIncs.has(inc.id) ? 'Selected' : 'Select')">
                                                        </span>

                                                        {{-- Package Badge --}}
                                                        <template x-if="packageInclusions.includes(inc.id)">
                                                            <span
                                                                class="ml-auto text-xs font-semibold px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full flex items-center gap-1">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                                </svg>
                                                                In Package
                                                            </span>
                                                        </template>
                                                    </div>

                                                    {{-- Hidden checkbox input --}}
                                                    <input type="checkbox" class="sr-only pointer-events-none"
                                                        name="inclusions[]" :value="inc.id"
                                                        :checked="selectedIncs.has(inc.id)">
                                                </div>

                                                {{-- Notes Section (only shows when selected) --}}
                                                <div x-show="selectedIncs.has(inc.id)" x-transition
                                                    class="px-4 pb-4 border-t border-gray-200 bg-white" @click.stop>
                                                    <label class="block text-xs font-medium text-gray-700 mb-2 mt-3">
                                                        <svg class="w-3.5 h-3.5 inline mr-1" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                        Add Your Notes (Optional)
                                                    </label>
                                                    <textarea :name="'inclusion_notes[' + inc.id + ']'"
                                                        x-model="inclusionNotes[inc.id]" rows="2"
                                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                                        :placeholder="'Special requests or preferences for ' + inc.name + '...'"></textarea>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>

                        {{-- Summary --}}
                        <div
                            class="mt-8 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl p-6 text-white shadow-lg">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <span class="text-emerald-100 text-sm">Selected Inclusions</span>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-2xl font-bold" x-text="selectedIncs.size"></span>
                                        <span class="text-emerald-100">item<span
                                                x-show="selectedIncs.size !== 1">s</span></span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-emerald-100 text-sm block">Subtotal</span>
                                    <span class="text-2xl font-bold">₱<span
                                            x-text="inclusionsSubtotal().toLocaleString()"></span></span>
                                </div>
                            </div>
                            <div class="border-t border-white/20 pt-3 flex items-center justify-between">
                                <span class="text-lg font-semibold">Grand Total</span>
                                <span class="text-3xl font-bold">₱<span
                                        x-text="grandTotal().toLocaleString()"></span></span>
                            </div>
                        </div>

                        @error('inclusions')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror

                        {{-- Notice about locked inclusions --}}
                        @if(count($originalInclusionIds) > 0)
                        <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4">
                            <div class="flex gap-3">
                                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="text-sm text-blue-900">
                                    <p class="font-semibold mb-1">Add-Only Mode</p>
                                    <p>Your current inclusions are <strong>locked</strong> and cannot be removed. You
                                        can <strong>add new inclusions</strong> to your event.
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Hidden inputs for locked inclusions (so they're always submitted) --}}
                @if(!$canRemoveInclusions)
                @foreach($originalInclusionIds as $lockedId)
                <input type="hidden" name="locked_inclusions[]" value="{{ $lockedId }}">
                @endforeach
                @endif

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
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
    <script>
        function editEventForm() {
    const eventSelections = @json($event->inclusions->pluck('id'));
    const oldSelections = @json(old('inclusions', []));
    const existingNotes = @json($existingNotes ?? []);
    const originalInclusions = new Set(eventSelections.map(id => Number(id)));
    const originalInclusionIds = @json($originalInclusionIds);
    const canRemove = false;
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
        canRemoveInclusions: canRemove,
        lockedInclusions: new Set(originalInclusionIds.map(id => Number(id))),
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
            
            // If trying to remove a locked inclusion, do nothing
            if (!this.canRemoveInclusions && this.lockedInclusions.has(id) && this.selectedIncs.has(id)) {
                return;
            }
            
            if (this.selectedIncs.has(id)) {
                this.selectedIncs.delete(id);
            } else {
                this.selectedIncs.add(id);
            }
        },
        
        isLocked(id) {
            return !this.canRemoveInclusions && this.lockedInclusions.has(Number(id));
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