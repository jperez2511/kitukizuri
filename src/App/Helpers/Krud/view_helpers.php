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

if (!function_exists('kitukizuriThemeSkinPresets')) {
    function kitukizuriThemeSkinPresets(): array
    {
        return [
            'default' => [
                'primary_color' => '#6576ff',
                'secondary_color' => '#0d7195',
                'accent_color' => '#b3c2ff',
                'surface_color' => '#f5f6fa',
            ],
            'blue_light' => [
                'primary_color' => '#0971fe',
                'secondary_color' => '#0b3175',
                'accent_color' => '#e1eeff',
                'surface_color' => '#f5f6fa',
            ],
            'egyptian' => [
                'primary_color' => '#2263b3',
                'secondary_color' => '#022c57',
                'accent_color' => '#e4ecf6',
                'surface_color' => '#f5f6fa',
            ],
            'purple' => [
                'primary_color' => '#854fff',
                'secondary_color' => '#3a2272',
                'accent_color' => '#f0eaff',
                'surface_color' => '#f5f6fa',
            ],
            'green' => [
                'primary_color' => '#0fac81',
                'secondary_color' => '#07523d',
                'accent_color' => '#e2f5f0',
                'surface_color' => '#f5f6fa',
            ],
            'red' => [
                'primary_color' => '#e14954',
                'secondary_color' => '#662828',
                'accent_color' => '#fbe9ea',
                'surface_color' => '#f5f6fa',
            ],
        ];
    }
}

if (!function_exists('kitukizuriLayoutDefaults')) {
    function kitukizuriLayoutDefaults(): array
    {
        return [
            'direction' => 'ltr',
            'ui_style' => 'default',
            'sidebar_style' => 'auto',
            'skin_mode' => 'light',
        ];
    }
}

if (!function_exists('kitukizuriPersonalizacionDefaults')) {
    function kitukizuriPersonalizacionDefaults(): array
    {
        return array_merge(
            kitukizuriThemeDefaults(),
            kitukizuriLayoutDefaults(),
            ['primary_skin' => 'custom']
        );
    }
}

if (!function_exists('kitukizuriPersonalizacionConfig')) {
    function kitukizuriPersonalizacionConfig(): array
    {
        static $cache = null;

        if ($cache !== null) {
            return $cache;
        }

        $defaults = kitukizuriPersonalizacionDefaults();
        $config = $defaults;
        $preset = kitukizuriThemeSkinPresets();

        try {
            if (!class_exists(\Icebearsoft\Kitukizuri\App\Models\Personalizacion::class)) {
                return $cache = $config;
            }

            if (!class_exists(\Illuminate\Support\Facades\Schema::class)) {
                return $cache = $config;
            }

            if (!\Illuminate\Support\Facades\Schema::hasTable('personalizaciones')) {
                return $cache = $config;
            }

            $row = \Icebearsoft\Kitukizuri\App\Models\Personalizacion::query()->first();
            if (empty($row)) {
                return $cache = $config;
            }

            foreach (kitukizuriThemeDefaults() as $key => $defaultValue) {
                $value = trim((string) ($row->{$key} ?? ''));
                if (preg_match('/^#[A-Fa-f0-9]{6}$/', $value)) {
                    $config[$key] = strtolower($value);
                }
            }

            if (\Illuminate\Support\Facades\Schema::hasColumn('personalizaciones', 'primary_skin')) {
                $skin = strtolower(trim((string) ($row->primary_skin ?? 'custom')));
                if ($skin === 'custom' || array_key_exists($skin, $preset)) {
                    $config['primary_skin'] = $skin;
                }
            }

            if (\Illuminate\Support\Facades\Schema::hasColumn('personalizaciones', 'direction')) {
                $direction = strtolower(trim((string) ($row->direction ?? $defaults['direction'])));
                if (in_array($direction, ['ltr', 'rtl'], true)) {
                    $config['direction'] = $direction;
                }
            }

            if (\Illuminate\Support\Facades\Schema::hasColumn('personalizaciones', 'ui_style')) {
                $uiStyle = strtolower(trim((string) ($row->ui_style ?? $defaults['ui_style'])));
                if (in_array($uiStyle, ['default', 'bordered'], true)) {
                    $config['ui_style'] = $uiStyle;
                }
            }

            if (\Illuminate\Support\Facades\Schema::hasColumn('personalizaciones', 'sidebar_style')) {
                $sidebarStyle = strtolower(trim((string) ($row->sidebar_style ?? $defaults['sidebar_style'])));
                if (in_array($sidebarStyle, ['auto', 'white', 'dark', 'theme'], true)) {
                    $config['sidebar_style'] = $sidebarStyle;
                }
            }

            if (\Illuminate\Support\Facades\Schema::hasColumn('personalizaciones', 'skin_mode')) {
                $skinMode = strtolower(trim((string) ($row->skin_mode ?? $defaults['skin_mode'])));
                if (in_array($skinMode, ['light', 'dark'], true)) {
                    $config['skin_mode'] = $skinMode;
                }
            }

            if ($config['primary_skin'] !== 'custom' && array_key_exists($config['primary_skin'], $preset)) {
                $config = array_merge($config, $preset[$config['primary_skin']]);
            }
        } catch (\Throwable $e) {
            return $cache = $config;
        }

        return $cache = $config;
    }
}

if (!function_exists('kitukizuriLayoutConfig')) {
    function kitukizuriLayoutConfig(): array
    {
        $config = kitukizuriPersonalizacionConfig();

        return [
            'direction' => $config['direction'],
            'ui_style' => $config['ui_style'],
            'sidebar_style' => $config['sidebar_style'],
            'skin_mode' => $config['skin_mode'],
        ];
    }
}

if (!function_exists('kitukizuriThemeColors')) {
    function kitukizuriThemeColors(): array
    {
        $config = kitukizuriPersonalizacionConfig();

        return [
            'primary_color' => $config['primary_color'],
            'secondary_color' => $config['secondary_color'],
            'accent_color' => $config['accent_color'],
            'surface_color' => $config['surface_color'],
        ];
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
        $secondaryRgb = kitukizuriHexToRgb($colors['secondary_color']);

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

            .nk-sidebar.is-theme,
            .nk-apps-sidebar.is-theme,
            .nk-header.is-theme:not([class*=bg-]) {
                background-color: var(--kz-secondary) !important;
                border-color: rgba({$secondaryRgb}, 0.35) !important;
            }

            .nk-sidebar.is-theme .nk-menu-link,
            .nk-sidebar.is-theme .nk-menu-icon,
            .nk-apps-sidebar.is-theme .nk-menu-link,
            .nk-apps-sidebar.is-theme .nk-menu-icon {
                color: rgba(255, 255, 255, 0.88) !important;
            }

            .nk-sidebar.is-theme .nk-menu-link:hover,
            .nk-sidebar.is-theme .nk-menu-item.active > .nk-menu-link,
            .nk-apps-sidebar.is-theme .nk-menu-link:hover,
            .nk-apps-sidebar.is-theme .nk-menu-item.active > .nk-menu-link {
                color: var(--kz-accent) !important;
            }

            .nk-body.ui-bordered .nk-header-wrap,
            .nk-body.ui-bordered .nk-sidebar,
            .nk-body.ui-bordered .card:not([class*="bg-"]),
            .nk-body.ui-bordered .code-block,
            .nk-body.ui-bordered .accordion:not(.accordion-s2):not(.accordion-s3),
            .nk-body.ui-bordered .card-bordered,
            .nk-body.ui-bordered .nk-chat,
            .nk-body.ui-bordered .nk-ibx,
            .nk-body.ui-bordered .nk-msg,
            .nk-body.ui-bordered .nk-fmg,
            .nk-body.ui-bordered .nk-download {
                border: 1px solid var(--bs-border-color, rgba({$primaryRgb}, 0.18));
                box-shadow: none !important;
            }
        CSS;
    }
}
