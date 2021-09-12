@extends($layout)

@section('content')
<div class="col-md-12 text-center">
    <h1>
        @if ($type == 'warning')
            <span class="mdi mdi-alert"></span>
        @endif
    </h1>
    <h3>{{ $msg }}</h3>
</div>
@stop