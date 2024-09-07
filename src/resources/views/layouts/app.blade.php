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
    </head>

    <body class="nk-body npc-default has-apps-sidebar has-sidebar no-touch nk-nio-theme ">
        <div class="nk-app-root">
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
            <div class="nk-main ">
                <div class="nk-wrap ">
                    <div class="nk-header nk-header-fixed is-light">
                        <div class="container-fluid">
                            @livewire('navigation-menu')
                        </div>
                    </div>
                    <div class="nk-sidebar" data-content="sidebarMenu">
                        <div class="nk-sidebar-inner" data-simplebar>
                            {!! session('menu') !!}
                            <ul class="nk-menu nk-menu-md">
                                <li class="nk-menu-item">
                                    <a href="html/email-templates.html" class="nk-menu-link">
                                        <span class="nk-menu-icon"><em class="icon ni ni-text-rich"></em></span>
                                        <span class="nk-menu-text">Email Template</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="nk-content ">
                        <div class="container-fluid">
                            <div class="nk-content-inner">
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
        
        @stack('modals')

        @livewireScripts
    </body>
</html>
