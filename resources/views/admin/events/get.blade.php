{{-- Reject Downpayment Modal --}}
<div x-show="showRejectDown" x-cloak @click.self="showRejectDown=false"
    class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-auto"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90">

        <form method="POST" action="{{ route('admin.events.rejectDownpayment', $event) }}">
            @csrf

            {{-- Modal Header --}}
            <div class="bg-rose-50 border-b border-rose-100 p-6 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-rose-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-rose-900">Reject Downpayment</h3>
                            <p class="text-sm text-rose-700">Provide reason for rejection</p>
                        </div>
                    </div>
                    <button type="button" @click="showRejectDown=false"
                        class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Modal Body --}}
            <div class="p-6">
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <label for="rejection_reason_down" class="block text-sm font-medium text-gray-700 mb-2">
                        Rejection Reason
                    </label>
                    <textarea id="rejection_reason_down" name="rejection_reason" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-rose-500 focus:ring-2 focus:ring-rose-200"
                        placeholder="E.g., Proof of payment is unclear, wrong amount, etc."></textarea>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="bg-gray-50 border-t border-gray-200 p-6 rounded-b-2xl flex justify-end gap-3">
                <button type="button" @click="showRejectDown=false"
                    class="px-5 py-2.5 border border-gray-300 rounded-lg font-medium hover:bg-gray-100 transition">
                    Cancel
                </button>
                <button type="submit"
                    class="px-6 py-2.5 bg-rose-600 text-white font-medium rounded-lg hover:bg-rose-700 transition shadow-lg shadow-rose-200">
                    Reject Payment
                </button>
            </div>
        </form>
    </div>
</div>

<div x-data="{ showProgress: false }">
    <!-- Floating Button -->
    <button @click="showProgress = true"
        class="fixed bottom-6 right-6 z-40 bg-black text-white p-4 rounded-full shadow-2xl hover:bg-gray-900 transition-all duration-300 hover:scale-110">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
    </button>

    <!-- Progress Modal -->
    <div x-show="showProgress" x-cloak @click.self="showProgress = false"
        class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <div @click.stop class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-auto max-h-[90vh] overflow-hidden"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90">

            <!-- Modal Header -->
            <div class="bg-black text-white px-6 py-5 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold">Event Progress Tracking</h3>
                    <p class="text-sm text-white/80 mt-1">{{ $event->name }}</p>
                </div>
                <button @click="showProgress = false" class="text-white/80 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Event Info -->
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Order ID:</span>
                        <span class="font-semibold ml-2">#{{ str_pad($event->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Event Date:</span>
                        <span class="font-semibold ml-2">{{ $event->event_date->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Progress Timeline -->
            <div class="p-6 overflow-y-auto max-h-[60vh]">
                @if($event->progress && $event->progress->count() > 0)
                <div class="space-y-4">
                    @foreach($event->progress as $progress)
                    <div class="flex gap-4">
                        <!-- Timeline Dot -->
                        <div class="flex flex-col items-center">
                            <div
                                class="w-10 h-10 rounded-full flex items-center justify-center {{ $loop->first ? 'bg-emerald-500' : 'bg-gray-300' }}">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            @if(!$loop->last)
                            <div class="w-0.5 h-full {{ $loop->first ? 'bg-emerald-300' : 'bg-gray-300' }} flex-1 my-1">
                            </div>
                            @endif
                        </div>

                        <!-- Progress Content -->
                        <div class="flex-1 pb-8">
                            <div
                                class="bg-white border-2 {{ $loop->first ? 'border-emerald-200' : 'border-gray-200' }} rounded-lg p-4">
                                <h4 class="font-bold text-lg text-gray-900">
                                    {{ ucfirst(str_replace('_', ' ', $progress->status)) }}
                                </h4>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $progress->progress_date->format('M d, Y g:i A') }}
                                </p>
                                @if($progress->details)
                                <p class="text-gray-700 mt-3">{{ $progress->details }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="text-gray-500">No progress updates yet</p>
                </div>
                @endif
            </div>

            <!-- Add New Progress Form -->
            <div class="px-6 py-6 bg-gray-50 border-t border-gray-200">
                <h4 class="font-bold text-lg mb-4 text-gray-900">Add New Update</h4>
                <form action="{{ route('admin.events.progress.store', $event) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Update</label>
                        <input type="text" name="status" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="e.g., Preparing decorations">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Details (Optional)</label>
                        <textarea name="details" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Add any additional information about this update..."></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Progress Date & Time</label>
                        <input type="datetime-local" name="progress_date" required
                            value="{{ now()->format('Y-m-d\TH:i') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <button type="submit"
                        class="w-full px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        Send Update & Notify Customer
                    </button>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                <button @click="showProgress = false"
                    class="px-6 py-2 bg-black text-white rounded-lg hover:bg-gray-900 transition">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>