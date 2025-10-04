<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Create User</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <form method="POST" enctype="multipart/form-data" action="{{ route('admin.create-user.store') }}"
                    class="space-y-4">
                    @csrf

                    <div>
                        <x-input-label>Profile Photo</x-input-label>
                        <input type="file" name="avatar" accept="image/*"
                            class="block w-full border rounded px-3 py-2" />
                        <x-input-error :messages="$errors->get('avatar')" />
                    </div>

                    <div>
                        <label class="block text-sm">Full Name</label>
                        <input name="name" class="mt-1 w-full rounded border px-3 py-2" value="{{ old('name') }}"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm">Username</label>
                        <input name="username" class="mt-1 w-full rounded border px-3 py-2"
                            value="{{ old('username') }}" required>
                    </div>

                    <div>
                        <label class="block text-sm">Email</label>
                        <input type="email" name="email" class="mt-1 w-full rounded border px-3 py-2"
                            value="{{ old('email') }}" required>
                    </div>

                    <div>
                        <label class="block text-sm">User Type</label>
                        <select name="user_type" class="mt-1 w-full rounded border px-3 py-2" required>
                            <option value="admin" {{ old('user_type')==='admin' ?'selected':'' }}>Admin</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm">Password</label>
                        <input type="password" name="password" class="mt-1 w-full rounded border px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="mt-1 w-full rounded border px-3 py-2"
                            required>
                    </div>

                    <div class="pt-2">
                        <button class="bg-gray-800 text-white px-4 py-2 rounded">Create User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>