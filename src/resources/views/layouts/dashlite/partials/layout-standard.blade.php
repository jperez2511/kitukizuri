@php
    $sidebarPosition = $sidebarPosition ?? 'inside-main';
    $showAppsSidebar = $showAppsSidebar ?? false;
@endphp

<div class="nk-app-root">
    @if ($showAppsSidebar)
        @include('layouts.dashlite.partials.apps-sidebar', ['layout' => $layout])
    @endif

    <div class="nk-main ">
        @if ($sidebarPosition === 'inside-main')
            @include('layouts.dashlite.partials.sidebar-standard', ['layout' => $layout])
        @endif

        <div class="{{ $layout['mainWrapClass'] }}">
            <div class="{{ $layout['headerClass'] }}">
                <div class="{{ $layout['headerContainerClass'] }}">
                    @livewire('navigation-menu')
                </div>
            </div>

            @if ($sidebarPosition === 'inside-wrap')
                @include('layouts.dashlite.partials.sidebar-standard', ['layout' => $layout])
            @endif

            @include('layouts.dashlite.partials.content-default', ['layout' => $layout, 'header' => $header ?? null, 'slot' => $slot])
        </div>
    </div>
</div>
