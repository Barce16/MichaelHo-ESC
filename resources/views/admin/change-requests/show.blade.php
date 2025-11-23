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
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6"
                x-data="{ showApproveModal: false, showRejectModal: false }">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Review Request</h3>

                <div class="flex gap-3">
                    {{-- Approve Button --}}
                    <button type="button" @click="showApproveModal = true"
                        class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold rounded-lg hover:from-green-600 hover:to-emerald-700 transition shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Approve Changes
                    </button>

                    {{-- Reject Button --}}
                    <button type="button" @click="showRejectModal = true"
                        class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Reject Changes
                    </button>
                </div>

                {{-- Approve Modal --}}
                <div x-show="showApproveModal" x-cloak @click.self="showApproveModal = false"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
                    <div @click.stop x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden">

                        {{-- Modal Header --}}
                        <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold text-white">Approve Change Request</h3>
                                <button @click="showApproveModal = false"
                                    class="text-white/80 hover:text-white transition">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Modal Body --}}
                        <form method="POST" action="{{ route('admin.change-requests.approve', $changeRequest) }}">
                            @csrf
                            <div class="p-6 space-y-4">
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <p class="text-sm text-green-800">
                                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        This will apply the customer's inclusion changes to the event and update the
                                        billing accordingly.
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Admin Notes (Optional)
                                    </label>
                                    <textarea name="admin_notes" rows="4"
                                        class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition resize-none"
                                        placeholder="Add any notes about this approval..."></textarea>
                                </div>
                            </div>

                            {{-- Modal Footer --}}
                            <div class="border-t border-gray-200 px-6 py-4 bg-gray-50 flex gap-3">
                                <button type="button" @click="showApproveModal = false"
                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-100 transition">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="flex-1 px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
                                    Confirm Approval
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Reject Modal --}}
                <div x-show="showRejectModal" x-cloak @click.self="showRejectModal = false"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
                    <div @click.stop x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden">

                        {{-- Modal Header --}}
                        <div class="bg-gradient-to-r from-red-500 to-rose-600 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold text-white">Reject Change Request</h3>
                                <button @click="showRejectModal = false"
                                    class="text-white/80 hover:text-white transition">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Modal Body --}}
                        <form method="POST" action="{{ route('admin.change-requests.reject', $changeRequest) }}">
                            @csrf
                            <div class="p-6 space-y-4">
                                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                    <p class="text-sm text-red-800">
                                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        This will reject the customer's request. Please provide a clear reason.
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Rejection Reason <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="admin_notes" rows="4" required
                                        class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 transition resize-none"
                                        placeholder="Please provide a clear reason for rejecting this request..."></textarea>
                                </div>
                            </div>

                            {{-- Modal Footer --}}
                            <div class="border-t border-gray-200 px-6 py-4 bg-gray-50 flex gap-3">
                                <button type="button" @click="showRejectModal = false"
                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-100 transition">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="flex-1 px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition">
                                    Confirm Rejection
                                </button>
                            </div>
                        </form>
                    </div>
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