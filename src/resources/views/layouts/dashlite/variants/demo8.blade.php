<div class="nk-app-root">
    <div class="{{ $layout['mainWrapClass'] }}">
        <div class="{{ $layout['headerClass'] }}">
            <div class="{{ $layout['headerContainerClass'] }}">
                @livewire('navigation-menu')
            </div>
        </div>

        @include('layouts.dashlite.partials.content-default', ['layout' => $layout, 'header' => $header ?? null, 'slot' => $slot])
    </div>
</div>
