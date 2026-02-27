@include('layouts.dashlite.partials.layout-standard', [
    'layout' => $layout,
    'sidebarPosition' => 'inside-wrap',
    'showAppsSidebar' => true,
    'header' => $header ?? null,
    'slot' => $slot,
])
