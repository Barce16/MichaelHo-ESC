<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Edit Event Inclusions</h2>
            <a href="{{ route('admin.events.show', $event) }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Event
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Event Info --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $event->name }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $event->customer->customer_name }}</p>
                        <p class="text-xs text-gray-500">{{ $event->event_date->format('F d, Y') }}</p>
                    </div>
                    <div class="text-right">
                        <div class="text-xs text-gray-500">Package</div>
                        <div class="text-sm font-semibold text-gray-900">{{ $event->package->name }}</div>
                    </div>
                </div>
            </div>

            {{-- Inclusions can NEVER be removed - only new ones can be added --}}
            @php
            $canRemoveInclusions = false;

            // All existing inclusions are locked
            $originalInclusionIds = $selectedInclusionIds->toArray();
            @endphp

            {{-- Notice about locked inclusions (always shown if there are existing inclusions) --}}
            @if(count($originalInclusionIds) > 0)
            <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-sm text-blue-900">
                        <p class="font-semibold mb-1">Add-Only Mode</p>
                        <p>Existing inclusions are <strong>locked</strong> and cannot be removed. You can only
                            <strong>add new inclusions</strong> to this event.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Form with Alpine.js --}}
            @php
            $alpineData = [
            'selectedInclusions' => $selectedInclusionIds->toArray(),
            'inclusionNotes' => $event->inclusions->mapWithKeys(function($inc) {
            return [$inc->id => $inc->pivot->notes ?? ''];
            })->toArray(),
            'originalInclusions' => $originalInclusionIds,
            'canRemove' => false, // Always false - inclusions can never be removed
            ];
            $grouped = $availableInclusions->groupBy('category');
            @endphp

            <form method="POST" action="{{ route('admin.events.updateInclusions', $event) }}" x-data='{
                    selectedInclusions: @json($alpineData["selectedInclusions"]),
                    inclusionNotes: @json($alpineData["inclusionNotes"]),
                    originalInclusions: @json($alpineData["originalInclusions"]),
                    canRemove: false,
                    activeCategory: "{{ $grouped->keys()->first() }}",
                    
                    toggleInclusion(id) {
                        // Original inclusions can never be removed
                        if (this.originalInclusions.includes(id)) {
                            return;
                        }
                        
                        const index = this.selectedInclusions.indexOf(id);
                        if (index > -1) {
                            this.selectedInclusions.splice(index, 1);
                        } else {
                            this.selectedInclusions.push(id);
                        }
                    },
                    
                    isSelected(id) {
                        return this.selectedInclusions.includes(id);
                    },
                    
                    isOriginal(id) {
                        return this.originalInclusions.includes(id);
                    },
                    
                    isLocked(id) {
                        return this.originalInclusions.includes(id);
                    }
                }'>
                @csrf
                @method('PUT')

                {{-- Tabbed Categories --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-violet-50 to-purple-50 border-b border-gray-200 px-6 py-4">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Select Inclusions
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Choose items from each category (existing items are
                            locked)</p>
                    </div>

                    <div class="p-6">
                        {{-- Category Tabs --}}
                        <div class="flex flex-wrap gap-2 mb-6 pb-4 border-b border-gray-200">
                            @foreach($grouped->keys() as $category)
                            <button type="button" @click="activeCategory = '{{ $category }}'" :class="activeCategory === '{{ $category }}' 
                                    ? 'bg-violet-500 text-white shadow-md' 
                                    : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                                class="px-4 py-2 rounded-lg font-medium text-sm transition-all">
                                {{ $category }}
                            </button>
                            @endforeach
                        </div>

                        {{-- Category Content --}}
                        @foreach($grouped as $category => $inclusions)
                        <div x-show="activeCategory === '{{ $category }}'" x-transition>
                            <div class="grid md:grid-cols-2 gap-3">
                                @foreach($inclusions as $inclusion)
                                <div class="border-2 rounded-lg transition relative" :class="isSelected({{ $inclusion->id }}) 
                                        ? (isLocked({{ $inclusion->id }}) ? 'border-slate-400 bg-slate-50' : 'border-violet-500 bg-violet-50') 
                                        : 'border-gray-200 bg-white'">

                                    {{-- Lock indicator for original inclusions --}}
                                    <div x-show="isLocked({{ $inclusion->id }})" class="absolute top-2 right-2 z-10">
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-1 bg-slate-600 text-white text-xs font-medium rounded-full">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                            Locked
                                        </span>
                                    </div>

                                    {{-- Inclusion Header --}}
                                    <label class="flex items-start gap-3 p-4 cursor-pointer hover:bg-gray-50"
                                        :class="isLocked({{ $inclusion->id }}) ? 'cursor-not-allowed opacity-75' : ''">

                                        {{-- Checkbox - disabled if locked --}}
                                        <input type="checkbox" name="inclusions[]" value="{{ $inclusion->id }}"
                                            @change="toggleInclusion({{ $inclusion->id }})"
                                            :checked="isSelected({{ $inclusion->id }})"
                                            :disabled="isLocked({{ $inclusion->id }})"
                                            class="mt-1 w-5 h-5 border-gray-300 rounded focus:ring-violet-500 flex-shrink-0"
                                            :class="isLocked({{ $inclusion->id }}) ? 'text-slate-400 cursor-not-allowed' : 'text-violet-600'">

                                        @if($inclusion->image)
                                        <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                            <img src="{{ asset('storage/' . $inclusion->image) }}"
                                                alt="{{ $inclusion->name }}" class="w-full h-full object-cover">
                                        </div>
                                        @endif

                                        <div class="flex-1">
                                            <div class="font-medium text-gray-900">{{ $inclusion->name }}</div>
                                            @if($inclusion->notes)
                                            <div class="text-xs text-gray-600 mt-1">{{ $inclusion->notes }}</div>
                                            @endif
                                            @if($inclusion->price > 0)
                                            <div class="text-sm font-semibold text-violet-600 mt-1">₱{{
                                                number_format($inclusion->price, 2) }}</div>
                                            @endif
                                        </div>
                                    </label>

                                    {{-- Notes Textarea (only show when selected) --}}
                                    <div x-show="isSelected({{ $inclusion->id }})" x-transition
                                        class="px-4 pb-4 border-t border-gray-200">
                                        <label class="block text-xs font-medium text-gray-700 mb-2 mt-3">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Notes for this item (Optional)
                                        </label>
                                        <textarea name="inclusion_notes[{{ $inclusion->id }}]"
                                            x-model="inclusionNotes[{{ $inclusion->id }}]" rows="2"
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-transparent"
                                            placeholder="Special requests, preferences, or details for {{ $inclusion->name }}..."></textarea>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Hidden inputs for locked inclusions (so they're always submitted) --}}
                @foreach($originalInclusionIds as $lockedId)
                <input type="hidden" name="locked_inclusions[]" value="{{ $lockedId }}">
                @endforeach

                {{-- Submit --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Updating inclusions will recalculate the total billing
                                amount</p>
                            @if(count($originalInclusionIds) > 0)
                            <p class="text-xs text-blue-600 mt-1">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                {{ count($originalInclusionIds) }} existing inclusion(s) are locked
                            </p>
                            @endif
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('admin.events.show', $event) }}"
                                class="px-5 py-2.5 border border-gray-300 rounded-lg font-medium hover:bg-gray-100 transition">
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-6 py-2.5 bg-violet-600 text-white font-medium rounded-lg hover:bg-violet-700 transition">
                                Update Inclusions
                            </button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>