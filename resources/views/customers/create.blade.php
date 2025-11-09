<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800">Add Walk-in Customer & Event</h2>
                <p class="text-sm text-gray-600 mt-1">Create customer account and event booking in one step</p>
            </div>
            <a href="{{ route('admin.customers.index') }}" class="text-gray-600 hover:text-gray-900 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <form method="POST" action="{{ route('admin.customers.storeWithEvent') }}" x-data="{
                selectedPackage: null,
                packages: @js($packagesData),
                allInclusions: @js($allInclusions),
                selectedInclusions: [],
                inclusionNotes: {}, // Track notes for each inclusion
                showCustomize: false,
                coordinationPrice: 25000,
                stylingPrice: 55000,
                
                selectPackage(packageId) {
                    this.selectedPackage = this.packages.find(p => p.id == packageId);
                    // Auto-select package default inclusions
                    if (this.selectedPackage) {
                        this.selectedInclusions = this.selectedPackage.inclusions.map(i => i.id);
                        this.coordinationPrice = parseFloat(this.selectedPackage.coordination_price || 25000);
                        this.stylingPrice = parseFloat(this.selectedPackage.event_styling_price || 55000);
                    }
                },
                
                // Filter inclusions by package type
                get filteredInclusions() {
                    if (!this.selectedPackage) return {};
                    
                    const packageType = this.selectedPackage.type;
                    const filtered = {};
                    
                    for (let category in this.allInclusions) {
                        const categoryInclusions = this.allInclusions[category].filter(inclusion => {
                            // Show if package_type matches OR if package_type is null (available for all)
                            return inclusion.package_type === packageType || 
                                   inclusion.package_type === null || 
                                   inclusion.package_type === '';
                        });
                        
                        if (categoryInclusions.length > 0) {
                            filtered[category] = categoryInclusions;
                        }
                    }
                    
                    return filtered;
                },
                
                toggleInclusion(inclusionId) {
                    const index = this.selectedInclusions.indexOf(inclusionId);
                    if (index > -1) {
                        this.selectedInclusions.splice(index, 1);
                    } else {
                        this.selectedInclusions.push(inclusionId);
                    }
                },
                
                isInclusionSelected(inclusionId) {
                    return this.selectedInclusions.includes(inclusionId);
                },
                
                getInclusionPrice(inclusionId) {
                    for (let category in this.allInclusions) {
                        const inclusion = this.allInclusions[category].find(i => i.id === inclusionId);
                        if (inclusion) return parseFloat(inclusion.price);
                    }
                    return 0;
                },
                
                get inclusionsSubtotal() {
                    return this.selectedInclusions.reduce((sum, id) => {
                        return sum + this.getInclusionPrice(id);
                    }, 0);
                },
                
                get grandTotal() {
                    return this.inclusionsSubtotal + this.coordinationPrice + this.stylingPrice;
                },
                
                formatPrice(amount) {
                    return '₱' + Number(amount).toLocaleString('en-PH', {minimumFractionDigits: 2});
                }
            }" class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            @csrf

            {{-- Customer Information Section --}}
            <div class="bg-white shadow-sm rounded-xl overflow-hidden">
                <div class="bg-gradient-to-r from-gray-900 to-gray-700 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-white">Customer Information</h3>
                            <p class="text-sm text-gray-300">Enter customer's personal details</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" name="customer_name" value="{{ old('customer_name') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                placeholder="e.g., John Doe">
                            @error('customer_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                placeholder="john@example.com">
                            <p class="text-xs text-gray-500 mt-1">Login credentials will be sent here</p>
                            @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                placeholder="09123456789">
                            @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gender *</label>
                            <select name="gender" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender')=='male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender')=='female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender')=='other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Address (Optional)</label>
                            <textarea name="address" rows="2"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                placeholder="Complete address">{{ old('address') }}</textarea>
                            @error('address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Package Selection Section --}}
            <div class="bg-white shadow-sm rounded-xl overflow-hidden">
                <div class="bg-gradient-to-r from-blue-900 to-blue-700 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-white">Select Package</h3>
                            <p class="text-sm text-gray-300">Choose the perfect package for the event</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    {{-- Package Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                        @foreach($packages as $package)
                        <label class="cursor-pointer group">
                            <input type="radio" name="package_id" value="{{ $package->id }}" required
                                class="sr-only peer" @click="selectPackage({{ $package->id }})" {{
                                old('package_id')==$package->id ? 'checked' : '' }}>

                            <div
                                class="relative overflow-hidden rounded-xl border-2 border-gray-200 transition-all duration-300 peer-checked:border-blue-500 peer-checked:ring-4 peer-checked:ring-blue-100 hover:border-gray-300 hover:shadow-lg">
                                {{-- Banner Image --}}
                                <div class="relative h-64 overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200">
                                    @if($package->banner_url)
                                    <img src="{{ $package->banner_url }}" alt="{{ $package->name }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    @endif

                                    {{-- Type Badge --}}
                                    <div class="absolute top-3 left-3">
                                        <span
                                            class="px-3 py-1 bg-black/80 backdrop-blur-sm text-white text-xs font-semibold uppercase tracking-wider rounded-full">
                                            {{ ucfirst($package->type) }}
                                        </span>
                                    </div>

                                    {{-- Selected Badge --}}
                                    <div class="absolute top-3 right-3 hidden peer-checked:block">
                                        <div
                                            class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center shadow-lg">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                {{-- Package Info --}}
                                <div class="p-4 bg-white">
                                    <h4 class="font-bold text-lg text-gray-900 mb-1">{{ $package->name }}</h4>
                                    <p class="text-2xl font-bold text-blue-600">₱{{ number_format($package->price, 2) }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-2">{{ $package->inclusions->count() }} default
                                        inclusions</p>
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>

                    @error('package_id')
                    <p class="text-red-500 text-sm mb-4">{{ $message }}</p>
                    @enderror

                    {{-- Selected Package Summary with Customize Button --}}
                    <div x-show="selectedPackage" x-cloak x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        class="mt-6 border-t-2 border-gray-100 pt-6">

                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                            <div class="flex items-start justify-between gap-4 mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h4 class="text-xl font-bold text-gray-900" x-text="selectedPackage?.name"></h4>
                                        <span
                                            class="px-3 py-1 bg-blue-600 text-white text-xs font-semibold uppercase tracking-wider rounded-full"
                                            x-text="selectedPackage?.type"></span>
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        <span x-text="selectedInclusions.length"></span> inclusions selected
                                    </div>
                                </div>

                                {{-- Customize Button --}}
                                <button type="button" @click="showCustomize = true"
                                    class="flex items-center gap-2 px-4 py-2 bg-white border-2 border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    Customize Inclusions
                                </button>
                            </div>

                            {{-- Price Breakdown --}}
                            <div class="bg-white rounded-lg p-4 space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Inclusions Subtotal:</span>
                                    <span class="font-semibold" x-text="formatPrice(inclusionsSubtotal)"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Coordination:</span>
                                    <span class="font-semibold" x-text="formatPrice(coordinationPrice)"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Event Styling:</span>
                                    <span class="font-semibold" x-text="formatPrice(stylingPrice)"></span>
                                </div>
                                <div class="border-t pt-2 flex justify-between text-lg">
                                    <span class="font-bold text-gray-900">Grand Total:</span>
                                    <span class="font-bold text-blue-600" x-text="formatPrice(grandTotal)"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Hidden Inputs for Selected Inclusions --}}
            <template x-for="inclusionId in selectedInclusions" :key="inclusionId">
                <input type="hidden" name="inclusions[]" :value="inclusionId">
            </template>

            {{-- Hidden Inputs for Inclusion Notes --}}
            <template x-for="inclusionId in selectedInclusions" :key="'note-' + inclusionId">
                <input type="hidden" :name="'inclusion_notes[' + inclusionId + ']'"
                    :value="inclusionNotes[inclusionId] || ''">
            </template>

            {{-- Event Details Section --}}
            <div class="bg-white shadow-sm rounded-xl overflow-hidden">
                <div class="bg-gradient-to-r from-purple-900 to-purple-700 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-white">Event Details</h3>
                            <p class="text-sm text-gray-300">Provide information about the event</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Event Name *</label>
                            <input type="text" name="event_name" value="{{ old('event_name') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                placeholder="e.g., John & Jane Wedding">
                            @error('event_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Event Date *</label>
                            <x-calendar-picker name="event_date" :value="old('event_date')" required />
                            @error('event_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Number of Guests</label>
                            <input type="number" name="guests" value="{{ old('guests') }}" min="1"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                placeholder="150">
                            @error('guests')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Venue Address *</label>
                            <textarea name="venue" rows="2" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                placeholder="Complete venue name and address">{{ old('venue') }}</textarea>
                            @error('venue')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Theme (Optional)</label>
                            <input type="text" name="theme" value="{{ old('theme') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                placeholder="e.g., Rustic Garden, Modern Minimalist">
                            @error('theme')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                            <textarea name="notes" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                placeholder="Special requests or important information...">{{ old('notes') }}</textarea>
                            @error('notes')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Email Notification --}}
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl p-6">
                <label class="flex items-start gap-4 cursor-pointer group">
                    <input type="checkbox" name="send_credentials_email" value="1" checked
                        class="mt-1 w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="font-semibold text-gray-900">Send Login Credentials via Email</span>
                        </div>
                        <p class="text-sm text-gray-600">Customer will receive an email with their account login details
                            and event information</p>
                    </div>
                </label>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-between gap-4 pt-4">
                <a href="{{ route('admin.customers.index') }}"
                    class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition font-medium">
                    Cancel
                </a>
                <button type="submit"
                    class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition font-medium shadow-lg hover:shadow-xl flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Create Customer & Event
                </button>
            </div>

            {{-- Customize Inclusions Modal --}}
            <div x-show="showCustomize" x-cloak @click.self="showCustomize = false"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100">

                <div @click.stop class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
                    x-transition:enter-end="opacity-100 scale-100">

                    {{-- Modal Header --}}
                    <div class="bg-gradient-to-r from-blue-900 to-blue-700 px-6 py-5 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">Customize Inclusions</h3>
                                <p class="text-sm text-white/80">Select or deselect items for this event</p>
                            </div>
                        </div>
                        <button @click="showCustomize = false" class="text-white/80 hover:text-white transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-6 overflow-y-auto max-h-[calc(90vh-180px)]">
                        <template x-for="(inclusions, category) in filteredInclusions" :key="category">
                            <div class="mb-6">
                                <h4 class="text-lg font-bold text-gray-900 mb-3 pb-2 border-b-2 border-gray-200"
                                    x-text="category"></h4>
                                <div class="grid grid-cols-1 gap-4">
                                    <template x-for="inclusion in inclusions" :key="inclusion.id">
                                        <div class="border-2 rounded-lg transition"
                                            :class="isInclusionSelected(inclusion.id) ? 'border-blue-500 bg-blue-50' : 'border-gray-200 bg-white'">

                                            {{-- Inclusion Header with Checkbox --}}
                                            <label class="flex items-start gap-3 p-3 cursor-pointer hover:bg-gray-50">
                                                <input type="checkbox" :checked="isInclusionSelected(inclusion.id)"
                                                    @change="toggleInclusion(inclusion.id)"
                                                    class="mt-1 w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 flex-shrink-0">

                                                {{-- Inclusion Image --}}
                                                <div
                                                    class="w-16 h-16 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100">
                                                    <img :src="inclusion.image_url" :alt="inclusion.name"
                                                        class="w-full h-full object-cover" loading="lazy">
                                                </div>

                                                <div class="flex-1 min-w-0">
                                                    <p class="font-medium text-gray-900" x-text="inclusion.name"></p>
                                                    <p class="text-sm text-blue-600 font-semibold"
                                                        x-text="formatPrice(inclusion.price)"></p>
                                                </div>
                                            </label>

                                            {{-- Notes Textarea (only show if selected) --}}
                                            <div x-show="isInclusionSelected(inclusion.id)" class="px-3 pb-3">
                                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                                    Add Note (Optional)
                                                </label>
                                                <textarea x-model="inclusionNotes[inclusion.id]" rows="2"
                                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                    :placeholder="'Special requests for ' + inclusion.name + '...'"></textarea>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="px-6 py-4 bg-gray-50 border-t flex items-center justify-between">
                        <div class="text-sm">
                            <span class="text-gray-600">Total Selected:</span>
                            <span class="font-bold text-lg text-blue-600 ml-2"
                                x-text="selectedInclusions.length"></span>
                            <span class="text-gray-600 ml-1">items</span>
                            <span class="mx-2">•</span>
                            <span class="font-bold text-lg text-blue-600"
                                x-text="formatPrice(inclusionsSubtotal)"></span>
                        </div>
                        <button type="button" @click="showCustomize = false"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                            Done
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>