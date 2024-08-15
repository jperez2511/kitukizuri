@props(['active'])

@php
$classes = ($active ?? false) ? 'text-info' : '';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
