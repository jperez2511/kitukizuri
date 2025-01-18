@props([
    'json'         => '',
    'columnClass'  => 'col-md-6',
    'inputClass'   => 'form-select',
    'type'         => '',
    'name'         => '',
    'id'           => '',
    'collection'   => [],
    'attr'         => [],
    'dependencies' => [],
    'value'        => null,
    'label',
])

@php
    if(!empty($collection)) {
        $collection = json_decode($collection);
    }

    if(!empty($attr)) {
        $attr = (array) json_decode($attr);
        $attributes = $attributes->merge($attr);
    }
    
@endphp

<x-dynamic-component 
    component="krud-input" 
    columnClass="{{$columnClass}} {{$editClass}}" 
    inputClass="{{$inputClass}}"
    label="{{__('Start Date')}}"
    name="{!!$name!!}"
    id="{{$id}}-startDate"
    collection="{!! $collection !!}"
    type="date"
    attr="{!! $attr !!}"
    value="{{$value}}"
    dependencies="{!! json_encode($dependencies) !!}"
/>

<x-dynamic-component 
    component="krud-input" 
    columnClass="{{$columnClass}} {{$editClass}}" 
    inputClass="{{$inputClass}}"
    label="{{__('End Date')}}"
    name="{!!$name!!}"
    id="{{$id}}-endDate"
    collection="{!! $collection !!}"
    type="date"
    attr="{!! $attr !!}"
    value="{{$value}}"
    dependencies="{!! json_encode($dependencies) !!}"
/>

@push('scripts')
    <script>
        //validaciones entre fechas
        document.addEventlistener('change', function() {
            let startDate = document.getElementById('{{$id}}-startDate-element').value;
            let endDate = document.getElementById('{{$id}}-endDate-element').value;
            if (startDate > endDate) {
                alert('La fecha de inicio no puede ser mayor a la fecha de fin');
                document.getElementById('{{$id}}-startDate').value = '';
            } else {
                console.log(startDate, endDate);
            }
        });
    </script>
@endpush
