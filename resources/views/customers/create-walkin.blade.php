<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Add Walk-in Customer & Event</h2>
    </x-slot>

    <script>
        window.__packagesData = @json($packagesData);
        window.__allInclusions = @json($allInclusions);
    </script>

    <div class="py-6" x-data="walkinForm()" x-init="init()">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- Progress Steps --}}
            <div class="mb-6 bg-white rounded-lg shadow-sm p-4">
                <div class="flex items-center justify-between">
                    <template x-for="(stepInfo, index) in steps" :key="index">
                        <div class="flex items-center flex-1">
                            <div class="flex items-center gap-3 flex-1">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold transition"
                                        :class="currentStep > index ? 'bg-emerald-500 text-white' : (currentStep === index ? 'bg-violet-500 text-white' : 'bg-gray-200 text-gray-600')">
                                        <span x-show="currentStep <= index" x-text="index + 1"></span>
                                        <svg x-show="currentStep > index" class="w-5 h-5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold"
                                        :class="currentStep >= index ? 'text-gray-900' : 'text-gray-500'"
                                        x-text="stepInfo.title"></p>
                                    <p class="text-xs text-gray-500" x-text="stepInfo.subtitle"></p>
                                </div>
                            </div>
                            <div x-show="index < steps.length - 1" class="w-16 h-0.5 mx-2"
                                :class="currentStep > index ? 'bg-emerald-500' : 'bg-gray-200'"></div>
                        </div>
                    </template>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.customers.storeWithEvent') }}" id="walkinForm">
                @csrf

                <div class="bg-white shadow-sm rounded-lg p-6 space-y-6">

                    {{-- STEP 1: Customer Information --}}
                    <div x-show="currentStep === 0" x-transition>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Customer Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label value="Full Name *" />
                                <x-text-input name="customer_name" x-model="formData.customer_name" class="mt-1 w-full"
                                    required />
                                <x-input-error :messages="$errors->get('customer_name')" class="mt-1" />
                            </div>

                            <div>
                                <x-input-label value="Email *" />
                                <x-text-input type="email" name="email" x-model="formData.email" class="mt-1 w-full"
                                    required />
                                <x-input-error :messages="$errors->get('email')" class="mt-1" />
                                <p class="text-xs text-gray-500 mt-1">Login credentials will be sent to this email</p>
                            </div>

                            <div>
                                <x-input-label value="Phone *" />
                                <x-text-input name="phone" x-model="formData.phone" class="mt-1 w-full" required />
                                <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                            </div>

                            <div>
                                <x-input-label value="Gender *" />
                                <select name="gender" x-model="formData.gender"
                                    class="mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                                <x-input-error :messages="$errors->get('gender')" class="mt-1" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label value="Address" />
                                <textarea name="address" x-model="formData.address" rows="2"
                                    class="mt-1 w-full border-gray-300 rounded-md shadow-sm"></textarea>
                                <x-input-error :messages="$errors->get('address')" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    {{-- STEP 2: Package Selection & Inclusions --}}
                    <div x-show="currentStep === 1" x-transition>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Select Package & Customize
                        </h3>

                        {{-- Package Type Tabs --}}
                        <div class="mb-6">
                            <div class="border-b border-gray-200">
                                <nav class="flex gap-4">
                                    <template x-for="type in packageTypes" :key="type">
                                        <button type="button" @click="selectedPackageType = type"
                                            class="px-4 py-2 border-b-2 font-medium text-sm transition" :class="selectedPackageType === type 
                                                ? 'border-violet-500 text-violet-600' 
                                                : 'border-transparent text-gray-500 hover:text-gray-700'">
                                            <span x-text="type.charAt(0).toUpperCase() + type.slice(1)"></span>
                                        </button>
                                    </template>
                                </nav>
                            </div>
                        </div>

                        {{-- Package Cards --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                            <template x-for="pkg in filteredPackages" :key="pkg.id">
                                <div @click="selectPackage(pkg.id)"
                                    class="border-2 rounded-lg p-4 cursor-pointer transition" :class="selectedPackageId === pkg.id 
                                        ? 'border-violet-500 bg-violet-50' 
                                        : 'border-gray-200 hover:border-violet-300'">
                                    <div class="flex items-start justify-between mb-2">
                                        <h4 class="font-semibold text-gray-900" x-text="pkg.name"></h4>
                                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                                            :class="selectedPackageId === pkg.id 
                                                ? 'border-violet-500 bg-violet-500' 
                                                : 'border-gray-300'">
                                            <svg x-show="selectedPackageId === pkg.id" class="w-3 h-3 text-white"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-2xl font-bold text-violet-600">₱<span
                                            x-text="Number(pkg.price).toLocaleString()"></span></p>
                                    <p class="text-xs text-gray-500 mt-1"
                                        x-text="pkg.type.charAt(0).toUpperCase() + pkg.type.slice(1) + ' Package'"></p>
                                </div>
                            </template>
                        </div>

                        <input type="hidden" name="package_id" :value="selectedPackageId">

                        {{-- Inclusions Customization --}}
                        <template x-if="selectedPackage">
                            <div class="mt-8 border-t pt-6">
                                <h4 class="text-md font-semibold text-gray-900 mb-4">Customize Inclusions</h4>

                                {{-- Category Tabs --}}
                                <div class="border-b border-gray-200 mb-4">
                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="cat in inclusionCategories" :key="cat">
                                            <button type="button" @click="activeInclusionTab = cat"
                                                class="px-4 py-2 border-b-2 font-medium text-sm transition" :class="activeInclusionTab === cat 
                                                    ? 'border-emerald-500 text-emerald-600 bg-emerald-50' 
                                                    : 'border-transparent text-gray-500 hover:text-gray-700'">
                                                <span x-text="cat"></span>
                                            </button>
                                        </template>
                                    </div>
                                </div>

                                {{-- Inclusions Grid --}}
                                <template x-for="category in inclusionCategories" :key="category">
                                    <div x-show="activeInclusionTab === category" x-transition>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                            <template x-for="inc in getInclusionsForCategory(category)" :key="inc.id">
                                                <div @click="toggleInclusion(inc.id)"
                                                    class="border rounded-lg p-3 cursor-pointer transition" :class="selectedInclusions.has(inc.id) 
                                                        ? 'border-emerald-500 bg-emerald-50' 
                                                        : 'border-gray-200 hover:border-emerald-300'">
                                                    <div class="flex items-start justify-between mb-2">
                                                        <h5 class="text-sm font-semibold text-gray-900 flex-1 line-clamp-2"
                                                            x-text="inc.name"></h5>
                                                        <div class="w-4 h-4 rounded border-2 flex items-center justify-center ml-2"
                                                            :class="selectedInclusions.has(inc.id) 
                                                                ? 'border-emerald-500 bg-emerald-500' 
                                                                : 'border-gray-300'">
                                                            <svg x-show="selectedInclusions.has(inc.id)"
                                                                class="w-2.5 h-2.5 text-white" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="3" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <p class="text-sm font-bold text-emerald-600">₱<span
                                                            x-text="Number(inc.price).toLocaleString()"></span></p>
                                                    <input type="checkbox" class="hidden" name="inclusions[]"
                                                        :value="inc.id" :checked="selectedInclusions.has(inc.id)">
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>

                                {{-- Summary --}}
                                <div
                                    class="mt-6 bg-gradient-to-r from-violet-500 to-purple-600 rounded-lg p-4 text-white">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <span class="text-sm opacity-90">Selected Inclusions</span>
                                            <p class="text-2xl font-bold"><span x-text="selectedInclusions.size"></span>
                                                items</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-sm opacity-90">Estimated Total</span>
                                            <p class="text-3xl font-bold">₱<span
                                                    x-text="calculateTotal().toLocaleString()"></span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- STEP 3: Event Details --}}
                    <div x-show="currentStep === 2" x-transition>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Event Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <x-input-label value="Event Name *" />
                                <x-text-input name="event_name" x-model="formData.event_name" class="mt-1 w-full"
                                    placeholder="e.g., John & Jane Wedding" required />
                            </div>

                            <div>
                                <x-input-label value="Event Date *" />
                                <x-text-input type="date" name="event_date" x-model="formData.event_date"
                                    class="mt-1 w-full" required />
                            </div>

                            <div>
                                <x-input-label value="Number of Guests" />
                                <x-text-input type="number" name="guests" x-model="formData.guests" class="mt-1 w-full"
                                    min="1" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label value="Venue *" />
                                <textarea name="venue" x-model="formData.venue" rows="2" required
                                    class="mt-1 w-full border-gray-300 rounded-md shadow-sm"
                                    placeholder="Complete venue address"></textarea>
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label value="Theme" />
                                <x-text-input name="theme" x-model="formData.theme" class="mt-1 w-full"
                                    placeholder="e.g., Rustic Garden, Modern Minimalist" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label value="Additional Notes" />
                                <textarea name="notes" x-model="formData.notes" rows="3"
                                    class="mt-1 w-full border-gray-300 rounded-md shadow-sm"
                                    placeholder="Any special requests or important information..."></textarea>
                            </div>
                        </div>

                        {{-- Email Notification Option --}}
                        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" name="send_credentials_email" value="1" checked
                                    class="mt-1 rounded border-gray-300 text-blue-600">
                                <div>
                                    <p class="font-medium text-gray-900">Send Login Credentials</p>
                                    <p class="text-sm text-gray-600">Send an email to customer with their account login
                                        details</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Navigation Buttons --}}
                    <div class="flex items-center justify-between pt-6 border-t">
                        <button type="button" @click="previousStep()" x-show="currentStep > 0"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                            Previous
                        </button>

                        <div class="flex-1"></div>

                        <button type="button" @click="nextStep()" x-show="currentStep < 2"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-violet-600 text-white rounded-lg hover:bg-violet-700 transition font-medium">
                            Continue
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                        <button type="submit" x-show="currentStep === 2"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Create Customer & Event
                        </button>
                    </div>

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
        function walkinForm() {
            return {
                currentStep: 0,
                steps: [
                    { title: 'Customer Info', subtitle: 'Basic details' },
                    { title: 'Package & Inclusions', subtitle: 'Select and customize' },
                    { title: 'Event Details', subtitle: 'Event information' }
                ],
                formData: {
                    customer_name: '',
                    email: '',
                    phone: '',
                    gender: '',
                    address: '',
                    event_name: '',
                    event_date: '',
                    guests: '',
                    venue: '',
                    theme: '',
                    notes: ''
                },
                packages: [],
                allInclusions: {},
                packageTypes: [],
                selectedPackageType: '',
                selectedPackageId: null,
                selectedPackage: null,
                selectedInclusions: new Set(),
                activeInclusionTab: '',
                inclusionCategories: [],

                init() {
                    this.packages = window.__packagesData || [];
                    this.allInclusions = window.__allInclusions || {};
                    this.packageTypes = [...new Set(this.packages.map(p => p.type))];
                    this.selectedPackageType = this.packageTypes[0] || '';
                    this.inclusionCategories = Object.keys(this.allInclusions);
                    this.activeInclusionTab = this.inclusionCategories[0] || '';
                },

                get filteredPackages() {
                    return this.packages.filter(p => p.type === this.selectedPackageType);
                },

                selectPackage(id) {
                    this.selectedPackageId = id;
                    this.selectedPackage = this.packages.find(p => p.id === id);
                    
                    // Pre-select package inclusions
                    this.selectedInclusions = new Set();
                    if (this.selectedPackage && this.selectedPackage.inclusions) {
                        this.selectedPackage.inclusions.forEach(inc => {
                            this.selectedInclusions.add(inc.id);
                        });
                    }
                },

                toggleInclusion(id) {
                    if (this.selectedInclusions.has(id)) {
                        this.selectedInclusions.delete(id);
                    } else {
                        this.selectedInclusions.add(id);
                    }
                },

                getInclusionsForCategory(category) {
                    const categoryInclusions = this.allInclusions[category] || [];
                    // Filter by package type if selected
                    if (this.selectedPackage) {
                        return categoryInclusions.filter(inc => 
                            !inc.package_type || 
                            inc.package_type === this.selectedPackage.type
                        );
                    }
                    return categoryInclusions;
                },

                calculateTotal() {
                    let total = 0;
                    if (this.selectedPackage) {
                        total += Number(this.selectedPackage.coordination_price || 0);
                        total += Number(this.selectedPackage.event_styling_price || 0);
                    }
                    
                    this.selectedInclusions.forEach(incId => {
                        for (let category in this.allInclusions) {
                            const inc = this.allInclusions[category].find(i => i.id === incId);
                            if (inc) {
                                total += Number(inc.price || 0);
                                break;
                            }
                        }
                    });
                    
                    return total;
                },

                nextStep() {
                    // Validation
                    if (this.currentStep === 0) {
                        if (!this.formData.customer_name || !this.formData.email || !this.formData.phone || !this.formData.gender) {
                            alert('Please fill in all required customer information fields');
                            return;
                        }
                    } else if (this.currentStep === 1) {
                        if (!this.selectedPackageId) {
                            alert('Please select a package');
                            return;
                        }
                    }
                    
                    if (this.currentStep < 2) {
                        this.currentStep++;
                    }
                },

                previousStep() {
                    if (this.currentStep > 0) {
                        this.currentStep--;
                    }
                }
            }
        }
    </script>
</x-app-layout>