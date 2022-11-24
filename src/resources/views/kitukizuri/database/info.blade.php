@extends($layout)

@section('content')

<div class="col-3">
    <div data-simplebar class="card" style="height: 600px; overflow: auto;">
        <div id="sidebar-menu" class="card-body email-leftbar">
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title text-center text-primary">Lista de tablas</li>
                @foreach ($tables as $table)
                    <li>
                        <a href="#" class=" waves-effect" onclick="viewTableInfo('{{ $table->name }}')">
                            <i class="mdi mdi-database"></i>
                            <span>{{ $table->name }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<div class="col-xl-9">
    <div class="row">
        <div class="col-12 text-center text-primary">   
            <div class="form-group">
                <i class="{!! $colors[$driver]['icono'] !!} fa-xl" id="databaseIcon"></i> <br>
                <strong>Base de datos:</strong> {{ $database }}
                <hr>
            </div> 
        </div>
    </div>
    <div class="row hide" id="infoTable">
        <div class="col-12">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="pills-propiedades-tab" data-bs-toggle="pill" data-bs-target="#pills-propiedades" role="tab" aria-controls="pills-propiedades" aria-selected="true">
                        <i class="fa-thin fa-info"></i> <span class="d-none d-md-inline-block">Propiedades</span> 
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="pills-data-tab" data-bs-toggle="pill" data-bs-target="#pills-data" role="tab" aria-controls="pills-data" aria-selected="true" onclick="initEditor()">
                        <i class="fa-thin fa-table"></i> <span class="d-none d-md-inline-block">Datos</span> 
                    </a>
                </li>
            </ul>
        </div>
        <div class="col-12">
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-propiedades" role="tabpanel" aria-labelledby="pills-propiedades-tab" tabindex="0">
                    <div class="row">
                        <div class="col-4">
                            <strong>Tabla:</strong>
                            <span id="tituloTabla"></span> 
                        </div>
                        <div class="col-4 text-center">
                            <strong>Collation:</strong>
                            <span id="collation"></span>
                        </div>
                        <div class="col-4 text-center">
                            <strong>Charset:</strong>
                            <span id="charset"></span>
                        </div>
                        <div class="col-12">
                            <hr>
                            <strong>Columnas:</strong>
                            <table class="table table-bordered" id="tableColumns">
                
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-data" role="tabpanel" aria-labelledby="pills-data-tab" tabindex="0">
                    <div class="row">
                        <div class="col-12">
                            <div id="editor" style="width: 100%; height:300px;"></div>
                        </div>
                        <div class="col-12 text-center">
                            <div class="form-group">
                                <br>
                                <a href="javascript:void(0)" class="btn btn-success btn-icon-split mr-2" onclick="getValueEditor()">
                                    <span class="icon">
                                        <i class="fa-duotone fa-rocket-launch"></i>
                                    </span>
                                    <span class="text">Generar resultados</span>
                                </a>                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

    <script> var require = { paths: { 'vs': '{{asset('kitukizuri/libs/monaco-editor/min/vs')}}' } };</script>
    <script type="text/javascript" src="{{asset('kitukizuri/libs/monaco-editor/min/vs/loader.js')}}"></script>
    <script type="text/javascript" src="{{asset('kitukizuri/libs/monaco-editor/min/vs/editor/editor.main.nls.js')}}"></script>
    <script type="text/javascript" src="{{asset('kitukizuri/libs/monaco-editor/min/vs/editor/editor.main.js')}}"></script>
    <script>
        "use strict";
        var el = document.getElementById('editor');
        var editor = null;
        
        function initEditor(){
            $('#editor').empty();
            monaco.editor.create(document.getElementById('editor'), {
                theme: 'vs-{{ config('kitukizuri.dark') ? 'dark' : 'light' }}',
                model: monaco.editor.createModel('', "sql")
            });
        }

        function getValueEditor(){
            var value = window.editor.getValue()    
            console.log(value)
        }

        function viewTableInfo(table){
            let data = {
                _token: '{!! csrf_token() !!}',
                table: table, 
                opcion: 1,
                driver  : '{!! $driver !!}',
                database: '{!! encrypt($database) !!}'
            }
            
            $('#databaseIcon').removeClass('{!! $colors[$driver]['icono'] !!}');
            $('#databaseIcon').addClass('fa-duotone fa-loader fa-spin-pulse');

            $.post("{{route('database.store')}}", data).done(response => {
                
                $('#databaseIcon').removeClass('fa-duotone fa-loader fa-spin-pulse');
                $('#databaseIcon').addClass('{!! $colors[$driver]['icono'] !!}');
                
                $('#infoTable').removeClass('hide');
                $('#tituloTabla').empty();
                $('#collation').empty();
                $('#charset').empty();
                $('#tituloTabla').append(table);

                $('#collation').append(response.information[0].collation);
                $('#charset').append(response.information[0].charset);
                $('#tableColumns').empty()

                $('#tableColumns').append(`
                        <tr>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Permite valor Nulo</th>
                        </tr>
                    `);

                response.columns.forEach(element => {
                    $('#tableColumns').append(`
                        <tr>
                            <td>`+element.name+`</td>
                            <td>`+element.type+`</td>
                            <td>`+element.IS_NULLABLE+`</td>
                        </tr>
                    `);
                });

            }).fail(error => alert(error.responseText));
        }

    </script> 
    
@endsection