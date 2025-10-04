<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Add Customer</h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <form method="POST" action="{{ route('customers.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <x-input-label value="Full Name" />
                        <x-text-input name="customer_name" value="{{ old('customer_name') }}" class="mt-1 w-full"
                            required />
                        <x-input-error :messages="$errors->get('customer_name')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label value="Email" />
                        <x-text-input type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full"
                            required />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label value="Phone" />
                        <x-text-input name="phone" value="{{ old('phone') }}" class="mt-1 w-full" />
                        <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label value="Address" />
                        <textarea name="address" rows="3"
                            class="mt-1 w-full border rounded px-3 py-2">{{ old('address') }}</textarea>
                        <x-input-error :messages="$errors->get('address')" class="mt-1" />
                    </div>
                    <div class="pt-2">
                        <button class="bg-gray-800 text-white px-4 py-2 rounded">Save</button>
                        <a href="{{ route('customers.index') }}"
                            class="ml-2 inline-block bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>