<!doctype html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>Kitu Kizuri</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Centro de ayuda" name="description" />
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
            <a href="{{ url('') }}"><i class="mdi mdi-home-variant h2"></i></a>
        </div>
        <div class="account-pages my-5 pt-5">
            <div class="container">
                @if ($tipo == 'help')
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row justify-content-center mt-3">
                                        <div class="col-lg-8">
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <div class="border p-3 text-center rounded mb-4">
                                                        <a href="#">
                                                            <div class="my-3">
                                                                <i class="dripicons-question h2 text-primary"></i>
                                                            </div>
                                                            <h5 class="font-size-15 mb-3">General Questions</h5>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="border p-3 text-center rounded mb-4">
                                                        <a href="#">
                                                            <div class="my-3">
                                                                <i class="dripicons-tags h2 text-primary"></i>
                                                            </div>
                                                            <h5 class="font-size-15 mb-3">Privacy Policy</h5>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="border p-3 text-center rounded mb-4">
                                                        <a href="#">
                                                            <div class="my-3">
                                                                <i class="dripicons-help h2 text-primary"></i>
                                                            </div>
                                                            <h5 class="font-size-15 mb-3">Help & Support</h5>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="border p-3 text-center rounded mb-4">
                                                        <a href="#">
                                                            <div class="my-3">
                                                                <i class="dripicons-article h2 text-primary"></i>
                                                            </div>
                                                            <h5 class="font-size-15 mb-3">Pricing & Plans</h5>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row justify-content-center">
                        <div class="col-lg-7">
                            <div class="text-center mb-5">
                                <div class="mb-5">
                                    <img src="{{asset('kitukizuri/images/logo.svg')}}" width="100px" alt="logo">
                                </div>
                                <h4 class="mt-4" id="titulo"></h4>    
                                <p id="comentario"></p>
                                @if (!empty($permitidos))
                                    @foreach ($permitidos as $permitido)
                                        <code>{{ $permitido }}</code> <br>
                                    @endforeach
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
                                                <p>para hacer una configuración correcta seguir el siguiente ejemplo:</p>
                                            </div>
                                        </div>
                                        <div id="editor"> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <br>
                            <p>Para conocer más puedes utilizar la función <code>$this->help();</code></p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <!-- end Account pages -->

        <!-- JAVASCRIPT -->
        <script src="{{asset('kitukizuri/libs/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('kitukizuri/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('kitukizuri/libs/metismenu/metisMenu.min.js')}}"></script>
        <script src="{{asset('kitukizuri/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('kitukizuri/libs/node-waves/waves.min.js')}}"></script>

        <script src="{{asset('kitukizuri/js/msg.json')}}"></script>
        <script src="{{asset('kitukizuri/js/app.js')}}"></script>

        <script> var require = { paths: { 'vs': '{{asset('kitukizuri/libs/monaco-editor/min/vs')}}' } };</script>
        <script type="text/javascript" src="{{asset('kitukizuri/libs/monaco-editor/min/vs/loader.js')}}"></script>
        <script type="text/javascript" src="{{asset('kitukizuri/libs/monaco-editor/min/vs/editor/editor.main.nls.js')}}"></script>
        <script type="text/javascript" src="{{asset('kitukizuri/libs/monaco-editor/min/vs/editor/editor.main.js')}}"></script>
        
        <script>
            "use strict";
            var code = data.{{$tipo}}
            $('#titulo').append(code.titulo)
            $('#comentario').append(code.comentario)

            var el = document.getElementById('editor');
            el.style.height = '600px';
            el.style.width = '100%';
            // window.editor is accessible. 
            var editor = null;
            var init = function () {
                require(['vs/editor/editor.main'], function () {
    
                    editor = monaco.editor.create(el, {
                        theme: 'vs-dark',
                        model: monaco.editor.createModel(code.codigo, "php")
                    });
    
                    editor.layout();
                });
                
                window.removeEventListener("load", init);
            };
    
            window.addEventListener("load", init);
        </script> 
    </body>
</html>