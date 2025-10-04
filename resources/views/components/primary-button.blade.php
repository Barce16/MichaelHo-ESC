<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-900'
    ]) }}>
    {{ $slot }}
</button>