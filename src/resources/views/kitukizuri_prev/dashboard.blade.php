@extends($layout)

@section('content')

<div class="col-6">
    <div class="card">
        <div class="card-body mt-3 mb-3 text-center" onmouseover="pulse($(this))" onmouseout="removePulse($(this))">
            <a href="{{ route('usuarios.index') }}" style="color:#7c8a96;">
                <i id="users" class="fa-duotone fa-users fa-2xl mb-4"></i><br>
                <strong>Usuarios</strong>
            </a>
        </div>
    </div>
</div>

<div class="col-6">
    <div class="card">
        <div class="card-body mt-3 mb-3 text-center" onmouseover="pulse($(this))" onmouseout="removePulse($(this))">
            <a href="{{ route('empresas.index') }}" style="color:#7c8a96;">
                <i class="fa-duotone fa-building fa-2xl mb-4"></i><br>
                <strong>Empresas</strong>
            </a>
        </div>
    </div>
</div>

<div class="col-6">
    <div class="card">
        <div class="card-body mt-3 mb-3 text-center" onmouseover="pulse($(this))" onmouseout="removePulse($(this))">
            <a href="{{ route('roles.index') }}" style="color:#7c8a96;">
                <i class="fa-duotone fa-user-shield fa-2xl mb-4"></i><br>
                <strong>Roles de usuarios</strong>
            </a>
        </div>
    </div>
</div>

<div class="col-6">
    <div class="card">
        <div class="card-body mt-3 mb-3 text-center" onmouseover="pulse($(this))" onmouseout="removePulse($(this))">
            <a href="#" style="color:#7c8a96;" id="avanzado">
                <i class="fa-duotone fa-window fa-2xl mb-4"></i><br>
                <strong>Avanzado</strong>
            </a>
        </div>
    </div>
</div>

<div class="modal fade" id="avanzadosModal" tabindex="-1" aria-labelledby="avanzadosModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="avanzadosModalLabel">{{ __('Opciones avanzadas') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group text-center">
                            <a href="{{ route('database.index') }}" class="btn btn-primary btn-icon-split btn-sm btn-block">
                                <span class="icon">
                                    <i class="fa-light fa-database"></i>
                                </span>
                                <span class="text">{{ __('Base de datos') }}</span>
                            </a>
                        </div>
                    </div> 
                    <div class="col-md-6">
                        <a href="{{ route('logs.index') }}" class="btn btn-tertiary btn-icon-split btn-sm btn-block">
                            <span class="icon">
                                <i class="fa-light fa-receipt"></i>
                            </span>
                            <span class="text">Logs</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')

<script>

    $('#avanzado').click(function (e) { 
        $('#avanzadosModal').modal('show');
    });

    function pulse(element)
    {
        element.children('a').children('i').addClass('fa-fade');
    }

    function removePulse(element)
    {
        element.children('a').children('i').removeClass('fa-fade');
    }

</script>

@endsection