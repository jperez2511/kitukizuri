
@push('styles')
    <style>
        .text-package{color:#d4bc89!important}
        .text-permission{color:#81b622!important}
        .btn-icon-split{
            padding:0;
            overflow:hidden;
            display:inline-flex;
            align-items:stretch;
            justify-content:center
        }
        .btn-icon-split .icon{
            background:rgba(0,0,0,.15);
            display:inline-block;
            padding:.375rem .75rem
        }
        .btn-icon-split .text{
            width: 100%;
            display:inline-block;
            padding:.375rem .75rem;
        }
        .btn-group-sm>.btn-icon-split.btn .icon,.btn-icon-split.btn-sm .icon{padding:.25rem .5rem}
        .btn-group-sm>.btn-icon-split.btn .text,.btn-icon-split.btn-sm .text{padding:.25rem .5rem}
        .btn-group-lg>.btn-icon-split.btn .icon,.btn-icon-split.btn-lg .icon{padding:.5rem 1rem}
        .btn-group-lg>.btn-icon-split.btn .text,.btn-icon-split.btn-lg .text{padding:.5rem 1rem}
        .hide{display: none;}

        .btn-tertiary{
            color:#fff;background-color:#0d7195;
            border-color:#0d7195
        }
        .btn-tertiary:hover{
            color:#fff;
            background-color:#0e7aa1;
            border-color:#0d7095
        }
        .btn-tertiary.focus,.btn-tertiary:focus{
            color:#fff;
            background-color:#0e7aa1;
            border-color:#0d7095;
            -webkit-box-shadow:0 0 0 .15rem rgba(53, 164, 205,.5);
            box-shadow:0 0 0 .15rem rgba(53, 164, 205,.5)
        }
        .btn-tertiary.disabled,.btn-tertiary:disabled{
            color:#fff;
            background-color:#0d7195;
            border-color:#0d7195
        }
        .btn-tertiary:not(:disabled):not(.disabled).active,
        .btn-tertiary:not(:disabled):not(.disabled):active,
        .show>.btn-tertiary.dropdown-toggle{
            color:#fff;
            background-color:#0d7095;
            border-color:#0c6889
        }
        .btn-tertiary:not(:disabled):not(.disabled).active:focus,
        .btn-tertiary:not(:disabled):not(.disabled):active:focus,
        .show>.btn-tertiary.dropdown-toggle:focus{
            -webkit-box-shadow:0 0 0 .15rem rgba(53, 164, 205,.5);
            box-shadow:0 0 0 .15rem rgba(53, 164, 205,.5)
        }
    </style>
@endpush

<x-app-layout>
     <x-slot name="header">
        <h3 class="title">
            {{ __($titulo) }}
        </h3>
    </x-slot>
    <div class="components-preview mx-auto">
        <div class="card card-bordered card-preview">
            <div class="card-inner"> 
                <div class="row">
                    <div class="col-12">
                        <strong>Conexiones encontradas</strong>
                        <hr>
                    </div>

                    @foreach ($connections as $dbName => $connection)
                        @php
                            if(str_contains($connection['driver'], 'mongo')){
                                $connection['driver'] = 'mongo';
                            }        
                        @endphp
                        <div class="col-6">
                            <div class="form-group">
                                <a href="javascript:void(0)" class="mb-3 btn btn-block btn-{{ $colors[$connection['driver']]['color'] }}" onclick="goConnection('{{ encrypt($dbName) }}')">
                                    <i class="icon {{ $colors[$connection['driver']]['icono'] }} fa-xl"></i><span>{{ $dbName }}</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('scripts')

        <script>

            var url = '{{ route('database.index') }}'
            
            function goConnection(idConnection) {
                window.location.assign(url+'?c='+idConnection);
            }

            function goTenants(idTenants, driver) {
                window.location.assign(url+'?ct='+idTenants+'&d='+driver);
            }
        </script>
    @endpush

</x-app-layout>