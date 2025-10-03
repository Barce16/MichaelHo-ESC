<x-admin.layouts.management>
    <form method="POST" action="{{ route('admin.management.vendors.update', $vendor) }}"
        class="bg-white rounded-lg shadow-sm p-6 space-y-4">
        @csrf
        @method('PATCH')

        <h3 class="text-lg font-semibold">Edit Vendor</h3>

        {{-- Errors --}}
        @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-2 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="name" value="Vendor Name" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                    value="{{ old('name', $vendor->name) }}" required />
            </div>

            <div>
                <x-input-label for="price" value="Price (₱)" />
                <x-text-input id="price" name="price" type="number" step="0.01" class="mt-1 block w-full"
                    value="{{ old('price', $vendor->price) }}" required />
            </div>

            <div>
                <x-input-label for="contact_person" value="Contact Person" />
                <x-text-input id="contact_person" name="contact_person" type="text" class="mt-1 block w-full"
                    value="{{ old('contact_person', $vendor->contact_person) }}" />
            </div>

            <div>
                <x-input-label for="category" value="Category" />
                <x-text-input id="category" name="category" type="text" class="mt-1 block w-full"
                    value="{{ old('category', $vendor->category) }}" />
            </div>

            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                    value="{{ old('email', $vendor->email) }}" />
            </div>

            <div>
                <x-input-label for="phone" value="Phone" />
                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full"
                    value="{{ old('phone', $vendor->phone) }}" />
            </div>

            <div class="md:col-span-2">
                <x-input-label for="address" value="Address" />
                <textarea id="address" name="address" rows="2"
                    class="mt-1 w-full border rounded px-3 py-2">{{ old('address', $vendor->address) }}</textarea>
            </div>

            <div class="md:col-span-2">
                <x-input-label for="notes" value="Notes" />
                <textarea id="notes" name="notes" rows="3"
                    class="mt-1 w-full border rounded px-3 py-2">{{ old('notes', $vendor->notes) }}</textarea>
            </div>

            <div class="md:col-span-2 flex items-center gap-2">
                <input id="is_active" name="is_active" type="checkbox" value="1" class="rounded border-gray-300"
                    @checked(old('is_active', $vendor->is_active)) />
                <x-input-label for="is_active" value="Active" />
            </div>
        </div>



        <div class="flex justify-end gap-2 pt-4 border-t">
            <a href="{{ route('admin.management.vendors.show', $vendor) }}" class="px-3 py-2 border rounded">Cancel</a>
            <button class="px-4 py-2 bg-gray-800 text-white rounded">Save Changes</button>
        </div>
    </form>
</x-admin.layouts.management>