@props(['variant' => 'primary'])

@php
$classes = match($variant) {
    'primary' => 'bg-green-900 hover:bg-green-800 text-white',
    'secondary' => 'bg-gray-300 hover:bg-gray-400 text-gray-800',
    'danger' => 'bg-red-600 hover:bg-red-700 text-white',
    default => 'bg-green-900 hover:bg-green-800 text-white',
};
@endphp

<button {{ $attributes->merge(['class' => "px-6 py-2 text-sm font-medium rounded-lg transition-colors $classes"]) }}>
    {{ $slot }}
</button>
