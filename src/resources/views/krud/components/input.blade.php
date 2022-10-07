@props([
    'columnClass' => 'col-md-6',
    'inputClass'  => '',
    'type'        => 'text',
    'label'
])

@php
    if($type != 'checkbox') {
        $inputClass = 'form-control';
    }
    
    if($type == 'hidden') {
        $columnClass = 'hide';
    }
@endphp

<div class="{{$columnClass}}">
    <div class="form-group">
        <label>{{ $label }}</label>
        <input type="{{ $type }}" {!! $attributes->merge(['class' => $inputClass]) !!} />
    </div>
</div>


