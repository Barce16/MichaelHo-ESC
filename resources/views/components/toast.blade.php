@props([
'type' => 'success', // success|info|warning|error
'message' => '',
'autoCloseMs' => 3000,
])

@php
$colors = [
'success' => 'bg-green-600',
'info' => 'bg-blue-600',
'warning' => 'bg-yellow-600',
'error' => 'bg-red-600',
];
$bg = $colors[$type] ?? $colors['success'];
@endphp

<div x-data="{ show: true }" x-init="setTimeout(() => show = false, {{ $autoCloseMs }})" x-show="show"
    x-transition.origin.top.right class="fixed top-4 right-4 z-50" style="display: none;">
    <div class="flex items-start gap-3 text-white shadow-lg rounded-lg px-4 py-3 {{ $bg }}">
        <div class="font-medium">
            {{ $message }}
        </div>
        <button @click="show = false" class="ml-2 opacity-80 hover:opacity-100" aria-label="Close" title="Close">
            ✕
        </button>
    </div>
</div>