@props(['dash' => false])

@php
    $defaultDashliteBodyClass = 'npc-default has-apps-sidebar has-sidebar';
    $dashliteBodyClass = (string) config('kitukizuri.dashliteBodyClass', $defaultDashliteBodyClass);
    $dashliteBodyClass = trim(preg_replace('/\s+/', ' ', $dashliteBodyClass));
    $hasAppsSidebar = str_contains(' '.$dashliteBodyClass.' ', ' has-apps-sidebar ');
    $isAsideLayout = str_contains(' '.$dashliteBodyClass.' ', ' has-aside ');
    $sidebarTarget = $isAsideLayout ? 'sideNav' : 'sidebarMenu';
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

    <body class="nk-body {{ $dashliteBodyClass }} no-touch nk-nio-theme">
        <div class="nk-app-root">
            @if ($hasAppsSidebar)
                <div class="nk-apps-sidebar is-dark">
                    <div class="nk-apps-brand text-center">
                        <a href="{{ route('home.index') }}">
                            <x-application-mark style="width:50%; margin-top:10px;" />
                        </a>
                    </div>
                    <div class="nk-sidebar-element">
                        <div class="nk-sidebar-body">
                            <div class="nk-sidebar-content" data-simplebar>
                                <div class="nk-sidebar-menu">
                                    <ul class="nk-menu apps-menu">
                                        <li class="nk-menu-item">
                                            <a href="html/cms/index.html" class="nk-menu-link" title="CMS Panel">
                                                <span class="nk-menu-icon"><em class="icon ni ni-template"></em></span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="nk-main ">
                <div class="{{ !empty($dash) && $dash == true ? '' : 'nk-wrap' }} ">
                    <div class="nk-header nk-header-fixed is-light">
                        <div class="container-fluid">
                            @livewire('navigation-menu')
                        </div>
                    </div>
                    @if (empty($dash) && $dash == false && !$isAsideLayout)
                        <div class="nk-sidebar" data-content="{{ $sidebarTarget }}">
                            <div class="nk-sidebar-inner" data-simplebar>
                                {!! session('menu') !!}
                            </div>
                        </div>
                    @endif
                    <div class="nk-content ">
                        <div class="container-fluid">
                            <div class="nk-content-inner">
                                @if (empty($dash) && $dash == false && $isAsideLayout)
                                    <div class="nk-aside" data-content="{{ $sidebarTarget }}" data-toggle-overlay="true" data-toggle-screen="lg" data-toggle-body="true">
                                        <div class="nk-sidebar-menu" data-simplebar>
                                            {!! session('menu') !!}
                                        </div>
                                    </div>
                                @endif
                                <div class="nk-content-body">
                                    <div class="nk-block-head nk-block-head-sm">
                                        <div class="nk-block-between">
                                            <div class="nk-block-head-content">
                                                @if (isset($header))
                                                    <h3 class="nk-block-title page-title">
                                                        {{ $header }}
                                                    </h3>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-block">
                                        <div class="row g-gs">
                                            <x-banner />
                                            {{ $slot }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        @stack('modals')
        @stack('scripts')
        
        @livewireScripts
    </body>
</html>
