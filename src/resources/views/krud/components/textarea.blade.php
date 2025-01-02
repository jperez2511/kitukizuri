@props([
    'columnClass'  => 'col-md-6',
    'inputClass'   => '',
    'name'         => '',
    'id'           => '',
    'attr'         => [],
    'dependencies' => [],
    'label',
    'value' => ''
])

@php
    if(empty($inputClass)) {
        $inputClass = 'form-control';
    }
    
    if(!empty($attr)) {
        $attr = (array) json_decode($attr);
        $attributes = $attributes->merge($attr);
    }

@endphp

<div class="{{$columnClass}}" id="{{$id}}-container">
    <div class="form-group">
        <label>{{ $label }}</label>
        <textarea 
            {!! $attributes->merge(['class' => $inputClass]) !!} 
            cols="30" 
            rows="10"
            id="{{ $id }}-element">{{ $value }}</textarea>
    </div>
</div>