<?php

if (!function_exists('typeArray')) {
    function typeArray(array $array): int|false
    {
        return match (true) {
            is_array($array) && array_reduce($array, fn ($carry, $item) => $carry && is_object($item), true) => 5,
            is_array(reset($array)) && array_reduce($array, fn ($carry, $item) => $carry && isset($item['input'], $item['value']), true) => 1,
            is_array(reset($array)) && array_reduce($array, fn ($carry, $item) => $carry && array_keys($item) === [0, 1], true) => 2,
            array_keys($array) === [0, 1] => 3,
            array_keys($array) === ['input', 'value'] => 4,
            default => false,
        };
    }
}

if (!function_exists('normalizeArray')) {
    function normalizeArray(array $array, $inputName): array
    {
        $type = typeArray($array);

        return match ($type) {
            5 => array_map(fn ($item) => [
                'input' => $item->input ?? null,
                'value' => $item->value ?? null,
                'dependent' => $inputName,
            ], $array),
            1 => array_map(fn ($item) => [
                'input' => $item['input'] ?? null,
                'value' => $item['value'] ?? null,
                'dependent' => $inputName,
            ], $array),
            2 => array_map(fn ($item) => [
                'input' => $item[0] ?? null,
                'value' => $item[1] ?? null,
                'dependent' => $inputName,
            ], $array),
            3 => [[
                'input' => $array[0] ?? null,
                'value' => $array[1] ?? null,
                'dependent' => $inputName,
            ]],
            4 => [[
                'input' => $array['input'] ?? null,
                'value' => $array['value'] ?? null,
                'dependent' => $inputName,
            ]],
            default => [],
        };
    }
}
