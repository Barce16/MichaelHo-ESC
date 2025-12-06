<x-admin.layouts.management>
    @php
    $pkgConfig = [
    'initialInclusions' => old('inclusions', []),
    'names' => $inclusions->pluck('name','id'),
    'categories' => $inclusions->pluck('category','id'),
    'prices' => $inclusions->pluck('price','id'),
    'notes' => $inclusions->pluck('notes','id'),
    'packageTypes' => $inclusions->pluck('package_type','id'),
    'images' => $inclusions->pluck('image_url','id'),
    'defaults' => [
    'coordinationPrice' => old('coordination_price', 25000),
    'eventStylingPrice' => old('event_styling_price', 55000),
    'packagePrice' => old('price', 0),
    'autoCalc' => (bool) old('autoCalc', true),
    ],
    ];
    @endphp
    <script type="application/json" id="pkg-config-create">
        {!! json_encode($pkgConfig, JSON_UNESCAPED_UNICODE) !!}
    </script>

    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-bold text-gray-900">Create New Package</h3>
                <p class="text-gray-500 mt-1">Set up a new event package with pricing and inclusions</p>
            </div>
            <a href="{{ route('admin.management.packages.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Cancel
            </a>
        </div>

        {{-- Error Display --}}
        @if ($errors->any())
        <div class="bg-rose-50 border border-rose-200 rounded-xl p-5">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-rose-800 mb-2">Please fix the following errors:</h3>
                    <ul class="list-disc list-inside space-y-1 text-sm text-rose-700">
                        @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.management.packages.store') }}" enctype="multipart/form-data"
            class="space-y-6">
            @csrf

            {{-- Basic Information --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                    <h4 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Basic Information
                    </h4>
                </div>

                <div class="p-6 space-y-6">
                    <div>
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                            Package Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-3 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400"
                            placeholder="e.g., Premium Wedding Package">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-lg border border-slate-200">
                        <input id="is_active" name="is_active" type="checkbox" value="1" @checked(old('is_active',
                            true))
                            class="w-5 h-5 text-emerald-600 rounded border-gray-300 focus:ring-2 focus:ring-emerald-200">
                        <div class="flex-1">
                            <label for="is_active" class="text-sm font-medium text-gray-900">Active Package</label>
                            <p class="text-xs text-gray-500 mt-0.5">Make this package available for booking immediately
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Alpine Pricing Scope --}}
            <div x-data="packagePricing()" x-init="init('#pkg-config-create')" x-cloak class="space-y-6">

                {{-- Package Type Selection --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                        <h4 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            Package Type
                        </h4>
                    </div>

                    <div class="p-6">
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                            Package Type <span class="text-rose-500">*</span>
                        </label>
                        <select name="type" id="type" required x-model="selectedType" @change="onTypeChange()"
                            class="w-full px-4 py-3 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400">
                            <option value="">Select package type</option>
                            @foreach(\App\Enums\PackageType::cases() as $type)
                            <option value="{{ $type->value }}" @selected(old('type')==$type->value)>
                                {{ $type->label() }}
                            </option>
                            @endforeach
                        </select>
                        <p class="mt-2 text-xs text-gray-500">
                            <span class="text-amber-600">Note:</span> Selecting a package type will filter the available
                            inclusions.
                        </p>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>
                </div>

                {{-- Inclusions Selection --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                        <div class="flex items-center justify-between flex-wrap gap-4">
                            <h4 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                                Package Inclusions
                            </h4>
                            <div class="flex items-center gap-3">
                                <span
                                    class="px-3 py-1.5 bg-violet-100 text-violet-700 text-xs font-semibold rounded-full"
                                    x-text="`${selected.length} selected`"></span>
                                <span x-show="selectedType"
                                    class="px-3 py-1.5 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full"
                                    x-text="`${availableCount} available`"></span>
                            </div>
                        </div>

                        {{-- Search & Filter Bar --}}
                        <div class="mt-4 flex flex-col sm:flex-row gap-3" x-show="selectedType">
                            <div class="relative flex-1">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input type="text" x-model="searchQuery" placeholder="Search inclusions..."
                                    class="w-full pl-10 pr-4 py-2 text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-200 focus:border-violet-400">
                            </div>
                            <select x-model="categoryFilter"
                                class="px-4 py-2 text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-200 focus:border-violet-400">
                                <option value="">All Categories</option>
                                @php
                                $categories = $inclusions->pluck('category')->unique()->filter()->sort();
                                @endphp
                                @foreach($categories as $cat)
                                <option value="{{ $cat->value }}">{{ $cat->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="p-6 space-y-6">
                        {{-- No Type Selected --}}
                        <template x-if="!selectedType">
                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 text-center">
                                <svg class="w-12 h-12 text-amber-400 mx-auto mb-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <p class="font-medium text-amber-800 mb-1">Please select a package type first</p>
                                <p class="text-sm text-amber-700">Inclusions will be filtered based on the selected
                                    package type.</p>
                            </div>
                        </template>

                        {{-- Inclusions Grid --}}
                        <template x-if="selectedType">
                            <div class="space-y-4">
                                <div
                                    class="flex items-center justify-between bg-gradient-to-r from-blue-50 to-violet-50 border border-blue-200 rounded-lg p-3">
                                    <p class="text-sm text-blue-800">
                                        <span class="font-medium">Showing:</span>
                                        <span x-text="selectedType" class="font-semibold"></span> inclusions
                                    </p>
                                    <button type="button" @click="searchQuery = ''; categoryFilter = ''"
                                        x-show="searchQuery || categoryFilter"
                                        class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                        Clear Filters
                                    </button>
                                </div>

                                {{-- Image Cards Grid --}}
                                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                                    @foreach($inclusions as $inc)
                                    <div x-show="isInclusionVisible({{ $inc->id }}, '{{ addslashes($inc->name) }}', '{{ $inc->category?->value }}')"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100" @click="toggle({{ $inc->id }})"
                                        class="inclusion-card relative group cursor-pointer rounded-xl overflow-hidden transition-all duration-300 bg-white"
                                        :class="has({{ $inc->id }}) ? 'ring-2 ring-violet-500 shadow-xl shadow-violet-100 scale-[1.02]' : 'ring-1 ring-gray-200 hover:ring-violet-300 hover:shadow-lg'">

                                        {{-- Image Container --}}
                                        <div
                                            class="relative aspect-[4/3] bg-gradient-to-br from-gray-100 to-gray-50 overflow-hidden">
                                            {{-- Actual Image with fallback --}}
                                            @if($inc->image_url)
                                            <img src="{{ $inc->image_url }}" alt="{{ $inc->name }}"
                                                class="w-full h-full object-cover transition-all duration-500 group-hover:scale-110"
                                                loading="lazy"
                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div
                                                class="hidden absolute inset-0 items-center justify-center bg-gradient-to-br from-violet-50 to-slate-100">
                                                <svg class="w-12 h-12 text-violet-300" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            @else
                                            <div
                                                class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-violet-50 to-slate-100">
                                                <svg class="w-12 h-12 text-violet-300" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            @endif

                                            {{-- Gradient overlay on hover --}}
                                            <div
                                                class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            </div>

                                            {{-- Selected State Overlay --}}
                                            <div x-show="has({{ $inc->id }})" x-transition.opacity.duration.200ms
                                                class="absolute inset-0 bg-violet-600/20 backdrop-blur-[1px]">
                                                <div
                                                    class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
                                                    <div
                                                        class="w-12 h-12 bg-violet-600 rounded-full flex items-center justify-center shadow-xl animate-bounce-subtle">
                                                        <svg class="w-6 h-6 text-white" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="3" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Top Badges Row --}}
                                            <div class="absolute top-2 left-2 right-2 flex items-start justify-between">
                                                {{-- All Types / Specific Type Badge --}}
                                                @if(!$inc->package_type)
                                                <span
                                                    class="px-2 py-1 bg-emerald-500 text-white text-[10px] font-bold rounded-md shadow-sm flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Universal
                                                </span>
                                                @else
                                                <span
                                                    class="px-2 py-1 bg-slate-700/90 text-white text-[10px] font-medium rounded-md shadow-sm backdrop-blur-sm">
                                                    {{ $inc->package_type }}
                                                </span>
                                                @endif

                                                {{-- Quick Preview Button --}}
                                                @if($inc->image_url)
                                                <button type="button"
                                                    @click.stop="openPreview('{{ $inc->image_url }}', '{{ addslashes($inc->name) }}', {{ $inc->price }}, '{{ $inc->category?->label() }}')"
                                                    class="p-1.5 bg-white/90 rounded-lg shadow-sm opacity-0 group-hover:opacity-100 transition-all duration-200 hover:bg-white hover:scale-110"
                                                    title="Quick preview">
                                                    <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                                    </svg>
                                                </button>
                                                @endif
                                            </div>

                                            {{-- Price Badge --}}
                                            <div class="absolute bottom-2 right-2">
                                                <div
                                                    class="px-2.5 py-1 bg-white text-gray-900 text-sm font-bold rounded-lg shadow-lg flex items-center gap-1">
                                                    <span class="text-violet-600">₱</span>{{ number_format($inc->price,
                                                    0) }}
                                                </div>
                                            </div>

                                            {{-- Hover Info (shows on hover) --}}
                                            <div
                                                class="absolute bottom-2 left-2 right-14 opacity-0 group-hover:opacity-100 transition-opacity duration-300 transform translate-y-2 group-hover:translate-y-0">
                                                <p class="text-white text-xs font-medium drop-shadow-lg line-clamp-2">{{
                                                    $inc->name }}</p>
                                            </div>
                                        </div>

                                        {{-- Content --}}
                                        <div class="p-3 border-t border-gray-100">
                                            <h5
                                                class="font-semibold text-gray-900 text-sm leading-snug line-clamp-2 min-h-[2.5rem] group-hover:text-violet-700 transition-colors">
                                                {{ $inc->name }}
                                            </h5>
                                            <div class="mt-2 flex items-center justify-between">
                                                @if($inc->category)
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 bg-violet-50 text-violet-700 text-[11px] font-medium rounded-full border border-violet-100">
                                                    {{ $inc->category->label() }}
                                                </span>
                                                @else
                                                <span></span>
                                                @endif

                                                {{-- Selection indicator --}}
                                                <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all duration-200"
                                                    :class="has({{ $inc->id }}) ? 'bg-violet-600 border-violet-600' : 'border-gray-300 group-hover:border-violet-400'">
                                                    <svg x-show="has({{ $inc->id }})" class="w-3 h-3 text-white"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="3" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="checkbox" class="sr-only" :checked="has({{ $inc->id }})">
                                    </div>
                                    @endforeach
                                </div>

                                {{-- Image Preview Modal --}}
                                <div x-show="previewOpen" x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                    x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                    @click="previewOpen = false" @keydown.escape.window="previewOpen = false"
                                    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
                                    style="display: none;">
                                    <div @click.stop
                                        class="relative max-w-2xl w-full bg-white rounded-2xl shadow-2xl overflow-hidden"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100">
                                        {{-- Close button --}}
                                        <button @click="previewOpen = false"
                                            class="absolute top-3 right-3 z-10 p-2 bg-white/90 rounded-full shadow-lg hover:bg-white transition">
                                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>

                                        {{-- Image --}}
                                        <div class="aspect-video bg-gray-100">
                                            <img :src="previewImage" :alt="previewName"
                                                class="w-full h-full object-contain">
                                        </div>

                                        {{-- Info --}}
                                        <div class="p-5">
                                            <div class="flex items-start justify-between gap-4">
                                                <div>
                                                    <h3 class="text-xl font-bold text-gray-900" x-text="previewName">
                                                    </h3>
                                                    <p class="text-sm text-violet-600 mt-1" x-text="previewCategory">
                                                    </p>
                                                </div>
                                                <div class="text-2xl font-bold text-violet-700">
                                                    ₱<span x-text="fmt(previewPrice)"></span>
                                                </div>
                                            </div>
                                            <button type="button" @click="toggleFromPreview(); previewOpen = false"
                                                class="mt-4 w-full py-3 rounded-xl font-semibold transition-all duration-200"
                                                :class="has(previewId) ? 'bg-rose-100 text-rose-700 hover:bg-rose-200' : 'bg-violet-600 text-white hover:bg-violet-700'">
                                                <span
                                                    x-text="has(previewId) ? 'Remove from Package' : 'Add to Package'"></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{-- No Results --}}
                                <div x-show="availableCount === 0" class="text-center py-8 text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="font-medium">No inclusions found</p>
                                    <p class="text-sm mt-1">Try adjusting your filters</p>
                                </div>
                            </div>
                        </template>

                        {{-- Selected Summary --}}
                        <template x-if="selected.length">
                            <div class="space-y-4 pt-6 border-t border-gray-200">
                                <div class="flex items-center justify-between">
                                    <h5 class="font-semibold text-gray-900 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        Selected Inclusions
                                        <span
                                            class="px-2 py-0.5 bg-violet-100 text-violet-700 text-xs font-bold rounded-full"
                                            x-text="selected.length"></span>
                                    </h5>
                                    <button type="button"
                                        @click="selected = []; if(autoCalc) packagePrice = Number(grandTotal().toFixed(2));"
                                        class="text-xs text-rose-600 hover:text-rose-800 font-medium flex items-center gap-1 hover:underline">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Clear All
                                    </button>
                                </div>

                                {{-- Selected Items Grid --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                    <template x-for="(row, idx) in selected" :key="row.id">
                                        <div
                                            class="group flex items-center gap-3 bg-gradient-to-r from-violet-50 to-purple-50 border border-violet-200 rounded-xl p-2 hover:shadow-md transition-all duration-200">
                                            {{-- Thumbnail --}}
                                            <div
                                                class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100 ring-2 ring-white shadow-sm">
                                                <img :src="images[row.id]" :alt="names[row.id]"
                                                    class="w-full h-full object-cover"
                                                    onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'%239ca3af\'%3E%3Cpath stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1\' d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\'/%3E%3C/svg%3E'">
                                            </div>

                                            {{-- Info --}}
                                            <div class="flex-1 min-w-0">
                                                <div class="font-semibold text-gray-900 text-sm truncate"
                                                    x-text="names[row.id]"></div>
                                                <template x-if="categories[row.id]">
                                                    <div class="text-xs text-violet-600 mt-0.5"
                                                        x-text="categories[row.id]"></div>
                                                </template>
                                                <div class="text-base font-bold text-violet-700 mt-1 flex items-center">
                                                    <span class="text-violet-500 text-sm mr-0.5">₱</span>
                                                    <span x-text="fmt(prices[row.id] ?? 0)"></span>
                                                </div>
                                            </div>

                                            {{-- Remove Button --}}
                                            <button type="button" @click.stop="remove(row.id)"
                                                class="flex-shrink-0 p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all duration-200 opacity-60 group-hover:opacity-100">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>

                                            <input type="hidden" :name="`inclusions[${idx}][id]`" :value="row.id">
                                        </div>
                                    </template>
                                </div>

                                {{-- Subtotal --}}
                                <div
                                    class="flex items-center justify-between bg-gradient-to-r from-violet-100 via-purple-100 to-violet-100 rounded-xl p-5 shadow-inner">
                                    <div>
                                        <span class="text-sm text-violet-600 font-medium">Inclusions Subtotal</span>
                                        <p class="text-xs text-violet-500 mt-0.5"
                                            x-text="`${selected.length} item${selected.length !== 1 ? 's' : ''} selected`">
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-3xl font-bold text-violet-700">₱<span
                                                x-text="fmt(subtotal())"></span></span>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <x-input-error :messages="$errors->get('inclusions')" class="mt-2" />
                    </div>
                </div>

                {{-- Coordination & Styling --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                        <h4 class="text-lg font-semibold text-gray-800">Coordination & Event Styling</h4>
                    </div>

                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-2 block">Coordination Price <span
                                        class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <span
                                        class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500">₱</span>
                                    <input type="number" name="coordination_price" step="0.01" min="0"
                                        x-model.number="coordinationPrice"
                                        @input="if(autoCalc) packagePrice = Number(grandTotal().toFixed(2))"
                                        class="w-full pl-8 pr-4 py-3 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400">
                                </div>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-2 block">Event Styling Price <span
                                        class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <span
                                        class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500">₱</span>
                                    <input type="number" name="event_styling_price" step="0.01" min="0"
                                        x-model.number="eventStylingPrice"
                                        @input="if(autoCalc) packagePrice = Number(grandTotal().toFixed(2))"
                                        class="w-full pl-8 pr-4 py-3 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-2 block">Coordination Description</label>
                            <textarea name="coordination" rows="3"
                                class="w-full px-4 py-3 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400"
                                placeholder="Describe the coordination services...">{{ old('coordination') }}</textarea>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-2 block">Event Styling Details</label>
                            <textarea name="event_styling_text" rows="4"
                                class="w-full px-4 py-3 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400"
                                placeholder="One item per line...">{{ old('event_styling_text') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">One item per line</p>
                        </div>
                    </div>
                </div>

                {{-- Package Price --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                        <h4 class="text-lg font-semibold text-gray-800">Package Price</h4>
                    </div>

                    <div class="p-6 space-y-6">
                        <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-lg border border-slate-200">
                            <input id="autoCalc" type="checkbox" x-model="autoCalc"
                                @change="if(autoCalc) packagePrice = Number(grandTotal().toFixed(2))"
                                class="w-5 h-5 text-violet-600 rounded border-gray-300 focus:ring-2 focus:ring-violet-200">
                            <div class="flex-1">
                                <label for="autoCalc" class="text-sm font-medium text-gray-900">Auto-calculate
                                    price</label>
                                <p class="text-xs text-gray-500 mt-0.5">Sum inclusions + coordination + styling</p>
                            </div>
                        </div>

                        <div
                            class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl p-6 border border-slate-200">
                            <div class="space-y-3 mb-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Inclusions</span>
                                    <span class="font-medium">₱<span x-text="fmt(subtotal())"></span></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Coordination</span>
                                    <span class="font-medium">₱<span x-text="fmt(coordinationPrice)"></span></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Event Styling</span>
                                    <span class="font-medium">₱<span x-text="fmt(eventStylingPrice)"></span></span>
                                </div>
                                <div class="border-t border-slate-300 pt-3 flex justify-between">
                                    <span class="font-semibold text-gray-900">Calculated Total</span>
                                    <span class="text-lg font-bold text-violet-700">₱<span
                                            x-text="fmt(grandTotal())"></span></span>
                                </div>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-2 block">Final Package Price <span
                                        class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <span
                                        class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 text-lg font-medium">₱</span>
                                    <input type="number" name="price" step="0.01" min="0" required
                                        x-model.number="packagePrice" :readonly="autoCalc"
                                        :class="autoCalc ? 'bg-gray-100 cursor-not-allowed' : 'bg-white'"
                                        class="w-full pl-10 pr-4 py-4 text-2xl font-bold border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Banner --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                    <h4 class="text-lg font-semibold text-gray-800">Package Banner (Optional)</h4>
                </div>
                <div class="p-6" x-data="{ bannerPreview: null }">
                    <input type="file" name="banner" id="banner" accept="image/*" class="hidden"
                        @change="bannerPreview = URL.createObjectURL($event.target.files[0])">

                    <template x-if="!bannerPreview">
                        <label for="banner"
                            class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-lg hover:border-slate-400 hover:bg-slate-50 cursor-pointer transition">
                            <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-sm text-gray-600">Click to upload banner</span>
                        </label>
                    </template>
                    <template x-if="bannerPreview">
                        <div class="relative">
                            <img :src="bannerPreview" class="w-full h-40 object-cover rounded-lg">
                            <button type="button"
                                @click="bannerPreview = null; document.getElementById('banner').value = ''"
                                class="absolute top-2 right-2 p-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </template>
                    <x-input-error :messages="$errors->get('banner')" class="mt-2" />
                </div>
            </div>

            {{-- Gallery --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200" x-data="galleryUploader()">
                <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                    <h4 class="text-lg font-semibold text-gray-800">Package Gallery <span class="text-rose-500">*</span>
                        <span class="text-xs font-normal text-gray-500">(min 4)</span></h4>
                </div>
                <div class="p-6 space-y-4">
                    <div
                        class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-slate-400 transition">
                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <input type="file" name="images[]" multiple accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200"
                            @change="handleFiles($event)">
                    </div>
                    <x-input-error :messages="$errors->get('images')" />

                    <template x-if="newItems.length">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <template x-for="(img, i) in newItems" :key="img.key">
                                <div class="relative group">
                                    <div class="aspect-[4/3] overflow-hidden rounded-lg border-2 border-gray-200">
                                        <img :src="img.url" class="w-full h-full object-cover">
                                        <button type="button" @click="removeNew(i)"
                                            class="absolute top-2 right-2 p-1.5 bg-rose-600 text-white rounded-lg opacity-0 group-hover:opacity-100 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <input type="text"
                                        class="mt-2 w-full text-xs px-3 py-2 border border-gray-300 rounded-lg"
                                        placeholder="Alt text" x-model="img.alt">
                                    <input type="hidden" :name="'images_alt['+i+']'" x-model="img.alt">
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Actions --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.management.packages.index') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-slate-700 text-white font-medium rounded-lg hover:bg-slate-800 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Create Package
                    </button>
                </div>
            </div>
        </form>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        @keyframes bounce-subtle {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-4px);
            }
        }

        .animate-bounce-subtle {
            animation: bounce-subtle 0.6s ease-in-out;
        }

        .inclusion-card {
            transform: translateZ(0);
        }

        /* GPU acceleration */
    </style>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('packagePricing', () => ({
                selected: [],
                names: {}, categories: {}, prices: {}, notes: {}, packageTypes: {}, images: {},
                selectedType: '{{ old('type', '') }}',
                searchQuery: '', categoryFilter: '',
                coordinationPrice: 25000, eventStylingPrice: 55000, packagePrice: 0,
                autoCalc: true, availableCount: 0,
                
                // Preview modal state
                previewOpen: false,
                previewImage: '',
                previewName: '',
                previewPrice: 0,
                previewCategory: '',
                previewId: null,

                fmt(n) { return Number(n || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }); },
                has(id) { return this.selected.findIndex(x => Number(x.id) === Number(id)) !== -1; },
                
                isInclusionAvailable(id) {
                    if (!this.selectedType) return false;
                    const t = this.packageTypes[id];
                    return !t || t === this.selectedType;
                },
                
                isInclusionVisible(id, name, category) {
                    if (!this.isInclusionAvailable(id)) return false;
                    if (this.searchQuery && !name.toLowerCase().includes(this.searchQuery.toLowerCase())) return false;
                    if (this.categoryFilter && category !== this.categoryFilter) return false;
                    return true;
                },

                onTypeChange() {
                    this.selected = this.selected.filter(row => this.isInclusionAvailable(row.id));
                    this.updateAvailableCount();
                    this.$nextTick(() => { if (this.autoCalc) this.packagePrice = Number(this.grandTotal().toFixed(2)); });
                },

                updateAvailableCount() {
                    let c = 0;
                    for (const id in this.names) { if (this.isInclusionAvailable(id)) c++; }
                    this.availableCount = c;
                },

                toggle(id) {
                    id = Number(id);
                    const i = this.selected.findIndex(x => Number(x.id) === id);
                    if (i > -1) this.selected.splice(i, 1);
                    else this.selected.push({ id });
                    this.$nextTick(() => { if (this.autoCalc) this.packagePrice = Number(this.grandTotal().toFixed(2)); });
                },

                remove(id) {
                    const i = this.selected.findIndex(x => Number(x.id) === Number(id));
                    if (i > -1) this.selected.splice(i, 1);
                    this.$nextTick(() => { if (this.autoCalc) this.packagePrice = Number(this.grandTotal().toFixed(2)); });
                },

                // Preview modal methods
                openPreview(image, name, price, category) {
                    // Find the ID from the name
                    for (const id in this.names) {
                        if (this.names[id] === name.replace(/\\'/g, "'")) {
                            this.previewId = Number(id);
                            break;
                        }
                    }
                    this.previewImage = image;
                    this.previewName = name.replace(/\\'/g, "'");
                    this.previewPrice = price;
                    this.previewCategory = category || '';
                    this.previewOpen = true;
                },
                
                toggleFromPreview() {
                    if (this.previewId) {
                        this.toggle(this.previewId);
                    }
                },

                subtotal() { return this.selected.reduce((s, r) => s + Number(this.prices[r.id] ?? 0), 0); },
                grandTotal() { return (this.subtotal() || 0) + (this.coordinationPrice || 0) + (this.eventStylingPrice || 0); },

                init(sel) {
                    const el = document.querySelector(sel || '#pkg-config-create');
                    const cfg = el ? JSON.parse(el.textContent || '{}') : {};
                    this.names = cfg.names || {};
                    this.categories = cfg.categories || {};
                    this.prices = cfg.prices || {};
                    this.notes = cfg.notes || {};
                    this.packageTypes = cfg.packageTypes || {};
                    this.images = cfg.images || {};

                    const raw = Array.isArray(cfg.initialInclusions) ? cfg.initialInclusions : Object.values(cfg.initialInclusions || []);
                    this.selected = raw.map(v => typeof v === 'object' && v?.id ? { id: Number(v.id) } : { id: Number(v) }).filter(r => Number.isFinite(r.id));

                    const d = cfg.defaults || {};
                    this.coordinationPrice = Number(d.coordinationPrice ?? 25000);
                    this.eventStylingPrice = Number(d.eventStylingPrice ?? 55000);
                    this.packagePrice = Number(d.packagePrice ?? 0);
                    this.autoCalc = !!d.autoCalc;
                    this.updateAvailableCount();
                    this.$nextTick(() => { if (this.autoCalc) this.packagePrice = Number(this.grandTotal().toFixed(2)); });
                },
            }));

            Alpine.data('galleryUploader', () => ({
                newItems: [],
                handleFiles(e) {
                    Array.from(e.target.files || []).forEach((f, i) => {
                        this.newItems.push({ key: Date.now() + '-' + i, file: f, url: URL.createObjectURL(f), alt: '' });
                    });
                },
                removeNew(i) {
                    if (this.newItems[i]?.url) URL.revokeObjectURL(this.newItems[i].url);
                    this.newItems.splice(i, 1);
                }
            }));
        });
    </script>
</x-admin.layouts.management>