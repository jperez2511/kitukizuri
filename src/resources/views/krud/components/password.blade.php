@props([
    'nombre' => '',
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
        <label>Confirmar {{$nombre}}</label>
        <input 
            type="password"
            name="{{$nombre}}"
            id="{{$nombre}}_2" 
            onkeyup="comparar('{{$nombre}}')" 
            class="form-control">
    </div>
</div>








