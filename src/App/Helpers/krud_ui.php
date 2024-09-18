<?php

if (!function_exists('usePrevUi')) {
    function usePrevUi($view = null)
    {
        $view = $view ?? 'default';

        $viewNames = [
            'dashboard' => ['default' => 'dashboard.index', 'prev' => 'dashboard'],
            'default'   => ['default' => 'index', 'prev' => 'index'],
        ];

        if (!empty(config('kitukizuri.prevUi')) && config('kitukizuri.prevUi') == true) {
            $kitukizuri = 'kitukizuri_prev::'.$viewNames[$view]['prev'];
            $krud       = 'krud_prev::layout';
        } else {
            $kitukizuri = 'kitukizuri::'.$viewNames[$view]['default'];
            $krud       = 'krud::layout';
        }

        return [
            'kitukizuri' => $kitukizuri,
            'krud'       => $krud,
        ];
    }
}