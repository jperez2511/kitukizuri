@extends($layout)

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('kitukizuri/libs/calendar/css/tui-time-picker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('kitukizuri/libs/calendar/css/tui-date-picker.css')}}">
    <link href="{{asset('kitukizuri/libs/calendar/css/tui-calendar.min.css')}}" rel="stylesheet" type="text/css">
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
                        <button id="dropdownMenu-calendarType" class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="true">
                            <i id="calendarTypeIcon" class="calendar-icon ic_view_month" style="margin-right: 4px;"></i>
                            <span id="calendarTypeName">Dropdown</span>&nbsp;
                            <i class="calendar-icon tui-full-calendar-dropdown-arrow"></i>
                        </button>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu-calendarType">
                            <li role="presentation">
                                <a class="dropdown-menu-title" role="menuitem" data-action="toggle-daily">
                                    <i class="calendar-icon ic_view_day"></i>Daily
                                </a>
                            </li>
                            <li role="presentation">
                                <a class="dropdown-menu-title" role="menuitem" data-action="toggle-weekly">
                                    <i class="calendar-icon ic_view_week"></i>Weekly
                                </a>
                            </li>
                            <li role="presentation">
                                <a class="dropdown-menu-title" role="menuitem" data-action="toggle-monthly">
                                    <i class="calendar-icon ic_view_month"></i>Month
                                </a>
                            </li>
                            <li role="presentation">
                                <a class="dropdown-menu-title" role="menuitem" data-action="toggle-weeks2">
                                    <i class="calendar-icon ic_view_week"></i>2 weeks
                                </a>
                            </li>
                            <li role="presentation">
                                <a class="dropdown-menu-title" role="menuitem" data-action="toggle-weeks3">
                                    <i class="calendar-icon ic_view_week"></i>3 weeks
                                </a>
                            </li>
                            <li role="presentation" class="dropdown-divider"></li>
                            <li role="presentation">
                                <a role="menuitem" data-action="toggle-workweek">
                                    <input type="checkbox" class="tui-full-calendar-checkbox-square" value="toggle-workweek" checked>
                                    <span class="checkbox-title"></span>Show weekends
                                </a>
                            </li>
                            <li role="presentation">
                                <a role="menuitem" data-action="toggle-start-day-1">
                                    <input type="checkbox" class="tui-full-calendar-checkbox-square" value="toggle-start-day-1">
                                    <span class="checkbox-title"></span>Start Week on Monday
                                </a>
                            </li>
                            <li role="presentation">
                                <a role="menuitem" data-action="toggle-narrow-weekend">
                                    <input type="checkbox" class="tui-full-calendar-checkbox-square" value="toggle-narrow-weekend">
                                    <span class="checkbox-title"></span>Narrower than weekdays
                                </a>
                            </li>
                        </ul>
                    </span>
                    <span id="menu-navi">
                        <button type="button" class="btn btn-default btn-sm move-today" data-action="move-today">Today</button>
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
    <!-- <script src="./js/theme/dooray.js"></script> -->
    <script src="{{asset('kitukizuri/libs/calendar/js/app.js')}}"></script>
@endsection