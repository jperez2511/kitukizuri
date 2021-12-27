@extends($layout)

@section('content')

    <div class="card">
        <div class="card-header">
            <h3>Asignar Permisos</h3>
        </div>
        <div class="card-body">
            <span>Todos los usuarios con este rol tendrán asignados los permisos que se seleccionen.</span>
            <div class="col-md-12 text-right">
                <a href="javascript:void(0)" onclick="todos()" title="">Seleccionar Todos</a>
                |
                <a href="javascript:void(0)" onclick="ninguno()" title="">Ninguno</a>
            </div>
        </div>
    </div>

    <form method="post">
        {{csrf_field()}}
        <div class="row">
            @foreach($modulos as $m)
                <div class="card">
                    <div class="card-header">
                        <h4>{{$m->nombre}}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($m->modulopermiso()->get() as $mp)
                                <?php $p = $mp->permisos()->first(); ?>
                                <div class="col-3">
                                    <input class="check" id="{{$mp->modulopermisoid}}" name="permisos[]" value="{{$mp->modulopermisoid}}" type="checkbox" {{in_array($mp->modulopermisoid, $rmp) ? 'checked' : ''}}>
                                    <label for="{{$mp->modulopermisoid}}">{{$p->nombre}}</label>    
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>            
        <div class="col-md-12 text-center">
            <input type="submit" class="btn btn-success" value="Guardar">
        </div>
    </form>

@stop
@section('scripts')
    <script>
        function todos(){
            $('.check').prop({
                checked: 'true',
            })
        }
        function ninguno(){
            $('.check').removeProp('checked')
        }
    </script>
@stop
