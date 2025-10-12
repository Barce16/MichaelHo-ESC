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

                {{-- Event Information Card --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-violet-50 to-purple-50 border-b border-gray-200 px-6 py-4">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Event Information
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Update your event details</p>
                    </div>

                    <div class="p-6 space-y-4">
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
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition">
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
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition"
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
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition">
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
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition">
                                @error('theme')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
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

                        {{-- Inclusions by Category --}}
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

                            <div class="p-6 space-y-8">
                                <template x-for="category in categories" :key="category.category">
                                    <div>
                                        <h4
                                            class="text-md font-semibold text-gray-800 mb-4 pb-2 border-b flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                            </svg>
                                            <span x-text="category.category"></span>
                                        </h4>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <template x-for="inc in category.items" :key="inc.id">
                                                <div @click="toggleInclusion(inc.id)"
                                                    class="group cursor-pointer bg-white border-2 rounded-xl p-4 transition-all duration-200 hover:shadow-md flex gap-4"
                                                    :class="selectedIncs.has(inc.id) ? 'border-emerald-500 bg-emerald-50/50 shadow-sm' : 'border-gray-200 hover:border-gray-300'">

                                                    {{-- Checkbox --}}
                                                    <div class="flex-shrink-0 pt-1">
                                                        <div class="w-6 h-6 rounded-md border-2 flex items-center justify-center transition-all duration-200"
                                                            :class="selectedIncs.has(inc.id) ? 'bg-emerald-500 border-emerald-500' : 'bg-white border-gray-300 group-hover:border-gray-400'">
                                                            <svg class="w-4 h-4 text-white transition-all duration-200"
                                                                :class="selectedIncs.has(inc.id) ? 'opacity-100 scale-100' : 'opacity-0 scale-50'"
                                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="3" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    {{-- Image --}}
                                                    <template x-if="inc.image">
                                                        <div
                                                            class="w-20 h-20 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100 border border-gray-200">
                                                            <img :src="`/storage/${inc.image}`" :alt="inc.name"
                                                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300 pointer-events-none">
                                                        </div>
                                                    </template>

                                                    {{-- Content --}}
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-start justify-between gap-2 mb-1">
                                                            <h5 class="font-semibold text-gray-900" x-text="inc.name">
                                                            </h5>
                                                            <span
                                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold whitespace-nowrap transition-all"
                                                                :class="selectedIncs.has(inc.id) ? 'bg-emerald-500 text-white' : 'bg-gray-100 text-gray-700'">
                                                                ₱<span x-text="fmt(inc.price)"></span>
                                                            </span>
                                                        </div>

                                                        <template x-if="inc.notes && inc.notes.trim()">
                                                            <p class="text-xs text-gray-600 line-clamp-2 mb-2"
                                                                x-text="inc.notes"></p>
                                                        </template>

                                                        <template x-if="packageInclusions.includes(inc.id)">
                                                            <span
                                                                class="inline-flex items-center gap-1 text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">
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
                                            </template>
                                        </div>
                                    </div>
                                </template>

                                {{-- Summary --}}
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

                {{-- Additional Details --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 border-b border-gray-200 px-6 py-4">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                            Additional Details
                        </h3>
                    </div>

                    <div class="p-6 space-y-4">
                        {{-- Budget --}}
                        <div>
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 36 36">
                                    <path d="M14.18,13.8V16h9.45a5.26,5.26,0,0,0,.08-.89,4.72,4.72,0,0,0-.2-1.31Z">
                                    </path>
                                    <path d="M14.18,19.7h5.19a4.28,4.28,0,0,0,3.5-1.9H14.18Z"></path>
                                    <path d="M19.37,10.51H14.18V12h8.37A4.21,4.21,0,0,0,19.37,10.51Z"></path>
                                    <path
                                        d="M17.67,2a16,16,0,1,0,16,16A16,16,0,0,0,17.67,2Zm10.5,15.8H25.7a6.87,6.87,0,0,1-6.33,4.4H14.18v6.54a1.25,1.25,0,1,1-2.5,0V17.8H8.76a.9.9,0,1,1,0-1.8h2.92V13.8H8.76a.9.9,0,1,1,0-1.8h2.92V9.26A1.25,1.25,0,0,1,12.93,8h6.44a6.84,6.84,0,0,1,6.15,4h2.65a.9.9,0,0,1,0,1.8H26.09a6.91,6.91,0,0,1,.12,1.3,6.8,6.8,0,0,1-.06.9h2a.9.9,0,0,1,0,1.8Z">
                                    </path>
                                </svg>
                                Budget (Optional)
                            </label>
                            <div class="relative">
                                <span
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₱</span>
                                <input type="number" name="budget" id="budget" step="0.01" min="0"
                                    value="{{ old('budget', $event->budget) }}"
                                    class="block w-full pl-10 pr-4 py-3 rounded-lg border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition"
                                    placeholder="0.00">
                            </div>
                            @error('budget')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

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
                                class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition resize-none"
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
                                class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition resize-none"
                                placeholder="Any special requests or requirements...">{{ old('notes', $event->notes) }}</textarea>
                            @error('notes')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

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

        return {
            selectedPackage: initialPkg,
            pkg: null,
            selectedIncs: new Set(),
            packageInclusions: [],
            categories: [],

            fmt(n){
                return Number(n || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },

            loadPackage(id){
                const p = window.__pkgData[id] || null;
                this.pkg = p;
                this.packageInclusions = p ? (p.inclusions || []) : [];
                this.selectedIncs = new Set();
                this.categories = window.__allInclusions || [];

                // Restore old selections if validation failed
                if (Array.isArray(oldSelections) && oldSelections.length > 0) {
                    oldSelections.forEach(id => this.selectedIncs.add(Number(id)));
                } 
                // Otherwise restore event's current selections
                else if (Array.isArray(eventSelections) && eventSelections.length > 0 && Number(@json($event->package_id)) === Number(id)) {
                    eventSelections.forEach(id => this.selectedIncs.add(Number(id)));
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

            init(){
                if (this.selectedPackage) {
                    this.loadPackage(this.selectedPackage);
                }
            }
        }
    }
    </script>
</x-app-layout>