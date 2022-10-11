@props([
    'columnClass' => 'col-md-6',
    'inputClass'  => 'form-control',
    'type'        => '',
    'collection'  => [],
    'value'       => '',
    'label',
])

@php
    $collection = json_decode($collection);
    if(empty($inputClass)) {
        $inputClass = 'form-control';
    }
@endphp

<div class="{{$columnClass}}">
    <div class="form-group">
        <label>{{ $label }}</label>
        <select {!! $attributes->merge(['class' => $inputClass]) !!}>
            @foreach ($collection as $item)
                <option value="{{$item->id}}" {{$value == $item->id ? 'selected' : null}}>{{$item->value}}</option>
            @endforeach
        </select>
    </div>
</div>

