<!doctype html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>Kitu Kizuri</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <<meta content="Panel de administración" name="description" />
        <meta name="author" content="Ice Bear Soft">
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('kitukizuri/images/logo.svg')}}">

        <!-- Bootstrap Css -->
        <link href="{{asset('/kitukizuri/css/bootstrap-dark.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{asset('/kitukizuri/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{asset('/kitukizuri/css/app-dark.min.css')}}" rel="stylesheet" type="text/css" />

    </head>

    <body>
        <div class="home-btn d-none d-sm-block">
            <a href="index.html"><i class="mdi mdi-home-variant h2"></i></a>
        </div>

        <div class="account-pages my-5 pt-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-7">
                        <div class="text-center mb-5">
                            <div class="mb-5">
                                <img src="{{asset('kitukizuri/images/logo.svg')}}" height="24" alt="logo">
                            </div>
                            @if ($tipo == 'setModelo')
                                <h4 class="mt-4">Configuración de modelo</h4>    
                                <p>Antes de todo siempre se debe configurar un modelo, puesto que va a ser el encargado de hacer el mapeo de los campos en al base de datos, asi como el encargado de guardar, editar, eliminar los datos.</p>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- end row -->
                <div class="row pt-4 align-items-center justify-content-center">
                    <div class="col-sm-5">
                        <div class="">
                            <img src="assets/images/maintenance.png" alt="" class="img-fluid mx-auto d-block">
                        </div>
                    </div>
                    <div class="col-lg-6 ml-lg-auto">
                        <div class="mt-5 mt-lg-0">
                            <div class="card maintenance-box">
                                <div class="card-body p-4">
                                    <div class="media">
                                        <div class="avatar-xs mr-3">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                01
                                            </span>
                                        </div>
                                        <div class="media-body">
                                            <h5 class="font-size-15 text-uppercase">Why is the Site Down?</h5>
                                            <p class="text-muted mb-0">There are many variations of passages of
                                                Lorem Ipsum available, but the majority have suffered alteration.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card maintenance-box">
                                <div class="card-body p-4">
                                    <div class="media">
                                        <div class="avatar-xs mr-3">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                02
                                            </span>
                                        </div>
                                        <div class="media-body">
                                            <h5 class="font-size-15 text-uppercase">
                                                What is the Downtime?</h5>
                                            <p class="text-muted mb-0">Contrary to popular belief, Lorem Ipsum is not
                                                simply random text. It has roots in a piece of classical.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card maintenance-box">
                                <div class="card-body p-4">
                                    <div class="media">
                                        <div class="avatar-xs mr-3">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                03
                                            </span>
                                        </div>
                                        <div class="media-body">
                                            <h5 class="font-size-15 text-uppercase">
                                                Do you need Support?</h5>
                                            <p class="text-muted mb-0">If you are going to use a passage of Lorem
                                                Ipsum, you need to be sure there isn't anything embar.. <a
                                                        href="mailto:no-reply@domain.com"
                                                        class="text-decoration-underline">no-reply@domain.com</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    

                </div>
            </div>
        </div>
        <!-- end Account pages -->

        <!-- JAVASCRIPT -->
        <script src="{{asset('kitukizuri/libs/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('kitukizuri/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('kitukizuri/libs/metismenu/metisMenu.min.js')}}"></script>
        <script src="{{asset('kitukizuri/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('kitukizuri/libs/node-waves/waves.min.js')}}"></script>

        <script src="{{asset('kitukizuri/js/app.js')}}"></script>

    </body>
</html>
