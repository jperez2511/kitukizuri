@props(['dash' => false])

@php
    $isDashPage = !empty($dash) && $dash == true;
    $layout = \Icebearsoft\Kitukizuri\App\Support\DashliteLayoutState::build($isDashPage);
    $variantView = \Icebearsoft\Kitukizuri\App\Support\DashliteLayoutState::variantView($layout['variant']);
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
    </head>

    <body class="nk-body {{ $layout['bodyClass'] }} variant-{{ $layout['variant'] }} no-touch nk-nio-theme">
        @include($variantView, ['layout' => $layout, 'header' => $header ?? null, 'slot' => $slot])

        @stack('modals')
        @stack('scripts')

        @livewireScripts
    </body>
</html>
