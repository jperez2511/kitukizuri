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

                @foreach($parents as $p)
                    <input type="hidden" name="{{$p['nombre']}}" id="{{$p['nombre']}}" value="{{$parentid}}">
                @endforeach

                <div class="row">
                    @foreach($campos as $c)
                        @if ($c['edit'] === true)
                            @if($c['tipo'] != 'password')
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
                data['{{$c['inputName']}}'] = $('#{{$c['inputName']}}').val();
            @endforeach

            @foreach($campos as $c)
                @if($c['tipo'] != 'password')
                    @if($c['component'] == 'select2')
                        data['{{$c['inputName']}}'] = $('#{{$c['inputName']}}').val();
                    @else
                        data['{{$c['inputName']}}'] = $('#{{$c['inputName']}}').val();
                    @endif
                @endif
            @endforeach
            
            $.post('{{ $action }}', data).done(response => {
                location.href = '{{ $urlBack }}';
            }).fail(error => alert(error.responseJSON.message));
        });

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

