@props([
    'columnClass'  => 'col-md-6',
    'inputClass'   => '',
    'label'        => '',
    'name'         => '',
    'id'           => '',
    'type'         => 'h1',
    'dependencies' => []
])

<div class="{{ $columnClass }}" id="{{ $name }}">
    {!! '<'.$type.' class="'.$inputClass.'">'.$label.'</'.$type.'>' !!}
</div>

@endif