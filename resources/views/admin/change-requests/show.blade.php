<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800">Change Request Details</h2>
                <p class="text-sm text-gray-600 mt-1">{{ $changeRequest->event->name }}</p>
            </div>
            <a href="{{ route('admin.change-requests.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Requests
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Status Badge --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Request Status</h3>
                        <p class="text-sm text-gray-600 mt-1">
                            Submitted {{ $changeRequest->created_at->format('M d, Y \a\t g:i A') }}
                        </p>
                    </div>
                    <span
                        class="px-4 py-2 text-sm font-semibold rounded-full {{ $changeRequest->getStatusBadgeClass() }}">
                        {{ $changeRequest->getStatusLabel() }}
                    </span>
                </div>

                @if($changeRequest->isApproved() || $changeRequest->isRejected())
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Reviewed by:</span>
                        <span class="font-medium text-gray-900">{{ $changeRequest->reviewedBy->name }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm mt-2">
                        <span class="text-gray-600">Reviewed at:</span>
                        <span class="font-medium text-gray-900">{{ $changeRequest->reviewed_at->format('M d, Y \a\t g:i
                            A') }}</span>
                    </div>
                </div>
                @endif
            </div>

            {{-- Customer & Event Info --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Event Information</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Customer:</span>
                        <p class="font-medium text-gray-900">{{ $changeRequest->customer->user->name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Event Date:</span>
                        <p class="font-medium text-gray-900">{{
                            \Carbon\Carbon::parse($changeRequest->event->event_date)->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Package:</span>
                        <p class="font-medium text-gray-900">{{ $changeRequest->event->package->name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Venue:</span>
                        <p class="font-medium text-gray-900">{{ $changeRequest->event->venue ?? 'Not specified' }}</p>
                    </div>
                </div>
            </div>

            {{-- Changes Summary --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-violet-50 to-purple-50 border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-bold text-gray-900">Inclusion Changes</h3>
                </div>

                <div class="p-6 space-y-6">
                    {{-- Added Inclusions --}}
                    @if(count($changeRequest->getAddedInclusions()) > 0)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <h4 class="font-semibold text-green-900 mb-3">Added Inclusions</h4>
                        <ul class="space-y-2">
                            @foreach($changeRequest->getAddedInclusions() as $inc)
                            <li class="flex items-center justify-between">
                                <span class="text-sm text-green-800">{{ $inc['name'] }}</span>
                                <span class="text-sm font-semibold text-green-700">+₱{{ number_format($inc['price'], 2)
                                    }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    {{-- Removed Inclusions --}}
                    @if(count($changeRequest->getRemovedInclusions()) > 0)
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <h4 class="font-semibold text-red-900 mb-3">Removed Inclusions</h4>
                        <ul class="space-y-2">
                            @foreach($changeRequest->getRemovedInclusions() as $inc)
                            <li class="flex items-center justify-between">
                                <span class="text-sm text-red-800">{{ $inc['name'] }}</span>
                                <span class="text-sm font-semibold text-red-700">-₱{{ number_format($inc['price'], 2)
                                    }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    {{-- No Changes --}}
                    @if(count($changeRequest->getAddedInclusions()) === 0 &&
                    count($changeRequest->getRemovedInclusions()) === 0)
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <p class="text-sm text-gray-600">No inclusion changes detected</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Billing Summary --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Billing Impact</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Current Total:</span>
                        <span class="font-semibold text-gray-900">₱{{ number_format($changeRequest->old_total, 2)
                            }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">New Total:</span>
                        <span class="font-semibold text-gray-900">₱{{ number_format($changeRequest->new_total, 2)
                            }}</span>
                    </div>
                    <div class="border-t border-gray-200 pt-3 flex items-center justify-between">
                        <span class="font-semibold text-gray-900">Difference:</span>
                        <span
                            class="text-lg font-bold {{ $changeRequest->difference > 0 ? 'text-red-600' : ($changeRequest->difference < 0 ? 'text-green-600' : 'text-gray-600') }}">
                            @if($changeRequest->difference > 0)
                            +₱{{ number_format($changeRequest->difference, 2) }}
                            @elseif($changeRequest->difference < 0) -₱{{ number_format(abs($changeRequest->difference),
                                2) }}
                                @else
                                ₱0.00
                                @endif
                        </span>
                    </div>
                </div>
            </div>

            {{-- Admin Notes (if reviewed) --}}
            @if($changeRequest->admin_notes)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-2">Admin Notes</h3>
                <p class="text-sm text-blue-800">{{ $changeRequest->admin_notes }}</p>
            </div>
            @endif

            {{-- Action Buttons (only if pending) --}}
            @if($changeRequest->isPending())
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Review Request</h3>

                <div class="space-y-4">
                    {{-- Approve Form --}}
                    <form method="POST" action="{{ route('admin.change-requests.approve', $changeRequest) }}"
                        x-data="{ showApproveForm: false }">
                        @csrf
                        <div class="space-y-3">
                            <button type="button" @click="showApproveForm = !showApproveForm"
                                class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold rounded-lg hover:from-green-600 hover:to-emerald-700 transition shadow-md hover:shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Approve Changes
                            </button>

                            <div x-show="showApproveForm" x-cloak x-transition class="space-y-3">
                                <label class="block text-sm font-medium text-gray-700">
                                    Admin Notes (Optional)
                                </label>
                                <textarea name="admin_notes" rows="3"
                                    class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition"
                                    placeholder="Add any notes about this approval..."></textarea>
                                <button type="submit"
                                    class="w-full px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
                                    Confirm Approval
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- Reject Form --}}
                    <form method="POST" action="{{ route('admin.change-requests.reject', $changeRequest) }}"
                        x-data="{ showRejectForm: false }">
                        @csrf
                        <div class="space-y-3">
                            <button type="button" @click="showRejectForm = !showRejectForm"
                                class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Reject Changes
                            </button>

                            <div x-show="showRejectForm" x-cloak x-transition class="space-y-3">
                                <label class="block text-sm font-medium text-gray-700">
                                    Rejection Reason <span class="text-red-500">*</span>
                                </label>
                                <textarea name="admin_notes" rows="3" required
                                    class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 transition"
                                    placeholder="Please provide a reason for rejecting this request..."></textarea>
                                <button type="submit"
                                    class="w-full px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition">
                                    Confirm Rejection
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</x-app-layout>