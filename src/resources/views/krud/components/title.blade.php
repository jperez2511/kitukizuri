@props([
    'columnClass'  => 'col-md-6',
    'inputClass'   => '',
    'label'        => '',
    'name'        => '',
    'type'         => 'h1',
    'dependencies' => []
])

<div class="{{ $columnClass }}" id="{{ $name }}">
    {!! '<'.$type.' class="'.$inputClass.'">'.$label.'</'.$type.'>' !!}
</div>

@if (!empty($dependencies) && $dependencies != 'null')
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                @php
                    $dependencies = json_decode($dependencies);
                    $typeArray    = typeArray($dependencies);
                    dd($name);
                @endphp

                @if ($typeArray == 5) 
                    @foreach ($dependencies as $dependency)
                        $('#{{ $dependency->input }}').change(function (e) { 
                            console.log('Cambio en {{ $name }}');
                        });
                    @endforeach
                @endif

                
            });
        </script>
    @endpush


@endif