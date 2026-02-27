<div class="nk-app-root">
    <div class="nk-main">
        @if ($layout['showSidebar'])
            <div class="nk-sidebar is-light nk-sidebar-fixed is-light" data-content="{{ $layout['sidebarTarget'] }}">
                <div class="nk-sidebar-element nk-sidebar-head">
                    <div class="nk-sidebar-brand">
                        <a href="{{ route('home.index') }}" class="logo-link nk-sidebar-logo">
                            <x-application-logo class="logo-light logo-img" style="height:34px;" />
                            <x-application-logo class="logo-dark logo-img" style="height:34px;" />
                            <x-application-mark class="logo-small logo-img logo-img-small" style="height:32px;" />
                        </a>
                    </div>
                    <div class="nk-menu-trigger me-n2">
                        <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="{{ $layout['sidebarTarget'] }}"><em class="icon ni ni-arrow-left"></em></a>
                    </div>
                </div>
                <div class="nk-sidebar-element">
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

            <div class="nk-content nk-content-fluid">
                <div class="{{ $layout['contentContainerClass'] }}">
                    <div class="nk-content-body">
                        @if (isset($header))
                            <div class="nk-block-head nk-block-head-sm">
                                <div class="nk-block-between">
                                    <div class="nk-block-head-content">
                                        <h3 class="nk-block-title page-title">
                                            {{ $header }}
                                        </h3>
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
