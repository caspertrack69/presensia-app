@props([
    'as' => 'div',
])

@php
$tag = $as;
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => 'rounded-xl border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900/80']) }}>
    {{ $slot }}
</{{ $tag }}>
