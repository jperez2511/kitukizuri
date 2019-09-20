@extends($layout)

@section('content')
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="panel-heading panel-heading-divider">
                Modulos de empresa
                <span class="panel-subtitle">
                    los modulos seleccionados seran los unicos a los que tiene acceso la empresa.
                </span>
            </div>
            <form action="{{route('moduloempresas.store')}}" method="post">
                {{csrf_field()}}
                <input type="hidden" name="empresa" value="{{$empresa}}">
                @foreach($modulos as $m)
                    <div class="col-md-3">
                        <div class="be-checkbox be-checkbox-color has-success inline">
                            <input id="{{$m->moduloid}}" name="modulos[]" value="{{$m->moduloid}}" type="checkbox" {{in_array($m->moduloid, $moduloEmpresas) ? 'checked' : ''}}>
                            <label for="{{$m->moduloid}}">{{$m->nombre}}</label>
                        </div>
                    </div>
                @endforeach
                <div class="clearfix"></div>
                <div style=" height: 20px;"></div>
                <div class="col-md-12 text-center">
                    <input type="submit" class="btn btn-success" value="Guardar">
                </div>
                
            </form>
        </div>
    </div>
@endsection
