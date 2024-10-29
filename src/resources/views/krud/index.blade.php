
<x-app-layout>
    <x-slot name="header">
        <h3 class="title">
            {{ __($titulo) }}
        </h2>
    </x-slot>

    <x-banner />
    
    <div class="card">
        <div class="card-inner">    
            <table id="table1" class="table table-bordered">
                <thead>
                    @foreach($columnas as $c)
                        <th>{{ $c }}</th>
                    @endforeach
                    <th width="10%">{{ __('Actions') }}</th>
                </thead>
                <tbody>                        
                </tbody>
            </table>
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
            <div class="modal-body">
                <div class="row" id="modalContent"></div>
            </div>
          </div>
        </div>
    </div>

    @push('scripts')
        <script>
           document.addEventListener('DOMContentLoaded', function() {
                $('#table1').DataTable({
                    language: {
                        search           : "",
                        searchPlaceholder: "{{ __('Search') }}",
                        lengthMenu       : "<div class='form-control-select'> _MENU_ </div>",
                        info             : "_START_ -_END_ of _TOTAL_",
                        infoEmpty        : "0",
                        infoFiltered     : "( Total _MAX_  )",
                        paginate         : {
                            "first": "{{ __('First') }}",
                            "last": "{{ __('Last') }}",
                            "next": "{{ __('Next') }}",
                            "previous": "{{ __('Prev') }}"
                        },
                    },
                    sortable  : false,
                    serverSide: true,
                    processing: true,
                    ajax      : {
                        url : "{{$ruta}}/0"+(String(window.location).includes('?') ? '?'+String(window.location).split('?')[1] : ''),
                        type: 'GET',
                    },
                    buttons: {
                        buttons: [
                            'copy', 'excel', 'pdf', 'colvis',
                            @if(in_array('create', $permisos))
                                {
                                    className: 'btn-success buttons-create',
                                    attr: {
                                        title: '{{ __('Create') }}' // Tooltip en el botón
                                    },
                                    action: function ( e, dt, node, config ) {
                                        window.location.href = '{{$ruta}}/create';
                                    }
                                },
                            @endif
                            @if(!empty($botonesDT))
                                @foreach($botonesDT as $boton)
                                    {
                                        className: '{{ $boton['class'] }}',
                                        attr: {
                                            title: '{{ __($boton['text']) }}'
                                        },
                                        action: {!! $boton['action'] !!}
                                    },
                                @endforeach
                            @endif   
                        ],
                    },
                    sDom: '<"row justify-between g-2 with-export"<"col-7 col-sm-4 text-start"f><"col-5 col-sm-8 text-end"<"datatable-filter"<"d-flex justify-content-end g-2" <"dt-export-buttons d-flex align-center"<"dt-export-title d-none d-md-inline-block">B>l>>>><"datatable-wrap mb-2"t><"row align-items-center"<"col-7 col-sm-12 col-md-9"p><"col-5 col-sm-12 col-md-3 text-start text-md-end"i>>',
                    initComplete: function() {
                        $('.buttons-create').removeClass('btn-secondary');
                    }
                })
            });
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
    @endpush

</x-app-layout>
