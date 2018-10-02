@extends($layout)

@section('content')
    <div class="form-group">
        <h3>{{$titulo}}</h3>
    </div>
    <form action="{{$action}}" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
        <input type="hidden" name="id" id="id" value="{{$id}}">
        <input type="hidden" name="{{$parent}}" id="{{$parent}}" value="{{$parentid}}">
        @foreach($parents as $p)
            <input type="hidden" name="{{$p['nombre']}}" id="{{$p['nombre']}}">
        @endforeach
        @foreach($campos as $c)
            <?php $nombre = array_key_exists('campoReal', $c) ? $c['campoReal'] : $c['campo']; ?>
            @if((($c['tipo'] == 'string' || $c['tipo'] == 'date') && $c['edit'] == true))
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{$c['nombre']}}</label>
                        <input type="text" name="{{$nombre}}" id="{{$nombre}}" value="{{!empty($c['value']) ? $c['value'] : null}}" class="form-control {{$c['tipo'] == 'date' ? 'date' : null}}">
                    </div>
                </div>
            @elseif($c['tipo'] == 'numeric')
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{$c['nombre']}}</label>
                        <input type="text" name="{{$nombre}}" id="{{$nombre}}" value="{{!empty($c['value']) ? number_format($c['value'])  : null}}" onfocusout="$(this).val(number_format($(this).val(), 2))" class="form-control">
                    </div>
                </div>
            @elseif($c['tipo'] == 'password')
            <div class="col-md-12">
                <div class="col-md-6" style="padding-left: 0px;">
                    <div class="form-group">
                        <label>{{$c['nombre']}}</label>
                        <input type="password" name="{{$nombre}}" id="{{$nombre}}" onkeyup="comparar('{{$nombre}}')" class="form-control">
                    </div>
                </div>
                    <div class="col-md-6" style="padding-right: 0px;">
                        <div class="form-group">
                            <label>Confirmar {{$c['nombre']}}</label>
                            <input type="password" name="" id="{{$nombre}}_2" onkeyup="comparar('{{$nombre}}')" class="form-control">
                        </div>
                    </div>
            </div>
            @elseif($c['tipo'] == 'combobox')
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{$c['nombre']}}</label>
                        <select name="{{$nombre}}" id="{{$nombre}}" class="form-control">
                            @foreach($c['options'] as $op)
                                <option value="{{$op[0]}}" {{!empty($c['value']) && $op[0] == $c['value'] ? 'selected' : ''}}>{{$op[1]}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @elseif($c['tipo'] == 'icono')
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{$c['nombre']}}</label>
                        <button class="btn btn-default form-control" name="{{$nombre}}" data-iconset="glyphicon" {{!empty($c['value']) ? 'data-icon='.$c['value'] : null}} role="iconpicker"></button>
                    </div>
                </div>
            @elseif($c['tipo'] == 'textarea')
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{$c['nombre']}}</label>
                        <textarea name="{{$nombre}}" class="form-control" id="{{$nombre}}" cols="30" rows="10">{{!empty($c['value']) ? $c['value'] : null}}</textarea>
                    </div>
                </div>
            @elseif($c['tipo'] == 'enum')
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{$c['nombre']}}</label>
                        <select name="{{$nombre}}" class="form-control">
                            @foreach($c['enumarray'] as $v)
                                <option value="{{$v}}">{{$v}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @elseif($c['tipo'] == 'image' || $c['tipo'] == 'file' || $c['tipo'] == 'file64')
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{$c['nombre']}}</label>
                        <input type="file" name="{{$nombre}}" class="form-control" id="{{$nombre}}">
                    </div>
                </div>
            @elseif($c['tipo'] == 'bool')
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{$c['nombre']}}</label><br>
                        <input type="checkbox" name="{{$nombre}}" id="{{$nombre}}" {{!empty($c['value']) ? 'checked' : null}}>
                    </div>
                </div>
            @endif
        @endforeach
        @if(empty($embed))
            <div class="col-md-12 text-right">
                <p id="msgError" class="hide" align="justify" style="color: darkred;">
                    Las contraseñas no coinciden
                </p>
                <button type="submit" class="btn btn-space btn-success" id="guardar">Guardar</button>
            </div>
        @endif
    </form>
        
    
    @if($embed)
        @foreach($embed as $e)
            {!!$e[0]!!}
        @endforeach

        <div class="panel panel-default panel-border-color panel-border-color-success">
            <div class="panel-body">
                <button class="btn btn-success btn-block" id="guardar">Guardar</button>
            </div>
        </div>
    @endif

    <script>
        $.urlParam = function(name){
            var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
            if (results==null){
                return null;
            }
            else{
                return decodeURI(results[1]) || 0;
            }
        }
    
        @foreach($parents as $p)
            $('#{{$p['nombre']}}').val($.urlParam('{{$p['value']}}'))
        @endforeach
    
        @if (!empty($template) && in_array('datetimepicker', $template))
            $('.date').datetimepicker();
        @endif
    
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
        function comparar(nombre){
            if($('#'+nombre).val() != $('#'+nombre+'_2').val()){
                $('#msgError').removeClass('hide');
                $('#guardar').hide();
            }else{
                $('#msgError').addClass('hide');
                $('#guardar').show();
            }
        }
        @if($embed)
        //haciendo submit de los campos genericos
            var array = {
                @foreach($campos as $c)
                    <?php $id = array_key_exists('campoReal', $c) ? $c['campoReal'] : $c['campo'];?>
                    @if($c['edit'] == true)
                        {{$id}} : $('#{{$id}}').val(),
                    @endif
                @endforeach
                id        : $('#id').val(),
                token : $('#_token').val()
            }
            $('#guardar').click(function(event){
                $.post('{{$action}}', array, function(data){
                    console.log(data);
                })
            })
        @endif
    </script>
@endsection
