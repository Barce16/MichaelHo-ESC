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

            <form method="POST" action="{{ route('customer.events.update', $event) }}" class="space-y-6">
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
                                <div class="md:col-span-2">
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
                                <div class="md:col-span-2">
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
                                    <p class="mt-1 text-xs text-gray-500">
                                        This will update your contact information for this event.
                                    </p>
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
                                        placeholder="Event location">
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
                                        placeholder="Event theme or style">
                                    @error('theme')
                                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Additional Details --}}
                        <div class="border-t border-gray-200 pt-6">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">Additional
                                Details</h4>
                            <div class="space-y-4">
                                {{-- Guests --}}
                                <div>
                                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        Guest Details
                                    </label>
                                    <textarea name="guests" id="guests" rows="4"
                                        class="block w-full px-4 py-3 rounded-lg border-[1px] border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition resize-none"
                                        placeholder="Guest count, names, or special requirements...">{{ old('guests', $event->guests) }}</textarea>
                                    @error('guests')
                                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Notes --}}
                                <div>
                                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Additional Notes
                                    </label>
                                    <textarea name="notes" id="notes" rows="3"
                                        class="block w-full px-4 py-3 rounded-lg border-[1px] border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition resize-none"
                                        placeholder="Any special requests or requirements...">{{ old('notes', $event->notes) }}</textarea>
                                    @error('notes')
                                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Package Details --}}
                <template x-if="pkg">
                    <div class="space-y-6">
                        {{-- Package Services (Always Included) --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="bg-gradient-to-r from-sky-50 to-blue-50 border-b border-gray-200 px-6 py-4">
                                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Included Services
                                </h3>
                                <p class="text-sm text-gray-500 mt-1">These services are included in your package</p>
                            </div>

                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {{-- Coordination --}}
                                    <div
                                        class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-lg p-5 border-[1px] border-blue-200">
                                        <div class="flex items-start justify-between gap-3 mb-2">
                                            <h4 class="font-semibold text-blue-900">Coordination</h4>
                                            <span class="text-lg font-bold text-blue-600">₱<span
                                                    x-text="fmt(pkg.coordination_price)"></span></span>
                                        </div>
                                        <p class="text-sm text-blue-800 leading-relaxed" x-text="pkg.coordination"></p>
                                    </div>

                                    {{-- Event Styling --}}
                                    <div
                                        class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg p-5 border-[1px] border-purple-200">
                                        <div class="flex items-start justify-between gap-3 mb-2">
                                            <h4 class="font-semibold text-purple-900">Event Styling</h4>
                                            <span class="text-lg font-bold text-purple-600">₱<span
                                                    x-text="fmt(pkg.event_styling_price)"></span></span>
                                        </div>
                                        <template x-if="pkg.event_styling && pkg.event_styling.length">
                                            <ul class="text-sm text-purple-800 space-y-1">
                                                <template x-for="item in pkg.event_styling" :key="item">
                                                    <li class="flex items-start gap-2">
                                                        <svg class="w-4 h-4 mt-0.5 text-purple-500 flex-shrink-0"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        <span x-text="item"></span>
                                                    </li>
                                                </template>
                                            </ul>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Inclusions Section --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="bg-gradient-to-r from-emerald-50 to-teal-50 border-b border-gray-200 px-6 py-4">
                                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    Select Your Inclusions
                                </h3>
                                <p class="text-sm text-gray-500 mt-1">Choose items from each category</p>
                            </div>

                            <div class="p-6 space-y-8">
                                {{-- Category Tabs --}}
                                <div class="flex flex-wrap gap-2 mb-6 pb-4 border-b border-gray-200">
                                    <template x-for="category in categories" :key="category.category">
                                        <button type="button" @click="activeTab = category.category" :class="activeTab === category.category 
                                                ? 'bg-emerald-500 text-white shadow-md' 
                                                : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                                            class="px-4 py-2 rounded-lg font-medium text-sm transition-all">
                                            <span x-text="category.category"></span>
                                        </button>
                                    </template>
                                </div>

                                {{-- Category Content --}}
                                <template x-for="category in categories" :key="category.category">
                                    <div x-show="activeTab === category.category" x-transition>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <template x-for="item in category.items" :key="item.id">
                                                <div>
                                                    <div
                                                        class="bg-gradient-to-br from-gray-50 to-slate-50 rounded-lg border-[1px] border-gray-200 hover:border-emerald-300 hover:shadow-md transition-all overflow-hidden">
                                                        <label class="flex items-start gap-4 p-4 cursor-pointer group">
                                                            <input type="checkbox" name="inclusions[]" :value="item.id"
                                                                @change="toggleInclusion(item.id)"
                                                                :checked="selectedIncs.has(item.id)"
                                                                class="mt-1 w-5 h-5 rounded border-gray-300 text-emerald-600 focus:ring-2 focus:ring-emerald-200 focus:ring-offset-0 cursor-pointer">

                                                            {{-- Image --}}
                                                            <template x-if="item.image">
                                                                <img :src="`/storage/${item.image}`" :alt="item.name"
                                                                    class="w-20 h-20 object-cover rounded-lg border border-gray-200 flex-shrink-0">
                                                            </template>

                                                            <div class="flex-1 min-w-0">
                                                                <div
                                                                    class="flex items-start justify-between gap-3 mb-2">
                                                                    <h4 class="font-semibold text-gray-900 group-hover:text-emerald-600 transition"
                                                                        x-text="item.name"></h4>
                                                                    <span
                                                                        class="text-lg font-bold text-emerald-600 whitespace-nowrap">₱<span
                                                                            x-text="fmt(item.price)"></span></span>
                                                                </div>
                                                                <template x-if="item.notes">
                                                                    <p class="text-sm text-gray-600 leading-relaxed line-clamp-2"
                                                                        x-text="item.notes"></p>
                                                                </template>
                                                                <template x-if="packageInclusions.includes(item.id)">
                                                                    <span
                                                                        class="inline-flex items-center gap-1 text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full mt-2">
                                                                        <svg class="w-3 h-3" fill="none"
                                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round" stroke-width="2"
                                                                                d="M5 13l4 4L19 7" />
                                                                        </svg>
                                                                        In Package
                                                                    </span>
                                                                </template>
                                                            </div>
                                                        </label>
                                                        {{-- Notes textarea (shows when item is selected) --}}
                                                        <div x-show="selectedIncs.has(item.id)" x-transition
                                                            class="my-3 px-4">
                                                            <label class="text-xs font-medium text-gray-600 mb-1 block">
                                                                Add notes for this item (optional)
                                                            </label>
                                                            <textarea :name="`inclusion_notes[${item.id}]`" rows="2"
                                                                x-model="inclusionNotes[item.id]"
                                                                class="block w-full px-3 py-2 text-sm rounded-lg border-[1px] border-gray-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition resize-none"
                                                                placeholder="e.g., specific color preferences, setup instructions..."></textarea>
                                                        </div>
                                                    </div>


                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>

                                {{-- Summary at Bottom --}}
                                <div
                                    class="bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl p-6 text-white shadow-lg">
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
                                                    x-text="fmt(inclusionsSubtotal())"></span></span>
                                        </div>
                                    </div>
                                    <div class="border-t border-white/20 pt-3 flex items-center justify-between">
                                        <span class="text-lg font-semibold">Grand Total</span>
                                        <span class="text-3xl font-bold">₱<span
                                                x-text="fmt(grandTotal())"></span></span>
                                    </div>
                                </div>

                                @error('inclusions')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </template>

                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('customer.events.show', $event) }}"
                        class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancel
                    </a>
                    <button type="submit"
                        class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-violet-500 to-purple-600 text-white font-semibold rounded-lg hover:from-violet-600 hover:to-purple-700 transition shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

    <script>
        function editEventForm(){
    const initialPkg = Number(@json(old('package_id', $event->package_id)) || 0);
    const oldSelections = @json(old('inclusions', [])) || [];
    const eventSelections = @json($event->inclusions->pluck('id')->toArray());
    const existingNotes = @json($existingNotes ?? []);

    return {
        selectedPackage: initialPkg,
        pkg: null,
        selectedIncs: new Set(),
        packageInclusions: [],
        categories: [],
        allCategories: [],
        activeTab: '',
        inclusionNotes: {},

        fmt(n){
            return Number(n || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },

        loadPackage(id){
            const p = window.__pkgData[id] || null;
            this.pkg = p;
            this.packageInclusions = p ? (p.inclusions || []) : [];
            this.selectedIncs = new Set();
            this.allCategories = window.__allInclusions || [];
            
            // Filter inclusions based on package type
            if (p && p.type) {
                this.categories = this.allCategories.map(cat => {
                    return {
                        category: cat.category,
                        items: cat.items.filter(item => {
                            return item.package_type === p.type || item.package_type === null;
                        })
                    };
                }).filter(cat => cat.items.length > 0);
            } else {
                this.categories = this.allCategories;
            }

            // Set first category as active tab
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

        init() {
            // Load existing notes into inclusionNotes
            Object.keys(existingNotes).forEach(id => {
                this.inclusionNotes[id] = existingNotes[id];
            });
            
            if (this.selectedPackage) {
                this.loadPackage(this.selectedPackage);
            }
        }
    }
}
    </script>
</x-app-layout>