<!-- el tipo tabla por el momento solo muestra elementos ordenados -->

@props([
    'columnClass' => 'col-md-6',
    'collection'  => [],
    'headers'     => [],
    'rows'        => [],
])

@php
    if(!empty($collection)) {
        $collection = json_decode($collection);
        
        if (!is_array($data) || empty($data)) {
            return response()->json(['error' => 'Invalid JSON data']);
        }

        // Generar headers (las claves del primer objeto)
        $headers = array_keys($data[0]);

        // Generar filas (los valores de cada objeto)
        $rows = array_map(function ($item) {
            return array_values($item);
        }, $data)
    } 

@endphp

<div class="{{$columnClass}} mb-3">
    <table class="table">
        <thead>
            <tr>
                @foreach($headers as $header)
                    <th>{{$header}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr>
                    @foreach($row as $column)
                        <td>{{$column}}</td>
                    @endforeach
                <tr>
            @endforeach
        </tbody>
    </table>
</div>