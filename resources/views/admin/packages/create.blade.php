<x-admin.layouts.management>
    @php
    $pkgConfig = [
    'initialInclusions' => old('inclusions', []),
    'names' => $inclusions->pluck('name','id'),
    'categories' => $inclusions->pluck('category','id'),
    'prices' => $inclusions->pluck('price','id'),
    'notes' => $inclusions->pluck('notes','id'),
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
                    {{-- Package Name --}}
                    <div>
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            Package Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-3 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400"
                            placeholder="e.g., Premium Wedding Package">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    {{-- Package Type --}}
                    <div>
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            Package Type <span class="text-rose-500">*</span>
                        </label>
                        <select name="type" id="type" required
                            class="w-full px-4 py-3 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400">
                            <option value="">Select package type</option>
                            @foreach(\App\Enums\PackageType::cases() as $type)
                            <option value="{{ $type->value }}" @selected(old('type')==$type->value)>
                                {{ $type->label() }}
                            </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>

                    {{-- Active Status --}}
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

                {{-- Inclusions Selection --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h4 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                                Package Inclusions
                            </h4>
                            <span class="px-3 py-1 bg-violet-100 text-violet-700 text-xs font-semibold rounded-full"
                                x-text="`${selected.length} selected`"></span>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        {{-- Available Inclusions --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($inclusions as $inc)
                            <label
                                class="flex items-center justify-between gap-3 border border-gray-200 rounded-lg px-4 py-3 hover:bg-slate-50 cursor-pointer transition"
                                :class="has({{ $inc->id }}) ? 'bg-violet-50 border-violet-300' : ''">
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    <input type="checkbox"
                                        class="w-5 h-5 text-violet-600 rounded border-gray-300 focus:ring-2 focus:ring-violet-200"
                                        :checked="has({{ $inc->id }})" @change="toggle({{ $inc->id }})">
                                    <div class="min-w-0">
                                        <div class="font-medium text-gray-900 truncate">{{ $inc->name }}</div>
                                        @if($inc->category)
                                        <div class="text-xs text-gray-500">{{ $inc->category }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-sm font-semibold text-gray-900 shrink-0">
                                    ₱{{ number_format($inc->price, 2) }}
                                </div>
                            </label>
                            @endforeach
                        </div>

                        {{-- Selected Inclusions Summary --}}
                        <template x-if="selected.length">
                            <div class="space-y-3 pt-4 border-t border-gray-200">
                                <h5 class="font-semibold text-gray-900 text-sm">Selected Inclusions:</h5>
                                <template x-for="(row, idx) in selected" :key="row.id">
                                    <div class="border border-violet-200 rounded-lg p-4 bg-violet-50/50">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="font-medium text-gray-900"
                                                        x-text="names[row.id] ?? 'Inclusion'"></span>
                                                    <template x-if="categories[row.id]">
                                                        <span
                                                            class="px-2 py-0.5 bg-violet-100 text-violet-700 text-xs font-medium rounded"
                                                            x-text="categories[row.id]"></span>
                                                    </template>
                                                </div>
                                                <template x-if="notes[row.id]">
                                                    <div class="text-xs text-gray-600 mt-2 bg-white rounded p-2 border border-violet-100"
                                                        x-text="notes[row.id]"></div>
                                                </template>
                                            </div>
                                            <div class="flex items-center gap-3 shrink-0">
                                                <span
                                                    class="px-3 py-1 rounded-lg text-sm font-bold bg-violet-100 text-violet-800">
                                                    ₱<span x-text="fmt(prices[row.id] ?? 0)"></span>
                                                </span>
                                                <button type="button"
                                                    class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-rose-700 hover:text-rose-900"
                                                    @click="remove(row.id)">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <input type="hidden" :name="`inclusions[${idx}][id]`" :value="row.id">
                                    </div>
                                </template>

                                {{-- Inclusions Subtotal --}}
                                <div
                                    class="flex items-center justify-between pt-3 border-t border-violet-200 bg-violet-50 rounded-lg p-4">
                                    <span class="text-sm font-semibold text-gray-900">Inclusions Subtotal</span>
                                    <span class="text-xl font-bold text-violet-900">₱<span
                                            x-text="fmt(subtotal())"></span></span>
                                </div>
                            </div>
                        </template>

                        <x-input-error :messages="$errors->get('inclusions')" class="mt-2" />
                    </div>
                </div>

                {{-- Coordination & Styling --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                        <h4 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Coordination & Event Styling
                        </h4>
                    </div>

                    <div class="p-6 space-y-6">
                        {{-- Coordination Price --}}
                        <div>
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Coordination Price <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span
                                    class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-medium">₱</span>
                                <input type="number" step="0.01" min="0" name="coordination_price"
                                    class="w-full pl-8 pr-4 py-3 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400"
                                    x-model.number="coordinationPrice" placeholder="25000.00">
                            </div>
                            <x-input-error :messages="$errors->get('coordination_price')" class="mt-2" />
                        </div>

                        {{-- Coordination Description --}}
                        <div>
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h7" />
                                </svg>
                                Coordination Details
                            </label>
                            <textarea name="coordination" rows="3"
                                class="w-full px-4 py-3 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400"
                                placeholder="Describe coordination services, e.g., Full coordination on the day, timeline management, supplier follow-ups">{{ old('coordination') }}</textarea>
                            <x-input-error :messages="$errors->get('coordination')" class="mt-2" />
                        </div>

                        {{-- Event Styling Price --}}
                        <div>
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                </svg>
                                Event Styling Price <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span
                                    class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-medium">₱</span>
                                <input type="number" step="0.01" min="0" name="event_styling_price"
                                    class="w-full pl-8 pr-4 py-3 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400"
                                    x-model.number="eventStylingPrice" placeholder="55000.00">
                            </div>
                            <x-input-error :messages="$errors->get('event_styling_price')" class="mt-2" />
                        </div>

                        {{-- Event Styling Details --}}
                        <div>
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h7" />
                                </svg>
                                Event Styling Items
                            </label>
                            <textarea name="event_styling_text" rows="4"
                                class="w-full px-4 py-3 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400"
                                placeholder="List styling items (one per line), e.g.:&#10;Stage setup&#10;Floral arrangements&#10;Aisle decor&#10;Table centerpieces">{{ old('event_styling_text') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Enter one item per line</p>
                            <x-input-error :messages="$errors->get('event_styling_text')" class="mt-2" />
                        </div>

                        {{-- Estimated Total --}}
                        <div class="bg-sky-50 border border-sky-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-sm font-semibold text-sky-900">Estimated Total</span>
                                </div>
                                <span class="text-2xl font-bold text-sky-900">₱<span
                                        x-text="fmt(grandTotal())"></span></span>
                            </div>
                            <p class="text-xs text-sky-700 mt-2">Inclusions + Coordination + Styling</p>
                        </div>
                    </div>
                </div>

                {{-- Package Price --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                        <h4 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            Final Package Price
                        </h4>
                    </div>

                    <div class="p-6 space-y-6">
                        <div>
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Package Price <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span
                                    class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-medium">₱</span>
                                <input type="number" id="price" name="price" step="0.01" min="0"
                                    class="w-full pl-8 pr-4 py-3 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400"
                                    x-model.number="packagePrice" x-bind:readonly="autoCalc"
                                    :class="autoCalc ? 'bg-slate-50' : ''" placeholder="0.00">
                            </div>
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-3 p-4 bg-emerald-50 rounded-lg border border-emerald-200">
                            <input type="checkbox"
                                class="w-5 h-5 text-emerald-600 rounded border-gray-300 focus:ring-2 focus:ring-emerald-200"
                                x-model="autoCalc" id="autoCalc">
                            <div class="flex-1">
                                <label for="autoCalc" class="text-sm font-medium text-emerald-900">Auto-calculate
                                    Package Price</label>
                                <p class="text-xs text-emerald-700 mt-0.5">Automatically sum inclusions + coordination +
                                    styling</p>
                            </div>
                        </div>
                        <input type="hidden" name="autoCalc" x-model="autoCalc">

                        <div x-effect="if (autoCalc) { packagePrice = Number(grandTotal().toFixed(2)); }"></div>
                    </div>
                </div>
            </div>

            {{-- Banner Upload --}}
            <div x-data="bannerUploader()" class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                    <h4 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Package Banner
                        <span class="ml-2 px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-semibold rounded">Portrait
                            - For Homepage Display</span>
                    </h4>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <input type="file" name="banner" accept="image/*" @change="previewBanner($event)"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition">
                        <p class="mt-2 text-xs text-gray-500">
                            Recommended size: <strong>600x900px (Portrait)</strong>. Max file size: 5MB
                        </p>
                        <x-input-error :messages="$errors->get('banner')" class="mt-2" />
                    </div>

                    {{-- Banner Preview - Portrait Style --}}
                    <div class="flex justify-center">
                        <div x-show="bannerPreview" x-transition
                            class="relative rounded-lg overflow-hidden border-2 border-gray-200 w-80">
                            <img :src="bannerPreview" alt="Banner preview" class="w-full h-[480px] object-cover">
                            <button type="button" @click="removeBanner()"
                                class="absolute top-2 right-2 p-2 bg-rose-500 text-white rounded-lg hover:bg-rose-600 transition shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                            <div
                                class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-3">
                                <p class="text-xs text-white font-medium">Preview</p>
                                <p class="text-[10px] text-white/80">Portrait orientation</p>
                            </div>
                        </div>
                    </div>

                    <div x-show="!bannerPreview" class="flex justify-center">
                        <div
                            class="flex items-center justify-center border-2 border-dashed border-gray-300 rounded-lg w-80 h-[480px] bg-gray-50">
                            <div class="text-center px-4">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-sm font-medium text-gray-600 mb-1">No banner uploaded yet</p>
                                <p class="text-xs text-gray-500">Portrait format (600x900px)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Gallery Upload --}}
            <div x-data="galleryUploader()" class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                    <h4 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Package Gallery <span class="text-rose-500">*</span>
                        <span class="ml-2 px-2 py-0.5 bg-amber-100 text-amber-700 text-xs font-semibold rounded">Minimum
                            4 images</span>
                    </h4>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <input type="file" name="images[]" accept="image/*" multiple
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition"
                            @change="handleFiles($event)">
                        <p class="mt-2 text-xs text-gray-500">Select multiple images at once. JPG, PNG or GIF. Max 2MB
                            each.</p>
                    </div>

                    <x-input-error :messages="$errors->get('images')" />
                    <x-input-error :messages="$errors->get('images.*')" />

                    <template x-if="newItems.length">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Selected Images (<span
                                        x-text="newItems.length"></span>)</span>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <template x-for="(img, i) in newItems" :key="img.key">
                                    <div class="relative group">
                                        <div
                                            class="relative w-full aspect-[4/3] overflow-hidden rounded-lg border-2 border-gray-200">
                                            <img :src="img.url" alt=""
                                                class="absolute inset-0 w-full h-full object-cover">
                                            <button type="button"
                                                class="absolute top-2 right-2 p-1.5 bg-rose-600 text-white rounded-lg opacity-0 group-hover:opacity-100 transition"
                                                @click="removeNew(i)">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                        <input type="text"
                                            class="mt-2 w-full text-xs px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-200 focus:border-slate-400"
                                            placeholder="Alt text / Caption (optional)" x-model="img.alt">
                                        <input type="hidden" :name="'images_alt['+i+']'" x-model="img.alt">
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Form Actions --}}
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
            display: none !important
        }
    </style>

    <script>
        document.addEventListener('alpine:init', () => {
        Alpine.data('packagePricing', () => ({
        selected: [],
        names: {}, categories: {}, prices: {}, notes: {},
        coordinationPrice: 25000,
        eventStylingPrice: 55000,
        packagePrice: 0,
        autoCalc: true,

        fmt(n){ return Number(n || 0).toLocaleString(undefined,{ minimumFractionDigits:2, maximumFractionDigits:2 }); },
        has(id){ id = Number(id); return this.selected.findIndex(x => Number(x.id) === id) !== -1; },
        toggle(id){
          id = Number(id);
          const i = this.selected.findIndex(x => Number(x.id) === id);
          if (i > -1) this.selected.splice(i, 1);
          else this.selected.push({ id });
          this.$nextTick(() => { if (this.autoCalc) this.packagePrice = Number(this.grandTotal().toFixed(2)); });
        },
        remove(id){
          id = Number(id);
          const i = this.selected.findIndex(x => Number(x.id) === id);
          if (i > -1) this.selected.splice(i, 1);
          this.$nextTick(() => { if (this.autoCalc) this.packagePrice = Number(this.grandTotal().toFixed(2)); });
        },
        subtotal(){ return this.selected.reduce((sum, row) => sum + Number(this.prices[row.id] ?? 0), 0); },
        grandTotal(){ return (this.subtotal() || 0) + (this.coordinationPrice || 0) + (this.eventStylingPrice || 0); },

        init(jsonSelector){
          const el = document.querySelector(jsonSelector || '#pkg-config-create');
          const cfg = el ? JSON.parse(el.textContent || '{}') : {};

          this.names      = cfg.names      || {};
          this.categories = cfg.categories || {};
          this.prices     = cfg.prices     || {};
          this.notes      = cfg.notes      || {};

          const raw = Array.isArray(cfg.initialInclusions) ? cfg.initialInclusions : Object.values(cfg.initialInclusions || []);
          this.selected = raw.map(v => {
            if (typeof v === 'object' && v !== null && 'id' in v) return { id: Number(v.id) };
            return { id: Number(v) };
          }).filter(r => Number.isFinite(r.id));

          const d = cfg.defaults || {};
          this.coordinationPrice = Number(d.coordinationPrice ?? 25000);
          this.eventStylingPrice = Number(d.eventStylingPrice ?? 55000);
          this.packagePrice      = Number(d.packagePrice ?? 0);
          this.autoCalc          = !!d.autoCalc;

          this.$nextTick(() => {
            if (this.autoCalc) this.packagePrice = Number(this.grandTotal().toFixed(2));
          });
        },
      }));

      Alpine.data('galleryUploader', () => ({
        newItems: [],
        handleFiles(e){
          const files = Array.from(e.target.files || []);
          files.forEach((f, idx) => {
            const url = URL.createObjectURL(f);
            this.newItems.push({ key: Date.now()+'-'+idx, file: f, url, alt: '' });
          });
        },
        removeNew(i){
          const item = this.newItems[i];
          if (item && item.url) URL.revokeObjectURL(item.url);
          this.newItems.splice(i, 1);
        }
      }));
    });

    
    </script>
</x-admin.layouts.management>