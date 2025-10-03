<x-admin.layouts.management>
    <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-semibold">Vendors</h3>
            <a href="{{ route('admin.management.vendors.create') }}" class="px-4 py-2 bg-gray-800 text-white rounded">
                + New Vendor
            </a>
        </div>

        <form method="GET" class="flex gap-2">
            <input type="text" name="q" value="{{ $q }}" placeholder="Search name, contact, email, phone"
                class="border rounded px-3 py-2 w-full">
            <button class="border px-3 py-2 rounded">Search</button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-4 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="text-gray-600">
                <tr>
                    <th class="text-left py-2">Vendor</th>
                    <th class="text-left py-2">Contact</th>
                    <th class="text-left py-2">Email / Phone</th>
                    <th class="text-left py-2">Price</th>
                    <th class="text-left py-2">Status</th>
                    <th class="text-left py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vendors as $v)
                <tr class="border-t">
                    <td class="py-2">
                        <div class="font-medium">{{ $v->name }}</div>
                        <div class="text-gray-500">{{ Str::limit($v->address, 40) }}</div>
                    </td>
                    <td class="py-2">{{ $v->contact_person ?: '—' }}</td>
                    <td class="py-2">
                        <div>{{ $v->email ?: '—' }}</div>
                        <div class="text-gray-500">{{ $v->phone ?: '' }}</div>
                    </td>
                    <td class="py-2">
                        ₱ {{ number_format($v->price ?? 0, 2) }}
                    </td>
                    <td class="py-2">
                        @php
                        $active = $v->is_active;
                        $badge = $active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="px-2 py-1 rounded text-xs {{ $badge }}">
                            {{ $active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="py-2 space-x-2">
                        <a href="{{ route('admin.management.vendors.show', $v) }}" class="underline">View</a>
                        <a href="{{ route('admin.management.vendors.edit', $v) }}" class="underline">Edit</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="py-6 text-center text-gray-500" colspan="6">No vendors found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $vendors->links() }}
        </div>
    </div>
</x-admin.layouts.management>