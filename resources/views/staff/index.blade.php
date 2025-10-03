<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Staff</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <div class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-start">
                    <a href="{{ route('staff.create') }}" class="px-3 py-2 bg-gray-800 text-white rounded">New Staff</a>
                </div>

                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search name/email/role"
                        class="border rounded px-3 py-2 md:col-span-2">
                    <select name="active" class="border rounded px-3 py-2">
                        <option value="">All Status</option>
                        <option value="1" @selected(request('active')==='1' )>Active</option>
                        <option value="0" @selected(request('active')==='0' )>Inactive</option>
                    </select>
                    <select name="gender" class="border rounded px-3 py-2">
                        <option value="">All Genders</option>
                        @foreach(['male','female','other'] as $g)
                        <option value="{{ $g }}" @selected(request('gender')===$g)>{{ ucfirst($g) }}</option>
                        @endforeach
                    </select>
                    <div class="flex justify-end gap-x-4 items-center">
                        <a href="{{ route('staff.index') }}" class="px-3 py-2 border rounded">Reset</a>
                        <button class="px-4 py-2 bg-gray-800 text-white rounded">Filter</button>
                    </div>
                </form>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-600 border-b">
                                <th class="py-2 pr-4">Name</th>
                                <th class="py-2 pr-4">Email</th>
                                <th class="py-2 pr-4">Contact</th>
                                <th class="py-2 pr-4">Role Type</th>
                                <th class="py-2 pr-4">Status</th>
                                <th class="py-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($staffs as $s)
                            <tr class="border-b">
                                <td class="py-2 pr-4">
                                    <div class="font-medium">{{ $s->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $s->user->username ?: '—' }}</div>
                                </td>
                                <td class="py-2 pr-4">{{ $s->user->email }}</td>
                                <td class="py-2 pr-4">{{ $s->contact_number ?: '—' }}</td>
                                <td class="py-2 pr-4">{{ $s->role_type ?: '—' }}</td>
                                <td class="py-2 pr-4">
                                    <span
                                        class="px-2 py-1 rounded text-xs {{ $s->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $s->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="py-2 text-right space-x-2">
                                    <a href="{{ route('staff.show', $s) }}" class="underline text-indigo-600">View</a>
                                    <a href="{{ route('staff.edit', $s) }}" class="underline">Edit</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-gray-500">No staff found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div>
                    {{ $staffs->withQueryString()->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>