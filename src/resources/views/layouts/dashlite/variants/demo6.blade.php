<div class="nk-app-root">
    <div class="{{ $layout['mainWrapClass'] }}">
        <div class="{{ $layout['headerClass'] }}">
            <div class="{{ $layout['headerContainerClass'] }}">
                @livewire('navigation-menu')
            </div>
        </div>

        <div class="nk-content nk-content-fluid">
            <div class="{{ $layout['contentContainerClass'] }}">
                <div class="nk-content-inner">
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
