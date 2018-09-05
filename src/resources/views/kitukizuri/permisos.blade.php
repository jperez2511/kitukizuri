@extends('layouts.app')

@section('content')
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="panel-heading panel-heading-divider">
				Permisos
				<span class="panel-subtitle">estos permisos pertenecen al modulo, seran agregados pero no activados.</span>
			</div>
			<form action="/permisos" method="post">
				{{csrf_field()}}
				<input type="hidden" name="modulo" value="{{$modulo}}">
				@foreach($permisos as $p)
					<div class="col-md-3">
						<div class="be-checkbox be-checkbox-color {{$p->color}} inline">
							<input id="{{$p->permisoid}}" name="permisos[]" value="{{$p->permisoid}}" type="checkbox" {{in_array($p->permisoid, $mp) ? 'checked' : ''}}>
							<label for="{{$p->permisoid}}">{{$p->nombre}}</label>
						</div>
					</div>
				@endforeach
				<div class="clearfix"></div>
				<div style=" height: 20px;"></div>
				<div class="col-md-12 text-center">
					<input type="submit" class="btn btn-success" value="Guardar">
				</div>
			</form>
		</div>
	</div>
@stop