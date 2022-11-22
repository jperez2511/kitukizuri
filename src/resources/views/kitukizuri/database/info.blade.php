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
                option: 1
            }
            
            $.post("{{route('database.store')}}", data).done(response => {
                console.log(response) 
            }).fail(error => console.log(error.responseText));
        }

    </script> 
    
@endsection