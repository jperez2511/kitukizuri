<?php

if (!function_exists('usePrevUi')) {
    function usePrevUi($view = null)
    {
        $view = $view ?? 'default';

        $viewNames = [
            'dashboard' => ['location' => 'kitukizuri', 'default' => 'dashboard.index', 'prev' => 'dashboard'],
            'default'   => ['location' => 'krud', 'default' => 'index', 'prev' => 'index'],
        ];

        if (!empty(config('kitukizuri.prevUi')) && config('kitukizuri.prevUi') == true) {
            $kitukizuri = $viewNames[$view]['location'].'_prev::'.$viewNames[$view]['prev'];
            $krud       = 'krud_prev::layout';
        } else {
            $kitukizuri = $viewNames[$view]['location'].'::'.$viewNames[$view]['default'];
            $krud       = 'krud::layout';
        }

        return [
            'kitukizuri' => $kitukizuri,
            'krud'       => $krud,
        ];
    }
}