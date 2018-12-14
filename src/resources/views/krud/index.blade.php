@extends($layout)

@section('content')
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">{!! $titulo !!} </div>
            <div class="panel-body">
                <table id="table1" class="table table-striped table-hover">
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
        </div>
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
            <div id="modalContent" class="modal-body">
              
            </div>
          </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        $('#table1').DataTable({
            //"bLengthChange": false,
            "sortable": false,
            "serverSide": true,
            "processing": true,
            "ajax": {
                url:  "/{{$ruta}}/0",
		        type:  'GET',
            },
            "buttons": [{
                text: 'Agregar',
                action: function ( e, dt, node, config ) {
                    location.replace('/{{$ruta}}'+'/create'+(String(window.location).includes('?') ? '?'+String(window.location).split('?')[1] : ''));
                }
            }],
            "sDom": 'Bfrtip'
            //"sDom": '<"row"<"col-sm-12 pull-left"f><"col-sm-4" <"btn-toolbar pull-right"  {!! in_array('create', $permisos) ? 'B <"btn-group btn-group-sm btn-group-agregar">' : null !!}>>>t<"pull-left" i><"pull-right"p>'
        });
        function edit(id){
            var url = '/{{$ruta}}';
            var id2 = null;
            if(url.includes('?')){
                var result = url.split('?');
                url = result[0];
                id2 = '?'+result[1];
            }
            window.location.replace(url+'/'+id+'/edit'+(id2 != null ? id2 : ''));
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
            $.post(url+'/'+id+id2,{_token:'{{csrf_token()}}', _method:'DELETE'}, function(data){
                if(data == 1) {
                    window.location.reload();
                }else{
                    alert(data);
                }
            });
        }
        function opciones(botones, id) {
            var buttons = '';
            $.each(botones, function(index, val) {
                var url = val.url.replace('{id}', id);
                buttons += '<div class="col-md-6"><a href="'+url+'" class="btn btn-'+val.class+' btn-block"><span class="zmdi zmdi-'+val.icon+'"></span> '+val.nombre+'</a></div>'
            });
            $('#modalContent').empty();
            $('#modalContent').append(buttons);
            $('#myModal').modal('show')
        }
    </script>
@endsection
