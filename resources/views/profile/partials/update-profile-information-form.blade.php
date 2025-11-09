<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" enctype="multipart/form-data" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <div class="md:col-span-2 flex gap-x-5 mb-4" x-data="{ preview: '{{ $user->profile_photo_url }}' }">
                <div class="mt-2">
                    <img :src="preview" class="h-14 w-14 rounded-full object-cover" alt="Avatar">
                </div>

                <div class="text-sm w-1/2">
                    <x-input-label>Profile Photo</x-input-label>
                    <input type="file" name="avatar" accept="image/*" class="block w-full border rounded px-3 py-2"
                        @change="
                   if ($event.target.files.length > 0) {
                       const file = $event.target.files[0];
                       preview = URL.createObjectURL(file);
                   }
               " />
                    <x-input-error :messages="$errors->get('avatar')" />
                </div>
            </div>

            @if($user->user_type === 'admin')
            <!-- Signature Upload (Admin Only) -->
            <div class="md:col-span-2 flex gap-x-5 mb-4 p-4 bg-blue-50 rounded-lg border border-blue-200"
                x-data="{ signaturePreview: '{{ $user->signature_url }}' }">
                <div class="mt-2">
                    <div
                        class="h-20 w-32 border-2 border-dashed border-gray-300 rounded flex items-center justify-center bg-white">
                        <img x-show="signaturePreview" :src="signaturePreview"
                            class="max-h-full max-w-full object-contain" alt="Signature">
                        <span x-show="!signaturePreview" class="text-xs text-gray-400">No signature</span>
                    </div>
                </div>

                <div class="text-sm flex-1">
                    <x-input-label class="text-blue-900 font-semibold">Digital Signature (Admin Only)</x-input-label>
                    <p class="text-xs text-blue-700 mb-2">This signature will appear on official receipts. PNG format
                        only.</p>
                    <input type="file" name="signature" accept=".png"
                        class="block w-full border rounded px-3 py-2 text-sm" @change="
                       if ($event.target.files.length > 0) {
                           const file = $event.target.files[0];
                           signaturePreview = URL.createObjectURL(file);
                       }
                   " />
                    <x-input-error :messages="$errors->get('signature')" class="mt-1" />

                    @if($user->signature_path)
                    <label class="flex items-center mt-2 text-xs text-gray-600 cursor-pointer hover:text-red-600">
                        <input type="checkbox" name="remove_signature" value="1" class="mr-2 rounded">
                        Remove current signature
                    </label>
                    @endif
                </div>
            </div>
            @endif

            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" name="username" type="text" class="mt-1 block w-full"
                :value="old('username', $user->username)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('username')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div>
                <p class="text-sm mt-2 text-gray-800">
                    {{ __('Your email address is unverified.') }}

                    <button form="send-verification"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                <p class="mt-2 font-medium text-sm text-green-600">
                    {{ __('A new verification link has been sent to your email address.') }}
                </p>
                @endif
            </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>