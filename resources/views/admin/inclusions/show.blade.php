<x-admin.layouts.management>
    <div class="bg-white rounded shadow p-6 space-y-6 max-w-3xl">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold">Inclusion Details</h2>
            <div class="flex gap-4 items-center">
                <a href="{{ route('admin.management.inclusions.edit', $inclusion) }}" class="underline">Edit</a>
                <a href="{{ route('admin.management.inclusions.index') }}"
                    class="px-3 py-1 border rounded text-sm">Back</a>
            </div>
        </div>

        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">Name</dt>
                <dd class="mt-1 text-base text-gray-900">{{ $inclusion->name }}</dd>
            </div>

            <div>
                @if($inclusion->category)
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    {{ $inclusion->category->label() }}
                </span>
                @else
                <span class="text-gray-400 text-sm">No category</span>
                @endif
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Price</dt>
                <dd class="mt-1 text-base text-gray-900">₱{{ number_format($inclusion->price, 2) }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Status</dt>
                <dd class="mt-1">
                    @if($inclusion->is_active)
                    <span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700 text-sm">Active</span>
                    @else
                    <span class="px-2 py-1 rounded bg-red-100 text-red-700 text-sm">Inactive</span>
                    @endif
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Contact Person</dt>
                <dd class="mt-1 text-base text-gray-900">{{ $inclusion->contact_person ?? '—' }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Contact Email</dt>
                <dd class="mt-1 text-base text-gray-900">{{ $inclusion->contact_email ?? '—' }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Contact Phone</dt>
                <dd class="mt-1 text-base text-gray-900">{{ $inclusion->contact_phone ?? '—' }}</dd>
            </div>
        </dl>

        <div>
            <dt class="text-sm font-medium text-gray-500">Notes</dt>
            <dd class="mt-1 text-base text-gray-900 whitespace-pre-line">
                {{ $inclusion->notes ?? '—' }}
            </dd>
        </div>
    </div>
</x-admin.layouts.management>