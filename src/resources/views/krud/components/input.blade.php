@props([
    'columnClass' => 'col-md-6',
    'inputClass'  => '',
    'type'        => 'text',
    'attr'        => [],
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

    if(!empty($attr)) {
        $attr = (array) json_decode($attr);
        $attributes = $attributes->merge($attr);
    }

@endphp

<div class="{{$columnClass}}">
    <div class="form-group">
        <label>{{ $label }}</label>
        <input type="{{ $type }}" {!! $attributes->merge(['class' => $inputClass]) !!} />
        @error($attributes['name'])
            <small class="text-danger">{{$message}}</small>
        @enderror
    </div>
</div>


