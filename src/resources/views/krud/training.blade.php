<!doctype html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>Kitu Kizuri</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Panel de administración" name="description" />
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

                @else
                    <div class="row justify-content-center">
                        <div class="col-lg-7">
                            <div class="text-center mb-5">
                                <div class="mb-5">
                                    <img src="{{asset('kitukizuri/images/logo.svg')}}" width="100px" alt="logo">
                                </div>
                                @if ($tipo == 'setModelo')
                                    <h4 class="mt-4">Debes configrar el <code>Modelo</code></h4>    
                                    <p>Antes de todo siempre se debe configurar un modelo, puesto que va a ser el encargado de hacer el mapeo de los campos en al base de datos, asi como el encargado de guardar, editar, eliminar los datos.</p>
                                @elseif ($tipo == 'setCampo')
                                    <h4 class="mt-4">Debes agregar la propiedad <code>Campo</code></h4>    
                                    <p>La propiedad Campo debe ser el nombre de la columna de la tabla en base de datos, donde se almacenara el valor ingresado por el usuario, si no se define un tipo de campo por defecto es texto abierto</p>
                                @elseif ($tipo == 'badType')
                                    <h4 class="mt-4">El tipo de campo <code>{{ $bad }}</code> No existe</h4>    
                                    <p>El tipo de campo define si será un texto, un campo numerico, etc. Para ello se han definido los siguientes tipos permitidos:</p>
                                    @foreach ($permitidos as $permitido)
                                        <code>{{ $permitido }}</code> <br>
                                    @endforeach
                                @elseif ($tipo == 'badView')
                                    <h4 class="mt-4">La vista <code>{{ $bad }}</code> No existe</h4>    
                                    <p>La vista permite mostrar en el index si sera una tabla o un calendario:</p>
                                    @foreach ($permitidos as $permitido)
                                        <code>{{ $permitido }}</code> <br>
                                    @endforeach
                                @elseif ($tipo == 'badTypeButton')
                                    <h4 class="mt-4">El parametro <code>{{ $bad }}</code> No existe</h4>    
                                    <p>Los parametros permitidos para configurar un botón son los siguientes:</p>
                                    @foreach ($permitidos as $permitido)
                                        <code>{{ $permitido }}</code> <br>
                                    @endforeach
                                @elseif ($tipo == 'typeCombo')
                                    <h4 class="mt-4">Falta la propiedad <code>Collect</code></h4>    
                                    <p>El tipo de campo combobox requiere de la propiedad collect, este puede ser un collection de laravel con dos elementos asi como se muestra en el ejemplo: </p>
                                @elseif ($tipo == 'filepath')
                                    <h4 class="mt-4">Falta la propiedad <code>FilePath</code></h4>    
                                    <p>El tipo de campo file requiere de la propiedad filepath, siendo esta la ruta donde se almacenaran los archivos dentro del servidor, esta puede ser una ruta relativa o absoluta por ejemplo: </p>
                                @elseif ($tipo == 'enum')
                                    <h4 class="mt-4">Falta la propiedad <code>EnumArray</code></h4>    
                                    <p>El tipo de campo enum requiere de la propiedad enumarray, aqui es donde se definen las opciones que estaran dispinbles, es importante recordad que deben ser las mismas que se han definido en la base de datos, por ejemplo: </p>
                                @elseif ($tipo == 'value')
                                    <h4 class="mt-4">Falta la propiedad <code>Value</code></h4>    
                                    <p>El tipo de campo hidden requiere de la propiedad value, ya que es un valor predeterminado se debe definir, por ejemplo: </p>
                                @endif
                                <br>
                                <p>Para conocer mas puedes mandar a llamar la función <code>$this->help();</code></p>
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

        <script src="{{asset('kitukizuri/js/app.js')}}"></script>

        <script> var require = { paths: { 'vs': '{{asset('kitukizuri/libs/monaco/min/vs')}}' } };</script>
        <script type="text/javascript" src="{{asset('kitukizuri/libs/monaco/min/vs/loader.js')}}"></script>
        <script type="text/javascript" src="{{asset('kitukizuri/libs/monaco/min/vs/editor/editor.main.nls.js')}}"></script>
        <script type="text/javascript" src="{{asset('kitukizuri/libs/monaco/min/vs/editor/editor.main.js')}}"></script>

        <script>
            @php
                if ($tipo == 'setModelo') {
                    $code = '<?php \n\nnamespace kitukizuri\\\\training; \n\nuse Krud; \nuse Icebearsoft\\\\Models\\\\Training; // <- Llamando al modelo \n\nclass ExapleController extends Krud\n{\n\tpublic function __construct()\n\t{\n\t\t$this->setModel(new Training); // <- configurando modelo (lo que falta en tu codigo)\n\t}\n}';
                } elseif($tipo == 'setCampo') {
                    $code = '<?php \n\nnamespace kitukizuri\\\\training; \n\nuse Krud; \nuse Icebearsoft\\\\Models\\\\Training; \n\nclass ExapleController extends Krud\n{\n\tpublic function __construct()\n\t{\n\t\t$this->setModel(new Training);\n\t\t$this->setCampo([\\\'nombre\\\'=>\\\'Label del campo\\\', \\\'campo\\\'=>\\\'nombre_columna_base_de_datos\\\']);\n\t}\n}';
                } elseif($tipo == 'badType') {
                    $code = '<?php \n\nnamespace kitukizuri\\\\training; \n\nuse Krud; \nuse Icebearsoft\\\\Models\\\\Training; \n\nclass ExapleController extends Krud\n{\n\tpublic function __construct()\n\t{\n\t\t$this->setModel(new Training);\n\t\t$this->setCampo([\\\'nombre\\\'=>\\\'Label del campo\\\', \\\'campo\\\'=>\\\'nombre_columna_base_de_datos\\\', \\\'tipo\\\'=>\\\'bool\\\']);\n\t}\n}';
                } elseif($tipo == 'badType') {
                    $code = '<?php \n\nnamespace kitukizuri\\\\training; \n\nuse Krud; \nuse Icebearsoft\\\\Models\\\\Training; \n\nclass ExapleController extends Krud\n{\n\tpublic function __construct()\n\t{\n\t\t$this->setModel(new Training);\n\t\t$this->setCampo([\\\'nombre\\\'=>\\\'Label del campo\\\', \\\'campo\\\'=>\\\'nombre_columna_base_de_datos\\\', \\\'tipo\\\'=>\\\'bool\\\']);\n\t}\n}';
                } elseif($tipo == 'badTypeButton') {
                    $code = '<?php \n\nnamespace kitukizuri\\\\training; \n\nuse Krud; \nuse Icebearsoft\\\\Models\\\\Training; \n\nclass ExapleController extends Krud\n{\n\tpublic function __construct()\n\t{\n\t\t$this->setModel(new Training);\n\t\t$this->setBoton([\\\'nombre\\\'=>\\\'Label del boton\\\', \\\'url\\\'=>\\\'URL\\\', \\\'class\\\'=>\\\'btn btn-success\\\', \\\'icon\\\'=>\\\'fa fa-trash\\\']);\n\t}\n}';
                } elseif($tipo == 'badView') {
                    $code = '<?php \n\nnamespace kitukizuri\\\\training; \n\nuse Krud; \nuse Icebearsoft\\\\Models\\\\Training; \n\nclass ExapleController extends Krud\n{\n\tpublic function __construct()\n\t{\n\t\t$this->setModel(new Training);\n\t\t$this->setView(\\\'calendar\\\']);\n\t}\n}';
                } elseif($tipo == 'typeCombo') {
                    $code = '<?php \n\nnamespace kitukizuri\\\\training; \n\nuse Krud; \nuse Icebearsoft\\\\Models\\\\Training; \nuse Icebearsoft\\\\Models\\\\Example; \n\nclass ExapleController extends Krud\n{\n\tpublic function __construct()\n\t{\n\t\t$collection = Example::select(\\\'id\\\', \\\'value\\\')->get();\n\t\t$this->setModel(new Training);\n\t\t$this->setCampo([\\\'nombre\\\'=>\\\'Label del campo\\\', \\\'campo\\\'=>\\\'nombre_columna_base_de_datos\\\', \\\'tipo\\\'=>\\\'combobox\\\', \\\'collect\\\'=>$collection]);\n\t}\n}';
                } elseif($tipo == 'filepath') {
                    $code = '<?php \n\nnamespace kitukizuri\\\\training; \n\nuse Krud; \nuse Icebearsoft\\\\Models\\\\Training; \n\nclass ExapleController extends Krud\n{\n\tpublic function __construct()\n\t{\n\t\t$this->setModel(new Training);\n\t\t$this->setCampo([\\\'nombre\\\'=>\\\'Label del campo\\\', \\\'campo\\\'=>\\\'nombre_columna_base_de_datos\\\', \\\'tipo\\\'=>\\\'file\\\', \\\'filepath\\\'=>\\\'/path/de/la/carpeta\\\']);\n\t}\n}';
                } elseif($tipo == 'enum') {
                    $code = '<?php \n\nnamespace kitukizuri\\\\training; \n\nuse Krud; \nuse Icebearsoft\\\\Models\\\\Training; \n\nclass ExapleController extends Krud\n{\n\tpublic function __construct()\n\t{\n\t\t$this->setModel(new Training);\n\t\t$this->setCampo([\\\'nombre\\\'=>\\\'Label del campo\\\', \\\'campo\\\'=>\\\'nombre_columna_base_de_datos\\\', \\\'tipo\\\'=>\\\'file\\\', \\\'filepath\\\'=>\\\'/path/de/la/carpeta\\\']);\n\t}\n}';
                } elseif($tipo == 'value') {
                    $code = '<?php \n\nnamespace kitukizuri\\\\training; \n\nuse Krud; \nuse Icebearsoft\\\\Models\\\\Training; \n\nclass ExapleController extends Krud\n{\n\tpublic function __construct()\n\t{\n\t\t$this->setModel(new Training);\n\t\t$this->setCampo([\\\'nombre\\\'=>\\\'Label del campo\\\', \\\'campo\\\'=>\\\'nombre_columna_base_de_datos\\\', \\\'tipo\\\'=>\\\'hidden\\\', \\\'value\\\'=>\\\'valorPredeterminado\\\']);\n\t}\n}';
                }
                
            @endphp
            
            var code = '{!! $code !!}'
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
