@props(['type' => 'text'])

<input type="{{ $type }}" {!! $attributes->merge(['class' => 'form-control']) !!}>