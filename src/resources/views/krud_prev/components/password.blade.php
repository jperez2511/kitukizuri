@props([
    'nombre' => '',
    'label',
])

<div class="col-md-6">
    <div class="form-group">
        <label>{{$label}}</label>
        <input 
            type="password" 
            name="{{$nombre}}"
            id="{{$nombre}}"
            onkeyup="comparar('{{$nombre}}')" 
            class="form-control">
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label>Confirmar {{$label}}</label>
        <input 
            type="password"
            name="{{$nombre}}"
            id="{{$nombre}}_2" 
            onkeyup="comparar('{{$nombre}}')" 
            class="form-control">
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
                $('#msgError').removeClass('hide');
                $('#guardar').hide();
            }else{
                $('#msgError').addClass('hide');
                $('#guardar').show();
            }
        }
    </script>
@endpush





