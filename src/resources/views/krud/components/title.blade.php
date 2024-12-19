@props([
    'columnClass' => 'col-md-6',
    'inputClass'  => '',
    'label'       => '',
    'type'        => 'h1',
])

<div class="{{ $columnClass }}">
    {!! '<'.$type.' class="'.$inputClass.'">'.$label.'</'.$type.'>' !!}
</div>