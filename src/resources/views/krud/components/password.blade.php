@props([
    'nombre' => '',
    'label',
])

@push('styles')
    <style>
        .hide{
            display: none;
        }
    </style>
@endpush

<div class="col-md-6">
    <div class="form-group mb-3">
        <div class="form-control-wrap">
            <label class="form-label-outlined" for="{{ $nombre }}">{{$label}}</label>
            <input 
                type="password" 
                name="{{$nombre}}"
                id="{{$nombre}}"
                onkeyup="comparar('{{$nombre}}')" 
                class="form-control form-control-outlined">
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <div class="form-control-wrap">
            <label class="form-label-outlined" for="{{ $nombre }}_2">Confirmar {{$label}}</label>
            <input 
                type="password"
                name="{{$nombre}}"
                id="{{$nombre}}_2" 
                onkeyup="comparar('{{$nombre}}')" 
                class="form-control form-control-outlined">
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // --------------------------------
        // Compara dos valores se utiliza
        // para validar las contraseñas
        // ---------------------------------
        function comparar(nombre){
            if($('#'+nombre).val() != $('#'+nombre+'_2').val()){
                $('#msgError').show();
                $('#guardar').hide();
            }else{
                $('#msgError').hide();
                $('#guardar').show();
            }
        }
    </script>
@endpush





