@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="/lib/datatables/css/dataTables.bootstrap.min.css"/>
    <style>
        div.dataTables_wrapper div.dataTables_filter{
            text-align: justify;
            padding-left: 15px;
        }
        .dataTables_info{
            padding-left:15px;
        }
        .dataTables_paginate{
            padding-right: 15px;
        }
    </style>
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default panel-table">
                <div class="panel-heading">{!! $titulo !!}
                    <div class="tools"><span class="icon mdi mdi-download"></span><span class="icon mdi mdi-more-vert"></span></div>
                </div>
                <div class="panel-body">
                    <table id="table1" class="table table-striped table-hover table-fw-widget">
                        <thead>
                            <tr>
                            @foreach($columnas as $c)
                                <td>{{$c}}</td>
                            @endforeach
                                <td width="10%"></td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $d)
                                <tr>
                                <?php $id = null; ?>
                                @foreach($d as $k => $v)
                                    @if($k !== '__id__')
                                        <td>{!! $v !!}</td>
                                    @else
                                        <?php $id = $v; ?>
                                    @endif
                                @endforeach
                                    <td>
                                        @if(!empty($botones) && is_array($botones))
                                            @foreach($botones as $b)
                                                <?php $b['url'] = str_replace('{id}', $id, $b['url']); ?>
                                                <a href="{{$b['url']}}" class="btn btn-xs btn-{{$b['class']}}"><span class="mdi mdi-{{$b['icon']}}"></span></a>
                                            @endforeach
                                        @endif

                                        @if(in_array('edit', $permisos))
                                            <a href="javascript:void(0)" onclick="edit('{{Crypt::encrypt($id)}}')" class="btn btn-xs btn-primary"><span class="mdi mdi-edit"></span></a>
                                        @endif
                                        @if(in_array('destroy', $permisos))
                                            <a href="javascript:void(0)" onclick="destroy('{{Crypt::encrypt($id)}}')" class="btn btn-xs btn-danger"><span class="mdi mdi-delete"></span></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="full-success" class="modal-container modal-full-color modal-full-color-success modal-effect-8" style="perspective: none;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><span class="mdi mdi-close"></span></button>
      </div>
      <div class="modal-body">
        <div class="text-center"><span class="modal-main-icon mdi mdi-check"></span>
          <h3>Opciones</h3>
        </div>
        <div style="height: 10px;"></div>
        <div class="text-center" id="modalContent"></div>
      </div>
      <div class="modal-footer"></div>
    </div>
  </div>
@endsection
@section('scripts')
    <script>
    $.fn.niftyModal('setDefaults',{
        overlaySelector: '.modal-overlay',
        closeSelector: '.modal-close',
        classAddAfterOpen: 'modal-show',
     });
    $('#table1').DataTable({
        "bLengthChange": false,
        "sortable": false,
        buttons: [{
            text: 'Agregar',
            action: function ( e, dt, node, config ) {
                location.replace('/{{$ruta}}'+'/create'+(String(window.location).includes('?') ? '?'+String(window.location).split('?')[1] : ''));
            }
        }],
        "sDom": '<"row"<"col-sm-8 pull-left"f><"col-sm-4" <"btn-toolbar pull-right"  {!! in_array('create', $permisos) ? 'B <"btn-group btn-group-sm btn-group-agregar">' : null !!}>>>t<"pull-left"i><"pull-right"p>'
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
            buttons += '<div class="col-md-6"><a href="'+url+'" class="btn btn-'+val.class+' btn-block"><span class="mdi mdi-'+val.icon+'"></span> '+val.nombre+'</a></div>'
        });
        $('#modalContent').empty();
        $('#modalContent').append(buttons);
    }
    </script>
@endsection
