<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    
    <!-- Data Table -->
    @if (!empty($template) && in_array('datatable', $template))
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
        <style>
            div.dataTables_wrapper div.dataTables_filter{
                text-align: justify;
                padding-left: 15px;
            }
            .dataTables_info{
                padding-left:15px;
            }
            .dataTables_paginate{
                padding-right: 15px;
            }
        </style>
    @endif
    
</head>
<body>
    @yield('content')
</body>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

<!-- Data Table -->
@if (!empty($template) && in_array('datatable', $template))
    <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
@endif

<!-- Scripts Custom -->
@yield('scripts')

</html>