@extends($layout)

@section('styles')
    <link rel="stylesheet" href="{{asset('/kitukizuri/libs/jstree/themes/default/style.min.css')}}">
    <link href="{{asset('/kitukizuri/libs/RWD-Table-Patterns/css/rwd-table.min.css')}}" rel="stylesheet" type="text/css" />
    <style>
        .hide {display: none;}
    </style>
@endsection

@section('content')

<div class="col-3">
    <div class="row">
        <div class="col-12 text-center text-primary">   
            <div class="form-group">
                <i class="fa-light fa-table-columns fa-xl" id="tableIcon"></i> <br>
                <strong>Listado de tablas</strong>
                <hr>
            </div> 
        </div>
        <div class="col-12 card card-body">
            <div id="treeTables" style="overflow: auto"></div>
        </div>
    </div>
</div>

<div class="col-9">
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
                        <i class="fa-thin fa-circle-info"></i> <span class="d-none d-md-inline-block">Propiedades</span> 
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="pills-data-tab" data-bs-toggle="pill" data-bs-target="#pills-data" role="tab" aria-controls="pills-data" aria-selected="true" onclick="getAllData()">
                        <i class="fa-thin fa-table"></i> <span class="d-none d-md-inline-block">Datos</span> 
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="pills-query-tab" data-bs-toggle="pill" data-bs-target="#pills-query" role="tab" aria-controls="pills-data" aria-selected="true" onclick="setScript('sql')">
                        <i class="fa-thin fa-message-code"></i> <span class="d-none d-md-inline-block">Query</span> 
                    </a>
                </li>
            </ul>
        </div>
        <div class="col-12">
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-propiedades" role="tabpanel" aria-labelledby="pills-propiedades-tab" tabindex="0">
                    <div class="card">
                        <div class="card-body">
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
                                    <table class="table table-bordered" id="tableColumns"></table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-data" role="tabpanel" aria-labelledby="pills-data-tab" tabindex="0">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-rep-plugin" id="tableData"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-query" role="tabpanel" aria-labelledby="pills-query-tab" tabindex="0">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <strong>Selección de lenguaje y ejeuctar consulta: </strong>
                                    <hr>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <a href="javascript:void(0)" class="btn btn-tertiary btn-icon-split btn-sm btn-block" data-bs-toggle="tooltip" data-placement="top" title="Permite el ingreso de consultas basados en Eloquent o Krud" onclick="setScript('orm')">
                                            <span class="icon">
                                                <i class="fa-light fa-function"></i>
                                            </span>
                                            <span class="text">ORM</span>
                                        </a>
                                    </div>
                                </div>
                                @if ($driver != 'mongo')
                                    <div class="col-4">
                                        <div class="form-group">
                                            <a href="javascript:void(0)" class="btn btn-primary btn-icon-split btn-sm btn-block" data-bs-toggle="tooltip" data-placement="top" title="Permite el ingreso de consultas basados SQL" onclick="setScript('sql')">
                                                <span class="icon">
                                                    <i class="fa-light fa-scroll"></i>
                                                </span>
                                                <span class="text">SQL</span>
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-4">
                                        <div class="form-group">
                                            <a href="javascript:void(0)" class="btn btn-primary btn-icon-split btn-sm btn-block" onclick="setScript('javascript')">
                                                <span class="icon">
                                                    <i class="fa-brands fa-square-js"></i>
                                                </span>
                                                <span class="text">JavaScript</span>
                                            </a>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-4 text-center">
                                    <div class="form-group">
                                        <a href="javascript:void(0)" class="btn btn-success btn-icon-split btn-sm btn-block" data-bs-toggle="tooltip" data-placement="top" title="Obtiene los resultados de la consulta" onclick="getValueEditor()">
                                            <span class="icon">
                                                <i class="fa-duotone fa-rocket-launch"></i>
                                            </span>
                                            <span class="text">Generar resultados</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <strong>lenguaje seleccionado:</strong> <span id="selectedLang"></span>
                                    <hr>
                                </div>
                                <div class="col-12">
                                    <div id="editor" style="width: 100%; height:400px;"></div>
                                </div>
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead id="queryThead"></thead>
                                            <tbody id="queryTbody"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
  
<!-- Modal -->
<div class="modal fade" id="resultados" tabindex="-1" role="dialog" aria-labelledby="resultadosLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resultadosLabel">Resultados</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <table class="table">
                            <tbody id="resultadosThead"></tbody>
                            <tbody id="resultadosTbody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-center">
                <div class="col-6">
                    <button class="btn btn-outline-primary">
                        Generar tabla de reporte
                    </button>
                </div>
                <div class="col-6">
                    <button class="btn btn-outline-primary">
                        Generar reporte en gráficas
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script src="{{asset('kitukizuri/libs/jstree/jstree.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('kitukizuri/libs/RWD-Table-Patterns/js/rwd-table.min.js')}}"></script>
    <script> var require = { paths: { 'vs': '{{asset('kitukizuri/libs/monaco-editor/min/vs')}}' } };</script>
    <script type="text/javascript" src="{{asset('kitukizuri/libs/monaco-editor/min/vs/loader.js')}}"></script>
    <script type="text/javascript" src="{{asset('kitukizuri/libs/monaco-editor/min/vs/editor/editor.main.nls.js')}}"></script>
    <script type="text/javascript" src="{{asset('kitukizuri/libs/monaco-editor/min/vs/editor/editor.main.js')}}"></script>
    <script type="text/javascript" src="{{asset('kitukizuri/js/snippets/orm.js')}}"></script>
    <script type="text/javascript" src="{{asset('kitukizuri/js/snippets/js.js')}}"></script>
    <script type="text/javascript" src="{{asset('kitukizuri/js/snippets/sql.js')}}"></script>
    <script>
        "use strict";
        var editor   = null;
        var token    = '{{ csrf_token() }}';
        var gTable   = '';
        var driver   = '{!! $driver !!}';
        var dataBase = '{!! encrypt($database) !!}';

        $('#treeTables').jstree({
            "plugins" : [ "search", "changed", 'contextmenu' ],
            'core' : {
                'data' : {
                    'url'     : '{{route('database.store')}}?opcion=1&db='+dataBase+'&drv='+driver,
                    'dataType': "json"
                }
            },
            'contextmenu': {
                'items': {
                    'viewData': { // The "rename" menu item
                        'label': "Ver datos",
                        'action': function (obj) {
                            let idNode = obj.reference.prevObject[0].id;
                        }
                    }
                },
            }
        }).on("select_node.jstree", function (e, data) { 
            viewTableInfo(data.node.id)
        });

        var to = false;
        $('#krud-search').keyup(function () {
            if(to) { clearTimeout(to); }
            to = setTimeout(function () {
                var v = $('#krud-search').val();
                $('#treeTables').jstree(true).search(v);
            }, 500);
        });

        function getAllData(limit) {
            let data = {
                _token  : token,
                opcion  : 2,
                limit   : limit,
                table   : gTable,
                driver  : driver,
                database: dataBase
            }

            $('#tableData').empty();
            $('#tableData').append(`
                <div class="table-responsive" id="dataTable" >
                    <table class="table table-striped table-bordered">
                        <thead id="dataThead"></thead>
                        <tbody id="dataTbody"></tbody>
                    </table>
                </div>
            `);

            $.post("{{route('database.store')}}", data)
                .done(response => {
                    let data = response.data

                    if(data.length > 0) {
                        // obteniendo nombre de columnas
                        let colsName = Object.keys(data[0]);
                        let colsFormat = '<tr>';

                        colsName.forEach(element => {
                            colsFormat += '<th>'+element+'</th>'
                        });

                        $('#dataThead').append(colsFormat+'</tr>');

                        // poblando valores de tabla
                        data.forEach(element => {
                            let colValue = Object.values(element)
                            let valFormat = '<tr>';

                            colValue.forEach(value => {
                                valFormat += '<td>'+value+'</td>'
                            });

                            $('#dataTbody').append(valFormat+'</tr>');
                        });
                        $("#dataTable").responsiveTable({stickyTableHeader:true})
                        $("#dataTable").responsiveTable('update')
                        
                    } else {
                        $('#dataTbody').append('</tr><td>Sin Datos</td></tr>');
                    }
                })
                .fail(error => alert(error.responseText));
        }
        
        var registerCompletion = null;
        function setScript(language) {
            $('#editor').empty();

            
            if(registerCompletion != null) {
                monaco.editor.getEditors().forEach(editor => editor.dispose());
                registerCompletion.dispose()
            }

            let defaultLanguage = 'sql';
            let classProposals  = SQL;

            if(language == 'orm') {
                defaultLanguage = 'php';
                classProposals = ORM;
            } else if(language == 'javascript') {
                defaultLanguage = language;
                classProposals = JS;
            }

            $('#selectedLang').text(defaultLanguage);

            initEditor(defaultLanguage, classProposals);
        }

        var editor = null;
        function initEditor(defaultLanguage, classProposals) {
           registerCompletion = monaco.languages.registerCompletionItemProvider(defaultLanguage, {
                provideCompletionItems: function (model, position) {
                    var word = model.getWordUntilPosition(position);
                    var range = {
                        startLineNumber: position.lineNumber,
                        endLineNumber  : position.lineNumber,
                        startColumn    : word.startColumn,
                        endColumn      : word.endColumn
                    };

                    return {
                        suggestions: classProposals.createDependencyProposals(range)
                    };
                }
            });

            editor = monaco.editor.create(document.getElementById('editor'), {
                language: defaultLanguage
            });
        }

        function getValueEditor(){
            let value = editor.getValue()    
            let data = {
                _token: token,
                query : value,
                opcion: 3,
                driver: driver,
                lang  : $('#selectedLang').text()
            }
            
            $.post("{{route('database.store')}}", data).done(response => {
                
                if(response.results.length > 0) {
                
                    let thead = Object.keys(response.results[0]);
                    thead.forEach(element => {
                        $('#resultadosThead').append('<th>'+element+'</th>');
                    });
                    response.results.response.forEach(element => {
                        $('#resultadosTbody').append('<tr><td>'+element+'</td></tr>');
                    })
                    
                } else {
                    $('#resultadosThead').append('<th></th>');
                    $('#resultadosTbody').append('<tr><td>No se encontraron resultados</td></tr>');
                }

                $('#resutlados').modal('show');

            }).fail(error => alert(error));
        }

        function viewTableInfo(table) {
            let data = {
                _token  : token,
                table   : table,
                opcion  : 1,
                driver  : driver,
                database: dataBase
            }

            gTable = table
            
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