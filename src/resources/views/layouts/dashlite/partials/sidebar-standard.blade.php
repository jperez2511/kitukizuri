@if ($layout['showSidebar'])
    <div class="nk-sidebar {{ $layout['sidebarStyleClass'] }}" data-content="{{ $layout['sidebarTarget'] }}">
        <div class="nk-sidebar-inner" data-simplebar>
            {!! session('menu') !!}
        </div>
    </div>
@endif
