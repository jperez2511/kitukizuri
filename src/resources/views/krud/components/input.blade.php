@props([
    'columnClass' => 'col-md-6',
    'inputClass'  => '',
    'type'        => 'text',
    'label'
])
<div class="{{$columnClass}}">
    <div class="form-group">
        <label>{{ $label }}</label>
        <input type="{{ $type }}" {!! $attributes->merge(['class' => 'form-control']) !!} />
    </div>
</div>


