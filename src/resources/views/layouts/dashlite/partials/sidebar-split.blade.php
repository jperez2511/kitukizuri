@if ($layout['showSidebar'])
    <div class="nk-sidebar" data-content="{{ $layout['sidebarTarget'] }}">
        <div class="nk-sidebar-bar">
            <div class="nk-apps-brand">
                <a href="{{ route('home.index') }}" class="logo-link">
                    <x-application-mark style="width:30px;" />
                </a>
            </div>
            <div class="nk-sidebar-element">
                <div class="nk-sidebar-body">
                    <div class="nk-sidebar-content" data-simplebar>
                        <div class="nk-sidebar-menu">
                            <ul class="nk-menu apps-menu">
                                <li class="nk-menu-item active">
                                    <a href="{{ route('home.index') }}" class="nk-menu-link" title="{{ __('Dashboard') }}">
                                        <span class="nk-menu-icon"><em class="icon ni ni-dashboard"></em></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="nk-sidebar-main is-light">
            <div class="nk-sidebar-inner" data-simplebar>
                {!! session('menu') !!}
            </div>
        </div>
    </div>
@endif
