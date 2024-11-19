<x-app-layout>
    <x-slot name="header">
        <h3 class="title">
            {{ __($titulo) }}
        </h3>
    </x-slot>

    <x-banner />

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 text-right">
                    <a href="#" id="todos" title="">{{ __('Seleccionar Todos') }}</a> | 
                    <a href="#" id="ninguno" title="">{{ __('Ninguno') }}</a> | 
                    <a href="#" id="todosPermisos" title="">{{ __('Expandir Módulos') }}</a> | 
                    <a href="#" id="ningunoPermisos" title="">{{ __('Contraer Módulos') }}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-body text-center">
                <div class="row">
                    <div id="accordion" class="accordion">
                        @foreach ($modulos as $m)
                            <div class="accordion-item">
                                <a href="#" class="accordion-head" data-bs-toggle="collapse" data-bs-target="#accordion-item-{{ $m->moduloid }}">
                                    <h6 class="title">{{ $m->nombre }}</h6>
                                    <span class="accordion-icon"></span>
                                </a>
                                <div class="accordion-body collapse" id="accordion-item-{{ $m->moduloid }}" data-bs-parent="#accordion">
                                    <div class="accordion-inner">
                                        <div class="row">
                                            @foreach ($m->modulopermiso as $mp)
                                                <div class="col-3">
                                                    <input class="check" id="{{$mp->modulopermisoid}}" name="permisos[]" value="{{$mp->modulopermisoid}}" type="checkbox" {{in_array($mp->modulopermisoid, $rmp) ? 'checked' : ''}}>
                                                    <label for="{{$mp->modulopermisoid}}"></label>    
                                                    {{ $mp->permisos()->first()?->nombre }}
                                                </div>
                                            @endforeach          
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="col-md-12 mt-5 text-center">
                        <div class="form-group">
                            <button class="btn btn-outline-success" id="guardar">{{ __('Guardar') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                
                $('#todos').click(function (e) { 
                    e.preventDefault();
                    $('.check').prop('checked', true);
                });

                $('#ninguno').click(function (e) { 
                    $('.check').prop('checked', false);
                });

                $('#todosPermisos').click(function (e) { 
                    e.preventDefault();
                    $('.accordion-body').addClass('show');
                });

                $('#ningunoPermisos').click(function (e) { 
                    e.preventDefault();
                    $('.accordion-body').removeClass('show');
                });

                $('#guardar').click(function (e) { 
                    e.preventDefault();
                    let data = {};
                    data.permisos = [];
                    $('.check:checked').each(function() {
                        data.permisos.push($(this).val());
                    });
                    data._token = '{{ csrf_token() }}';
                    data.id = $.urlParam('id');
                    $.post("{{ route('rolpermisos.store') }}", data, function(response) {
                        window.location.href = "{{ route('roles.index') }}";
                    });
                });

                $.urlParam = function(name){
                    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
                    if (results==null){
                        return null;
                    }
                    else{
                        return decodeURI(results[1]) || 0;
                    }
                }
            });
        </script>
    @endpush

</x-app-layout>
