<div class="nk-app-root">
    <div class="nk-main">
        @if ($layout['showSidebar'])
            <div class="nk-sidebar nk-sidebar-short nk-sidebar-fixed is-light" data-content="{{ $layout['sidebarTarget'] }}">
                <div class="nk-sidebar-element nk-sidebar-head">
                    <div class="nk-sidebar-brand">
                        <a href="{{ route('home.index') }}" class="logo-link nk-sidebar-logo">
                            <x-application-logo class="logo-light logo-img" style="height:34px;" />
                            <x-application-logo class="logo-dark logo-img" style="height:34px;" />
                        </a>
                        <a href="{{ route('home.index') }}" class="logo-link nk-sidebar-logo-small">
                            <x-application-mark class="logo-light logo-img" style="height:30px;" />
                            <x-application-mark class="logo-dark logo-img" style="height:30px;" />
                        </a>
                    </div>
                    <div class="nk-menu-trigger me-n2">
                        <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="{{ $layout['sidebarTarget'] }}"><em class="icon ni ni-arrow-left"></em></a>
                    </div>
                </div>
                <div class="nk-sidebar-element">
                    <div class="nk-sidebar-body" data-simplebar>
                        <div class="nk-sidebar-content">
                            <div class="nk-sidebar-menu nk-sidebar-menu-middle">
                                {!! session('menu') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="{{ $layout['mainWrapClass'] }}">
            <div class="nk-header nk-header-fluid nk-header-fixed nk-header-onlymobile is-light">
                <div class="container-fluid">
                    <div class="nk-header-wrap">
                        <div class="nk-header-brand">
                            <a href="{{ route('home.index') }}" class="logo-link">
                                <x-application-logo class="logo-light logo-img" style="height:30px;" />
                                <x-application-logo class="logo-dark logo-img" style="height:30px;" />
                            </a>
                        </div>
                        <div class="nk-menu-trigger ms-auto me-n1">
                            <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="{{ $layout['sidebarTarget'] }}"><em class="icon ni ni-menu"></em></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="nk-content">
                <div class="container-fluid">
                    <div class="nk-content-body">
                        @if (isset($header))
                            <div class="nk-block-head nk-block-head-sm">
                                <div class="nk-block-between flex-wrap g-1 align-start">
                                    <div class="nk-block-head-content">
                                        <h5 class="nk-block-title">
                                            {{ $header }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        @endif
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
