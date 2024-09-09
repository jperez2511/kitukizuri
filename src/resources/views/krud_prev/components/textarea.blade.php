@props([
    'columnClass' => 'col-md-6',
    'inputClass'  => '',
    'attr'        => [],
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

<div class="{{$columnClass}}">
    <div class="form-group">
        <label>{{ $label }}</label>
        <textarea 
            {!! $attributes->merge(['class' => $inputClass]) !!} 
            cols="30" 
            rows="10">{{$value}}</textarea>
    </div>
</div>