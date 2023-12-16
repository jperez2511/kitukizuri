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
    <div class="card">
        <div class="card-body">
            hola mundo
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12 text-center">
                    <h3>Log</h3>
                </div>
                <div class="col-12">
                    <table class="table">
                        <thead>
                            <th>Fecha</th>
                            <th>Mensaje</th>
                            <th>Stack Trace</th>
                        </thead>
                        <tbody>
                            @php $contador = 0; @endphp
                            @foreach ($logs as $item)
                            <tr>
                                <td>{{ $item['date'] }}</td>
                                <td class="wrap-text" style="width: 33.33%;">{!! htmlspecialchars($item['msg']) !!}</td>
                                <td class="wrap-text" style="font-size: 8px;">
                                    <div class="accordion" id="accordionExample{{ $contador }}">
                                        <div class="accordion-item">
                                          <h2 class="accordion-header" id="headingOne{{ $contador }}">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne{{ $contador }}" aria-expanded="false" aria-controls="collapseOne">
                                                {{ explode('#', $item['stacktrace'])[0] }}
                                            </button>
                                          </h2>
                                          <div id="collapseOne{{ $contador }}" class="accordion-collapse collapse" aria-labelledby="headingOne{{ $contador }}" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                @foreach (explode('#', $item['stacktrace']) as $value)
                                                    {{ $value }} <br>
                                                    <hr>
                                                @endforeach
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                </td>
                            </tr>
                            @php $contador++; @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection