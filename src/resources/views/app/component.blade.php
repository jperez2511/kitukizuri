@extends('krud.layout')

@section('styles')
   
@endsection

@section('content')
    <div id="vue">
        <{{ $componente }} :datos="{{ json_encode($props) }}"></{{ $componente }}>
    </div>
@endsection

@section('scripts')
    @vite(['resources/js/app.js'])
@endsection