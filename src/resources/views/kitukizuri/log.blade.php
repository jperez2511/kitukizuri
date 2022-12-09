@extends('krud::layout')

@section('content')
    <div class="row">
        <div class="col-12 text-center">
            <h3>Log</h3>
        </div>
        <div class="col-12">
            <table class="table">
                <thead>
                    <th>Tipo</th>
                    <th>Descripción</th>
                </thead>
                <tbody>
                    @foreach ($logs as $log)
                        <tr>
                            <td>{{dd($log)}}</td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection