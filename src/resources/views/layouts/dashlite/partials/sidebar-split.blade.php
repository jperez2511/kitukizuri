@if ($layout['showSidebar'])
    @php
        $splitSidebarClass = trim((string) ($layout['sidebarStyleClass'] ?? ''));
        $splitSidebarMainClass = $splitSidebarClass !== '' ? $splitSidebarClass : 'is-light';
    @endphp
    <div class="nk-sidebar {{ $splitSidebarClass }}" data-content="{{ $layout['sidebarTarget'] }}">
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
        <div class="nk-sidebar-main {{ $splitSidebarMainClass }}">
            <div class="nk-sidebar-inner" data-simplebar>
                {!! session('menu') !!}
            </div>
        </div>
    </div>
@endif
