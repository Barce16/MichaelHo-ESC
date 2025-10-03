<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Edit Event</h2>
    </x-slot>

    {{-- Expose package data for Alpine (prices, inclusion notes, etc.) --}}
    <script>
        window.__pkgData = {
        @foreach($packages as $p)
            {{ $p->id }}: @js([
                'id'   => $p->id,
                'name' => $p->name,
                'description' => $p->description,
                'coordination' => $p->coordination,
                'coordination_price' => $p->coordination_price ?? 25000,
                'event_styling' => is_array($p->event_styling) ? array_values($p->event_styling) : [],
                'event_styling_price' => $p->event_styling_price ?? 55000,
                'inclusions' => $p->inclusions->map(fn($i) => [
                    'id'    => $i->id,
                    'name'  => $i->name,
                    'price' => $i->price,
                    'notes' => $i->notes,
                    'category' => $i->category,
                ])->values(),
            ]),
        @endforeach
        };
    </script>

    @php
    // Event's currently selected inclusions (ids)
    $eventIncIds = $event->inclusions->pluck('id')->map(fn($i)=>(int)$i)->all();

    // Seed guests payload for Alpine:
    // Prefer old('guests') after a validation error; otherwise event guests.
    $seedGuests = collect(old('guests', $event->guests->map(fn($g) => [
    'id' => $g->id,
    'name' => $g->name,
    'email' => $g->email,
    'contact_number' => $g->contact_number,
    'party_size' => $g->party_size,
    ])->values()))->values();
    @endphp

    <div class="py-6" x-data="editEventForm()">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <form method="POST" action="{{ route('customer.events.update', $event) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <x-input-label for="name" value="Event Name" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                value="{{ old('name', $event->name) }}" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="event_date" value="Event Date" />
                            <x-text-input id="event_date" name="event_date" type="date" class="mt-1 block w-full"
                                value="{{ old('event_date', \Illuminate\Support\Carbon::parse($event->event_date)->format('Y-m-d')) }}"
                                required />
                            <x-input-error :messages="$errors->get('event_date')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="package_id" value="Package" />
                            <select id="package_id" name="package_id" class="mt-1 w-full border rounded px-3 py-2"
                                x-model.number="selectedPackage" @change="loadPackage(selectedPackage)">
                                <option value="">-- Choose Package --</option>
                                @foreach($packages as $p)
                                <option value="{{ $p->id }}" @selected(old('package_id', $event->package_id) == $p->id)>
                                    {{ $p->name }}
                                </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('package_id')" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="venue" value="Venue" />
                            <x-text-input id="venue" name="venue" type="text" class="mt-1 block w-full"
                                value="{{ old('venue', $event->venue) }}" />
                            <x-input-error :messages="$errors->get('venue')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="budget" value="Budget" />
                            <x-text-input id="budget" name="budget" type="number" step="0.01" min="0"
                                class="mt-1 block w-full" value="{{ old('budget', $event->budget) }}" />
                            <x-input-error :messages="$errors->get('budget')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="theme" value="Theme" />
                            <x-text-input id="theme" name="theme" type="text" class="mt-1 block w-full"
                                value="{{ old('theme', $event->theme) }}" />
                            <x-input-error :messages="$errors->get('theme')" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="notes" value="Notes" />
                            <textarea id="notes" name="notes" rows="3"
                                class="mt-1 w-full border rounded px-3 py-2">{{ old('notes', $event->notes) }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>
                    </div>

                    {{-- Package details + selectable inclusions --}}
                    <div class="mt-6 md:col-span-2" x-show="pkg" x-cloak>
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="text-lg font-semibold mb-1" x-text="pkg?.name || ''"></div>
                                    <div class="text-gray-700 font-medium">
                                        Estimated Total: ₱<span x-text="fmt(grandTotal())" class="font-bold"></span>
                                    </div>
                                </div>
                                <span class="px-2 py-1 text-xs rounded bg-emerald-100 text-emerald-800">Selected</span>
                            </div>

                            <template x-if="pkg?.description">
                                <p class="text-sm text-gray-600 mt-2" x-text="pkg.description"></p>
                            </template>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">

                                {{-- Inclusions (toggle) --}}
                                <div>
                                    <div class="text-xs uppercase tracking-wide text-gray-500 mb-1">Inclusions</div>

                                    <template x-if="!(pkg?.inclusions?.length)">
                                        <div class="text-sm text-gray-500">—</div>
                                    </template>

                                    <div class="space-y-2" x-show="pkg?.inclusions?.length">
                                        <template x-for="inc in (pkg?.inclusions || [])" :key="inc.id">
                                            <label
                                                class="flex items-start justify-between gap-3 border rounded px-3 py-2 bg-white">
                                                <div class="flex-1 min-w-0">
                                                    <div class="font-medium text-gray-900">
                                                        <span x-text="inc.name"></span>
                                                        <template x-if="inc.category">
                                                            <span class="ml-2 text-xs text-gray-500">• <span
                                                                    x-text="inc.category"></span></span>
                                                        </template>
                                                    </div>
                                                    <template x-if="(inc.notes || '').trim() !== ''">
                                                        <ul class="mt-1 text-xs text-gray-600 list-disc pl-4">
                                                            <template
                                                                x-for="line in (inc.notes || '').split(/\r\n|\r|\n/).slice(0,3)"
                                                                :key="line">
                                                                <li x-text="line"></li>
                                                            </template>
                                                        </ul>
                                                    </template>
                                                </div>
                                                <div class="shrink-0 text-right">
                                                    <div class="text-sm font-semibold">₱<span
                                                            x-text="fmt(inc.price || 0)"></span></div>
                                                    <input type="checkbox" class="mt-1 rounded border-gray-300"
                                                        :checked="selectedIncs.has(inc.id)"
                                                        @change="toggleInclusion(inc.id)">
                                                    {{-- submit only selected --}}
                                                    <template x-if="selectedIncs.has(inc.id)">
                                                        <input type="hidden" name="inclusions[]" :value="inc.id">
                                                    </template>
                                                </div>
                                            </label>
                                        </template>
                                    </div>

                                    <x-input-error :messages="$errors->get('inclusions')" class="mt-2" />
                                    <x-input-error :messages="$errors->get('inclusions.*')" class="mt-1" />
                                </div>

                                {{-- Coordination / Styling and totals --}}
                                <div class="space-y-3">
                                    <div class="rounded border bg-white p-3">
                                        <div class="text-xs uppercase tracking-wide text-gray-500">Coordination</div>
                                        <div class="mt-1 text-sm text-gray-700 whitespace-pre-line"
                                            x-text="pkg?.coordination || '—'"></div>
                                        <div class="mt-2 font-semibold">
                                            ₱<span x-text="fmt(pkg?.coordination_price ?? 25000)"></span>
                                        </div>
                                    </div>

                                    <div class="rounded border bg-white p-3">
                                        <div class="text-xs uppercase tracking-wide text-gray-500">Event Styling</div>
                                        <template x-if="pkg?.event_styling?.length">
                                            <ul>
                                                <template x-for="item in (pkg?.event_styling || [])" :key="item">
                                                    <li x-text="item"></li>
                                                </template>
                                            </ul>
                                        </template>
                                        <template x-if="!(pkg?.event_styling?.length)">
                                            <div class="mt-1 text-sm text-gray-500">—</div>
                                        </template>
                                        <div class="mt-2 font-semibold">
                                            ₱<span x-text="fmt(pkg?.event_styling_price ?? 55000)"></span>
                                        </div>
                                    </div>

                                    <div class="rounded border bg-white p-3 text-sm text-gray-800">
                                        <div class="flex items-center justify-between">
                                            <span>Inclusions Subtotal</span>
                                            <span class="font-semibold">₱<span
                                                    x-text="fmt(inclusionsSubtotal())"></span></span>
                                        </div>
                                        <div class="mt-1 flex items-center justify-between">
                                            <span>Estimated Total</span>
                                            <span class="font-semibold">₱<span x-text="fmt(grandTotal())"></span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="guests" class="block text-sm font-medium text-gray-700">Guest List/Details</label>
                        <textarea name="guests" id="guests" rows="4"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Enter guest names, count, or special requirements...">{{ old('guests', $event->guests) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">You can list guest names, total count, or any special
                            guest requirements</p>
                        @error('guests')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Actions --}}
                    <div class="mt-6 flex justify-end gap-2">
                        <a href="{{ route('customer.events.show',$event) }}" class="px-4 py-2 border rounded">Cancel</a>
                        <button class="px-4 py-2 bg-gray-800 text-white rounded">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editEventForm(){
            const initialPkg = Number(@json(old('package_id', $event->package_id)) || 0);

            // Old() inclusions if validation failed; otherwise event's current ones
            const oldSelected = new Set((@json(old('inclusions')) || []).map(Number));
            const eventSelected = new Set((@json($eventIncIds) || []).map(Number));

            return {
                selectedPackage: initialPkg,
                pkg: null,
                selectedIncs: new Set(),

                fmt(n){
                    return Number(n || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                },

                loadPackage(id){
                    const p = window.__pkgData[id] || null;
                    this.pkg = p;
                    this.selectedIncs.clear();
                    if (p && Array.isArray(p.inclusions)) {
                        if (oldSelected.size > 0 && Number(@json(old('package_id', $event->package_id))) === Number(id)) {
                            p.inclusions.forEach(i => { if (oldSelected.has(Number(i.id))) this.selectedIncs.add(Number(i.id)); });
                        } else if (eventSelected.size > 0 && Number(@json($event->package_id)) === Number(id)) {
                            p.inclusions.forEach(i => { if (eventSelected.has(Number(i.id))) this.selectedIncs.add(Number(i.id)); });
                        } else {
                            p.inclusions.forEach(i => this.selectedIncs.add(Number(i.id)));
                        }
                    }
                },

                toggleInclusion(id){
                    id = Number(id);
                    if (this.selectedIncs.has(id)) this.selectedIncs.delete(id);
                    else this.selectedIncs.add(id);
                },

                inclusionsSubtotal(){
                    if (!this.pkg || !this.pkg.inclusions) return 0;
                    return this.pkg.inclusions.reduce((sum, i) => {
                        if (this.selectedIncs.has(Number(i.id))) sum += Number(i.price || 0);
                        return sum;
                    }, 0);
                },

                grandTotal(){
                    const coord = Number(this.pkg?.coordination_price ?? 25000);
                    const styl  = Number(this.pkg?.event_styling_price ?? 55000);
                    return this.inclusionsSubtotal() + coord + styl;
                },

                init(){
                    if (this.selectedPackage) {
                        this.$nextTick(() => this.loadPackage(this.selectedPackage));
                    }
                }
            }
        }

        // Guests editor Alpine component
        function guestsEditor(seed){
            return {
                items: [],          // editable rows (existing + newly added)
                removedIds: [],     // removed existing ids
                draft: { name:'', email:'', contact_number:'', party_size:1 },

                init(){
                    const arr = Array.isArray(seed) ? seed : [];
                    // Give each row a stable key for x-for
                    this.items = arr.map(g => ({
                        _key: `ex-${g.id ?? Math.random().toString(36).slice(2)}`,
                        id: g.id ?? null,
                        name: g.name ?? '',
                        email: g.email ?? '',
                        contact_number: g.contact_number ?? '',
                        party_size: Number(g.party_size ?? 1),
                    }));
                },

                count(){
                    return this.items.length;
                },

                addDraft(){
                    // focus UX optional; here we just show a draft row at top (already present)
                },

                commitDraft(){
                    const n = (this.draft.name || '').trim();
                    const e = (this.draft.email || '').trim();
                    const c = (this.draft.contact_number || '').trim();
                    const p = Number(this.draft.party_size || 1);

                    if (!n && !e && !c) return; // ignore empty draft

                    this.items.push({
                        _key: `new-${Date.now()}-${Math.random().toString(36).slice(2)}`,
                        id: null,
                        name: n,
                        email: e,
                        contact_number: c,
                        party_size: (p > 0 ? p : 1),
                    });

                    this.draft = { name:'', email:'', contact_number:'', party_size:1 };
                },

                remove(i){
                    const row = this.items[i];
                    if (!row) return;
                    // If it's an existing guest, track its id to remove on backend
                    if (row.id && !this.removedIds.includes(row.id)) {
                        this.removedIds.push(row.id);
                    }
                    this.items.splice(i, 1);
                },
            }
        }
    </script>
</x-app-layout>