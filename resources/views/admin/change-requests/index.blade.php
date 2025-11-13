<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Inclusion Change Requests</h2>

            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-600">
                    Pending: <span class="font-semibold text-amber-600">{{ $changeRequests->where('status',
                        'pending')->count() }}</span>
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if($changeRequests->isEmpty())
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No Change Requests</h3>
                <p class="mt-1 text-sm text-gray-500">There are no inclusion change requests at this time.</p>
            </div>
            @else
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Event & Customer
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Changes
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Amount Change
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Requested
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($changeRequests as $request)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $request->event->name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $request->customer->user->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    @php
                                    $added = count($request->getAddedInclusions());
                                    $removed = count($request->getRemovedInclusions());
                                    @endphp

                                    @if($added > 0)
                                    <span class="text-green-600">+{{ $added }}</span>
                                    @endif

                                    @if($added > 0 && $removed > 0)
                                    <span class="text-gray-400">/</span>
                                    @endif

                                    @if($removed > 0)
                                    <span class="text-red-600">-{{ $removed }}</span>
                                    @endif

                                    <span class="text-gray-500">items</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div
                                    class="text-sm font-semibold {{ $request->difference > 0 ? 'text-red-600' : ($request->difference < 0 ? 'text-green-600' : 'text-gray-600') }}">
                                    @if($request->difference > 0)
                                    +₱{{ number_format($request->difference, 2) }}
                                    @elseif($request->difference < 0) -₱{{ number_format(abs($request->difference), 2)
                                        }}
                                        @else
                                        ₱0.00
                                        @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 text-xs font-medium rounded-full {{ $request->getStatusBadgeClass() }}">
                                    {{ $request->getStatusLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $request->created_at->format('M d, Y') }}
                                <div class="text-xs text-gray-400">
                                    {{ $request->created_at->diffForHumans() }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <a href="{{ route('admin.change-requests.show', $request) }}"
                                    class="text-violet-600 hover:text-violet-900">
                                    View Details
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $changeRequests->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>