<div class="nk-content ">
    <div class="{{ $layout['contentContainerClass'] }}">
        <div class="nk-content-inner">
            <div class="nk-aside" data-content="{{ $layout['sidebarTarget'] }}" data-toggle-overlay="true" data-toggle-screen="lg" data-toggle-body="true">
                <div class="nk-sidebar-menu" data-simplebar>
                    {!! session('menu') !!}
                </div>
            </div>
            <div class="nk-content-body">
                <div class="nk-content-wrap">
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
