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
                        <div class="mt-1">
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full
                                @if(in_array($event->status, ['requested', 'approved', 'request_meeting', 'meeting']))
                                    bg-blue-100 text-blue-700
                                @else
                                    bg-amber-100 text-amber-700
                                @endif
                            ">
                                {{ ucfirst(str_replace('_', ' ', $event->status)) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            @php
            // Determine if we're in "editable" mode (can switch inclusions) or "locked" mode (add only)
            $editableStatuses = ['requested', 'approved', 'request_meeting', 'meeting'];
            $lockedStatuses = ['scheduled', 'ongoing'];
            $isEditableMode = in_array($event->status, $editableStatuses);
            $isLockedMode = in_array($event->status, $lockedStatuses);

            $originalInclusionIds = $selectedInclusionIds->toArray();

            // Build inclusion data for Alpine.js with category info
            $inclusionData = $availableInclusions->mapWithKeys(function($inc) {
            return [$inc->id => [
            'id' => $inc->id,
            'name' => $inc->name,
            'price' => $inc->price,
            'category' => $inc->category instanceof \BackedEnum ? $inc->category->value : (string)$inc->category,
            ]];
            })->toArray();

            // Get all categories
            $allCategories = $availableInclusions->pluck('category')->map(function($cat) {
            return $cat instanceof \BackedEnum ? $cat->value : (string)$cat;
            })->unique()->values()->toArray();

            // Get original notes
            $originalNotes = $event->inclusions->mapWithKeys(function($inc) {
            return [$inc->id => $inc->pivot->notes ?? ''];
            })->toArray();

            // Group inclusions
            $grouped = $availableInclusions->groupBy(function($inc) {
            return $inc->category instanceof \BackedEnum ? $inc->category->value : (string)$inc->category;
            });
            @endphp

            {{-- Mode Notice --}}
            @if($isEditableMode)
            <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-sm text-blue-900">
                        <p class="font-semibold mb-1">Edit Mode</p>
                        <p>You can <strong>add or remove</strong> inclusions freely. However, <strong>each category must
                                have at least one inclusion</strong> selected.</p>
                    </div>
                </div>
            </div>
            @elseif($isLockedMode)
            <div class="bg-amber-50 border-l-4 border-amber-500 rounded-lg p-4">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <div class="text-sm text-amber-900">
                        <p class="font-semibold mb-1">Add-Only Mode</p>
                        <p>Event is <strong>{{ $event->status }}</strong>. Existing inclusions are
                            <strong>locked</strong> and cannot be removed. You can only <strong>add new
                                inclusions</strong>.</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Form with Alpine.js --}}
            <form method="POST" action="{{ route('admin.events.updateInclusions', $event) }}" x-data='{
                    selectedInclusions: @json($selectedInclusionIds->toArray()),
                    inclusionNotes: @json($originalNotes),
                    originalInclusions: @json($originalInclusionIds),
                    originalNotes: @json($originalNotes),
                    inclusionData: @json($inclusionData),
                    allCategories: @json($allCategories),
                    isEditableMode: @json($isEditableMode),
                    isLockedMode: @json($isLockedMode),
                    activeCategory: @json($grouped->keys()->first()),
                    showConfirmModal: false,
                    
                    init() {
                        this.selectedInclusions = this.selectedInclusions.map(id => parseInt(id));
                        this.originalInclusions = this.originalInclusions.map(id => parseInt(id));
                    },
                    
                    toggleInclusion(id) {
                        id = parseInt(id);
                        
                        if (this.isLockedMode && this.originalInclusions.includes(id)) {
                            return;
                        }
                        
                        const index = this.selectedInclusions.indexOf(id);
                        if (index > -1) {
                            if (this.isEditableMode) {
                                const inclusion = this.getInclusion(id);
                                if (inclusion) {
                                    const categoryCount = this.getCategorySelectedCount(inclusion.category);
                                    if (categoryCount <= 1) {
                                        return;
                                    }
                                }
                            }
                            this.selectedInclusions.splice(index, 1);
                            delete this.inclusionNotes[id];
                        } else {
                            this.selectedInclusions.push(id);
                            this.inclusionNotes[id] = "";
                        }
                    },
                    
                    isSelected(id) {
                        id = parseInt(id);
                        return this.selectedInclusions.includes(id);
                    },
                    
                    isOriginal(id) {
                        id = parseInt(id);
                        return this.originalInclusions.includes(id);
                    },
                    
                    isLocked(id) {
                        id = parseInt(id);
                        if (this.isLockedMode) {
                            return this.originalInclusions.includes(id);
                        }
                        return false;
                    },
                    
                    isLastInCategory(id) {
                        id = parseInt(id);
                        if (!this.isEditableMode) return false;
                        
                        const inclusion = this.getInclusion(id);
                        if (!inclusion) return false;
                        
                        const categoryCount = this.getCategorySelectedCount(inclusion.category);
                        return this.isSelected(id) && categoryCount <= 1;
                    },
                    
                    getInclusion(id) {
                        return this.inclusionData[String(id)] || this.inclusionData[parseInt(id)] || null;
                    },
                    
                    getCategorySelectedCount(category) {
                        return this.selectedInclusions.filter(id => {
                            const inc = this.getInclusion(id);
                            return inc && inc.category === category;
                        }).length;
                    },
                    
                    getCategoriesWithErrors() {
                        if (!this.isEditableMode) return [];
                        
                        return this.allCategories.filter(category => {
                            return this.getCategorySelectedCount(category) === 0;
                        });
                    },
                    
                    hasValidationErrors() {
                        return this.getCategoriesWithErrors().length > 0;
                    },
                    
                    getNewInclusions() {
                        return this.selectedInclusions.filter(id => !this.originalInclusions.includes(parseInt(id)));
                    },
                    
                    getRemovedInclusions() {
                        if (!this.isEditableMode) return [];
                        return this.originalInclusions.filter(id => !this.selectedInclusions.includes(parseInt(id)));
                    },
                    
                    getModifiedNotes() {
                        let modified = [];
                        for (let id of this.originalInclusions) {
                            if (!this.selectedInclusions.includes(parseInt(id))) continue;
                            
                            const oldNote = this.originalNotes[id] || this.originalNotes[String(id)] || "";
                            const newNote = this.inclusionNotes[id] || this.inclusionNotes[String(id)] || "";
                            if (oldNote !== newNote) {
                                const inclusion = this.getInclusion(id);
                                modified.push({
                                    id: id,
                                    name: inclusion?.name || "Unknown",
                                    oldNote: oldNote,
                                    newNote: newNote
                                });
                            }
                        }
                        return modified;
                    },
                    
                    getTotalNewPrice() {
                        return this.getNewInclusions().reduce((sum, id) => {
                            const inclusion = this.getInclusion(id);
                            const price = parseFloat(inclusion?.price) || 0;
                            return sum + price;
                        }, 0);
                    },
                    
                    getTotalRemovedPrice() {
                        return this.getRemovedInclusions().reduce((sum, id) => {
                            const inclusion = this.getInclusion(id);
                            const price = parseFloat(inclusion?.price) || 0;
                            return sum + price;
                        }, 0);
                    },
                    
                    getNetPriceChange() {
                        return this.getTotalNewPrice() - this.getTotalRemovedPrice();
                    },
                    
                    hasChanges() {
                        return this.getNewInclusions().length > 0 || 
                               this.getRemovedInclusions().length > 0 || 
                               this.getModifiedNotes().length > 0;
                    },
                    
                    formatPrice(amount) {
                        return new Intl.NumberFormat("en-PH", {
                            style: "currency",
                            currency: "PHP",
                            minimumFractionDigits: 2
                        }).format(amount);
                    },
                    
                    openConfirmModal() {
                        if (this.hasValidationErrors()) {
                            return;
                        }
                        if (!this.hasChanges()) {
                            alert("No changes detected. Please modify inclusions or notes before saving.");
                            return;
                        }
                        this.showConfirmModal = true;
                    },
                    
                    submitForm() {
                        this.$refs.form.submit();
                    }
                }' x-ref="form" @submit.prevent="openConfirmModal()">
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
                        <p class="text-sm text-gray-500 mt-1">
                            <template x-if="isEditableMode">
                                <span>Choose items from each category. Each category must have at least one
                                    selection.</span>
                            </template>
                            <template x-if="isLockedMode">
                                <span>Existing items are locked. You can only add new items.</span>
                            </template>
                        </p>
                    </div>

                    <div class="p-6">
                        {{-- Category Tabs with Error Indicators --}}
                        <div class="flex flex-wrap gap-2 mb-6 pb-4 border-b border-gray-200">
                            @foreach($grouped->keys() as $category)
                            <button type="button" @click="activeCategory = '{{ $category }}'" :class="[
                                    activeCategory === '{{ $category }}' 
                                        ? 'bg-violet-500 text-white shadow-md' 
                                        : 'bg-gray-100 text-gray-600 hover:bg-gray-200',
                                    getCategorySelectedCount('{{ $category }}') === 0 && isEditableMode
                                        ? 'ring-2 ring-red-500 ring-offset-1'
                                        : ''
                                ]" class="px-4 py-2 rounded-lg font-medium text-sm transition-all relative">
                                {{ $category }}
                                <span x-show="getCategorySelectedCount('{{ $category }}') > 0"
                                    x-text="'(' + getCategorySelectedCount('{{ $category }}') + ')'"
                                    class="ml-1 opacity-75"></span>
                                <span x-show="getCategorySelectedCount('{{ $category }}') === 0 && isEditableMode"
                                    class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full border-2 border-white"></span>
                            </button>
                            @endforeach
                        </div>

                        {{-- Category Content --}}
                        @foreach($grouped as $category => $inclusions)
                        <div x-show="activeCategory === '{{ $category }}'" x-transition>
                            {{-- Category Error Message --}}
                            <div x-show="getCategorySelectedCount('{{ $category }}') === 0 && isEditableMode"
                                class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-sm text-red-700 font-medium flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    You must select at least one inclusion from this category
                                </p>
                            </div>

                            <div class="grid md:grid-cols-2 gap-3">
                                @foreach($inclusions as $inclusion)
                                @php
                                $categoryStr = $inclusion->category instanceof \BackedEnum ? $inclusion->category->value
                                : (string)$inclusion->category;
                                @endphp
                                <div class="border-2 rounded-lg transition relative" :class="[
                                        isSelected({{ $inclusion->id }}) 
                                            ? (isLocked({{ $inclusion->id }}) ? 'border-slate-400 bg-slate-50' : 'border-violet-500 bg-violet-50') 
                                            : 'border-gray-200 bg-white',
                                        isLastInCategory({{ $inclusion->id }}) ? 'ring-2 ring-amber-400' : ''
                                    ]">

                                    {{-- Lock indicator (for locked mode) --}}
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

                                    {{-- Last in category warning (for editable mode) --}}
                                    <div x-show="isLastInCategory({{ $inclusion->id }}) && !isLocked({{ $inclusion->id }})"
                                        class="absolute top-2 right-2 z-10">
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-1 bg-amber-500 text-white text-xs font-medium rounded-full">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            Required
                                        </span>
                                    </div>

                                    {{-- NEW badge for newly added inclusions --}}
                                    <div x-show="isSelected({{ $inclusion->id }}) && !isOriginal({{ $inclusion->id }}) && !isLastInCategory({{ $inclusion->id }})"
                                        class="absolute top-2 right-2 z-10">
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-1 bg-emerald-500 text-white text-xs font-medium rounded-full">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                            New
                                        </span>
                                    </div>

                                    {{-- Inclusion Header --}}
                                    <label class="flex items-start gap-3 p-4 cursor-pointer hover:bg-gray-50" :class="[
                                            isLocked({{ $inclusion->id }}) ? 'cursor-not-allowed opacity-75' : '',
                                            isLastInCategory({{ $inclusion->id }}) ? 'cursor-not-allowed' : ''
                                        ]">

                                        {{-- Checkbox --}}
                                        <input type="checkbox" name="inclusions[]" value="{{ $inclusion->id }}"
                                            @change="toggleInclusion({{ $inclusion->id }})"
                                            :checked="isSelected({{ $inclusion->id }})"
                                            :disabled="isLocked({{ $inclusion->id }}) || isLastInCategory({{ $inclusion->id }})"
                                            class="mt-1 w-5 h-5 border-gray-300 rounded focus:ring-violet-500 flex-shrink-0"
                                            :class="[
                                                isLocked({{ $inclusion->id }}) ? 'text-slate-400 cursor-not-allowed' : 'text-violet-600',
                                                isLastInCategory({{ $inclusion->id }}) ? 'text-amber-500 cursor-not-allowed' : ''
                                            ]">

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
                                            {{-- Last in category warning text --}}
                                            <div x-show="isLastInCategory({{ $inclusion->id }})"
                                                class="text-xs text-amber-600 mt-1 font-medium">
                                                Cannot remove - last item in category
                                            </div>
                                        </div>
                                    </label>

                                    {{-- Notes Textarea --}}
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
                                            placeholder="Special requests, preferences, or details..."></textarea>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Hidden inputs for locked inclusions (only in locked mode) --}}
                @if($isLockedMode)
                @foreach($originalInclusionIds as $lockedId)
                <input type="hidden" name="locked_inclusions[]" value="{{ $lockedId }}">
                @endforeach
                @endif

                {{-- Live Change Summary --}}
                <div x-show="hasChanges() && !hasValidationErrors()" x-transition
                    class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl border border-emerald-200 p-6">
                    <h4 class="text-sm font-semibold text-emerald-800 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        Pending Changes
                    </h4>
                    <div class="space-y-2 text-sm">
                        <div x-show="getNewInclusions().length > 0" class="flex items-center gap-2 text-emerald-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            <span x-text="getNewInclusions().length + ' inclusion(s) to add'"></span>
                            <span class="font-semibold" x-text="'+ ' + formatPrice(getTotalNewPrice())"></span>
                        </div>
                        <div x-show="getRemovedInclusions().length > 0" class="flex items-center gap-2 text-red-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                            </svg>
                            <span x-text="getRemovedInclusions().length + ' inclusion(s) to remove'"></span>
                            <span class="font-semibold" x-text="'- ' + formatPrice(getTotalRemovedPrice())"></span>
                        </div>
                        <div x-show="getModifiedNotes().length > 0" class="flex items-center gap-2 text-blue-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span x-text="getModifiedNotes().length + ' note(s) modified'"></span>
                        </div>
                        <div x-show="getNewInclusions().length > 0 || getRemovedInclusions().length > 0"
                            class="pt-2 mt-2 border-t border-emerald-200 flex items-center gap-2 font-semibold"
                            :class="getNetPriceChange() >= 0 ? 'text-emerald-700' : 'text-red-600'">
                            <span>Net change:</span>
                            <span
                                x-text="(getNetPriceChange() >= 0 ? '+ ' : '- ') + formatPrice(Math.abs(getNetPriceChange()))"></span>
                        </div>
                    </div>
                </div>

                {{-- Validation Error Summary --}}
                <div x-show="hasValidationErrors()" x-transition class="bg-red-50 border border-red-200 rounded-xl p-6">
                    <h4 class="text-sm font-semibold text-red-800 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Cannot Save - Missing Required Selections
                    </h4>
                    <p class="text-sm text-red-700 mb-2">The following categories need at least one inclusion:</p>
                    <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                        <template x-for="category in getCategoriesWithErrors()" :key="category">
                            <li x-text="category"></li>
                        </template>
                    </ul>
                </div>

                {{-- Submit --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Updating inclusions will recalculate the total billing
                                amount</p>
                            <template x-if="isLockedMode && originalInclusions.length > 0">
                                <p class="text-xs text-amber-600 mt-1 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    <span
                                        x-text="originalInclusions.length + ' existing inclusion(s) are locked'"></span>
                                </p>
                            </template>
                            <template x-if="isEditableMode">
                                <p class="text-xs text-blue-600 mt-1 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Each category must have at least one inclusion
                                </p>
                            </template>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('admin.events.show', $event) }}"
                                class="px-5 py-2.5 border border-gray-300 rounded-lg font-medium hover:bg-gray-100 transition">
                                Cancel
                            </a>
                            <button type="submit" :disabled="hasValidationErrors()" :class="hasValidationErrors() 
                                    ? 'bg-gray-300 text-gray-500 cursor-not-allowed' 
                                    : 'bg-violet-600 text-white hover:bg-violet-700'"
                                class="px-6 py-2.5 font-medium rounded-lg transition inline-flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Review & Save Changes
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Confirmation Modal --}}
                <div x-show="showConfirmModal" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto"
                    style="display: none;">

                    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showConfirmModal = false"></div>

                    <div class="flex min-h-full items-center justify-center p-4">
                        <div x-show="showConfirmModal" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            class="relative bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden"
                            @click.away="showConfirmModal = false">

                            <div class="bg-gradient-to-r from-violet-600 to-purple-600 px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                        </svg>
                                        Confirm Changes
                                    </h3>
                                    <button type="button" @click="showConfirmModal = false"
                                        class="text-white/80 hover:text-white transition">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-violet-100 text-sm mt-1">Please review the changes before saving</p>
                            </div>

                            <div class="p-6 overflow-y-auto max-h-[60vh]">
                                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-violet-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">{{ $event->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $event->customer->customer_name }} •
                                                {{ $event->event_date->format('F d, Y') }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div x-show="getNewInclusions().length > 0" class="mb-6">
                                    <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                        <span
                                            class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                        </span>
                                        New Inclusions to Add
                                    </h4>
                                    <div class="space-y-2">
                                        <template x-for="id in getNewInclusions()" :key="id">
                                            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-3">
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <span class="font-medium text-gray-900"
                                                            x-text="getInclusion(id)?.name"></span>
                                                        <span class="text-xs text-gray-500 ml-2"
                                                            x-text="'(' + (getInclusion(id)?.category || '') + ')'"></span>
                                                    </div>
                                                    <span class="font-semibold text-emerald-600"
                                                        x-text="formatPrice(parseFloat(getInclusion(id)?.price) || 0)"></span>
                                                </div>
                                                <div x-show="inclusionNotes[id] && inclusionNotes[id].trim() !== ''"
                                                    class="mt-2 text-sm text-gray-600 bg-white rounded p-2 border border-emerald-100">
                                                    <span class="text-xs font-medium text-gray-500">Note:</span>
                                                    <span x-text="inclusionNotes[id]"></span>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                    <div
                                        class="mt-3 pt-3 border-t border-emerald-200 flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-700">Subtotal for New
                                            Inclusions</span>
                                        <span class="text-lg font-bold text-emerald-600"
                                            x-text="formatPrice(getTotalNewPrice())"></span>
                                    </div>
                                </div>

                                <div x-show="getRemovedInclusions().length > 0" class="mb-6">
                                    <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                        <span class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 12H4" />
                                            </svg>
                                        </span>
                                        Inclusions to Remove
                                    </h4>
                                    <div class="space-y-2">
                                        <template x-for="id in getRemovedInclusions()" :key="id">
                                            <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <span class="font-medium text-gray-900"
                                                            x-text="getInclusion(id)?.name"></span>
                                                        <span class="text-xs text-gray-500 ml-2"
                                                            x-text="'(' + (getInclusion(id)?.category || '') + ')'"></span>
                                                    </div>
                                                    <span class="font-semibold text-red-600"
                                                        x-text="'- ' + formatPrice(parseFloat(getInclusion(id)?.price) || 0)"></span>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="mt-3 pt-3 border-t border-red-200 flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-700">Subtotal to Remove</span>
                                        <span class="text-lg font-bold text-red-600"
                                            x-text="'- ' + formatPrice(getTotalRemovedPrice())"></span>
                                    </div>
                                </div>

                                <div x-show="getModifiedNotes().length > 0" class="mb-6">
                                    <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                        <span class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </span>
                                        Modified Notes
                                    </h4>
                                    <div class="space-y-3">
                                        <template x-for="item in getModifiedNotes()" :key="item.id">
                                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                                <div class="font-medium text-gray-900 mb-2" x-text="item.name"></div>
                                                <div class="grid grid-cols-2 gap-3 text-sm">
                                                    <div>
                                                        <div class="text-xs font-medium text-gray-500 mb-1">Previous
                                                            Note</div>
                                                        <div
                                                            class="bg-white rounded p-2 border border-gray-200 min-h-[40px]">
                                                            <span x-show="item.oldNote" x-text="item.oldNote"
                                                                class="text-gray-600"></span>
                                                            <span x-show="!item.oldNote" class="text-gray-400 italic">No
                                                                note</span>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs font-medium text-blue-600 mb-1">New Note
                                                        </div>
                                                        <div
                                                            class="bg-white rounded p-2 border border-blue-300 min-h-[40px]">
                                                            <span x-show="item.newNote" x-text="item.newNote"
                                                                class="text-gray-900"></span>
                                                            <span x-show="!item.newNote"
                                                                class="text-gray-400 italic">Note removed</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <div x-show="getNetPriceChange() !== 0"
                                    class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                                    <div class="flex gap-3">
                                        <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <div class="text-sm">
                                            <p class="font-semibold text-amber-800">Billing Impact</p>
                                            <p class="text-amber-700 mt-1">
                                                This will
                                                <span x-show="getNetPriceChange() > 0">increase</span>
                                                <span x-show="getNetPriceChange() < 0">decrease</span>
                                                the total billing amount by
                                                <strong x-text="formatPrice(Math.abs(getNetPriceChange()))"></strong>.
                                                The customer will be notified of the updated billing.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                                <div class="flex items-center justify-end gap-3">
                                    <button type="button" @click="showConfirmModal = false"
                                        class="px-5 py-2.5 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-100 transition">
                                        Go Back & Edit
                                    </button>
                                    <button type="button" @click="submitForm()"
                                        class="px-6 py-2.5 bg-violet-600 text-white font-medium rounded-lg hover:bg-violet-700 transition inline-flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Confirm & Save Changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>