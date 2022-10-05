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
        @csrf
        <input type="hidden" name="id" id="id" value="{{$id}}">
        <input type="hidden" name="{{$parent}}" id="{{$parent}}" value="{{$parentid}}">
        @foreach($parents as $p)
            <input type="hidden" name="{{$p['nombre']}}" id="{{$p['nombre']}}">
        @endforeach
        <div class="row">
            @foreach($campos as $campo)
                @php $nombre = array_key_exists('campoReal', $campo) ? $c['campoReal'] : $campo['campo']; @endphp

                







                
                @if($c['tipo'] != 'password')
                    <div class="{!! !empty($c['columnclass']) ? $c['columnclass'] : 'col-md-6' !!}">
                        <div class="form-group">
                            @if($c['tipo'] != 'bool' && $c['tipo'] != 'hidden' && $c['edit'] != false)
                                <label for="{{$nombre}}">{{$c['nombre']}}</label>
                            @endif
                            
                            @if((($c['tipo'] == 'string' || $c['tipo'] == 'date') && $c['edit'] == true))
                                <input type="{{$c['tipo'] == 'date' ? 'date' : 'text'}}" name="{{$nombre}}" id="{{$nombre}}" value="{{!empty($c['value']) ? $c['value'] : null}}" class="form-control {!! !empty($c['inputclass']) ? $c['inputclass'] : null !!}">
                            @elseif($c['tipo'] == 'numeric')
                                <input type="text" name="{{$nombre}}" id="{{$nombre}}" value="{{!empty($c['value']) ? number_format($c['value'])  : null}}" onfocusout="$(this).val(number_format($(this).val(), 2))" class="form-control">
                            @elseif($c['tipo'] == 'combobox')
                                <select name="{{$nombre}}" id="{{$nombre}}" class="form-control">
                                    @foreach($c['options'] as $op)
                                        <option value="{{$op[0]}}" {{!empty($c['value']) && $op[0] == $c['value'] ? 'selected' : ''}}>{{$op[1]}}</option>
                                    @endforeach
                                </select>    
                            @elseif($c['tipo'] == 'icono')
                                <button class="btn btn-default form-control" name="{{$nombre}}" data-iconset="glyphicon" {{!empty($c['value']) ? 'data-icon='.$c['value'] : null}} role="iconpicker"></button>
                            @elseif($c['tipo'] == 'textarea')
                                <textarea name="{{$nombre}}" class="form-control" id="{{$nombre}}" cols="30" rows="10">{{!empty($c['value']) ? $c['value'] : null}}</textarea>
                            @elseif($c['tipo'] == 'enum')
                                <select name="{{$nombre}}" class="form-control">
                                    @foreach($c['enumarray'] as $v)
                                        <option value="{{$v}}">{{$v}}</option>
                                    @endforeach
                                </select>
                            @elseif($c['tipo'] == 'image' || $c['tipo'] == 'file' || $c['tipo'] == 'file64')
                                <input type="file" name="{{$nombre}}" class="form-control" id="{{$nombre}}">
                            @elseif($c['tipo'] == 'bool')
                                <input type="checkbox" name="{{$nombre}}" id="{{$nombre}}" {{!empty($c['value']) ? 'checked' : null}}>
                                <label for="{{$nombre}}">{{$c['nombre']}}</label>
                            @endif 
                        </div>
                    </div>
                @else
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{$c['nombre']}}</label>
                                    <input type="password" name="{{$nombre}}" id="{{$nombre}}" onkeyup="comparar('{{$nombre}}')" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Confirmar {{$c['nombre']}}</label>
                                    <input type="password" name="" id="{{$nombre}}_2" onkeyup="comparar('{{$nombre}}')" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
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
