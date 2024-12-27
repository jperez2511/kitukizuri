@props([
    'columnClass'  => 'col-md-6',
    'inputClass'   => '',
    'type'         => 'text',
    'attr'         => [],
    'id'           => '',
    'dependencies' => [],
    'label'
])

@php
    $checkboxValue = 0;
    if($type != 'checkbox') {
        $inputClass = 'form-control form-control-outlined';
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
    <div class="form-group mb-3">
        <div class="form-control-wrap">
            <label class="{!! $type != 'checkbox' ? 'form-label-outlined' : null !!}" for="{{ $id }}">{{ $label }}</label>
            <input type="{{ $type }}" id="{{ $id }}" {!! $attributes->merge(['class' => $inputClass]) !!} />
            @error($attributes['name'])
                <small class="text-danger">{{$message}}</small>
            @enderror
        </div>
    </div>
</div>

@push('scripts')

    @if(!empty($dependencies))
        <script>
            
        </script>
    @endif
@endpush
