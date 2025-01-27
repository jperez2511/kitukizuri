@push('styles')
    <style>
        .hide{
            display: none;
        }
    </style>
@endpush

<x-app-layout>
    <x-slot name="header">
        <h3 class="title">
            {{ __($titulo) }}
        </h3>
    </x-slot>

    <x-banner />

    <div class="components-preview wide-md mx-auto">
        <div class="card card-bordered card-preview">
            <div class="card-inner">    
                @if (!empty($parent))
                    <input type="hidden" name="{{$parent}}" id="{{$parent}}" value="{{$parentid}}">    
                @endif

                <div class="row">
                    @php $mergeDependencies = []; @endphp
                    @foreach($campos as $c)
                        @if ($c['edit'] === true)
                            @if($c['tipo'] != 'password' )
                                @php
                                    if (!empty($c['dependencies'])) {
                                        $mergeDependencies[] = $c['dependencies'];        
                                    }
                                @endphp

                                <x-dynamic-component 
                                    :component="$c['component']" 
                                    columnClass="{{$c['columnClass']}} {{$c['editClass']}}" 
                                    inputClass="{{$c['inputClass']}}"
                                    label="{{$c['nombre']}}"
                                    name="{!!$c['inputName']!!}"
                                    id="{{ $c['inputId'] ?? $c['inputName']}}"
                                    collection="{!! $c['collect'] !!}"
                                    type="{{$c['htmlType']}}"
                                    attr="{!! $c['htmlAttr'] !!}"
                                    value="{{$c['value']}}"
                                    dependencies="{!! json_encode($c['dependencies']) !!}"
                                />

                            @else
                                <x-dynamic-component 
                                    :component="$c['component']" 
                                    nombre="{{$c['inputName']}}"
                                    label="{{$c['nombre']}}"
                                />
                            @endif
                        @endif
                    @endforeach
                </div>
                @if(empty($embed))
                    <div class="col-md-12 text-right">
                        <p id="msgError" class="hide" align="justify" style="color: darkred;">
                            Las contraseñas no coinciden
                        </p>
                        <a href="{!! $urlBack !!}" class="btn btn-space btn-danger"> Cancelar</a>
                        <button type="submit" class="btn btn-space btn-success" id="guardar">Guardar</button>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --------------------------------
            // Almacenando valores
            // ---------------------------------

            $('#guardar').click(function (e) { 
                e.preventDefault();
                
                var data = {};

                data['_token'] = '{{ csrf_token() }}';
                data['id']     = '{{ $id }}';

                @if (!empty($parent))
                    data['{{$parent}}'] = $('#{{$parent}}').val();
                @endif

                @foreach($parents as $c)
                    data['{{$c['nombre']}}'] = $.urlParam('{{$p['value']}}');
                @endforeach

                @php $initialValues = [] @endphp
                @foreach($campos as $c)
                    @if(!in_array($c['tipo'], ['h1', 'h2', 'h3', 'h4', 'strong', 'bool']))
                        @if(!empty($c['dependencies']))
                            @foreach($c['dependencies'] as $dependency)
                                @if(!in_array($dependency['input'], $initialValues))
                                    const {{ $dependency['input'] }}_initial = $('#{{ $dependency['input'] }}-element').is(':checkbox') 
                                        ? $('#{{ $dependency['input'] }}-element').is(':checked') 
                                        : $('#{{ $dependency['input'] }}-element').val();
                                    @php $initialValues[] = $dependency['input'] @endphp
                                @endif

                                if ({{ $dependency['input'] }}_initial == '{{ $dependency['value'] }}') {
                                    data['{{$c['inputName']}}'] = $('#{{$c['inputId'] ?? $c['inputName']}}-element').val();
                                }
                            @endforeach
                        @else
                            data['{{$c['inputName']}}'] = $('#{{$c['inputId'] ?? $c['inputName']}}-element').val();
                        @endif
                    @elseif($c['tipo'] == 'bool' && empty($c['dependencies']))
                        data['{{$c['inputName']}}'] = $('#{{$c['inputId'] ?? $c['inputName']}}-element').is(':checked') ? 1 : 0;
                    @endif
                @endforeach
                
                $(this).prop('disabled', true);
                const text = $(this).text();
                $(this).empty();
                $(this).append('<i class="fa-light fa-arrows-rotate-reverse fa-spin fa-spin-reverse"></i>');

                $.post('{{ $action }}', data).done(response => {
                    location.href = '{{ $urlBack }}';
                }).fail(error => {
                    alert(error.responseJSON.message)
                    $(this).prop('disabled', false);
                    $(this).empty();
                    $(this).append(text);
                });
            });

            // --------------------------------
            // Validando campos dependientes
            // ---------------------------------

            @php $initialValues = [] @endphp

            @foreach ($mergeDependencies as $dependencyArray)
                @php
                    $condicion = '';    
                @endphp    
                @foreach ($dependencyArray as $dependency)
                    @php
                        if($condicion == '') {
                            $condicion = "$('#{$dependency['input']}-element').val() == '{$dependency['value']}'";
                        } else {
                            $condicion .= " && $('#{$dependency['input']}-element').val() == '{$dependency['value']}'";
                        }
                    @endphp
                @endforeach

                @foreach ($dependencyArray as $dependency)
                    $('#{{ $dependency['input'] }}-element').on('change', function (e) { 
                        // Verifica el valor actual del input
                        const inputValue = $(this).is(':checkbox') ? $(this).is(':checked') : $(this).val();

                        // Compara con el valor esperado
                        if ({!! $condicion !!}) {
                            $('#{{ $dependency['dependent'] }}-container').show(); // Muestra el campo dependiente
                        } else {
                            $('#{{ $dependency['dependent'] }}-element').val('');
                            $('#{{ $dependency['dependent'] }}-container').hide(); // Oculta el campo dependiente
                        }
                    });

                    $('#{{ $dependency['input'] }}-element').trigger('change');
                @endforeach
            @endforeach
        

        // --------------------------------
        // Extrae las variables por URL
        // ---------------------------------
        $.urlParam = function(name){
            var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
            if (results==null){
                return null;
            }
            else{
                return decodeURI(results[1]) || 0;
            }
        }

        // --------------------------------
        // Estableciendo los valores de los
        // valores padres. 
        // ---------------------------------
        @foreach($parents as $p)
            $('#{{$p['nombre']}}').val($.urlParam('{{$p['value']}}'))
        @endforeach

        // --------------------------------
        // estableciendo libreria para 
        // los campos tipo fecha.
        // --------------------------------
        @if (!empty($template) && in_array('datetimepicker', $template))
            $('.date').datetimepicker();
        @endif

        // --------------------------------
        // Si la URL esta disponible 
        // lista los departamentos
        // --------------------------------
        $('#departamentoid').change(function(event){
            $.get('/deptos/'+$(this).val(), {}, function(data){
                var muni = '';
                $.each(data, function(index,val){
                    muni += '<option value="'+val.municipioid+'">'+val.nombre+'</option>';
                });
                $('#municipioid').empty();
                $('#municipioid').append(muni);
            });
        });

        // --------------------------------
        // Establece el formato numerico
        // a un valor y elimina las letras 
        // ---------------------------------
        function number_format(amount, decimals) {
            amount += ''; // por si pasan un numero en vez de un string
            amount = parseFloat(amount.replace(/[^0-9\.]/g, '')); // elimino cualquier cosa que no sea numero o punto

            decimals = decimals || 0; // por si la variable no fue fue pasada

            // si no es un numero o es igual a cero retorno el mismo cero
            if (isNaN(amount) || amount === 0)
                return parseFloat(0).toFixed(decimals);

            // si es mayor o menor que cero retorno el valor formateado como numero
            amount = '' + amount.toFixed(decimals);

            var amount_parts = amount.split('.'),
                regexp = /(\d+)(\d{3})/;

            while (regexp.test(amount_parts[0]))
                amount_parts[0] = amount_parts[0].replace(regexp, '$1' + ',' + '$2');

            return amount_parts.join('.');
        }
    });
    </script>    
@endpush

</x-app-layout>

