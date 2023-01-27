@extends($layout)

@section('styles')
    <!-- Plugins -->
    <link href="{{asset('kitukizuri/libs/datatables.net-bs'.$vBootstrap.'/css/dataTables.bootstrap'.$vBootstrap.'.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('kitukizuri/libs/datatables.net-buttons-bs'.$vBootstrap.'/css/buttons.bootstrap'.$vBootstrap.'.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('kitukizuri/libs/datatables.net-responsive-bs'.$vBootstrap.'/css/responsive.bootstrap'.$vBootstrap.'.min.css')}}" rel="stylesheet" type="text/css" />     
@endsection

@section('content')
    @if (!empty(Session::get('type')) && !empty(Session::get('msg')))
        <div class="alert alert-{{Session::get('type')}}" role="alert">
            {{Session::get('msg')}}
        </div>    
    @endif
    <div class="col-md-12">
        <table id="table1" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
            <thead>
                <tr>
                    @foreach($columnas as $c)
                        <td>{{ $c }}</td>
                    @endforeach
                    <td width="10%"></td>
                </tr>
            </thead>
            <tbody>                        
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Opciones</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row" id="modalContent"></div>
            </div>
          </div>
        </div>
    </div>

@endsection
@section('scripts')
    <!-- Required datatable js -->
    <script src="{{asset('kitukizuri/libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('kitukizuri/libs/datatables.net-bs'.$vBootstrap.'/js/dataTables.bootstrap'.$vBootstrap.'.min.js')}}"></script>
    <!-- Buttons examples -->
    <script src="{{asset('kitukizuri/libs/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('kitukizuri/libs/datatables.net-buttons-bs'.$vBootstrap.'/js/buttons.bootstrap'.$vBootstrap.'.min.js')}}"></script>
    <script src="{{asset('kitukizuri/libs/jszip/jszip.min.js')}}"></script>
    <script src="{{asset('kitukizuri/libs/pdfmake/build/pdfmake.min.js')}}"></script>
    <script src="{{asset('kitukizuri/libs/pdfmake/build/vfs_fonts.js')}}"></script>
    <script src="{{asset('kitukizuri/libs/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('kitukizuri/libs/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{asset('kitukizuri/libs/datatables.net-buttons/js/buttons.colVis.min.js')}}"></script>
    <!-- Responsive examples -->
    <script src="{{asset('kitukizuri/libs/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('kitukizuri/libs/datatables.net-responsive-bs'.$vBootstrap.'/js/responsive.bootstrap'.$vBootstrap.'.min.js')}}"></script>
    <script>
        $('#table1').DataTable({
            "language": {
                "url": "{{asset('kitukizuri/libs/datatables.net/lang/es_es.json')}}"
            },
            "sortable": false,
            "serverSide": true,
            "processing": true,
            "ajax": {
                url:  "{{$ruta}}/0"+(String(window.location).includes('?') ? '?'+String(window.location).split('?')[1] : ''),
		        type:  'GET',
            },
            "buttons":{
                "buttons": [
                    "copy",
                    "excel",
                    "pdf",
                    "colvis",
                    @if(in_array('create', $permisos))
                        {
                            text: 'Agregar',
                            className: '{{$dtBtnAdd}}',
                            action: function ( e, dt, node, config ) {
                                location.assign('{{$ruta}}'+'/create'+(String(window.location).includes('?') ? '?'+String(window.location).split('?')[1] : ''));
                            }
                        }
                    @endif
                    @if(!empty($botonesDT))
                        @foreach($botonesDT as $boton)
                            {
                                text: '{{ $boton['text'] }}',
                                className: '{{ $boton['class'] }}',
                                action: {!! $boton['action'] !!}
                            }
                        @endforeach
                    @endif
                ],
                "dom": {
                    button: {
                        className: '{{$dtBtnLiner}}'
                    },
                    buttonLiner: {
                        tag: null
                    }
                }
            },
            "sDom": '<"row" <"col-sm-6" B><"col-sm-6" f>>rt <"row" <"col-sm-6" i><"col-sm-6" p>>'
        })
        function edit(id){
            var url     = '{{$ruta}}';
            var urlBase = String(window.location);
            var id2     = null;
            if(urlBase.includes('?')){
                var result = urlBase.split('?');
                url = result[0];
                id2 = '?'+result[1];
            }

            window.location.assign(url+'/'+id+'/edit'+(id2 != null ? id2 : ''));
        }
        function destroy(id){
            var url = String(window.location);
            var id2 = null;
            if(url.includes('?')){
                var result = url.split('?');
                url = result[0];
                id2 = '?'+result[1];
            }else{
                id2 = '';
            }

            var confirmResult = confirm('¿Esta seguro de eliminar este elemento?');

            if(confirmResult == true) {
                $.post(url+'/'+id+id2,{_token:'{{csrf_token()}}', _method:'DELETE'}, function(data){
                    if(data == 1) {
                        window.location.reload();
                    }else{
                        alert(data);
                    }
                });
            }
        }
        function opciones(id) {
            var buttons = '';
            var botones = {!! $botones !!}
            console.log(botones)
            $.each(botones, function(index, val) {
                var url = val.url.replace('{id}', id);
                buttons += '<div class="col-md-6"><a href="'+url+'" class="btn btn-'+val.class+' btn-block"><span class="'+val.icon+'"></span> '+val.nombre+'</a></div>'
            });
            $('#modalContent').empty();
            $('#modalContent').append(buttons);
            $('#myModal').modal('show')
        }
    </script>
@endsection
