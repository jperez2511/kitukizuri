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
            // Estructura 0: Array de objetos
            is_array($array) && array_reduce($array, fn($carry, $item) => $carry && is_object($item), true) => 5,

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

if (!function_exists('normalizeArray')) {
    function normalizeArray(array $array, $inputName): array {
        $type = typeArray($array);
    
        return match ($type) {
            // Estructura 0: Array de objetos
            5 => array_map(fn($item) => [
                'input'     => $item->input ?? null,
                'value'     => $item->value ?? null,
                'dependent' => $inputName,
            ], $array),
    
            // Estructura 1: Array de arrays asociativos
            1 => array_map(fn($item) => [
                'input'     => $item['input'] ?? null,
                'value'     => $item['value'] ?? null,
                'dependent' => $inputName,
            ], $array),
    
            // Estructura 2: Array de arrays secuenciales
            2 => array_map(fn($item) => [
                'input'     => $item[0] ?? null,
                'value'     => $item[1] ?? null,
                'dependent' => $inputName,
            ], $array),
    
            // Estructura 3: Array simple secuencial
            3 => [
                [
                    'input'     => $array[0] ?? null,
                    'value'     => $array[1] ?? null,
                    'dependent' => $inputName,
                ]
            ],
    
            // Estructura 4: Array simple asociativo
            4 => [
                [
                    'input'     => $array['input'] ?? null,
                    'value'     => $array['value'] ?? null,
                    'dependent' => $inputName,
                ]
            ],
    
            // Default: No coincide con ninguna estructura
            default => []
        };
    }
}    