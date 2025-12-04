<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.events.show', $event) }}"
                        class="inline-flex items-center gap-1 text-gray-500 hover:text-gray-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div>
                        <h2 class="font-bold text-2xl text-gray-800">Post-Event Expenses</h2>
                        <p class="text-sm text-gray-500 mt-1">{{ $event->name }} • {{ $event->event_date->format('M d,
                            Y') }}</p>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-500">Total Expenses</div>
                <div class="text-2xl font-bold text-rose-600">₱{{ number_format($totalExpenses, 2) }}</div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Messages --}}
            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            <div class="grid lg:grid-cols-3 gap-6">
                {{-- Left Column: Add Expense Form --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 sticky top-6">
                        <div class="bg-gradient-to-r from-rose-500 to-pink-600 px-6 py-4 rounded-t-xl">
                            <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add Expense
                            </h3>
                        </div>

                        <form method="POST" action="{{ route('admin.events.expenses.store', $event) }}"
                            enctype="multipart/form-data" class="p-6 space-y-4">
                            @csrf

                            {{-- Description --}}
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                    Description <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="description" id="description" required
                                    value="{{ old('description') }}" placeholder="e.g., Extra flowers for centerpiece"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500">
                                @error('description')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Amount --}}
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">
                                    Amount <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">₱</span>
                                    <input type="number" name="amount" id="amount" required step="0.01" min="0.01"
                                        value="{{ old('amount') }}" placeholder="0.00"
                                        class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500">
                                </div>
                                @error('amount')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Category --}}
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">
                                    Category
                                </label>
                                <select name="category" id="category"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500">
                                    <option value="">Select category...</option>
                                    @foreach(\App\Models\EventExpense::getCategories() as $key => $label)
                                    <option value="{{ $key }}" {{ old('category')==$key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Expense Date --}}
                            <div>
                                <label for="expense_date" class="block text-sm font-medium text-gray-700 mb-1">
                                    Date
                                </label>
                                <input type="date" name="expense_date" id="expense_date"
                                    value="{{ old('expense_date', now()->toDateString()) }}"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500">
                            </div>

                            {{-- Notes --}}
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                                    Notes
                                </label>
                                <textarea name="notes" id="notes" rows="2" placeholder="Additional details..."
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500">{{ old('notes') }}</textarea>
                            </div>

                            {{-- Receipt Image --}}
                            <div>
                                <label for="receipt_image" class="block text-sm font-medium text-gray-700 mb-1">
                                    Receipt (Optional)
                                </label>
                                <input type="file" name="receipt_image" id="receipt_image" accept="image/*"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100">
                                <p class="text-xs text-gray-500 mt-1">Max 5MB. JPG, PNG, GIF</p>
                            </div>

                            {{-- Submit Button --}}
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-rose-600 text-white font-semibold rounded-lg hover:bg-rose-700 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add Expense
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Right Column: Expenses List --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Summary Cards --}}
                    @if($expenses->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                            <div class="text-sm text-gray-500 mb-1">Total Expenses</div>
                            <div class="text-xl font-bold text-gray-900">₱{{ number_format($totalExpenses, 2) }}</div>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                            <div class="text-sm text-gray-500 mb-1">Total Items</div>
                            <div class="text-xl font-bold text-gray-900">{{ $expenses->count() }}</div>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                            <div class="text-sm text-gray-500 mb-1">Highest</div>
                            <div class="text-xl font-bold text-gray-900">₱{{ number_format($expenses->max('amount'), 2)
                                }}</div>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                            <div class="text-sm text-gray-500 mb-1">Average</div>
                            <div class="text-xl font-bold text-gray-900">₱{{ number_format($expenses->avg('amount'), 2)
                                }}</div>
                        </div>
                    </div>

                    {{-- Category Breakdown --}}
                    @if($expensesByCategory->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h4 class="font-semibold text-gray-800 mb-4">Breakdown by Category</h4>
                        <div class="space-y-3">
                            @foreach($expensesByCategory as $category => $amount)
                            @php
                            $percentage = $totalExpenses > 0 ? ($amount / $totalExpenses) * 100 : 0;
                            $categoryLabel = \App\Models\EventExpense::getCategories()[$category] ?? ucfirst($category
                            ?? 'Uncategorized');
                            @endphp
                            <div>
                                <div class="flex items-center justify-between text-sm mb-1">
                                    <span class="text-gray-600">{{ $categoryLabel }}</span>
                                    <span class="font-medium text-gray-900">₱{{ number_format($amount, 2) }}</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2">
                                    <div class="bg-rose-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @endif

                    {{-- Expenses List --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Expense Records
                            </h3>
                        </div>

                        @if($expenses->count() > 0)
                        <div class="divide-y divide-gray-100">
                            @foreach($expenses as $expense)
                            <div class="p-4 hover:bg-gray-50 transition"
                                x-data="{ editing: false, showReceipt: false }">
                                {{-- View Mode --}}
                                <div x-show="!editing" class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-1">
                                            <h4 class="font-medium text-gray-900">{{ $expense->description }}</h4>
                                            @if($expense->category)
                                            <span
                                                class="px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-600 rounded-full">
                                                {{ $expense->category_label }}
                                            </span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-4 text-sm text-gray-500">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                {{ $expense->expense_date?->format('M d, Y') ?? 'No date' }}
                                            </span>
                                            @if($expense->addedBy)
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                {{ $expense->addedBy->name }}
                                            </span>
                                            @endif
                                        </div>
                                        @if($expense->notes)
                                        <p class="text-sm text-gray-600 mt-2">{{ $expense->notes }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <div class="text-lg font-bold text-rose-600">₱{{ number_format($expense->amount,
                                            2) }}</div>
                                        <div class="flex items-center gap-1 mt-2">
                                            @if($expense->receipt_image)
                                            <button @click="showReceipt = true"
                                                class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                                title="View Receipt">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </button>
                                            @endif
                                            <button @click="editing = true"
                                                class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                                                title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <form method="POST"
                                                action="{{ route('admin.events.expenses.destroy', [$event, $expense]) }}"
                                                onsubmit="return confirm('Delete this expense?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                                    title="Delete">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                {{-- Edit Mode --}}
                                <div x-show="editing" x-cloak>
                                    <form method="POST"
                                        action="{{ route('admin.events.expenses.update', [$event, $expense]) }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="grid md:grid-cols-2 gap-4">
                                            <div>
                                                <label
                                                    class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                                                <input type="text" name="description"
                                                    value="{{ $expense->description }}" required
                                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-xs font-medium text-gray-600 mb-1">Amount</label>
                                                <input type="number" name="amount" value="{{ $expense->amount }}"
                                                    required step="0.01"
                                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-xs font-medium text-gray-600 mb-1">Category</label>
                                                <select name="category"
                                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500">
                                                    <option value="">Select...</option>
                                                    @foreach(\App\Models\EventExpense::getCategories() as $key =>
                                                    $label)
                                                    <option value="{{ $key }}" {{ $expense->category == $key ?
                                                        'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Date</label>
                                                <input type="date" name="expense_date"
                                                    value="{{ $expense->expense_date?->format('Y-m-d') }}"
                                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500">
                                            </div>
                                            <div class="md:col-span-2">
                                                <label
                                                    class="block text-xs font-medium text-gray-600 mb-1">Notes</label>
                                                <textarea name="notes" rows="2"
                                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500">{{ $expense->notes }}</textarea>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-end gap-2 mt-4">
                                            <button type="button" @click="editing = false"
                                                class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                                Cancel
                                            </button>
                                            <button type="submit"
                                                class="px-4 py-2 text-sm bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition">
                                                Save Changes
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                {{-- Receipt Modal --}}
                                @if($expense->receipt_image)
                                <div x-show="showReceipt" x-cloak @click.self="showReceipt = false"
                                    class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/70">
                                    <div
                                        class="relative max-w-3xl w-full bg-white rounded-xl shadow-2xl overflow-hidden">
                                        <button @click="showReceipt = false"
                                            class="absolute top-4 right-4 z-10 bg-white rounded-full p-2 shadow-lg hover:bg-gray-100 transition">
                                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                        <div class="bg-gray-100 px-6 py-4 border-b">
                                            <h3 class="font-semibold text-gray-900">Receipt for: {{
                                                $expense->description }}</h3>
                                            <p class="text-sm text-gray-500">₱{{ number_format($expense->amount, 2) }}
                                            </p>
                                        </div>
                                        <div class="p-4 bg-gray-50">
                                            <img src="{{ asset('storage/' . $expense->receipt_image) }}" alt="Receipt"
                                                class="w-full h-auto rounded-lg"
                                                style="max-height: 70vh; object-fit: contain;">
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @else
                        {{-- Empty State --}}
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="text-gray-500 font-medium mb-2">No expenses recorded yet</p>
                            <p class="text-gray-400 text-sm">Use the form to add post-event expenses</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</x-app-layout>