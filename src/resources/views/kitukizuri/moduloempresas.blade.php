<x-app-layout>
    <x-slot name="header">
        <h3 class="title">
            {{ __($titulo) }}
        </h3>
    </x-slot>
    
    <div  class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12 mb-5">
                    <strong>{{ __('Modules') }}</strong> <br>
                    <small> {{ __('the selected modules will be the only ones the company has access to.') }} </small>
                </div>

                @foreach($modulos as $m)
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <input id="{{$m->moduloid}}" name="modulos[]" value="{{$m->moduloid}}" type="checkbox" {{in_array($m->moduloid, $moduloEmpresas) ? 'checked' : ''}}>
                            <label for="{{$m->moduloid}}">{{$m->nombre}}</label>
                        </div>
                    </div>
                @endforeach

                <div class="col-12 text-center">
                    <button class="btn btn-outline-success" id="save">{{ __('Save') }}</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                $('#save').click(function() {
                    var modulos = [];
                    $('input[name="modulos[]"]:checked').each(function() {
                        modulos.push($(this).val());
                    });

                    const data = {
                        _token: "{{ csrf_token() }}",
                        empresa: "{{$empresa}}",
                        modulos: modulos
                    };

                    $.post("{{route('moduloempresas.store')}}", data).done(response => {
                        location.replace("{{route('empresas.index')}}");
                    }).fail(error => {
                        alert(error);
                    });
                });
            });
        </script>
    @endpush

</x-app-layout>

