@extends($layout)

@section('content')
<div class="col-lg-3 col-md-6">
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-row">
                <div class="round round-lg align-self-center round-primary"><i class="zmdi zmdi-accounts zmdi-hc-fw"></i></div>
                <div class="m-l-10 align-self-center">
                    <h3 class="m-b-0 font-light">{{$usuarios}}</h3>
                    <h5 class="text-muted m-b-0">Total Usuarios</h5></div>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-6">
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-row">
                <div class="round round-lg align-self-center round-success"><i class="zmdi zmdi-balance zmdi-hc-fw"></i></div>
                <div class="m-l-10 align-self-center">
                    <h3 class="m-b-0 font-light">{{$empresas}}</h3>
                    <h5 class="text-muted m-b-0">Total Empresas</h5></div>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-6">
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-row">
                <div class="round round-lg align-self-center round-danger"><i class="zmdi zmdi-apps zmdi-hc-fw"></i></div>
                <div class="m-l-10 align-self-center">
                    <h3 class="m-b-0 font-light">{{$modulos}}</h3>
                    <h5 class="text-muted m-b-0">Total Modulos</h5></div>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-6">
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-row">
                <div class="round round-lg align-self-center round-warning"><i class="zmdi zmdi-traffic zmdi-hc-fw"></i></div>
                <div class="m-l-10 align-self-center">
                    <h3 class="m-b-0 font-light">{{$roles}}</h3>
                    <h5 class="text-muted m-b-0">Total Roles</h5></div>
            </div>
        </div>
    </div>
</div>
@stop