<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Request New Event</h2>
    </x-slot>

    <script>
        window.__pkgData = {
      @foreach($packages as $p)
        {{ $p->id }}: @js([
          'id'                   => $p->id,
          'name'                 => $p->name,
          'type'          => $p->type,
          'coordination'         => $p->coordination,
          'coordination_price'   => $p->coordination_price ?? 25000,
          'event_styling'        => is_array($p->event_styling) ? array_values($p->event_styling) : [],
          'event_styling_price'  => $p->event_styling_price ?? 55000,
          'inclusions'           => $p->inclusions->map(fn($i) => [
            'id'    => $i->id,
            'name'  => $i->name,
            'price' => $i->price,
            'notes' => $i->notes,
          ])->values(),
        ]),
      @endforeach
    };
    </script>

    <div class="py-6" x-data="eventForm()" x-init="init()">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <form method="POST" action="{{ route('customer.events.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <x-input-label for="name" value="Event Name" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                value="{{ old('name') }}" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="event_date" value="Event Date" />
                            <x-text-input id="event_date" name="event_date" type="date" class="mt-1 block w-full"
                                value="{{ old('event_date') }}" required />
                            <x-input-error :messages="$errors->get('event_date')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="package_id" value="Package" />
                            <select id="package_id" name="package_id" class="mt-1 w-full border rounded px-3 py-2"
                                x-model.number="selectedPackage" @change="loadPackage(selectedPackage)">
                                <option value="">-- Choose Package --</option>
                                @foreach($packages as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('package_id')" class="mt-2" />
                        </div>

                        {{-- Package Details + Selectable Inclusions --}}
                        <template x-if="pkg">
                            <div class="md:col-span-2">
                                <div class="mt-3 rounded-lg border border-gray-200 bg-gray-50 p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <div class="text-lg font-semibold mb-2" x-text="pkg ? pkg.name : ''"></div>
                                            <div class="text-gray-700 text-xl font-medium">
                                                Estimated Total:
                                                ₱<span x-text="fmt(grandTotal())" class="font-bold"></span>
                                            </div>
                                        </div>
                                        <span
                                            class="px-2 py-1 text-xs rounded bg-emerald-100 text-emerald-800">Selected</span>
                                    </div>

                                    <template x-if="pkg && pkg.type">
                                        <p class="text-sm text-gray-600 mt-2" x-text="pkg.type"></p>
                                    </template>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                                        {{-- Inclusions with checkboxes --}}
                                        <div>
                                            <div class="text-xs uppercase tracking-wide text-gray-500 mb-1">Inclusions
                                            </div>

                                            <template x-if="!pkg || !pkg.inclusions || pkg.inclusions.length === 0">
                                                <div class="text-sm text-gray-500">—</div>
                                            </template>

                                            <div class="space-y-2"
                                                x-show="pkg && pkg.inclusions && pkg.inclusions.length">
                                                <template x-for="inc in (pkg ? (pkg.inclusions || []) : [])"
                                                    :key="inc.id">
                                                    <label
                                                        class="flex items-start justify-between gap-3 border rounded px-3 py-2 bg-white">
                                                        <div class="flex-1 min-w-0">
                                                            <div class="font-medium" x-text="inc.name"></div>
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
                                                            <template x-if="selectedIncs.has(inc.id)">
                                                                <input type="hidden" name="inclusions[]"
                                                                    :value="inc.id">
                                                            </template>
                                                        </div>
                                                    </label>
                                                </template>
                                            </div>

                                            <x-input-error :messages="$errors->get('inclusions')" class="mt-2" />
                                            <x-input-error :messages="$errors->get('inclusions.*')" class="mt-1" />
                                        </div>

                                        {{-- Coordination / Styling and subtotals --}}
                                        <div class="space-y-3">
                                            <div class="rounded border bg-white p-3">
                                                <div class="text-xs uppercase tracking-wide text-gray-500">Coordination
                                                </div>
                                                <div class="mt-1 text-sm text-gray-700"
                                                    x-text="pkg ? (pkg.coordination || '—') : ''"></div>
                                                <div class="mt-1 font-semibold">
                                                    ₱<span x-text="fmt(pkg ? pkg.coordination_price : 0)"></span>
                                                </div>
                                            </div>

                                            <div class="rounded border bg-white p-3">
                                                <div class="text-xs uppercase tracking-wide text-gray-500">Event Styling
                                                </div>
                                                <template x-if="pkg && pkg.event_styling && pkg.event_styling.length">
                                                    <ul class="mt-1 text-sm text-gray-700 list-disc pl-5 space-y-0.5">
                                                        <template x-for="item in (pkg ? (pkg.event_styling || []) : [])"
                                                            :key="item">
                                                            <li x-text="item"></li>
                                                        </template>
                                                    </ul>
                                                </template>
                                                <template
                                                    x-if="!(pkg && pkg.event_styling && pkg.event_styling.length)">
                                                    <div class="mt-1 text-sm text-gray-500">—</div>
                                                </template>
                                                <div class="mt-1 font-semibold">
                                                    ₱<span x-text="fmt(pkg ? pkg.event_styling_price : 0)"></span>
                                                </div>
                                            </div>

                                            <div class="rounded border bg-white p-3">
                                                <div class="text-sm text-gray-700 flex items-center justify-between">
                                                    <span>Inclusions Subtotal</span>
                                                    <span class="font-semibold">₱<span
                                                            x-text="fmt(inclusionsSubtotal())"></span></span>
                                                </div>
                                                <div
                                                    class="mt-1 text-sm text-gray-700 flex items-center justify-between">
                                                    <span>Estimated Total</span>
                                                    <span class="font-semibold">₱<span
                                                            x-text="fmt(grandTotal())"></span></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- Venue / Theme / Budget / Notes --}}
                        <div>
                            <x-input-label for="venue" value="Venue" />
                            <x-text-input id="venue" name="venue" type="text" class="mt-1 block w-full"
                                value="{{ old('venue') }}" />
                            <x-input-error :messages="$errors->get('venue')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="theme" value="Theme" />
                            <x-text-input id="theme" name="theme" type="text" class="mt-1 block w-full"
                                value="{{ old('theme') }}" />
                            <x-input-error :messages="$errors->get('theme')" class="mt-2" />
                        </div>

                        <div class="md:col-span-2 relative">
                            <x-input-label for="budget" value="Budget" />
                            <div class="absolute left-2 top-1/2">
                                <span class="text-lg text-emerald-700">₱</span>
                            </div>
                            <x-text-input id="budget" name="budget" type="number" step="0.01" min="0"
                                class="mt-1 block w-full pl-7" value="{{ old('budget') }}" />
                            <x-input-error :messages="$errors->get('budget')" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <label for="guests" class="block text-sm font-medium text-gray-700">Guest
                                List/Details</label>
                            <textarea name="guests" id="guests" rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Enter guest names, count, or special requirements...">{{ old('guests') }}</textarea>
                            @error('guests')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="notes" value="Notes" />
                            <textarea id="notes" name="notes" rows="3"
                                class="mt-1 w-full border rounded px-3 py-2">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                    </div>

                    <div class="mt-6 flex justify-end gap-2">
                        <a href="{{ route('customer.events.index') }}" class="px-4 py-2 border rounded">Cancel</a>
                        <button class="px-4 py-2 bg-gray-800 text-white rounded">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function eventForm(){
      const initialPkg   = Number(@json(old('package_id', request('package_id'))) || 0);
      const oldSelected  = new Set((@json(old('inclusions', [])) || []).map(Number));

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
            // Use old selections if they belong to the currently selected package
            if (oldSelected.size > 0 && Number(@json(old('package_id', request('package_id')))) === Number(id)) {
              p.inclusions.forEach(i => { if (oldSelected.has(Number(i.id))) this.selectedIncs.add(Number(i.id)); });
            } else {
              // Default: all inclusions selected
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