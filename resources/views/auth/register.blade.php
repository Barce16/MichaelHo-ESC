<x-guest-layout>
    <form method="POST" enctype="multipart/form-data" action="{{ route('register') }}">
        @csrf

        {{-- Avatar Upload + Preview --}}
        <div x-data="{ preview: null }">
            <x-input-label>Profile Photo</x-input-label>

            {{-- Preview box --}}
            <div class="mt-2 flex items-center gap-4">
                <template x-if="preview">
                    <img :src="preview" alt="Preview" class="h-16 w-16 rounded-full object-cover ring-1 ring-gray-200">
                </template>
                <template x-if="!preview">
                    <div class="h-16 w-16 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </template>

                <input type="file" name="avatar" accept="image/*" class="mt-1 block w-full border rounded px-3 py-2"
                    @change="preview = URL.createObjectURL($event.target.files[0])">
            </div>

            <x-input-error :messages="$errors->get('avatar')" class="mt-2" />
        </div>

        {{-- Full Name --}}
        <x-input-label for="name" :value="__('Full Name')" class="mt-4" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name') }}" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />

        {{-- Username --}}
        <x-input-label for="username" :value="__('Username')" class="mt-4" />
        <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" value="{{ old('username') }}"
            required />
        <x-input-error :messages="$errors->get('username')" class="mt-2" />

        {{-- Email --}}
        <x-input-label for="email" :value="__('Email')" class="mt-4" />
        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" value="{{ old('email') }}"
            required />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />

        {{-- Phone --}}
        <x-input-label for="phone" :value="__('Phone')" class="mt-4" />
        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" value="{{ old('phone') }}" />
        <x-input-error :messages="$errors->get('phone')" class="mt-2" />

        {{-- Address --}}
        <x-input-label for="address" :value="__('Address')" class="mt-4" />
        <textarea id="address" name="address" rows="2"
            class="mt-1 w-full border rounded px-3 py-2">{{ old('address') }}</textarea>
        <x-input-error :messages="$errors->get('address')" class="mt-2" />

        {{-- Password --}}
        <x-input-label for="password" :value="__('Password')" class="mt-4" />
        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
        <x-input-error :messages="$errors->get('password')" class="mt-2" />

        {{-- Confirm Password --}}
        <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="mt-4" />
        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full"
            required />

        {{-- Actions --}}
        <div class="mt-6 flex items-center justify-end gap-3">
            <a href="{{ url('/') }}" class="inline-block bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                Cancel
            </a>
            <x-primary-button>
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>