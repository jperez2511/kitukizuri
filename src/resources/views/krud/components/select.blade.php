@props([
    'columnClass' => 'col-md-6',
    'inputClass'  => 'form-control',
    'type' => '',
    'collection'  => [],
    'label',
])

@php
    $collection = json_decode($collection);
@endphp

<div class="{{$columnClass}}">
    <div class="form-group">
        <label>{{ $label }}</label>
        <select {!! $attributes->merge(['class' => $inputClass]) !!}>
            @foreach ($collection as $item)
                <option value="{{$item->id}}">{{$item->value}}</option>
            @endforeach
        </select>
    </div>
</div>

