@props([
    'columnClass'  => 'col-md-6',
    'inputClass'   => 'form-control',
    'name'         => '',
    'id'           => '',
    'collection'   => [],
    'attr'         => [],
    'dependencies' => [],
    'type'         => '',
    'value'        => '',
    'label',
])

@php
    $collection = json_decode($collection);
    if(empty($inputClass)) {
        $inputClass = 'form-control';
    }
    
    if(!empty($attr)) {
        $attr = (array) json_decode($attr);
        $attributes = $attributes->merge($attr);
    }
@endphp

<div class="{{$columnClass}}" id="{{$id}}">
    <div class="form-group mb-3">
        <label>{{ $label }}</label>
        <select {!! $attributes->merge(['class' => $inputClass]) !!} id="{{ $name }}">
            @foreach ($collection as $item)
                <option value="{{$item->id}}" {{$value == $item->id ? 'selected' : null}}>{{$item->value}}</option>
            @endforeach
        </select>
    </div>
</div>

