<!-- el tipo tabla por el momento solo muestra elementos ordenados -->

@props([
    'columnClass'  => 'col-md-6',
    'name'         => '',
    'id'           => '',
    'collection'   => [],
    'headers'      => [],
    'rows'         => [],
    'dependencies' => []
])

@php
    if(!empty($collection)) {
        $data = json_decode($collection);
        
        if (!is_array($data) || empty($data)) {
            return response()->json(['error' => 'Invalid JSON data']);
        }

        // Generar headers (las claves del primer objeto)
        $headers = array_keys(get_object_vars($data[0]));

        // Generar filas (los valores de cada objeto)
        $rows = array_map(function ($item) {
            return array_values(get_object_vars($item));
        }, $data);
    } 

@endphp

<div class="{{$columnClass}} mb-3" id="{{$id}}-container" name="{{$name}}">
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