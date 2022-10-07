@props([
    'columnClass' => 'col-md-6',
    'inputClass'  => '',
    'label'
    'value' => ''
])

<div class="{{$columnClass}}">
    <div class="form-group">
        <label>{{ $label }}</label>
        <textarea 
            name="{{$nombre}}" 
            {!! $attributes->merge(['class' => $inputClass]) !!} 
            cols="30" 
            rows="10">{{$value}}</textarea>
    </div>
</div>