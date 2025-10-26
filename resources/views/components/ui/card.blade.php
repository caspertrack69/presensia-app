@props([
    'as' => 'div',
])

@php
$tag = $as;
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => 'rounded-xl border border-slate-200 bg-white shadow-soft']) }}>
    {{ $slot }}
</{{ $tag }}>
