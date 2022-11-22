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
        <div class="col-12">
            <a href="btn btn-{{$colors[$driver]['color']}} btn-block">
                <i class="{!! $colors[$driver]['icono'] !!} fa-xl"></i> <br>
                {{ $database }}
            </a>
        </div>
    </div>
    <div class="row hide" id="loader">
        <div class="col-12 text-center">
            <i class="fa-duotone fa-loader fa-spin-pulse"></i> <strong>Cargando datos...</strong>
        </div>
    </div>
    <div class="row hide" id="infoTable">
        <div class="col-3">
            <strong>Tabla:</strong>
            <span id="tituloTabla"></span> 
        </div>
        <div class="col-9">
            <div class="row">
                <div class="col-6 text-center">
                    <div class="form-group">
                        <strong>Collation:</strong>
                        <span id="collation"></span>
                    </div>
                </div>
                <div class="col-6 text-center">
                    <div class="form-group">
                        <strong>Charset:</strong>
                        <span id="charset"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
        var init = function () {
            require(['vs/editor/editor.main'], function () {
                editor = monaco.editor.create(el, {
                    theme: 'vs-{{ config('kitukizuri.dark') ? 'dark' : 'light' }}',
                    model: monaco.editor.createModel('select * from tabla', "sql")
                });
                editor.layout();
            });
            window.removeEventListener("load", init);
        };
        window.addEventListener("load", init);

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
                database: '{!! $database !!}'
            }
            
            $('#loader').removeClass('hide');

            $.post("{{route('database.store')}}", data).done(response => {
                
                $('#loader').addClass('hide');
                
                $('#infoTable').removeClass('hide');
                $('#tituloTabla').empty();
                $('#collation').empty();
                $('#charset').empty();
                $('#tituloTabla').append(table);

                $('#collation').append(response.information[0].collation);
                $('#charset').append(response.information[0].charset);

            }).fail(error => console.log(error.responseText));
        }

    </script> 
    
@endsection