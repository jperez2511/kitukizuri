@extends($layout)

@section('content')
    <div class="row">
        <div class="col-12 text-center">
            <strong>Conexiones pre-configuradas encontradas</strong>
            <hr>
        </div>
        @foreach ($connections as $dbName => $connection)
            <div class="col-4">
                <div class="form-group">
                    <a href="javascript:void(0)" class="btn btn-block btn-{{ $colors[$connection['driver']]['color'] }}" onclick="goConnection('{{ encrypt($dbName) }}')">
                        <i class="{{ $colors[$connection['driver']]['icono'] }} fa-xl"></i> <br>
                        {{ $dbName }}
                    </a>
                </div>
            </div>
        @endforeach
    </div>


    @if (!empty($tenants))
        <div class="col-12 text-center">
            <strong>Conexiones encontradas en tenants</strong>
            <hr>
        </div>

        @foreach ($tenants as $connection)
            <div class="col-6">
                <a href="javascript:void(0)" class="btn btn-block btn-primary" onclick="goTenants('{{ encrypt($connection->tenant_id) }}', '{{ encrypt('mysql') }}')">
                    <i class="fa-duotone fa-server fa-xl"></i> <br>
                    {{ $connection->db }}
                </a>
                @if (!empty($connection->mongo_db))
                    <a href="javascript:void(0)" class="btn btn-block btn-success" onclick="goTenants('{{ encrypt($connection->tenant_id)}}', '{{ encrypt('mongo') }}')">
                        <i class="fa-duotone fa-server fa-xl"></i> <br>
                        {{ $connection->mongo_db }}
                    </a>
                @endif
            </div>
        @endforeach
    @endif

@endsection

@section('scripts')
    
    <script>

        var url = '{{ route('database.index') }}'
        
        function goConnection(idConnection) {
            window.location.assign(url+'?c='+idConnection);
        }

        function goTenants(idTenants, driver) {
            window.location.assign(url+'?ct='+idTenants+'&d='+driver);
        }
    </script>

@endsection