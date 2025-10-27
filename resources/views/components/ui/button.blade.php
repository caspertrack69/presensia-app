@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'as' => 'button',
])

@php
$base = 'inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-offset-slate-100 dark:focus-visible:ring-offset-slate-900 disabled:pointer-events-none disabled:opacity-60';
$variants = [
    'primary' => 'bg-blue-600 text-white hover:bg-blue-700 focus-visible:ring-blue-500 dark:bg-blue-500 dark:text-slate-950 dark:hover:bg-blue-400 dark:focus-visible:ring-blue-300',
    'secondary' => 'bg-slate-900 text-white hover:bg-slate-700 focus-visible:ring-slate-400 dark:bg-slate-200 dark:text-slate-900 dark:hover:bg-slate-100 dark:focus-visible:ring-slate-300',
    'outline' => 'border border-slate-200 bg-white text-slate-900 hover:bg-slate-100 focus-visible:ring-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800 dark:focus-visible:ring-slate-600',
    'ghost' => 'text-slate-700 hover:bg-slate-100 focus-visible:ring-slate-200 dark:text-slate-200 dark:hover:bg-slate-800 dark:focus-visible:ring-slate-700',
    'destructive' => 'bg-red-600 text-white hover:bg-red-700 focus-visible:ring-red-500 dark:bg-rose-500 dark:text-white dark:hover:bg-rose-600 dark:focus-visible:ring-rose-400',
];
$sizes = [
    'sm' => 'h-9 px-3',
    'md' => 'h-10 px-4',
    'lg' => 'h-11 px-6 text-base',
    'icon' => 'h-10 w-10',
];
$classes = $base.' '.($variants[$variant] ?? $variants['primary']).' '.($sizes[$size] ?? $sizes['md']);
$tag = in_array($as, ['a', 'button', 'span']) ? $as : 'button';
@endphp

@if ($tag === 'a')
    <a {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes, 'type' => $type]) }}>
        {{ $slot }}
    </button>
@endif
