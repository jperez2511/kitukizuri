@include('layouts.dashlite.partials.layout-plain', [
    'layout' => $layout,
    'header' => $header ?? null,
    'slot' => $slot,
])
