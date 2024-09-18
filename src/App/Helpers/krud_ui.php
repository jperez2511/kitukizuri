<?php

if (!function_exists('usePrevUi')) {
    function usePrevUi()
    {
        if (!empty(config('kitukizuri.prevUi')) && config('kitukizuri.prevUi') == true) {
            $kitukizuri = 'kitukizuri_prev::dashboard';
            $krud       = 'krud_prev::layout';
        } else {
            $kitukizuri = 'kitukizuri::dashboard.index';
            $krud       = 'krud::layout';
        }

        return [
            'kitukizuri' => $kitukizuri,
            'krud'       => $krud,
        ];
    }
}