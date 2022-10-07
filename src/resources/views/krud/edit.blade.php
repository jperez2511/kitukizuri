@extends($layout)

@section('content')
    <style>
        .hide {
            display: none;
        }
    </style>
     @if (!empty(Session::get('type')) && !empty(Session::get('msg')))
        <div class="alert alert-{{Session::get('type')}}" role="alert">
            {{Session::get('msg')}}
        </div>    
    @endif
    <form action="{{$action}}" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
        <input type="hidden" name="id" id="id" value="{{$id}}">
        <input type="hidden" name="{{$parent}}" id="{{$parent}}" value="{{$parentid}}">
        @foreach($parents as $p)
            <input type="hidden" name="{{$p['nombre']}}" id="{{$p['nombre']}}">
        @endforeach
        <div class="row">
            @foreach($campos as $c)
                @php 
                    $nombre        = array_key_exists('campoReal', $c) ? $c['campoReal'] : $c['campo'];
                    $componentName = "krud-".$c['input'];
                    $columnClass   = !empty($c['columnclass']) ? $c['columnclass'] : 'col-md-6';
                    $inputClass    = !empty($c['inputClass']) ? $c['inputClass'] : null ;
                    $editClass     = $c['edit'] == false ? 'hide' : '';
                    $collection    = json_encode(!empty($c['collect']) ? $c['collect'] : []);
                    $value         = !empty($c['value']) ? $c['value'] : null;
                @endphp
                
                @if($c['tipo'] != 'password' && $c['edit'] == true)
                    <x-dynamic-component 
                        :component="$componentName" 
                        columnClass="{{$columnClass}} {{$editClass}}" 
                        inputClass="{{$inputClass}}"
                        label="{{$c['nombre']}}"
                        name="{{$nombre}}"
                        id="{{$nombre}}"
                        collection="{!! $collection !!}"
                        type="{{$c['htmlType']}}"
                        value="{{$value}}"
                    />
                @else
                    <x-dynamic-component 
                        :component="$componentName" 
                        nombre="{{$nombre}}"
                    />
                @endif
            @endforeach
        </div>
        @if(empty($embed))
            <div class="col-md-12 text-right">
                <p id="msgError" class="hide" align="justify" style="color: darkred;">
                    Las contraseñas no coinciden
                </p>
                <a href="#" class="btn btn-space btn-danger" onclick="history.back()"> Cancelar</a>
                <button type="submit" class="btn btn-space btn-success" id="guardar">Guardar</button>
            </div>
        @endif
    </form>
@endsection
@section('scripts')
    <script>
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
        // Compara dos valores se utiliza
        // para validar las contraseñas
        // ---------------------------------
        function comparar(nombre){
            if($('#'+nombre).val() != $('#'+nombre+'_2').val()){
                $('#msgError').removeClass('hide');
                $('#guardar').hide();
            }else{
                $('#msgError').addClass('hide');
                $('#guardar').show();
            }
        }

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
    </script>
@endsection
