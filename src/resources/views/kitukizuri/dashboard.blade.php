@extends($layout)

@section('content')
<div class="col-lg-3 col-md-3">
    <div class="card card-inverse card-primary">
        <div class="card-body">
            <div class="d-flex">
                <div class="m-r-20 align-self-center">
                    <h1 class="text-white"><i class="zmdi zmdi-accounts zmdi-hc-fw"></i></h1>
                </div>
                <div>
                    <h3 class="card-title">Total de usuarios</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="font-light text-white">{{$usuarios}}</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-3 col-md-3">
    <div class="card card-inverse card-success">
        <div class="card-body">
            <div class="d-flex">
                <div class="m-r-20 align-self-center">
                    <h1 class="text-white"><i class="zmdi zmdi-balance zmdi-hc-fw"></i></h1>
                </div>
                <div>
                    <h3 class="card-title">Total de Empresas</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="font-light text-white">{{$empresas}}</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-3 col-md-3">
    <div class="card card-inverse card-danger">
        <div class="card-body">
            <div class="d-flex">
                <div class="m-r-20 align-self-center">
                    <h1 class="text-white"><i class="zmdi zmdi-apps zmdi-hc-fw"></i></h1>
                </div>
                <div>
                    <h3 class="card-title">Total de Modulos</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="font-light text-white">{{$modulos}}</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-3 col-md-3">
    <div class="card card-inverse card-warning">
        <div class="card-body">
            <div class="d-flex">
                <div class="m-r-20 align-self-center">
                    <h1 class="text-white"><i class="zmdi zmdi-traffic zmdi-hc-fw"></i></h1>
                </div>
                <div>
                    <h3 class="card-title">Total de Roles</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="font-light text-white">{{$roles}}</h1>
                </div>
            </div>
        </div>
    </div>
</div>
@stop