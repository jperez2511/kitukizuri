<div class="nk-app-root">
    <div class="nk-main">
        @if ($layout['showSidebar'])
            <div class="nk-sidebar nk-sidebar-fixed" data-content="{{ $layout['sidebarTarget'] }}">
                <div class="nk-sidebar-element nk-sidebar-head">
                    <div class="nk-sidebar-brand">
                        <a href="{{ route('home.index') }}" class="logo-link nk-sidebar-logo">
                            <x-application-mark style="width:34px;" />
                        </a>
                    </div>
                    <div class="nk-menu-trigger me-n2">
                        <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="{{ $layout['sidebarTarget'] }}"><em class="icon ni ni-arrow-left"></em></a>
                    </div>
                </div>
                <div class="nk-sidebar-element">
                    <div class="nk-sidebar-body" data-simplebar>
                        <div class="nk-sidebar-content">
                            <div class="nk-sidebar-menu">
                                {!! session('menu') !!}
                            </div>
                            <div class="nk-sidebar-footer">
                                <ul class="nk-menu nk-menu-footer">
                                    <li class="nk-menu-item">
                                        <a href="#" class="nk-menu-link">
                                            <span class="nk-menu-icon"><em class="icon ni ni-help-alt"></em></span>
                                            <span class="nk-menu-text">{{ __('Support') }}</span>
                                        </a>
                                    </li>
                                    <li class="nk-menu-item ms-auto">
                                        <a href="#" class="nk-menu-link">
                                            <span class="nk-menu-icon"><em class="icon ni ni-globe"></em></span>
                                            <span class="nk-menu-text">{{ __('English') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
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
                <div class="container-xl wide-lg">
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
