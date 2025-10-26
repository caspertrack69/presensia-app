@props([
    'variant' => 'default',
])

@php
$base = 'inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2';
$variants = [
    'default' => 'border-transparent bg-primary-100 text-primary-700 focus:ring-primary-200',
    'success' => 'border-transparent bg-emerald-100 text-emerald-700 focus:ring-emerald-200',
    'warning' => 'border-transparent bg-amber-100 text-amber-700 focus:ring-amber-200',
    'danger' => 'border-transparent bg-red-100 text-red-700 focus:ring-red-200',
    'muted' => 'border-transparent bg-slate-100 text-slate-700 focus:ring-slate-200',
];
$classes = $base.' '.($variants[$variant] ?? $variants['default']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
