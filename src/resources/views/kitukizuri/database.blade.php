@extends($layout)

@section('content')
    <div class="row">
        <div class="col-12 text-center">
            <strong>Conexiones encontradas</strong>
            <hr>
        </div>
        @foreach ($connections as $dbName => $connection)
            <div class="col-2">
                <div class="form-group">
                    <a href="#" class="btn btn-block btn-{{ $colors[$connection['driver']] }}">{{ $dbName }}</a>
                </div>
            </div>
        @endforeach
    </div>

@endsection