@include('layouts.dashlite.partials.layout-standard', [
    'layout' => $layout,
    'sidebarPosition' => 'inside-main',
    'showAppsSidebar' => false,
    'header' => $header ?? null,
    'slot' => $slot,
])
