<div class="nk-app-root">
    <div class="nk-main">
        @if ($layout['showSidebar'])
            @php($sidebarTone = $layout['sidebarStyleClass'] ?: 'is-dark')
            <div class="nk-sidebar nk-sidebar-fixed {{ $sidebarTone }}" data-content="{{ $layout['sidebarTarget'] }}">
                <div class="nk-sidebar-element nk-sidebar-head">
                    <div class="nk-menu-trigger">
                        <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="{{ $layout['sidebarTarget'] }}"><em class="icon ni ni-arrow-left"></em></a>
                        <a href="#" class="nk-nav-compact nk-quick-nav-icon d-none d-xl-inline-flex" data-target="{{ $layout['sidebarTarget'] }}"><em class="icon ni ni-menu"></em></a>
                    </div>
                    <div class="nk-sidebar-brand">
                        <a href="{{ route('home.index') }}" class="logo-link nk-sidebar-logo">
                            <x-application-mark style="width:34px;" />
                        </a>
                    </div>
                </div>
                <div class="nk-sidebar-element nk-sidebar-body">
                    <div class="nk-sidebar-content">
                        <div class="nk-sidebar-menu" data-simplebar>
                            {!! session('menu') !!}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="{{ $layout['mainWrapClass'] }}">
            <div class="{{ $layout['headerClass'] }}">
                <div class="{{ $layout['headerContainerClass'] }}">
                    @livewire('navigation-menu')
                </div>
            </div>

            @include('layouts.dashlite.partials.content-default', ['layout' => $layout, 'header' => $header ?? null, 'slot' => $slot])
        </div>
    </div>
</div>
