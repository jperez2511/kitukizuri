@extends($layout)

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('kitukizuri/libs/calendar/css/tui-time-picker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('kitukizuri/libs/calendar/css/tui-date-picker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('kitukizuri/libs/calendar/css/tui-calendar.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('kitukizuri/libs/calendar/css/default.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('kitukizuri/libs/calendar/css/icons.css')}}">
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex">
            <div id="lnb">
                <div class="lnb-new-schedule text-center">
                    <button id="btn-new-schedule" type="button" class="btn btn-primary" data-toggle="modal">Agregar Evento</button>
                </div>
                <div id="lnb-calendars" class="lnb-calendars">
                    <div>
                        <div class="lnb-calendars-item">
                            <label>
                                <input class="tui-full-calendar-checkbox-square" type="checkbox" value="all" checked>
                                <span></span>
                                <strong>Ver todo</strong>
                            </label>
                        </div>
                    </div>
                    <div id="calendarList" class="lnb-calendars-d1">
                    </div>
                </div>
            </div>
            <div id="right">
                <div id="menu">
                    <span class="dropdown">
                        <button id="dropdownMenu-calendarType" class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <i id="calendarTypeIcon" class="calendar-icon ic_view_month" style="margin-right: 4px;"></i>
                            <span id="calendarTypeName">Dropdown</span>&nbsp;
                        </button>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu-calendarType">
                            <li role="presentation"><a class="dropdown-menu-title" role="menuitem" data-action="toggle-daily"><i class="calendar-icon ic_view_day"></i>Día</a>
                            </li>
                            <li role="presentation">
                                <a class="dropdown-menu-title" role="menuitem" data-action="toggle-weekly">
                                    <i class="calendar-icon ic_view_week"></i>Semana
                                </a>
                            </li>
                            <li role="presentation">
                                <a class="dropdown-menu-title" role="menuitem" data-action="toggle-monthly">
                                    <i class="calendar-icon ic_view_month"></i>Mes
                                </a>
                            </li>
                            <li role="presentation">
                                <a class="dropdown-menu-title" role="menuitem" data-action="toggle-weeks2">
                                    <i class="calendar-icon ic_view_week"></i>2 Semanas
                                </a>
                            </li>
                            <li role="presentation">
                                <a class="dropdown-menu-title" role="menuitem" data-action="toggle-weeks3">
                                    <i class="calendar-icon ic_view_week"></i>3 Semanas
                                </a>
                            </li>
                            <li role="presentation" class="dropdown-divider"></li>
                            <li role="presentation">
                                <a role="menuitem" data-action="toggle-workweek">
                                    <input type="checkbox" class="tui-full-calendar-checkbox-square" value="toggle-workweek" checked>
                                    <span class="checkbox-title"></span>Mostrar fines de semana
                                </a>
                            </li>
                            <li role="presentation">
                                <a role="menuitem" data-action="toggle-start-day-1">
                                    <input type="checkbox" class="tui-full-calendar-checkbox-square" value="toggle-start-day-1">
                                    <span class="checkbox-title"></span>Iniciar semana en lunes
                                </a>
                            </li>
                            <li role="presentation">
                                <a role="menuitem" data-action="toggle-narrow-weekend">
                                    <input type="checkbox" class="tui-full-calendar-checkbox-square" value="toggle-narrow-weekend">
                                    <span class="checkbox-title"></span>Estrechar fines de semana
                                </a>
                            </li>
                        </ul>
                    </span>
                    <span id="menu-navi">
                        <button type="button" class="btn btn-default btn-sm move-today" data-action="move-today">Hoy</button>
                        <button type="button" class="btn btn-default btn-sm move-day" data-action="move-prev">
                            <i class="calendar-icon ic-arrow-line-left" data-action="move-prev"></i>
                        </button>
                        <button type="button" class="btn btn-default btn-sm move-day" data-action="move-next">
                            <i class="calendar-icon ic-arrow-line-right" data-action="move-next"></i>
                        </button>
                    </span>
                    <span id="renderRange" class="render-range"></span>
                </div>
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="eventCalendar" tabindex="-1" role="dialog" aria-labelledby="eventCalendarTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <strong class="modal-title" id="eventCalendarTitle">Agendar Evento</strong>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route($action)}}" method="POST">
                    @csrf
                    @php $nombreCampos = [] @endphp
                    @foreach($campos as $c)
                        @php 
                            $nombre = array_key_exists('campoReal', $c) ? $c['campoReal'] : $c['campo']; 
                            $nombreCampos[] = $nombre;
                        @endphp
                        @if($c['tipo'] != 'password')
                            <div class="{!! !empty($c['columnclass']) ? $c['columnclass'] : 'col-md-6' !!}">
                                <div class="form-group">
                                    @if($c['tipo'] != 'bool')
                                        <label for="{{$nombre}}">{{$c['nombre']}}</label>
                                    @endif
                                    
                                    @if((($c['tipo'] == 'string' || $c['tipo'] == 'date') && $c['edit'] == true))
                                        <input type="{{$c['tipo'] == 'date' ? 'date' : 'text'}}" name="{{$nombre}}" id="{{$nombre}}" value="{{!empty($c['value']) ? $c['value'] : null}}" class="form-control {!! !empty($c['inputclass']) ? $c['inputclass'] : null !!}">
                                    @elseif($c['tipo'] == 'numeric')
                                        <input type="text" name="{{$nombre}}" id="{{$nombre}}" value="{{!empty($c['value']) ? number_format($c['value'])  : null}}" onfocusout="$(this).val(number_format($(this).val(), 2))" class="form-control">
                                    @elseif($c['tipo'] == 'combobox')
                                        <select name="{{$nombre}}" id="{{$nombre}}" class="form-control">
                                            @foreach($c['options'] as $op)
                                                <option value="{{$op[0]}}" {{!empty($c['value']) && $op[0] == $c['value'] ? 'selected' : ''}}>{{$op[1]}}</option>
                                            @endforeach
                                        </select>    
                                    @elseif($c['tipo'] == 'icono')
                                        <button class="btn btn-default form-control" name="{{$nombre}}" data-iconset="glyphicon" {{!empty($c['value']) ? 'data-icon='.$c['value'] : null}} role="iconpicker"></button>
                                    @elseif($c['tipo'] == 'textarea')
                                        <textarea name="{{$nombre}}" class="form-control" id="{{$nombre}}" cols="30" rows="10">{{!empty($c['value']) ? $c['value'] : null}}</textarea>
                                    @elseif($c['tipo'] == 'enum')
                                        <select name="{{$nombre}}" class="form-control">
                                            @foreach($c['enumarray'] as $v)
                                                <option value="{{$v}}">{{$v}}</option>
                                            @endforeach
                                        </select>
                                    @elseif($c['tipo'] == 'image' || $c['tipo'] == 'file' || $c['tipo'] == 'file64')
                                        <input type="file" name="{{$nombre}}" class="form-control" id="{{$nombre}}">
                                    @elseif($c['tipo'] == 'bool')
                                        <input type="checkbox" name="{{$nombre}}" id="{{$nombre}}" {{!empty($c['value']) ? 'checked' : null}}>
                                        <label for="{{$nombre}}">{{$c['nombre']}}</label>
                                    @endif 
                                </div>
                            </div>
                        @else
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{$c['nombre']}}</label>
                                            <input type="password" name="{{$nombre}}" id="{{$nombre}}" onkeyup="comparar('{{$nombre}}')" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Confirmar {{$c['nombre']}}</label>
                                            <input type="password" name="" id="{{$nombre}}_2" onkeyup="comparar('{{$nombre}}')" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    <a href="javascript:void(0)" class="btn btn-danger btn-space" onclick="clearInputs()"> Cancelar</a>
                    <button type="submit" class="btn btn-space btn-success" id="guardar">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{asset('kitukizuri/libs/calendar/js/tui-code-snippet.min.js')}}"></script>
    <script src="{{asset('kitukizuri/libs/calendar/js/tui-time-picker.min.js')}}"></script>
    <script src="{{asset('kitukizuri/libs/calendar/js/tui-date-picker.min.js')}}"></script>
    <script src="{{asset('kitukizuri/libs/calendar/js/moment.min.js')}}"></script>
    <script src="{{asset('kitukizuri/libs/calendar/js/chance.min.js')}}"></script>
    <script src="{{asset('kitukizuri/libs/calendar/js/tui-calendar.min.js')}}"></script>
    <script src="{{asset('kitukizuri/libs/calendar/js/calendars.js')}}"></script>
    <script src="{{asset('kitukizuri/libs/calendar/js/schedules.js')}}"></script>
    <script>
        var defaultView = '{{$defaultView}}';
        function number_format(amount, decimals) {
		    amount += ''; // por si pasan un numero en vez de un string
		    amount = parseFloat(amount.replace(/[^0-9\.]/g, '')); // elimino cualquier cosa que no sea numero o punto

		    decimals = decimals || 0; // por si la variable no fue fue pasada

		    // si no es un numero o es igual a cero retorno el mismo cero
		    if (isNaN(amount) || amount === 0)
		        return parseFloat(0).toFixed(decimals);

		    // si es mayor o menor que cero retorno el valor formateado como numero
		    amount = '' + amount.toFixed(decimals);

		    var amount_parts = amount.split('.'),
		        regexp = /(\d+)(\d{3})/;

		    while (regexp.test(amount_parts[0]))
		        amount_parts[0] = amount_parts[0].replace(regexp, '$1' + ',' + '$2');

		    return amount_parts.join('.');
		}

        function clearInputs() {
            $('#eventCalendar').modal('hide')
            @foreach($nombreCampos as $campo)
                $('#{{ $campo }}').val('');
            @endforeach
        }
    </script>
    <script src="{{asset('kitukizuri/libs/calendar/js/app.js')}}"></script>
@endsection
