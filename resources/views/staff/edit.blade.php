<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Edit Staff</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
            <div class="mb-4 rounded border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                <div class="font-semibold mb-1">Please fix the following:</div>
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" enctype="multipart/form-data" action="{{ route('staff.update', $staff) }}"
                class="bg-white p-6 rounded shadow-sm space-y-4">
                @csrf
                @method('PUT')

                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold">Edit Staff</h3>
                    <div class="flex gap-2">
                        <a href="{{ route('staff.show', $staff) }}" class="px-3 py-2 border rounded">Cancel</a>
                        <button class="px-4 py-2 bg-gray-800 text-white rounded">Save</button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- User fields --}}

                    <div class="md:col-span-2 flex gap-x-5">
                        @isset($staff)
                        <div>
                            <img src="{{ $staff->user->profile_photo_url }}" class="h-14 w-14 rounded-full object-cover"
                                alt="Avatar">
                        </div>
                        @endisset
                        <div class="text-sm w-1/2">
                            <x-input-label>Profile Photo</x-input-label>
                            <input type="file" name="avatar" accept="image/*" class="w-full border rounded px-3 py-2" />
                            <x-input-error :messages="$errors->get('avatar')" />
                        </div>
                    </div>



                    <div>
                        <x-input-label>Name</x-input-label>
                        <x-text-input name="name" value="{{ old('name', $staff->user->name) }}" class="w-full"
                            autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" />
                    </div>
                    <div>
                        <x-input-label>Email</x-input-label>
                        <x-text-input type="email" name="email" value="{{ old('email', $staff->user->email) }}"
                            class="w-full" autocomplete="email" />
                        <x-input-error :messages="$errors->get('email')" />
                    </div>
                    <div>
                        <x-input-label>Username (optional)</x-input-label>
                        <x-text-input name="username" value="{{ old('username', $staff->user->username) }}"
                            class="w-full" autocomplete="username" />
                        <x-input-error :messages="$errors->get('username')" />
                    </div>
                    <div>
                        <x-input-label>New Password (leave blank to keep)</x-input-label>
                        <x-text-input type="password" name="password" class="w-full" autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" />
                    </div>

                    <div>
                        <x-input-label>Contact Number</x-input-label>
                        <x-text-input name="contact_number" value="{{ old('contact_number', $staff->contact_number) }}"
                            class="w-full" autocomplete="tel" />
                        <x-input-error :messages="$errors->get('contact_number')" />
                    </div>
                    <div>
                        <x-input-label>Role Type</x-input-label>
                        <x-text-input name="role_type" value="{{ old('role_type', $staff->role_type) }}"
                            class="w-full" />
                        <x-input-error :messages="$errors->get('role_type')" />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label>Address</x-input-label>
                        <x-text-input name="address" value="{{ old('address', $staff->address) }}" class="w-full"
                            autocomplete="street-address" />
                        <x-input-error :messages="$errors->get('address')" />
                    </div>
                    <div>
                        <x-input-label>Default Rate (per event)</x-input-label>
                        <x-text-input name="rate" type="number" step="0.01" min="0"
                            value="{{ old('rate', $staff->rate ?? '') }}" class="w-full" />
                        <x-input-error :messages="$errors->get('rate')" />
                    </div>
                    <input type="hidden" name="rate_type" value="per_event">

                    <div>
                        <x-input-label>Gender</x-input-label>
                        <select name="gender" class="border rounded px-3 py-2 w-full">
                            <option value="">—</option>
                            @foreach(['male','female','other'] as $g)
                            <option value="{{ $g }}" @selected(old('gender', $staff->gender)===$g)>{{ ucfirst($g) }}
                            </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('gender')" />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label>Remarks</x-input-label>
                        <textarea name="remarks" rows="3"
                            class="w-full border rounded px-3 py-2">{{ old('remarks', $staff->remarks) }}</textarea>
                        <x-input-error :messages="$errors->get('remarks')" />
                    </div>
                    <div>
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" @checked(old('is_active',
                                $staff->is_active)) class="rounded border-gray-300">
                            <span>Active</span>
                        </label>
                        <x-input-error :messages="$errors->get('is_active')" />
                    </div>
                </div>

                <div class="pt-4 border-t">
                    <h4 class="text-md font-semibold mb-2">Assigned Events (read-only)</h4>
                    <div class="text-sm text-gray-600">
                        Manage assignments on the event page. This list is for reference.
                    </div>
                    <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-2">
                        @forelse($staff->events->take(6) as $e)
                        <div class="border rounded p-3">
                            <div class="font-medium">{{ $e->name }}</div>
                            <div class="text-xs text-gray-500">
                                {{ \Illuminate\Support\Carbon::parse($e->event_date)->format('Y-m-d') }}
                                — {{ $e->pivot->assignment_role ?? '—' }}
                            </div>
                            <a href="{{ route('admin.events.show', $e) }}"
                                class="text-indigo-600 text-xs underline">View Event</a>
                        </div>
                        @empty
                        <div class="text-gray-500">No events assigned.</div>
                        @endforelse
                    </div>
                </div>
            </form>

            <div class="mt-4">
                <a href="{{ route('staff.index') }}" class="underline">Back to staff</a>
            </div>

        </div>
    </div>
</x-app-layout>