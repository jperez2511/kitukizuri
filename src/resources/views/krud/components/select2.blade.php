@props([
    'json'         => '',
    'columnClass'  => 'col-md-6',
    'inputClass'   => 'form-select',
    'type'         => '',
    'name'         => '',
    'id'           => '',
    'collection'   => [],
    'attr'         => [],
    'dependendies' => [],
    'value'        => null,
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
        $value = json_decode($value);
    }
    
@endphp

<div class="{{$columnClass}}" id="{{$id}}-container">
    <div class="form-group mb-3">
        <label>{{ $label }}</label>
        <div class="form-control-wrap">
            <select {!! $attributes->merge(['class' => $inputClass]) !!} id="{{ $id }}-element" name="{{ $name }}">
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
                placeholder: "{{ __('select an option') }}",
                allowClear: false,
                width: "resolve",
                theme: "default"
            });
        });
    </script>
@endpush
