@include('layouts.dashlite.partials.layout-split', [
    'layout' => $layout,
    'header' => $header ?? null,
    'slot' => $slot,
])
