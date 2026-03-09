<?php

if (!function_exists('usePrevUi')) {
    function usePrevUi($view = null): array
    {
        $view = $view ?? 'default';

        $viewNames = [
            'dashboard' => ['location' => 'kitukizuri', 'default' => 'dashboard.index', 'prev' => 'dashboard'],
            'default' => ['location' => 'krud', 'default' => 'index', 'prev' => 'index'],
            'edit' => ['location' => 'krud', 'default' => 'edit', 'prev' => 'edit'],
        ];

        if (!empty(config('kitukizuri.prevUi')) && config('kitukizuri.prevUi') == true) {
            $kitukizuri = $viewNames[$view]['location'].'_prev::'.$viewNames[$view]['prev'];
            $krud = 'krud_prev::layout';
        } else {
            $kitukizuri = $viewNames[$view]['location'].'::'.$viewNames[$view]['default'];
            $krud = 'krud::layout';
        }

        return [
            'kitukizuri' => $kitukizuri,
            'krud' => $krud,
        ];
    }
}

if (!function_exists('kitukizuriThemeDefaults')) {
    function kitukizuriThemeDefaults(): array
    {
        return [
            'primary_color' => '#6576ff',
            'secondary_color' => '#0d7195',
            'accent_color' => '#b3c2ff',
            'surface_color' => '#f5f6fa',
        ];
    }
}

if (!function_exists('kitukizuriThemeColors')) {
    function kitukizuriThemeColors(): array
    {
        static $cache = null;

        if ($cache !== null) {
            return $cache;
        }

        $colors = kitukizuriThemeDefaults();

        try {
            if (!class_exists(\Icebearsoft\Kitukizuri\App\Models\Personalizacion::class)) {
                return $cache = $colors;
            }

            if (!class_exists(\Illuminate\Support\Facades\Schema::class)) {
                return $cache = $colors;
            }

            if (!\Illuminate\Support\Facades\Schema::hasTable('personalizaciones')) {
                return $cache = $colors;
            }

            $config = \Icebearsoft\Kitukizuri\App\Models\Personalizacion::query()->first();
            if (empty($config)) {
                return $cache = $colors;
            }

            foreach ($colors as $key => $defaultValue) {
                $value = trim((string) ($config->{$key} ?? ''));
                if (preg_match('/^#[A-Fa-f0-9]{6}$/', $value)) {
                    $colors[$key] = strtolower($value);
                }
            }
        } catch (\Throwable $e) {
            return $cache = $colors;
        }

        return $cache = $colors;
    }
}

if (!function_exists('kitukizuriHexToRgb')) {
    function kitukizuriHexToRgb(string $hex): string
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) !== 6) {
            return '101, 118, 255';
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        return $r.', '.$g.', '.$b;
    }
}

if (!function_exists('kitukizuriThemeCss')) {
    function kitukizuriThemeCss(): string
    {
        $colors = kitukizuriThemeColors();
        $primaryRgb = kitukizuriHexToRgb($colors['primary_color']);

        return <<<CSS
            :root {
                --kz-primary: {$colors['primary_color']};
                --kz-secondary: {$colors['secondary_color']};
                --kz-accent: {$colors['accent_color']};
                --kz-surface: {$colors['surface_color']};
                --bs-primary: var(--kz-primary);
                --bs-primary-rgb: {$primaryRgb};
                --bs-link-color: var(--kz-primary);
                --bs-link-hover-color: var(--kz-primary);
            }

            .nk-body,
            .main-content,
            .page-content {
                background-color: var(--kz-surface);
            }

            .text-primary,
            .nk-menu-link:hover .nk-menu-text,
            .nk-menu-link:hover .nk-menu-icon,
            .nk-menu-item.active > .nk-menu-link .nk-menu-text,
            .nk-menu-item.active > .nk-menu-link .nk-menu-icon {
                color: var(--kz-primary) !important;
            }

            .bg-primary,
            .badge.bg-primary {
                background-color: var(--kz-primary) !important;
                border-color: var(--kz-primary) !important;
            }

            .btn-primary {
                background-color: var(--kz-primary) !important;
                border-color: var(--kz-primary) !important;
            }

            .btn-outline-primary {
                color: var(--kz-primary) !important;
                border-color: var(--kz-primary) !important;
            }

            .btn-outline-primary:hover,
            .btn-outline-primary:focus,
            .btn-outline-primary:active {
                color: #fff !important;
                background-color: var(--kz-primary) !important;
                border-color: var(--kz-primary) !important;
            }

            .btn-tertiary,
            .btn-secondary {
                color: #fff !important;
                background-color: var(--kz-secondary) !important;
                border-color: var(--kz-secondary) !important;
            }

            .btn-tertiary:hover,
            .btn-tertiary:focus,
            .btn-secondary:hover,
            .btn-secondary:focus {
                color: #fff !important;
                background-color: var(--kz-secondary) !important;
                border-color: var(--kz-secondary) !important;
                filter: brightness(1.05);
            }
        CSS;
    }
}
