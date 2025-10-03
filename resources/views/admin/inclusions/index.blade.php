<x-admin.layouts.management>
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold">Inclusions</h3>
        <a href="{{ route('admin.management.inclusions.create') }}" class="px-3 py-2 bg-gray-800 text-white rounded">New
            Inclusion</a>
    </div>

    <form method="GET" class="mb-3 flex gap-2">
        <input name="q" value="{{ $q }}" placeholder="Search name/category" class="border rounded px-3 py-2 w-1/2">
        <button class="px-3 py-2 border rounded">Search</button>
    </form>

    <div class="overflow-x-auto bg-white rounded shadow-sm">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left text-gray-600 border-b">
                    <th class="py-2 px-3">Name</th>
                    <th class="py-2 px-3">Contact</th>
                    <th class="py-2 px-3">Category</th>
                    <th class="py-2 px-3">Price</th>
                    <th class="py-2 px-3">Status</th>
                    <th class="py-2 px-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($inclusions as $i)
                <tr class="border-b">
                    <td class="py-2 px-3 font-medium">
                        <a href="{{ route('admin.management.inclusions.show', $i) }}"
                            class="text-indigo-600 underline">{{ $i->name }}</a>
                    </td>
                    <td class="py-2 px-3">{{ $i->contact_person ?: '—' }}</td>
                    <td class="py-2 px-3">{{ $i->category ?: '—' }}</td>
                    <td class="py-2 px-3">₱{{ number_format($i->price,2) }}</td>
                    <td class="py-2 px-3">
                        <span
                            class="px-2 py-1 rounded text-xs {{ $i->is_active ? 'bg-green-100 text-green-800':'bg-gray-100 text-gray-800' }}">
                            {{ $i->is_active ? 'Active':'Inactive' }}
                        </span>
                    </td>
                    <td class="py-2 px-3 text-right">
                        <a href="{{ route('admin.management.inclusions.edit', $i) }}" class="underline">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-3">{{ $inclusions->links() }}</div>
    </div>
</x-admin.layouts.management>