<x-admin.layouts.management>
    <div class="bg-white rounded-lg shadow-sm p-6 space-y-4">
        <h3 class="text-lg font-semibold">Add New Vendor</h3>

        <form method="POST" action="{{ route('admin.management.vendors.store') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="name" value="Vendor Name" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name') }}"
                        required />
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="category" value="Category" />
                    <x-text-input id="category" name="category" type="text" class="mt-1 block w-full"
                        value="{{ old('category', $vendor->category) }}" required />
                    <x-input-error :messages="$errors->get('category')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="contact_person" value="Contact Person" />
                    <x-text-input id="contact_person" name="contact_person" type="text" class="mt-1 block w-full"
                        value="{{ old('contact_person') }}" />
                    <x-input-error :messages="$errors->get('contact_person')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="email" value="Email" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                        value="{{ old('email') }}" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="phone" value="Phone" />
                    <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full"
                        value="{{ old('phone') }}" />
                    <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="price" value="Price" />
                    <x-text-input id="price" name="price" type="number" step="0.01" min="0" class="mt-1 block w-full"
                        value="{{ old('price') }}" required />
                    <x-input-error :messages="$errors->get('price')" class="mt-1" />
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="address" value="Address" />
                    <textarea id="address" name="address" rows="2"
                        class="mt-1 w-full border rounded px-3 py-2">{{ old('address') }}</textarea>
                    <x-input-error :messages="$errors->get('address')" class="mt-1" />
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="notes" value="Notes" />
                    <textarea id="notes" name="notes" rows="3"
                        class="mt-1 w-full border rounded px-3 py-2">{{ old('notes') }}</textarea>
                    <x-input-error :messages="$errors->get('notes')" class="mt-1" />
                </div>

                <div class="md:col-span-2 flex items-center gap-2">
                    <input id="is_active" name="is_active" type="checkbox" value="1" class="rounded border-gray-300"
                        @checked(old('is_active', true)) />
                    <x-input-label for="is_active" value="Active" />
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t">
                <a href="{{ route('admin.management.vendors.index') }}" class="px-4 py-2 border rounded">Cancel</a>
                <x-primary-button>Save Vendor</x-primary-button>
            </div>
        </form>
    </div>
</x-admin.layouts.management>