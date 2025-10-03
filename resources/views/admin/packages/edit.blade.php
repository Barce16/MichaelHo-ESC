<x-admin.layouts.management>
    @php
    $config = [
    'initialInclusions' => old(
    'inclusions',
    $package->inclusions->map(fn ($i) => ['id' => $i->id])->values()
    ),
    'names' => $inclusions->pluck('name','id'),
    'categories' => $inclusions->pluck('category','id'),
    'prices' => $inclusions->pluck('price','id'),
    'notes' => $inclusions->pluck('notes','id'),
    'defaults' => [
    'coordinationPrice' => old('coordination_price', $package->coordination_price ?? 25000),
    'eventStylingPrice' => old('event_styling_price', $package->event_styling_price ?? 55000),
    'packagePrice' => old('price', $package->price ?? 0),
    'autoCalc' => (bool) old('autoCalc', true),
    'coordination' => old('coordination', $package->coordination ?? ''),
    'eventStylingText' => old('event_styling_text', is_array($package->event_styling) ? implode("\n",
    $package->event_styling) : ''),
    'isActive' => (bool) old('is_active', $package->is_active),
    ],
    ];

    $existingImages = $package->images->map(fn($img) => [
    'id' => (int) $img->id,
    'url' => $img->url,
    'alt' => (string) ($img->alt ?? ''),
    ])->values();
    @endphp

    <script type="application/json" id="pkg-config-edit">
        {!! json_encode($config, JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script type="application/json" id="pkg-images-json">
        {!! $existingImages->toJson(JSON_UNESCAPED_UNICODE) !!}
    </script>

    <div class="bg-white rounded-lg shadow-sm p-6 space-y-6 max-w-3xl" x-data="packagePricing()"
        x-init="init('#pkg-config-edit')" x-cloak>
        <h3 class="text-lg font-semibold">Edit Package</h3>

        <form method="POST" action="{{ route('admin.management.packages.update', $package) }}" class="space-y-6"
            enctype="multipart/form-data" x-data="{ formError: '', galleryValid: true }" @submit.prevent="
            formError = '';
            const comp = $refs.gallery && $refs.gallery.__x ? $refs.gallery.__x.$data : null;
            const ok = comp ? comp.ensureMin() : true;
            galleryValid = ok;
            if (ok) { $el.submit(); }
            else {
            formError = comp?.error || 'Please keep at least 4 images.';
            $nextTick(() => document.getElementById('gallery-error')?.scrollIntoView({behavior:'smooth'}));
            }
        ">
            @csrf
            @method('PUT')

            {{-- Basic info --}}
            <div>
                <x-input-label for="name" value="Package Name" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                    value="{{ old('name', $package->name) }}" required />
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

            {{-- Inclusions --}}
            <div class="space-y-4">
                <h4 class="font-semibold text-base">Inclusions</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    @foreach($inclusions as $inc)
                    <label class="flex items-center justify-between gap-3 border rounded px-3 py-2 hover:bg-gray-50">
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
                        <div class="text-sm text-gray-700 shrink-0">₱{{ number_format($inc->price, 2) }}</div>
                    </label>
                    @endforeach
                </div>

                <template x-if="selected.length">
                    <div class="space-y-2">
                        <template x-for="(row, idx) in selected" :key="row.id">
                            <div class="border rounded-lg p-3 bg-emerald-800/5">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <div class="text-sm font-medium truncate" x-text="names[row.id] ?? 'Inclusion'">
                                        </div>
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

                                {{-- read-only notes from Inclusion model --}}
                                <template x-if="notes[row.id]">
                                    <div
                                        class="mt-2 text-xs text-gray-700 whitespace-pre-line border rounded px-3 py-2 bg-white/60">
                                        <span x-text="notes[row.id]"></span>
                                    </div>
                                </template>

                                {{-- submit inclusion id only --}}
                                <input type="hidden" :name="`inclusions[${idx}][id]`" :value="row.id">
                            </div>
                        </template>

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
            <div>
                <x-input-label>Coordination</x-input-label>
                <textarea name="coordination" rows="3" class="w-full border rounded px-3 py-2" x-model="coordination"
                    placeholder="e.g., Full coordination on the day; timeline and supplier follow-ups"></textarea>
                <x-input-error :messages="$errors->get('coordination')" />
            </div>

            <div>
                <x-input-label>Event Styling (one per line)</x-input-label>
                <textarea name="event_styling_text" rows="4" class="w-full border rounded px-3 py-2"
                    x-model="eventStylingText" placeholder="Stage setup
2-3 candles
Aisle decor"></textarea>
                <x-input-error :messages="$errors->get('event_styling_text')" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label>Coordination Price</x-input-label>
                    <x-text-input type="number" step="0.01" min="0" name="coordination_price" class="w-full"
                        x-model.number="coordinationPrice" />
                    <x-input-error :messages="$errors->get('coordination_price')" />
                </div>
                <div>
                    <x-input-label>Event Styling Price</x-input-label>
                    <x-text-input type="number" step="0.01" min="0" name="event_styling_price" class="w-full"
                        x-model.number="eventStylingPrice" />
                    <x-input-error :messages="$errors->get('event_styling_price')" />
                </div>
            </div>

            {{-- Package Price --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="price" value="Package Price" />
                    <x-text-input id="price" name="price" type="number" step="0.01" class="mt-1 block w-full"
                        x-model.number="packagePrice" x-bind:readonly="autoCalc" required />
                    <div class="mt-2 flex items-center gap-2 text-sm">
                        <input type="checkbox" class="rounded border-gray-300" id="autoCalc" x-model="autoCalc">
                        <label for="autoCalc">Auto-calc from inclusions + coordination + styling</label>
                    </div>
                    <input type="hidden" name="autoCalc" :value="autoCalc ? 1 : 0">
                </div>

                <div class="flex items-center gap-2 mt-6">
                    <input id="is_active" name="is_active" type="checkbox" value="1" class="rounded border-gray-300"
                        :checked="isActive" @change="isActive = $event.target.checked" />
                    <x-input-label for="is_active" value="Active" />
                </div>
            </div>

            {{-- keep price synced when selections change --}}
            <div style="display:none"
                x-init="$watch(() => selected.map(r => r.id).join(','), () => { if (autoCalc) packagePrice = Number(grandTotal().toFixed(2)); })">
            </div>
            <div x-effect="if (autoCalc) { packagePrice = Number(grandTotal().toFixed(2)); }"></div>

            {{-- GALLERY with instant remove and min-4 client validation --}}
            <div x-data="galleryManager()" x-init="init('#pkg-images-json')" x-ref="gallery"
                x-effect="$root.closest('form')?.__x?.$data && ($root.closest('form').__x.$data.galleryValid = isValid)">
                <h4 class="font-semibold text-base">Gallery</h4>

                {{-- Existing images --}}
                <template x-if="existing.length">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <template x-for="(img, i) in existing" :key="img.id">
                            <div class="relative border rounded-lg p-2" x-show="!img.removed">
                                <div class="relative w-full aspect-[4/3] overflow-hidden rounded-md">
                                    <img :src="img.url" :alt="img.alt || 'Image'"
                                        class="absolute inset-0 w-full h-full object-cover">
                                </div>

                                <input type="text" class="mt-2 w-full text-xs border rounded px-2 py-1"
                                    placeholder="Alt/Caption" x-model="img.alt" :name="`existing[${img.id}]`">

                                <button type="button"
                                    class="absolute top-2 right-2 bg-red-600 text-white text-xs px-2 py-1 rounded"
                                    @click="removeExisting(i)">
                                    Remove
                                </button>
                            </div>
                        </template>
                    </div>
                </template>
                <template x-if="!existing.length">
                    <div class="text-sm text-gray-500">No images yet. Add at least 4 below.</div>
                </template>

                {{-- Hidden inputs for removed existing image IDs --}}
                <template x-for="rid in removedIds" :key="`rid-${rid}`">
                    <input type="hidden" name="remove_image_ids[]" :value="rid">
                </template>

                {{-- New uploads --}}
                <div class="space-y-2">
                    <x-input-label value="Add Images (you can select multiple; min 4 total after save)" />
                    <input type="file" name="images[]" accept="image/*" multiple class="block"
                        @change="handleFiles($event)">

                    <x-input-error :messages="$errors->get('images')" />
                    <x-input-error :messages="$errors->get('images.*')" />

                    <template x-if="newItems.length">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <template x-for="(img, i) in newItems" :key="img.key">
                                <div class="relative group border rounded-lg p-2">
                                    <div class="relative w-full aspect-[4/3] overflow-hidden rounded-md">
                                        <img :src="img.url" alt="" class="absolute inset-0 w-full h-full object-cover">
                                    </div>
                                    <input type="text" class="mt-2 w-full text-xs border rounded px-2 py-1"
                                        placeholder="Alt/Caption" x-model="img.alt">
                                    <button type="button"
                                        class="absolute top-2 right-2 bg-black/60 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100"
                                        @click="removeNew(i)">
                                        Remove
                                    </button>
                                </div>
                            </template>
                        </div>
                    </template>

                    {{-- Hidden alts for new uploads (aligned with files[]) --}}
                    <template x-for="(img, i) in newItems" :key="`alt-${img.key}`">
                        <input type="hidden" :name="`images_alt[${i}]`" x-model="img.alt">
                    </template>
                </div>

            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.management.packages.index') }}" class="px-3 py-2 border rounded">Cancel</a>
                <button type="submit" class="px-4 py-2 rounded text-white"
                    :class="galleryValid ? 'bg-gray-800 hover:bg-gray-700' : 'bg-gray-400 cursor-not-allowed'"
                    :disabled="!galleryValid">
                    Update Package
                </button>
            </div>
        </form>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <script>
        document.addEventListener('alpine:init', () => {
        // Pricing and inclusions
        Alpine.data('packagePricing', () => ({
            names: {}, categories: {}, prices: {}, notes: {},
            selected: [],
            coordinationPrice: 0,
            eventStylingPrice: 0,
            packagePrice: 0,
            autoCalc: true,
            coordination: '',
            eventStylingText: '',
            isActive: true,

            fmt(n){
                return Number(n || 0).toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            },

            has(id){
                id = Number(id);
                return this.selected.findIndex(x => Number(x.id) === id) !== -1;
            },
            toggle(id){
                id = Number(id);
                const i = this.selected.findIndex(x => Number(x.id) === id);
                if (i > -1) this.selected.splice(i, 1);
                else this.selected.push({ id });
                this.$nextTick(() => {
                    if (this.autoCalc) this.packagePrice = Number(this.grandTotal().toFixed(2));
                });
            },
            remove(id){
                id = Number(id);
                const i = this.selected.findIndex(x => Number(x.id) === id);
                if (i > -1) this.selected.splice(i, 1);
                this.$nextTick(() => {
                    if (this.autoCalc) this.packagePrice = Number(this.grandTotal().toFixed(2));
                });
            },

            subtotal(){
                return this.selected.reduce((sum, row) => {
                    const p = Number(this.prices[row.id] ?? 0);
                    return sum + (isNaN(p) ? 0 : p);
                }, 0);
            },
            grandTotal(){
                return (this.subtotal() || 0) + (this.coordinationPrice || 0) + (this.eventStylingPrice || 0);
            },

            init(jsonSelector){
                const cfgEl = document.querySelector(jsonSelector || '#pkg-config-edit');
                const cfg = cfgEl ? JSON.parse(cfgEl.textContent || '{}') : {};

                this.names      = cfg.names      || {};
                this.categories = cfg.categories || {};
                this.prices     = cfg.prices     || {};
                this.notes      = cfg.notes      || {};

                const raw = Array.isArray(cfg.initialInclusions)
                    ? cfg.initialInclusions
                    : Object.values(cfg.initialInclusions || []);
                this.selected = raw.map(v => {
                    if (typeof v === 'object' && v !== null && 'id' in v) return { id: Number(v.id) };
                    return { id: Number(v) };
                }).filter(r => Number.isFinite(r.id));

                const d = cfg.defaults || {};
                this.coordinationPrice = Number(d.coordinationPrice ?? 25000);
                this.eventStylingPrice = Number(d.eventStylingPrice ?? 55000);
                this.packagePrice      = Number(d.packagePrice ?? 0);
                this.autoCalc          = !!d.autoCalc;
                this.coordination      = d.coordination ?? '';
                this.eventStylingText  = d.eventStylingText ?? '';
                this.isActive          = !!d.isActive;

                this.$watch('coordinationPrice', () => {
                    if (this.autoCalc) this.packagePrice = Number(this.grandTotal().toFixed(2));
                });
                this.$watch('eventStylingPrice', () => {
                    if (this.autoCalc) this.packagePrice = Number(this.grandTotal().toFixed(2));
                });
                this.$watch('autoCalc', () => {
                    if (this.autoCalc) this.packagePrice = Number(this.grandTotal().toFixed(2));
                });

                this.$nextTick(() => {
                    if (this.autoCalc) this.packagePrice = Number(this.grandTotal().toFixed(2));
                });
            },
        }));

        // Gallery manager: instant remove + min-4 validation
        Alpine.data('galleryManager', () => ({
            existing: [],   
            newItems: [],      
            removedIds: [],    
            error: '',

            init(jsonSelector){
                const el = document.querySelector(jsonSelector || '#pkg-images-json');
                let arr = [];
                try { arr = JSON.parse(el?.textContent || '[]') || []; } catch(e) { arr = []; }
                this.existing = arr.map(x => ({
                    id: Number(x.id),
                    url: x.url,
                    alt: x.alt || '',
                    removed: false
                }));
            },

            handleFiles(e){
                const files = Array.from(e.target.files || []);
                files.forEach((f, idx) => {
                    const url = URL.createObjectURL(f);
                    this.newItems.push({
                        key: `${Date.now()}-${idx}-${Math.random().toString(36).slice(2)}`,
                        file: f,
                        url,
                        alt: ''
                    });
                });
                if (this.totalCount() >= 4) this.error = '';
            },

            removeExisting(index){
                const img = this.existing[index];
                if (!img) return;
                img.removed = true;
                if (!this.removedIds.includes(img.id)) this.removedIds.push(img.id);
                this.error = this.totalCount() < 4
                    ? 'Please keep at least 4 images in total (existing minus removed plus new).'
                    : '';
            },

            removeNew(index){
                const item = this.newItems[index];
                if (item?.url) URL.revokeObjectURL(item.url);
                this.newItems.splice(index, 1);
                this.error = this.totalCount() < 4
                    ? 'Please keep at least 4 images in total (existing minus removed plus new).'
                    : '';
            },

            totalCount(){
                const remainingExisting = this.existing.filter(x => !x.removed).length;
                return remainingExisting + this.newItems.length;
            },

            ensureMin(){
                const ok = this.totalCount() >= 4;
                this.error = ok ? '' : 'Please keep at least 4 images in total (existing minus removed plus new).';
                return ok;
            },

            get isValid(){
                return this.totalCount() >= 4;
            },


        }));
    });
    </script>
</x-admin.layouts.management>