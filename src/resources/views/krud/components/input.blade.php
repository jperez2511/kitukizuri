@props(['type' => 'text', 'name'])

<div class="form-group">
    <label>{{ $name }}</label>
    <input type="{{ $type }}" {!! $attributes->merge(['class' => 'form-control']) !!}>
</div>

