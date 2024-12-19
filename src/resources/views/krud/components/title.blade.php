@props([
    'columnClass' => 'col-md-6',
    'inputClass'  => '',
    'label'       => '',
    'type'        => 'h1',
])

<div class="{{ $columnClass }}">
    {!! '<'.$tipo.' class="'.$inputClass.'">'.$label.'</'.$tipo.'>' !!}
</div>