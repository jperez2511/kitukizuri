@props(['type' => 'text'])

<input type="{{ $type }}" class="{!! $attributes->merge(['class' => 'form-control']) !!}">