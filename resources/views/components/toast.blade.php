<!-- Success Toast -->
@if(session('success'))
<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition.origin.top.right
    class="fixed top-4 right-4 z-50" style="display: none;">
    <div class="flex items-start gap-3 text-white shadow-lg rounded-lg px-4 py-3 bg-green-600">
        <div class="font-medium">{{ session('success') }}</div>
        <button @click="show = false" class="ml-2 opacity-80 hover:opacity-100">✕</button>
    </div>
</div>
@endif

<!-- Error Toast -->
@if(session('error'))
<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 7000)" x-show="show" x-transition.origin.top.right
    class="fixed top-4 right-4 z-50" style="display: none;">
    <div class="flex items-start gap-3 text-white shadow-lg rounded-lg px-4 py-3 bg-red-600">
        <div class="font-medium">{{ session('error') }}</div>
        <button @click="show = false" class="ml-2 opacity-80 hover:opacity-100">✕</button>
    </div>
</div>
@endif

<!-- Validation Errors Toast -->
@if($errors->any())
<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 8000)" x-show="show" x-transition.origin.top.right
    class="fixed top-4 right-4 z-50 max-w-md" style="display: none;">
    <div class="text-white shadow-lg rounded-lg px-4 py-3 bg-red-600">
        <div class="flex items-start justify-between gap-3">
            <div>
                <div class="font-semibold mb-2">Please fix the following errors:</div>
                <ul class="text-sm space-y-1">
                    @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button @click="show = false" class="opacity-80 hover:opacity-100">✕</button>
        </div>
    </div>
</div>
@endif