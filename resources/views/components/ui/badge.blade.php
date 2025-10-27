@props([
    'variant' => 'default',
])

@php
$base = 'inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-100 dark:focus:ring-offset-slate-900';
$variants = [
    'default' => 'border-transparent bg-blue-100 text-blue-700 focus:ring-blue-200 dark:bg-blue-500/20 dark:text-blue-200 dark:focus:ring-blue-400/40',
    'success' => 'border-transparent bg-emerald-100 text-emerald-700 focus:ring-emerald-200 dark:bg-emerald-500/20 dark:text-emerald-200 dark:focus:ring-emerald-400/40',
    'warning' => 'border-transparent bg-amber-100 text-amber-700 focus:ring-amber-200 dark:bg-amber-500/20 dark:text-amber-200 dark:focus:ring-amber-400/40',
    'danger' => 'border-transparent bg-red-100 text-red-700 focus:ring-red-200 dark:bg-rose-500/20 dark:text-rose-200 dark:focus:ring-rose-400/40',
    'muted' => 'border-transparent bg-slate-100 text-slate-700 focus:ring-slate-200 dark:bg-slate-700/40 dark:text-slate-200 dark:focus:ring-slate-500/40',
];
$classes = $base.' '.($variants[$variant] ?? $variants['default']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
