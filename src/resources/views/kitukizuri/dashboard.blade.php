<x-app-layout>
    <div class="nk-block-head nk-block-head-lg wide-xs mx-auto">
        <div class="nk-block-head-content text-center">
            <h2 class="nk-block-title fw-normal">{{__('Control Panel')}}</h2>
            <div class="nk-block-des">
                <p>{{__('Welcome to the Control Panel! You have the following options available within your application.')}}</p>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body mt-3 mb-3 text-center">
                <a href="{{ route('usuarios.index') }}" style="color:#7c8a96;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 90 90" style="width: 15%;">
                        <rect x="5" y="7" width="60" height="56" rx="7" ry="7" fill="#e3e7fe" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        <rect x="25" y="27" width="60" height="56" rx="7" ry="7" fill="#e3e7fe" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        <rect x="15" y="17" width="60" height="56" rx="7" ry="7" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        <line x1="15" y1="29" x2="75" y2="29" fill="none" stroke="#6576ff" stroke-miterlimit="10" stroke-width="2" />
                        <circle cx="53" cy="23" r="2" fill="#c4cefe" />
                        <circle cx="60" cy="23" r="2" fill="#c4cefe" />
                        <circle cx="67" cy="23" r="2" fill="#c4cefe" />
                        <rect x="22" y="39" width="20" height="20" rx="2" ry="2" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        <circle cx="32" cy="45.81" r="2" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        <path d="M29,54.31a3,3,0,0,1,6,0" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        <line x1="49" y1="40" x2="69" y2="40" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        <line x1="49" y1="51" x2="69" y2="51" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        <line x1="49" y1="57" x2="59" y2="57" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        <line x1="64" y1="57" x2="66" y2="57" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        <line x1="49" y1="46" x2="59" y2="46" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        <line x1="64" y1="46" x2="66" y2="46" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                    </svg><br>
                    <strong>{{__('Users')}}</strong>
                </a>
            </div>
        </div>
    </div>
    
    
<div class="col-6">
    <div class="card">
        <div class="card-body mt-3 mb-3 text-center" onmouseover="pulse($(this))" onmouseout="removePulse($(this))">
            <a href="{{ route('empresas.index') }}" style="color:#7c8a96;" >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 90 90" style="width: 15%;">
                    <rect x="5" y="5" width="53.97" height="69.95" rx="7" ry="7" fill="#e3e7fe" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <path d="M51.66,15H22.4A7.22,7.22,0,0,0,15,22V78a7.21,7.21,0,0,0,7.41,7H61.56A7.2,7.2,0,0,0,69,78V30.5Z" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <polyline points="68.96 30.98 51.97 30.91 51.97 15.99" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <line x1="23" y1="34" x2="44" y2="34" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <line x1="23" y1="42" x2="57" y2="42" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <line x1="23" y1="50" x2="57" y2="50" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <line x1="23" y1="58" x2="32" y2="58" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <ellipse cx="61.1" cy="61.11" rx="23.9" ry="23.89" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <polygon points="69.56 74.43 47.7 52.84 52.46 48.15 74.32 69.74 69.56 74.43" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <line x1="54.98" y1="51.16" x2="54.22" y2="51.91" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <line x1="57.62" y1="53.77" x2="55.59" y2="55.78" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <line x1="71.22" y1="67.2" x2="70.46" y2="67.95" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <line x1="68.87" y1="64.88" x2="66.84" y2="66.89" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <path d="M69.55,48.21l5,4.89L55.42,72H51V67.6Z" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <line x1="65.67" y1="52.24" x2="70.35" y2="56.86" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                </svg><br>
                <strong>{{__('Companies')}}</strong>
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

</x-app-layout>