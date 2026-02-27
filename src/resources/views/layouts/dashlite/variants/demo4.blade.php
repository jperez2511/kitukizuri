@include('layouts.dashlite.partials.layout-aside', [
    'layout' => $layout,
    'header' => $header ?? null,
    'slot' => $slot,
])
