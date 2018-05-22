@extends('layouts.app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="panel-heading panel-heading-divider">
                <h3>Asignar Permisos</h3>
                <span class="panel-subtitle">Todos los usuarios con este rol tendran asignados los permisos que se seleccionen.</span>
            </div>
            <form method="post">
                {{csrf_field()}}
                <?php $color = ['ribbon-default', 'ribbon-primary', 'ribbon-info', 'ribbon-success', 'ribbon-danger', 'ribbon-warning', 'ribbon-custom']; ?>
                <div class="col-md-12 text-right">
                    <a href="javascript:void(0)" onclick="todos()" title="">Todos</a>
                    |
                    <a href="javascript:void(0)" onclick="none()" title="">Ninguno</a>
                </div>              
                @foreach($modulos as $m)
                    <div class="col-md-12">
                        <div class="ribbon-wrapper">
                            <div class="ribbon {!! $color[rand(0,6)] !!}">{{$m->nombre}}</div>
                            @foreach($m->modulopermiso()->get() as $mp)
                                <?php $p = $mp->permisos()->first(); ?>
                                <div class="col-md-3 col-xs-3">
                                    <div class="be-checkbox be-checkbox-color {{$p->color}} inline">
                                        <input class="check" id="{{$mp->modulopermisoid}}" name="permisos[]" value="{{$mp->modulopermisoid}}" type="checkbox" {{in_array($mp->modulopermisoid, $rmp) ? 'checked' : ''}}>
                                        <label for="{{$mp->modulopermisoid}}">{{$p->nombre}}</label>
                                    </div>
                                </div>
                            @endforeach
                            <div class="clearfix"></div>
                        </div>
                        <div style="height: 10px;"></div>
                    </div>
                @endforeach
                <div class="col-md-12 text-center">
                    <input type="submit" class="btn btn-success" value="Guardar">
                </div>
            </form>
            <div class="clearfix"></div>
        </div>
    </div>
@stop
@section('scripts')
    <script>
        function todos(){
            $('.check').prop({
                checked: 'true',
            })
        }
        function none(){
            $('.check').removeProp('checked')
        }
    </script>
@stop
