<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="Panel de administración" name="description" />
    <meta name="author" content="Ice Bear Soft">
    <!-- Favicon icon -->
    <link rel="icon" type="image/svg" sizes="16x16" href="{{asset('kitukizuri/images/logo.svg')}}">
    <title>{{ $titulo ?? 'Kitu Kizuri'}}</title>
    
    @yield('styles')
    
    @stack('css')

    @php 
        $isDark = !empty(config('kitukizuri.dark')) ? '-dark' : null;
        $sideBar = (!empty($isDark) || !empty(config('kitukizuri.darkSideBar')) ? 'dark' : null); 
    @endphp


    <!-- Bootstrap -->
    <link href="{{asset('/kitukizuri/libs/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/kitukizuri/css/bootstrap'.$isDark.'.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{asset('/kitukizuri/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
     <!-- App Css-->
     <link href="{{asset('/kitukizuri/css/app'.$isDark.'.min.css')}}" rel="stylesheet" type="text/css" />

     <link href="{{asset('/kitukizuri/css/style.css')}}" rel="stylesheet" type="text/css" />

     <link href="{{asset('/kitukizuri/fonts/fontawesome/css/all.css')}}" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body data-sidebar="{{$sideBar}}" class="sidebar-enable">
    <!-- Begin page -->
    <div id="layout-wrapper">
        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- LOGO -->
                    <div class="navbar-brand-box">
                        <a href="{{url('/')}}" class="logo logo-light">
                            <span class="logo-sm">
                                <img src="{{asset('kitukizuri/images/logo.svg')}}" alt="" height="40">
                            </span>
                            <span class="logo-lg">
                                <img src="{{asset('kitukizuri/images/logo.svg')}}" alt="" height="40">
                            </span>
                        </a>
                    </div>

                    <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                        <i class="mdi mdi-backburger"></i>
                    </button>

                    <!-- App Search-->
                    <form class="app-search d-none d-lg-block">
                        <div class="position-relative">
                            <input type="text" class="form-control" id="krud-search" placeholder="Buscar...">
                            <span class="mdi mdi-magnify"></span>
                        </div>
                    </form>
                </div>

                <div class="d-flex">

                    <div class="dropdown d-inline-block d-lg-none ml-2">
                        <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-magnify"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0"
                            aria-labelledby="page-header-search-dropdown">
                
                            <form class="p-3">
                                <div class="form-group m-0">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Buscar..." aria-label="Recipient's username">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                            <i class="mdi mdi-information-variant"></i>
                        </button>
                    </div>

                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-incognito"></i>  
                            <span class="d-none d-sm-inline-block ml-1">{{Auth::user()->name}}</span>
                            <i class="mdi mdi-chevron-down d-none d-sm-inline-block"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <!-- item-->
                            <a class="dropdown-item" href="#"><i class="mdi mdi-face-profile font-size-16 align-middle mr-1"></i> Perfil</a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="mdi mdi-logout font-size-16 align-middle mr-1"></i> Cerrar Sesión
                                </a>
                            </form>
                        </div>
                    </div>
        
                </div>
            </div>
        </header>

        <!-- ========== Left Sidebar Start ========== -->
        <div class="vertical-menu">
            <div data-simplebar class="h-100">
                <!--- Sidemenu -->
                <div id="sidebar-menu">
                    <!-- Left Menu Start -->
                    @if (empty($kmenu) || $kmenu ==  false)
                       {!! session('menu')  !!}
                    @else
                        <ul class="metismenu list-unstyled" id="side-menu">
                            <li>
                                <a href="/" class="waves-effect">
                                    <i class="mdi mdi-keyboard-return"></i>
                                    <span>Regresar a la aplicación</span>
                                </a>
                            </li>

                            <li class="menu-title">Organización</li>

                            <li>
                                <a href="{{route('empresas.index')}}" class=" waves-effect">
                                    <i class="mdi mdi-office-building"></i>
                                    <span>Empresas</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{route('usuarios.index')}}" class=" waves-effect">
                                    <i class="mdi mdi-account-supervisor"></i>
                                    <span>Usuarios</span>
                                </a>
                            </li>

                            <li class="menu-title">Configuraciones</li>

                            <li>
                                <a href="{{route('modulos.index')}}" class=" waves-effect">
                                    <i class="mdi mdi-view-module"></i>
                                    <span>Modulos de la aplicación</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{route('roles.index')}}" class=" waves-effect">
                                    <i class="mdi mdi-shield-account"></i>
                                    <span>Roles de usuario</span>
                                </a>
                            </li>

                            <li class="menu-title">Administración de datos</li>

                            <li>
                                <a href="{{route('database.index')}}" class=" waves-effect">
                                    <i class="mdi mdi-database"></i>
                                    <span>Gestión de base de datos</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{route('logs.index')}}" class=" waves-effect">
                                    <i class="mdi mdi-file-document-box-multiple"></i>
                                    <span>Ver Logs</span>
                                </a>
                            </li>
                        </ul>
                    @endif
                </div>
                <!-- Sidebar -->
            </div>
        </div>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <h4 class="mb-0 font-size-18">{{ $titulo }}</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <!-- <li class="breadcrumb-item"><a href="javascript: void(0);">Apaxy</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li> -->
                                        <li class="breadcrumb-item active">{{ $titulo }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>     
                    <!-- end page title -->

                    <div class="row">
                        @if (!empty($dash))
                            @yield('content')
                        @else
                            <!-- Column -->
                            <div class="col-lg-12 col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        @yield('content')
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                </div> <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="offset-sm-6 col-sm-6">
                            <div class="text-sm-right d-none d-sm-block">
                                <span id="year"></span> © Ice Bear Soft.
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <!-- Right Sidebar -->
    <div class="right-bar">
        <div data-simplebar class="h-100">

            <!-- Nav tabs -->
            <ul class="nav nav-tabs nav-tabs-custom rightbar-nav-tab nav-justified" role="tablist">
                <li class="nav-item">
                    <a class="nav-link py-3 active" data-toggle="tab" href="#tasks-tab" role="tab">
                        <i class="mdi mdi-format-list-checkbox font-size-22"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-3" data-toggle="tab" href="#settings-tab" role="tab">
                        <i class="mdi mdi-settings font-size-22"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-3" data-toggle="tab" href="#chat-tab" role="tab">
                        <i class="mdi mdi-message-text font-size-22"></i>
                    </a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content text-muted">
                <div class="tab-pane" id="chat-tab" role="tabpanel">
                    <h6 class="font-weight-medium px-2 py-3 text-uppercase bg-light">Soporte Técnico</h6>
                    <div class="p-2">
                        <div class="form-group">
                            <textarea required="" class="form-control" rows="5" placeholder="Mensaje"></textarea>
                            <div class="font-size-12 text-muted">
                                <p class="mt-2 text-justify text-warning">El mensaje se enviará por correo electrónico y uno de nuestros técnicos se comunicará con ud.</p>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button type="button" class="btn btn-outline-success btn-block waves-effect waves-float waves-light"><i class="mdi mdi-telegram"></i> Enviar</button>
                        </div>
                    </div>

                </div>

                <div class="tab-pane active" id="tasks-tab" role="tabpanel">
                    <h6 class="font-weight-medium px-2 py-3 text-uppercase bg-light">Plan </h6>

                    <div class="p-2">
                        <a href="javascript: void(0);" class="text-reset item-hovered d-block p-3">
                            <p class="text-muted mb-0">Empresas<span class="float-right">0%</span></p>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 75%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </a>

                        <a href="javascript: void(0);" class="text-reset item-hovered d-block p-3">
                            <p class="text-muted mb-0">Usuarios<span class="float-right">37%</span></p>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 37%" aria-valuenow="37" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="tab-pane" id="settings-tab" role="tabpanel">
                        <h6 class="font-weight-medium px-4 py-3 text-uppercase bg-light">Configuraciones</h6>

                    <div class="p-4">
                        <h6 class="font-weight-medium">Online Status</h6>
                        <div class="custom-control custom-switch mb-1">
                            <input type="checkbox" class="custom-control-input" id="settings-check1" name="settings-check1" checked="">
                            <label class="custom-control-label font-weight-normal" for="settings-check1">Show your status to all</label>
                        </div>

                        <h6 class="font-weight-medium mt-4">Auto Updates</h6>
                        <div class="custom-control custom-switch mb-1">
                            <input type="checkbox" class="custom-control-input" id="settings-check2" name="settings-check2" checked="">
                            <label class="custom-control-label font-weight-normal" for="settings-check2">Keep up to date</label>
                        </div>

                        <h6 class="font-weight-medium mt-4">Backup Setup</h6>
                        <div class="custom-control custom-switch mb-1">
                            <input type="checkbox" class="custom-control-input" id="settings-check3" name="settings-check3">
                            <label class="custom-control-label font-weight-normal" for="settings-check3">Auto backup</label>
                        </div>

                    </div>

                    <h6 class="font-weight-medium px-4 py-3 mt-2 text-uppercase bg-light">Advanced Settings</h6>

                    <div class="p-4">
                        <h6 class="font-weight-medium">Application Alerts</h6>
                        <div class="custom-control custom-switch mb-1">
                            <input type="checkbox" class="custom-control-input" id="settings-check4" name="settings-check4" checked="">
                            <label class="custom-control-label font-weight-normal" for="settings-check4">Email Notifications</label>
                        </div>

                        <div class="custom-control custom-switch mb-1">
                            <input type="checkbox" class="custom-control-input" id="settings-check5" name="settings-check5">
                            <label class="custom-control-label font-weight-normal" for="settings-check5">SMS Notifications</label>
                        </div>

                        <h6 class="font-weight-medium mt-4">API</h6>
                        <div class="custom-control custom-switch mb-1">
                            <input type="checkbox" class="custom-control-input" id="settings-check6" name="settings-check6">
                            <label class="custom-control-label font-weight-normal" for="settings-check6">Enable access</label>
                        </div>

                    </div>
                </div>
            </div>

        </div> <!-- end slimscroll-menu-->
    </div>
    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    <script src="{{asset('kitukizuri/libs/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('kitukizuri/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('kitukizuri/libs/metismenu/metisMenu.min.js')}}"></script>
    <script src="{{asset('kitukizuri/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{asset('kitukizuri/libs/node-waves/waves.min.js')}}"></script>

    <script src="{{asset('kitukizuri/js/app.js')}}"></script>
    
    @stack('js')

    @yield('scripts')

    <script>
        var year = new Date();
        $('#year').text(year.getFullYear()); 
    </script>
</body>
</html>
