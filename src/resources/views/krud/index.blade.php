
@push('styles')
    <style>
        .krud-index .datatable-wrap {
            border: 1px solid var(--bs-border-color, #dbdfea);
            border-radius: 0.375rem;
            overflow: hidden;
        }

        .krud-index table.dataTable > thead > tr > th {
            white-space: nowrap;
        }

        .krud-index .dataTables_wrapper .dataTables_filter input,
        .krud-index .dt-container .dt-search input {
            min-width: min(100%, 260px);
        }

        .krud-index .krud-actions-col,
        .krud-index .krud-actions-col .krud-action-group {
            white-space: nowrap;
        }

        .krud-index .krud-action-group .btn {
            min-width: 2rem;
            min-height: 2rem;
            padding: 0.25rem 0.45rem;
        }

        .krud-index .krud-action-group .btn i {
            font-size: 0.875rem;
        }

        .krud-index .dt-buttons .buttons-create:before {
            content: "+";
            font-family: inherit;
            font-size: 1.125rem;
            font-weight: 600;
            line-height: 1;
        }

        @media (max-width: 575.98px) {
            .krud-index .datatable-filter .d-flex {
                flex-wrap: wrap;
                justify-content: flex-start !important;
            }

            .krud-index .dataTables_wrapper .dataTables_filter input,
            .krud-index .dt-container .dt-search input {
                min-width: 100%;
            }
        }
    </style>
@endpush

<x-app-layout>
    <x-slot name="header">
        <h3 class="title">
            {{ __($titulo) }}
        </h3>
    </x-slot>

    <div class="col-12 krud-index">
        <div class="card card-bordered card-stretch">
            <div class="card-inner">
                <div class="table-responsive">
                    <table id="table1" class="table table-bordered table-hover align-middle nowrap w-100">
                        <thead>
                            <tr>
                                @foreach($columnas as $c)
                                    <th>{{ $c }}</th>
                                @endforeach
                                <th width="10%" class="text-center">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Opciones</h5>
                    <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
                </div>
                <div class="modal-body">
                    <div class="row" id="modalContent"></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            var uriArgs = (String(window.location).includes('?') ? '?'+String(window.location).split('?')[1] : '');
            document.addEventListener('DOMContentLoaded', function() {
                var actionColumnIndex = {{ count($columnas) }};
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
                    responsive: true,
                    order     : [],
                    autoWidth : false,
                    serverSide: true,
                    processing: true,
                    ajax      : {
                        url : "{{$ruta}}/0"+uriArgs,
                        type: 'GET',
                    },
                    buttons: {
                        buttons: [
                            @if(!empty($defaultBtnDT))
                                @foreach($defaultBtnDT as $btn)
                                    '{{ $btn }}',
                                @endforeach
                            @else
                                'copy', 'excel', 'pdf', 'colvis',
                            @endif
                            @if(in_array('create', $permisos))
                                {
                                    className: 'buttons-create',
                                    attr: {
                                        title: '{{ __('Create') }}' // Tooltip en el botón
                                    },
                                    action: function ( e, dt, node, config ) {
                                        window.location.href = '{{$ruta}}/create'+uriArgs;
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
                    columnDefs: [
                        {
                            targets   : actionColumnIndex,
                            orderable : false,
                            searchable: false,
                            className : 'krud-actions-col text-center align-middle'
                        }
                    ],
                    sDom: '<"row justify-between g-2 with-export"<"col-12 col-sm-6 col-md-4 text-start"f><"col-12 col-sm-6 col-md-8 text-start text-sm-end"<"datatable-filter"<"d-flex justify-content-sm-end g-2"<"dt-export-buttons d-flex align-center flex-wrap"<"dt-export-title d-none d-md-inline-block">B>l>>>><"datatable-wrap mt-2 mb-2"t><"row align-items-center g-2"<"col-12 col-md-9"p><"col-12 col-md-3 text-start text-md-end"i>>'
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

                var confirmResult = confirm('{{ __('Are you sure you want to delete this item?') }}');

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
                $.each(botones, function(index, val) {
                    var url = val.url.replace('{id}', id);
                    buttons += '<div class="col-md-6 mb-3"><a href="'+url+'" class="btn btn-'+val.class+' w-100"><i class="'+val.icon+'"></i> '+val.nombre+'</a></div>'
                });
                $('#modalContent').empty();
                $('#modalContent').append(buttons);
                var myModal = new bootstrap.Modal(document.getElementById('myModal'))
                myModal.show()
            }
        </script>
    @endpush

</x-app-layout>
