@props([
    'json'        => '',
    'columnClass' => 'col-md-6',
    'inputClass'  => 'form-select',
    'type'        => '',
    'collection'  => [],
    'attr'        => [],
    'value'       => null,
    'label',
])

@php
    if(!empty($collection)) {
        $collection = json_decode($collection);
    }

    if(empty($inputClass)) {
        $inputClass = 'form-select js-select2';
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
    <div class="form-group mb-3">
        <label>{{ $label }}</label>
        <div class="form-control-wrap">
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
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.js-select2').select2({
                placeholder: "Selecciona una opción",
                allowClear: false,
                width: "resolve",
                theme: "default"
            });
        });
    </script>
@endpush
