@props([
    'columnClass' => 'col-md-6',
    'inputClass'  => '',
    'label',
    'value' => ''
])

@php
    if(empty($inputClass)) {
        $inputClass = 'form-control';
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