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

    <div class="bg-white rounded-lg shadow-sm p-6 space-y-4 max-w-3xl">
        <h3 class="text-lg font-semibold">New Package</h3>

        <form method="POST" action="{{ route('admin.management.packages.store') }}" class="space-y-6"
            enctype="multipart/form-data">
            @csrf

            {{-- Basic info --}}
            <div>
                <x-input-label for="name" value="Package Name" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name') }}"
                    required />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <label for="type" class="block text-sm font-medium">Package Type</label>
                <select name="type" id="type" required class="mt-1 block w-full rounded-md border-gray-300">
                    <option value="">Select Type</option>
                    @foreach(\App\Enums\PackageType::cases() as $type)
                    <option value="{{ $type->value }}" {{ old('type', $package->type ?? '') == $type->value ? 'selected'
                        : '' }}>
                        {{ $type->label() }}
                    </option>
                    @endforeach
                </select>
                @error('type')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Pricing + Inclusions (Alpine scope) --}}
            <div x-data="packagePricing()" x-init="init('#pkg-config-create')" x-cloak class="space-y-6">
                {{-- Inclusions --}}
                <div class="bg-white rounded-lg shadow-sm p-4 space-y-4">
                    <h4 class="font-semibold text-base">Inclusions</h4>

                    {{-- Picker --}}
                    <div class="grid grid-cols-1 gap-2">
                        @foreach($inclusions as $inc)
                        <label
                            class="flex items-center justify-between gap-3 border rounded px-3 py-2 hover:bg-gray-50">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="rounded border-gray-300" :checked="has({{ $inc->id }})"
                                    @change="toggle({{ $inc->id }})">
                                <div class="min-w-0">
                                    <div class="font-medium truncate">{{ $inc->name }}</div>
                                    @if($inc->category)
                                    <div class="text-[11px] text-gray-500">• {{ $inc->category }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="text-sm text-gray-700 shrink-0">
                                ₱{{ number_format($inc->price, 2) }}
                            </div>
                        </label>
                        @endforeach
                    </div>

                    {{-- Selected list with price and read-only notes --}}
                    <template x-if="selected.length">
                        <div class="space-y-2">
                            <template x-for="(row, idx) in selected" :key="row.id">
                                <div class="border rounded-lg p-3 bg-emerald-800/5">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <div class="text-sm font-medium truncate"
                                                x-text="names[row.id] ?? 'Inclusion'"></div>
                                            <div class="text-[11px] text-gray-500" x-show="categories[row.id]"
                                                x-text="`• ${categories[row.id]}`"></div>
                                        </div>
                                        <div class="flex items-center gap-3 shrink-0">
                                            <span class="px-2 py-1 rounded text-xs bg-emerald-100 text-emerald-800">
                                                ₱<span x-text="fmt(prices[row.id] ?? 0)"></span>
                                            </span>
                                            <button type="button" class="text-xs text-gray-500 hover:text-red-600"
                                                @click="remove(row.id)">Remove</button>
                                        </div>
                                    </div>

                                    <template x-if="notes[row.id]">
                                        <div
                                            class="mt-2 text-xs text-gray-700 whitespace-pre-line border rounded px-3 py-2 bg-white/60">
                                            <span x-text="notes[row.id]"></span>
                                        </div>
                                    </template>

                                    {{-- Hidden field to submit only the inclusion ID --}}
                                    <input type="hidden" :name="`inclusions[${idx}][id]`" :value="row.id">
                                </div>
                            </template>

                            {{-- Subtotal --}}
                            <div class="flex items-center justify-between mt-3 border-t pt-3">
                                <div class="text-sm text-gray-600">Inclusions Subtotal</div>
                                <div class="text-base font-semibold">₱<span x-text="fmt(subtotal())"></span></div>
                            </div>
                        </div>
                    </template>

                    <x-input-error :messages="$errors->get('inclusions')" />
                    <x-input-error :messages="$errors->get('inclusions.*.id')" />
                </div>

                {{-- Coordination / Event Styling --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2 relative">
                        <x-input-label>Coordination Price</x-input-label>
                        <div class="absolute left-2 top-1/2 transform">
                            <span class="text-lg text-emerald-600">₱</span>
                        </div>
                        <x-text-input type="number" step="0.01" min="0" name="coordination_price" class="w-full pl-7"
                            x-model.number="coordinationPrice" />
                        <x-input-error :messages="$errors->get('coordination_price')" />
                    </div>

                    <div class="md:col-span-2 relative">
                        <x-input-label>Event Styling Price</x-input-label>
                        <div class="absolute left-2 top-1/2 transform">
                            <span class="text-lg text-emerald-600">₱</span>
                        </div>
                        <x-text-input type="number" step="0.01" min="0" name="event_styling_price" class="w-full pl-7"
                            x-model.number="eventStylingPrice" />
                        <x-input-error :messages="$errors->get('event_styling_price')" />
                    </div>

                </div>

                <div>
                    <x-input-label>Coordination (notes/description)</x-input-label>
                    <textarea name="coordination" rows="3" class="w-full border rounded px-3 py-2 placeholder:text-sm"
                        placeholder="e.g.,
Full coordination on the day
timeline and supplier follow-ups">{{ old('coordination') }}</textarea>
                    <x-input-error :messages="$errors->get('coordination')" />
                </div>

                <div>
                    <x-input-label>Event Styling (one per line)</x-input-label>
                    <textarea name="event_styling_text" rows="4"
                        class="w-full border rounded px-3 py-2 placeholder:text-sm" placeholder="e.g.,
Stage setup
2-3 candles
Aisle decor">{{ old('event_styling_text') }}</textarea>
                    <x-input-error :messages="$errors->get('event_styling_text')" />
                </div>

                <div class="flex items-center justify-between text-sm text-gray-600">
                    <span>Estimated Total (Inclusions + Coordination + Styling)</span>
                    <span class="text-base font-semibold">₱<span x-text="fmt(grandTotal())"></span></span>
                </div>

                {{-- Package Price --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="md:col-span-2 relative">
                            <x-input-label for="price" value="Package Price" />
                            <div class="absolute left-2 top-1/2 transform">
                                <span class="text-lg text-emerald-600">₱</span>
                            </div>
                            <x-text-input id="price" name="price" type="number" step="0.01"
                                class="mt-1 block w-full pl-7" x-model.number="packagePrice"
                                x-bind:readonly="autoCalc" />
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>
                        <div class="mt-2 flex items-center gap-2 text-sm">
                            <input type="checkbox" class="rounded border-gray-300" x-model="autoCalc" id="autoCalc">
                            <label for="autoCalc">Auto-calc from inclusions + coordination + styling</label>
                        </div>
                        <input type="hidden" name="autoCalc" x-model="autoCalc">
                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-2 md:mt-6">
                        <input id="is_active" name="is_active" type="checkbox" value="1" class="rounded border-gray-300"
                            @checked(old('is_active', true)) />
                        <x-input-label for="is_active" value="Active" />
                    </div>
                </div>

                <div x-effect="if (autoCalc) { packagePrice = Number(grandTotal().toFixed(2)); }"></div>
            </div>

            {{-- Gallery: multi-file upload with preview (min 4) --}}
            <div x-data="galleryUploader()" class="space-y-3">
                <x-input-label value="Gallery Images (minimum 4)" />
                <input type="file" name="images[]" accept="image/*" multiple class="block"
                    @change="handleFiles($event)">
                <x-input-error :messages="$errors->get('images')" />
                <x-input-error :messages="$errors->get('images.*')" />

                <template x-if="newItems.length">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <template x-for="(img, i) in newItems" :key="img.key">
                            <div class="relative group">
                                <div class="relative w-full aspect-[4/3] overflow-hidden rounded-lg border">
                                    <img :src="img.url" alt="" class="absolute inset-0 w-full h-full object-cover">
                                </div>
                                <input type="text" class="mt-1 w-full text-xs border rounded px-2 py-1"
                                    placeholder="Alt/Caption" x-model="img.alt">
                                <button type="button"
                                    class="absolute top-1 right-1 bg-black/60 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100"
                                    @click="removeNew(i)">Remove</button>
                            </div>
                        </template>
                    </div>
                </template>

                {{-- Hidden alts to match picked order --}}
                <template x-for="(img, i) in newItems" :key="'alt-'+img.key">
                    <input type="hidden" :name="'images_alt['+i+']'" x-model="img.alt">
                </template>

                <p class="text-xs text-gray-500">Tip: Select multiple files at once. Minimum of 4 images is required.
                </p>
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.management.packages.index') }}" class="px-3 py-2 border rounded">Cancel</a>
                <button class="px-4 py-2 bg-gray-800 text-white rounded">Save Package</button>
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