<?php

if (!function_exists('usePrevUi')) {
    function usePrevUi($view = null)
    {
        $view = $view ?? 'default';

        $viewNames = [
            'dashboard' => ['location' => 'kitukizuri', 'default' => 'dashboard.index', 'prev' => 'dashboard'],
            'default'   => ['location' => 'krud', 'default' => 'index', 'prev' => 'index'],
            'edit'      => ['location' => 'krud', 'default' => 'edit', 'prev' => 'edit'],
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

if (!function_exists('typeArray')) {
    function typeArray(array $array): int|false {
        return match (true) {
            // Estructura 1: Array de arrays asociativos
            is_array(reset($array)) && array_reduce($array, fn($carry, $item) => $carry && isset($item['input'], $item['value']), true) => 1,
    
            // Estructura 2: Array de arrays secuenciales
            is_array(reset($array)) && array_reduce($array, fn($carry, $item) => $carry && array_keys($item) === [0, 1], true) => 2,
    
            // Estructura 3: Array simple secuencial
            array_keys($array) === [0, 1] => 3,
    
            // Estructura 4: Array simple asociativo
            array_keys($array) === ['input', 'value'] => 4,
    
            // No coincide con ninguna estructura
            default => false,
        };
    }
}