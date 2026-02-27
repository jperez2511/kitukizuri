@php
    $defaultDashliteBodyClass = 'npc-default has-apps-sidebar has-sidebar';
    $configuredBodyClass = (string) config('kitukizuri.dashliteBodyClass', $defaultDashliteBodyClass);
    $configuredBodyClass = trim(preg_replace('/\s+/', ' ', $configuredBodyClass));
    $dashliteVariant = trim((string) config('kitukizuri.dashliteVariant', 'demo3'));

    $guestBodyClass = preg_replace(
        '/\b(has-apps-sidebar|has-aside|has-sidebar-short|has-sidebar)\b/',
        '',
        $configuredBodyClass
    );
    $guestBodyClass = trim(preg_replace('/\s+/', ' ', (string) $guestBodyClass));
    if ($guestBodyClass === '') {
        $guestBodyClass = 'bg-lighter npc-general';
    }
    $guestBodyClass .= ' pg-auth';
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
    <body class="nk-body {{ $guestBodyClass }} variant-{{ $dashliteVariant }} no-touch nk-nio-theme">
        <div class="nk-app-root">
            <div class="nk-main">
                <div class="nk-wrap nk-wrap-nosidebar">
                    <div class="nk-content">
                        <div class="container-fluid">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @stack('scripts')
        @livewireScripts
    </body>
</html>
