@props([
    'columnClass' => 'col-md-6',
    'inputClass'  => '',
    'type'        => 'text',
    'label'
])

@php
    $checkboxValue = 0;
    if($type != 'checkbox') {
        $inputClass = 'form-control';
    } else {
        if ($attributes['value'] == 1 || $attributes['value'] == 'on') {
            $attributes['checked'] = true;
        }
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


