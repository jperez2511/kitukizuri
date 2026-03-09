@props(['dash' => false])

@php
    $dashliteContext = dashliteLayoutContext($dash);
    $layout = $dashliteContext['layout'];
    $variantView = $dashliteContext['variantView'];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
        @stack('styles')
        <style id="kitukizuri-theme-css">
            {!! kitukizuriThemeCss() !!}
        </style>
    </head>

    <body class="nk-body {{ $layout['bodyClass'] }} variant-{{ $layout['variant'] }} no-touch nk-nio-theme">
        @include($variantView, ['layout' => $layout, 'header' => $header ?? null, 'slot' => $slot])

        @stack('modals')
        @stack('scripts')

        @livewireScripts
    </body>
</html>
