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
    <link href="{{asset('/kitukizuri/libs/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
@endpush

@php
    if(!empty($collection)) {
        $collection = json_decode($collection);
    }

    if(empty($inputClass)) {
        $inputClass = 'form-control input-select2';
    }
    
    if(!empty($attr)) {
        $attr = (array) json_decode($attr);
        $attributes = $attributes->merge($attr);
    }

    if (!empty($value)) {
        $isJson = isJson($value);
        if($isJson == true) {
            $value = json_decode($value);
        }        
    }

    function isJson($string) {
            json_decode($string);
            return json_last_error() === JSON_ERROR_NONE;
        }
    
@endphp

<div class="{{$columnClass}}">
    <div class="form-group">
        <label>{{ $label }}</label>
        <select {!! $attributes->merge(['class' => $inputClass]) !!}>
            @foreach ($collection as $item)
                @php
                    $selected = null;
                    if(is_array($value) && in_array($item->id, $value)) {
                        $selected = 'selected';
                    } else if(!is_array($value) && $value == $item->id) {
                        $selected = 'selected';
                    }        
                @endphp
                <option value="{{$item->id}}" {{$selected}}>{{$item->value}}</option>
            @endforeach
        </select>
    </div>
</div>


@push('js')
    <script src="{{asset('kitukizuri/libs/select2/js/select2.min.js')}}"></script>
    <script>
        $('.input-select2').select2();
    </script>
@endpush