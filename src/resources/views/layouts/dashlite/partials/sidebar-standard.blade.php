@if ($layout['showSidebar'])
    <div class="nk-sidebar" data-content="{{ $layout['sidebarTarget'] }}">
        <div class="nk-sidebar-inner" data-simplebar>
            {!! session('menu') !!}
        </div>
    </div>
@endif
