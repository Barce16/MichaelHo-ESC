<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Request New Event</h2>
            <a href="{{ route('customer.events.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Events
            </a>
        </div>
    </x-slot>

    <script>
        window.__pkgData = {
  @foreach($packages as $p)
    {{ $p->id }}: @js([
      'id'                   => $p->id,
      'name'                 => $p->name,
      'type'                 => $p->type,
      'coordination'         => $p->coordination,
      'coordination_price'   => $p->coordination_price ?? 25000,
      'event_styling'        => is_array($p->event_styling) ? array_values($p->event_styling) : [],
      'event_styling_price'  => $p->event_styling_price ?? 55000,
      'inclusions'           => $p->inclusions->map(fn($i) => $i->id),
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
            'package_type' => $i->package_type, // Add this line
        ])->values()
    ];
})->values());
    </script>

    <div class="py-6" x-data="eventForm()" x-init="init()">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <form method="POST" action="{{ route('customer.events.store') }}" class="space-y-6">
                @csrf

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
                        <p class="text-sm text-gray-500 mt-1">Tell us about your special event</p>
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
                                    <input type="text" name="name" id="name" required value="{{ old('name') }}"
                                        class="block w-full px-4 py-3 rounded-lg border-[1px] border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition"
                                        placeholder="e.g., Sarah's 18th Birthday">
                                    @error('name')
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
                                    <x-calendar-picker name="event_date" :value="old('event_date')" required />
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
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
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
                                    <input type="text" name="venue" id="venue" value="{{ old('venue') }}"
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
                                    <input type="text" name="theme" id="theme" value="{{ old('theme') }}"
                                        class="block w-full px-4 py-3 rounded-lg border-[1px] border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition"
                                        placeholder="e.g., Garden, Vintage, Modern">
                                    @error('theme')
                                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Divider --}}
                        <div class="border-t border-gray-200"></div>

                        {{-- Additional Details --}}
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">Additional
                                Details</h4>
                            <div class="space-y-4">
                                {{-- Guest Count --}}
                                <div>
                                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        Guest Count
                                    </label>
                                    <input type="number" name="guests" id="guests" min="1"
                                        class="block w-full px-4 py-3 rounded-lg border-[1px] border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition"
                                        placeholder="Enter number of guests" value="{{ old('guests') }}">
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
                                        placeholder="Any special requests or requirements...">{{ old('notes') }}</textarea>
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
                        {{-- Package Overview --}}
                        <div
                            class="bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h3 class="text-2xl font-bold mb-2" x-text="pkg.name"></h3>
                                    <p class="text-violet-100 text-sm" x-text="pkg.type"></p>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-violet-100">Estimated Total</div>
                                    <div class="text-3xl font-bold">₱<span x-text="fmt(grandTotal())"></span></div>
                                </div>
                            </div>
                        </div>

                        {{-- Package Services --}}
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
                            </div>

                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {{-- Coordination --}}
                                    <div
                                        class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-lg p-4 border border-blue-200">
                                        <div class="flex items-start justify-between gap-3 mb-2">
                                            <h4 class="font-semibold text-blue-900">Coordination</h4>
                                            <span class="text-lg font-bold text-blue-600">₱<span
                                                    x-text="fmt(pkg.coordination_price)"></span></span>
                                        </div>
                                        <p class="text-sm text-blue-800" x-text="pkg.coordination"></p>
                                    </div>

                                    {{-- Event Styling --}}
                                    <div
                                        class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg p-4 border border-purple-200">
                                        <div class="flex items-start justify-between gap-3 mb-2">
                                            <h4 class="font-semibold text-purple-900">Event Styling</h4>
                                            <span class="text-lg font-bold text-purple-600">₱<span
                                                    x-text="fmt(pkg.event_styling_price)"></span></span>
                                        </div>
                                        <template x-if="pkg.event_styling && pkg.event_styling.length">
                                            <ul class="text-sm text-purple-800 space-y-1">
                                                <template x-for="item in pkg.event_styling" :key="item">
                                                    <li class="flex items-start gap-1">
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

                        {{-- Inclusions with Tabs by Category --}}
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
                                <p class="text-sm text-gray-500 mt-1">Choose the services you'd like to add to your
                                    package</p>
                            </div>

                            <div class="p-6">
                                {{-- Tabs Navigation --}}
                                <div class="border-b border-gray-200 mb-6">
                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="(category, index) in categories" :key="category.category">
                                            <button type="button" @click="activeTab = category.category" :class="{
                                                    'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === category.category,
                                                    'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== category.category
                                                }"
                                                class="px-4 py-2.5 border-b-2 font-medium text-sm transition-colors duration-200 whitespace-nowrap"
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
                                                            'ring-2 ring-emerald-500 border-emerald-500 shadow-md': selectedIncs.has(inc.id),
                                                            'border-gray-300 hover:border-emerald-300': !selectedIncs.has(inc.id)
                                                        }" @click="toggleInclusion(inc.id)">

                                                        {{-- Image --}}
                                                        <div class="aspect-video bg-gray-100 overflow-hidden">
                                                            <img :src="inc.image" :alt="inc.name"
                                                                class="w-full h-full object-cover">
                                                        </div>

                                                        {{-- Content --}}
                                                        <div class="p-4">
                                                            <div class="flex items-start justify-between gap-2 mb-2">
                                                                <h5 class="font-semibold text-gray-900 line-clamp-2 flex-1"
                                                                    x-text="inc.name"></h5>
                                                                <span
                                                                    class="text-sm font-bold text-emerald-600 whitespace-nowrap">₱<span
                                                                        x-text="fmt(inc.price)"></span></span>
                                                            </div>

                                                            <p class="text-xs text-gray-600 line-clamp-2 mb-3"
                                                                x-text="inc.notes"></p>

                                                            {{-- Selection Indicator --}}
                                                            <div class="flex items-center gap-2">
                                                                <div class="flex-shrink-0 w-5 h-5 rounded border-2 flex items-center justify-center transition"
                                                                    :class="{
                                                                        'bg-emerald-500 border-emerald-500': selectedIncs.has(inc.id),
                                                                        'border-gray-300': !selectedIncs.has(inc.id)
                                                                    }">
                                                                    <svg x-show="selectedIncs.has(inc.id)"
                                                                        class="w-3 h-3 text-white" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="3"
                                                                            d="M5 13l4 4L19 7" />
                                                                    </svg>
                                                                </div>
                                                                <span class="text-xs font-medium" :class="{
                                                                        'text-emerald-600': selectedIncs.has(inc.id),
                                                                        'text-gray-500': !selectedIncs.has(inc.id)
                                                                    }"
                                                                    x-text="selectedIncs.has(inc.id) ? 'Selected' : 'Select'">
                                                                </span>

                                                                {{-- Package Badge --}}
                                                                <template x-if="packageInclusions.includes(inc.id)">
                                                                    <span
                                                                        class="ml-auto text-xs font-semibold px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full flex items-center gap-1">
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

                                                            {{-- Hidden checkbox input --}}
                                                            <input type="checkbox" class="sr-only pointer-events-none"
                                                                name="inclusions[]" :value="inc.id"
                                                                :checked="selectedIncs.has(inc.id)">
                                                        </div>

                                                        {{-- Notes Section (only shows when selected) --}}
                                                        <div x-show="selectedIncs.has(inc.id)" x-transition
                                                            class="px-4 pb-4 border-t border-gray-200 bg-white"
                                                            @click.stop>
                                                            <label
                                                                class="block text-xs font-medium text-gray-700 mb-2 mt-3">
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
                    <a href="{{ route('customer.events.index') }}"
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
                        Submit Event Request
                    </button>
                </div>
            </form>
            <div x-show="showConfirmModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                    {{-- Background overlay --}}
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                        @click="showConfirmModal = false"></div>

                    {{-- Modal panel --}}
                    <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full"
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-violet-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-violet-600" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                                        Confirm Event Request
                                    </h3>
                                    <div class="mt-4">
                                        <p class="text-sm text-gray-500 mb-4">
                                            Please review your event details before submitting:
                                        </p>

                                        {{-- Event Summary --}}
                                        <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Event Information</h4>
                                            <div class="text-sm text-gray-600 space-y-1">
                                                <p><strong>Package:</strong> <span
                                                        x-text="pkg?.name || 'Not selected'"></span></p>
                                                <p><strong>Selected Inclusions:</strong> <span
                                                        x-text="selectedIncs.size"></span> items</p>
                                            </div>
                                        </div>

                                        {{-- Selected Inclusions --}}
                                        <div class="max-h-60 overflow-y-auto mb-4">
                                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Selected Inclusions:
                                            </h4>
                                            <ul class="space-y-2">
                                                <template x-for="cat in categories" :key="cat.category">
                                                    <template x-for="item in cat.items" :key="item.id">
                                                        <li x-show="selectedIncs.has(item.id)"
                                                            class="flex justify-between items-center text-sm bg-white p-2 rounded border border-gray-200">
                                                            <span x-text="item.name" class="text-gray-700"></span>
                                                            <span class="font-medium text-violet-600">
                                                                ₱<span
                                                                    x-text="Number(item.price).toLocaleString()"></span>
                                                            </span>
                                                        </li>
                                                    </template>
                                                </template>
                                            </ul>
                                        </div>

                                        {{-- Price Breakdown --}}
                                        <div
                                            class="bg-gradient-to-r from-violet-50 to-purple-50 rounded-lg p-4 border border-violet-200">
                                            <h4 class="text-sm font-semibold text-gray-800 mb-3">Price Breakdown</h4>
                                            <div class="space-y-2 text-sm">
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Inclusions Subtotal:</span>
                                                    <span class="font-medium">₱<span
                                                            x-text="inclusionsSubtotal().toLocaleString()"></span></span>
                                                </div>
                                                <div class="flex justify-between"
                                                    x-show="pkg && pkg.coordination_price">
                                                    <span class="text-gray-600">Event Coordination:</span>
                                                    <span class="font-medium">₱<span
                                                            x-text="Number(pkg?.coordination_price || 0).toLocaleString()"></span></span>
                                                </div>
                                                <div class="flex justify-between"
                                                    x-show="pkg && pkg.event_styling_price">
                                                    <span class="text-gray-600">Event Styling:</span>
                                                    <span class="font-medium">₱<span
                                                            x-text="Number(pkg?.event_styling_price || 0).toLocaleString()"></span></span>
                                                </div>
                                                <div class="border-t-2 border-violet-300 pt-2 flex justify-between">
                                                    <span class="font-bold text-gray-900">Grand Total:</span>
                                                    <span class="font-bold text-violet-600 text-lg">₱<span
                                                            x-text="grandTotal().toLocaleString()"></span></span>
                                                </div>
                                            </div>
                                        </div>

                                        <p class="mt-4 text-xs text-gray-500">
                                            * This is an estimate. Final pricing will be confirmed after admin approval.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" @click="confirmAndSubmit()"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-violet-600 text-base font-medium text-white hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Confirm & Submit Request
                            </button>
                            <button type="button" @click="showConfirmModal = false"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Go Back & Edit
                            </button>
                        </div>
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
        function eventForm(){
    const initialPkg = Number(@json(old('package_id', request('package_id'))) || 0);
    const oldSelections = @json(old('inclusions', [])) || [];

    return {
        selectedPackage: initialPkg,
        pkg: null,
        selectedIncs: new Set(),
        inclusionNotes: {}, // Track notes for each inclusion
        packageInclusions: [],
        categories: [],
        allCategories: [], // Store all categories
        activeTab: '', // Track active tab
        showConfirmModal: false,
        formSubmitted: false,

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
                            // Show if package_type matches OR package_type is null (available for all)
                            return item.package_type === p.type || item.package_type === null;
                        })
                    };
                }).filter(cat => cat.items.length > 0); // Remove empty categories
            } else {
                // If no package type, show all inclusions
                this.categories = this.allCategories;
            }

            // Set first category as active tab
            if (this.categories.length > 0) {
                this.activeTab = this.categories[0].category;
            }

            // Pre-select items that are in the package
            if (p && this.categories.length) {
                this.categories.forEach(cat => {
                    cat.items.forEach(item => {
                        if (this.packageInclusions.includes(item.id)) {
                            this.selectedIncs.add(item.id);
                        }
                    });
                });
            }

            // Restore old selections if any
            if (Array.isArray(oldSelections) && oldSelections.length > 0) {
                oldSelections.forEach(id => {
                    const idNum = Number(id);
                    // Only restore if the inclusion is visible for this package
                    const isVisible = this.categories.some(cat => 
                        cat.items.some(item => item.id === idNum)
                    );
                    if (isVisible) {
                        this.selectedIncs.add(idNum);
                    }
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

        handleSubmit(event) {
        event.preventDefault();
        
        // If already confirmed, let form submit
        if (this.formSubmitted) {
            return true;
        }

        // Check if package is selected
        if (!this.selectedPackage) {
            alert('Please select a package first.');
            return false;
        }

        // Check if at least one inclusion is selected
        if (this.selectedIncs.size === 0) {
            alert('Please select at least one inclusion.');
            return false;
        }

        // Show confirmation modal
        this.showConfirmModal = true;
        return false;
    },

    confirmAndSubmit() {
        this.formSubmitted = true;
        this.showConfirmModal = false;
        
        // Submit the form
        const form = document.getElementById('eventCreateForm');
        if (form) {
            form.submit();
        }
    },

    

    init(){
            if (this.selectedPackage) {
                this.loadPackage(this.selectedPackage);
            }

            // 🆕 ADD THIS SECTION:
            const form = document.getElementById('eventCreateForm');
            if (form) {
                form.onsubmit = (e) => {
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