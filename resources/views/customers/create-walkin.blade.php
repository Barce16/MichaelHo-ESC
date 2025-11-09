<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Add Walk-in Customer & Event</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg">
                <form method="POST" action="{{ route('admin.customers.storeWithEvent') }}" class="p-6 space-y-8">
                    @csrf

                    {{-- Customer Information --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Customer Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label value="Full Name *" />
                                <x-text-input name="customer_name" value="{{ old('customer_name') }}"
                                    class="mt-1 w-full" required />
                                <x-input-error :messages="$errors->get('customer_name')" class="mt-1" />
                            </div>

                            <div>
                                <x-input-label value="Email *" />
                                <x-text-input type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full"
                                    required />
                                <x-input-error :messages="$errors->get('email')" class="mt-1" />
                                <p class="text-xs text-gray-500 mt-1">Login credentials will be sent to this email</p>
                            </div>

                            <div>
                                <x-input-label value="Phone *" />
                                <x-text-input name="phone" value="{{ old('phone') }}" class="mt-1 w-full" required />
                                <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                            </div>

                            <div>
                                <x-input-label value="Gender *" />
                                <select name="gender" class="mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender')=='male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender')=='female' ? 'selected' : '' }}>Female
                                    </option>
                                    <option value="other" {{ old('gender')=='other' ? 'selected' : '' }}>Other</option>
                                </select>
                                <x-input-error :messages="$errors->get('gender')" class="mt-1" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label value="Address" />
                                <textarea name="address" rows="2"
                                    class="mt-1 w-full border-gray-300 rounded-md shadow-sm">{{ old('address') }}</textarea>
                                <x-input-error :messages="$errors->get('address')" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    {{-- Package Selection --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Package Selection</h3>
                        <div>
                            <x-input-label value="Select Package *" />
                            <select name="package_id" id="packageSelect"
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">Choose a package...</option>
                                @foreach($packages as $package)
                                <option value="{{ $package->id }}" data-price="{{ $package->price }}"
                                    data-type="{{ $package->type }}" {{ old('package_id')==$package->id ? 'selected' :
                                    '' }}>
                                    {{ $package->name }} - {{ ucfirst($package->type) }} (₱{{
                                    number_format($package->price, 2) }})
                                </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('package_id')" class="mt-1" />

                            <div id="packageInfo" class="mt-3 p-4 bg-gray-50 rounded-lg hidden">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Selected Package:</p>
                                        <p id="packageName" class="text-lg font-bold text-gray-900"></p>
                                        <p id="packageType" class="text-sm text-gray-600"></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-600">Package Price</p>
                                        <p id="packagePrice" class="text-2xl font-bold text-gray-900"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Event Details --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Event Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <x-input-label value="Event Name *" />
                                <x-text-input name="event_name" value="{{ old('event_name') }}" class="mt-1 w-full"
                                    placeholder="e.g., John & Jane Wedding" required />
                                <x-input-error :messages="$errors->get('event_name')" class="mt-1" />
                            </div>

                            <div>
                                <x-input-label value="Event Date *" />
                                <x-text-input type="date" name="event_date" value="{{ old('event_date') }}"
                                    class="mt-1 w-full" required />
                                <x-input-error :messages="$errors->get('event_date')" class="mt-1" />
                            </div>

                            <div>
                                <x-input-label value="Number of Guests" />
                                <x-text-input type="number" name="guests" value="{{ old('guests') }}"
                                    class="mt-1 w-full" min="1" />
                                <x-input-error :messages="$errors->get('guests')" class="mt-1" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label value="Venue *" />
                                <textarea name="venue" rows="2" required
                                    class="mt-1 w-full border-gray-300 rounded-md shadow-sm"
                                    placeholder="Complete venue address">{{ old('venue') }}</textarea>
                                <x-input-error :messages="$errors->get('venue')" class="mt-1" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label value="Theme" />
                                <x-text-input name="theme" value="{{ old('theme') }}" class="mt-1 w-full"
                                    placeholder="e.g., Rustic Garden, Modern Minimalist" />
                                <x-input-error :messages="$errors->get('theme')" class="mt-1" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label value="Additional Notes" />
                                <textarea name="notes" rows="3" class="mt-1 w-full border-gray-300 rounded-md shadow-sm"
                                    placeholder="Any special requests or important information...">{{ old('notes') }}</textarea>
                                <x-input-error :messages="$errors->get('notes')" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    {{-- Email Notification Option --}}
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="send_credentials_email" value="1" checked
                                class="mt-1 rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <div>
                                <p class="font-medium text-gray-900">Send Login Credentials</p>
                                <p class="text-sm text-gray-600">Send an email to customer with their account login
                                    details</p>
                            </div>
                        </label>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center gap-3 pt-4 border-t">
                        <button type="submit"
                            class="bg-gray-800 text-white px-6 py-3 rounded-lg hover:bg-gray-900 transition font-medium">
                            Create Customer & Event
                        </button>
                        <a href="{{ route('admin.customers.index') }}"
                            class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition font-medium">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('packageSelect').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const infoDiv = document.getElementById('packageInfo');
            
            if (this.value) {
                const price = selected.dataset.price;
                const type = selected.dataset.type;
                const name = selected.text.split(' - ')[0];
                
                document.getElementById('packageName').textContent = name;
                document.getElementById('packageType').textContent = type.charAt(0).toUpperCase() + type.slice(1) + ' Package';
                document.getElementById('packagePrice').textContent = '₱' + parseFloat(price).toLocaleString('en-PH', {minimumFractionDigits: 2});
                
                infoDiv.classList.remove('hidden');
            } else {
                infoDiv.classList.add('hidden');
            }
        });
    </script>
    @endpush
</x-app-layout>