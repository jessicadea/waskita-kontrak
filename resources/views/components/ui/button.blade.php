@props(['variant' => 'primary'])

@php
$classes = match($variant) {
    'primary' => 'bg-blue-600 text-white hover:bg-blue-700',
    'secondary' => 'bg-slate-100 text-slate-700 hover:bg-slate-200',
    'success' => 'bg-green-600 text-white hover:bg-green-700',
    'danger' => 'bg-red-600 text-white hover:bg-red-700',
    default => 'bg-blue-600 text-white',
};
@endphp

<button {{ $attributes->merge(['class' => "px-4 py-2 rounded-xl text-sm font-medium transition $classes"]) }}>
    {{ $slot }}
</button>