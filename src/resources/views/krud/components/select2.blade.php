@props([
    'json'        => '',
    'columnClass' => 'col-md-6',
    'inputClass'  => 'form-control',
    'type'        => '',
    'collection'  => [],
    'attr'        => [],
    'value'       => '',
    'label',
])

@push('css')
    <link href="{{asset('/kitukizuri/libs/select2/css/selec2.min.css')}}" rel="stylesheet" type="text/css" />
@endpush

@php
    if(!empty()$collection)) {
        $collection = json_decode($collection);
    }

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
        <select {!! $attributes->merge(['class' => $inputClass]) !!}>
            @foreach ($collection as $item)
                <option value="{{$item->id}}" {{$value == $item->id ? 'selected' : null}}>{{$item->value}}</option>
            @endforeach
        </select>
    </div>
</div>


@push('js')
    <script src="{{asset('kitukizuri/libs/select2/select2.min.js')}}"></script>
    <script>
        
    </script>
@endpush