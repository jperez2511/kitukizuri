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
                                <img src="{{asset('kitukizuri/images/logo.svg')}}" width="100px" alt="logo">
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
                    <div class="col-lg-12 ml-lg-auto">
                        <div class="mt-5 mt-lg-0">
                            <div class="card maintenance-box">
                                <div class="card-body p-4">
                                    <div class="media">
                                        <div class="avatar-xs mr-3">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                <i class="mdi mdi-information-outline"></i>
                                            </span>
                                        </div>
                                        <div class="media-body">
                                            <h5 class="font-size-15 text-uppercase">Ejemplo de configuración</h5>
                                        </div>
                                    </div>
                                    <div id="editor"> </div>
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

        <script> var require = { paths: { 'vs': '{{asset('kitukizuri/libs/monaco/min/vs')}}' } };</script>
        <script type="text/javascript" src="{{asset('kitukizuri/libs/monaco/min/vs/loader.js')}}"></script>
        <script type="text/javascript" src="{{asset('kitukizuri/libs/monaco/min/vs/editor/editor.main.nls.js')}}"></script>
        <script type="text/javascript" src="{{asset('kitukizuri/libs/monaco/min/vs/editor/editor.main.js')}}"></script>

        <script>
            @if ($tipo == 'setModelo')
                var code = "<?php \n\nnamespace kitukizuri\\training; \n\nuse Krud; \nuse Icebearsoft\\Models\\Training; \n\nclass ExapleController extends Krud\n{\n\tpublic function __construct()\n\t$this->setModel(new Training);\n\t}\n}"
            @endif
        </script>

        <script>
            "use strict";
            var el = document.getElementById('editor');
            el.style.height = '300px';
            el.style.width = '100%';
    
            // window.editor is accessible. 
            var editor = null;
            var init = function () {
    
                require(['vs/editor/editor.main'], function () {
    
                    editor = monaco.editor.create(el, {
                        theme: 'vs-dark',
                        model: monaco.editor.createModel(code, "php")
                    });
    
                    editor.layout();
                });
    
                // no point in keeping this around.
                window.removeEventListener("load", init);
            };
    
            window.addEventListener("load", init);
        </script> 
    </body>
</html>
