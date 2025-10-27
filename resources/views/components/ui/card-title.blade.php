@props(['level' => 'h3'])

@php
$tag = in_array($level, ['h1','h2','h3','h4','h5','h6']) ? $level : 'h3';
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => 'text-lg font-semibold leading-none tracking-tight text-slate-900 dark:text-slate-100']) }}>
    {{ $slot }}
</{{ $tag }}>
