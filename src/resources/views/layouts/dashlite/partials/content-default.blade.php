<div class="nk-content ">
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
