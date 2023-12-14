@extends('krud::layout')

@section('styles')
    <style>
        .wrap-text {
            word-break: break-all; /* Esto forzará la ruptura de palabras */
            word-wrap: break-word; /* Esto permite que las palabras largas se ajusten */
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 text-center">
            <h3>Log</h3>
        </div>
        <div class="col-12">
            <table class="table">
                <thead>
                    <th>Fecha</th>
                    <th>Mensaje</th>
                </thead>
                <tbody>
                    @foreach ($logs as $item)
                    <tr>
                        <td>{{ $item['date'] }}</td>
                        <td class="wrap-text" style="width: 33.33%;">{!! htmlspecialchars($item['msg']) !!}</td>
                        <td class="wrap-text" >
                            @foreach (explode('#', $item['stacktrace']) as $value)
                                {{ $value }} <br>
                            @endforeach
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection