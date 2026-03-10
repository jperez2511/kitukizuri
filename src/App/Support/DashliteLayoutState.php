<?php

namespace Icebearsoft\Kitukizuri\App\Support;

class DashliteLayoutState
{
    public static function build($isDashPage = false)
    {
        $defaultBodyClass = 'npc-default has-apps-sidebar has-sidebar';
        $layoutConfig = self::layoutConfig();
        $variant = trim((string) config('kitukizuri.dashliteVariant', 'demo3'));

        if (!array_key_exists($variant, self::variantViews())) {
            $variant = 'demo3';
        }

        $configuredBodyClass = trim((string) config('kitukizuri.dashliteBodyClass', $defaultBodyClass));
        $configuredBodyClass = trim((string) preg_replace('/\s+/', ' ', $configuredBodyClass));
        $bodyClass = self::normalizeBodyClass($variant, $configuredBodyClass, $defaultBodyClass);
        $bodyClass = self::applyVisualPreferences($bodyClass, $layoutConfig);

        $paddedBodyClass = ' '.$bodyClass.' ';
        $hasAppsSidebar = str_contains($paddedBodyClass, ' has-apps-sidebar ');
        $hasSidebar = str_contains($paddedBodyClass, ' has-sidebar ');
        $isAsideLayout = str_contains($paddedBodyClass, ' has-aside ');
        $isLightSurface = str_contains($paddedBodyClass, ' bg-white ')
            || str_contains($paddedBodyClass, ' bg-lighter ')
            || str_contains($paddedBodyClass, ' ui-clean ');
        $isRounder = str_contains($paddedBodyClass, ' ui-rounder ');

        $usesSplitSidebar = $variant === 'demo7' && !$isAsideLayout;
        $showSidebar = !$isDashPage && !$isAsideLayout && $hasSidebar;
        $usesFluidHeader = $usesSplitSidebar || $variant === 'demo6' || $variant === 'covid';
        $usesFixedHeader = $hasSidebar || $isAsideLayout;
        $usesThemeHeader = $variant === 'demo6';

        $sidebarTarget = $isAsideLayout ? 'sideNav' : 'sidebarMenu';
        $mainWrapClass = $isDashPage ? '' : 'nk-wrap';
        $sidebarStyle = self::resolveSidebarStyle($variant, (string) $layoutConfig['sidebar_style']);
        $sidebarStyleClass = self::mapSidebarStyleClass($sidebarStyle);
        $appsSidebarToneClass = $layoutConfig['sidebar_style'] === 'auto'
            ? ($isLightSurface ? 'is-light' : 'is-dark')
            : self::mapSidebarStyleClass((string) $layoutConfig['sidebar_style']);
        $appsSidebarClass = 'nk-apps-sidebar'.($appsSidebarToneClass !== '' ? ' '.$appsSidebarToneClass : '');
        $headerContainerClass = $isAsideLayout ? 'container-lg wide-xl' : 'container-fluid';
        $contentContainerClass = $isAsideLayout ? 'container wide-xl' : 'container-fluid';
        if (in_array($variant, ['demo6', 'demo9'], true)) {
            $headerContainerClass = 'container-xl wide-xl';
            $contentContainerClass = 'container-xl wide-xl';
        }

        $headerClass = 'nk-header';
        if ($usesFixedHeader) {
            $headerClass .= ' nk-header-fixed';
        }
        if ($usesFluidHeader) {
            $headerClass .= ' nk-header-fluid';
        }
        if ($usesThemeHeader) {
            $headerClass .= ' is-theme';
        } elseif ($isLightSurface || in_array($variant, ['demo3', 'demo7', 'demo8', 'demo9'], true)) {
            $headerClass .= ' is-light';
        }
        if ($isRounder && $variant !== 'demo9') {
            $headerClass .= ' border-bottom';
        }

        return [
            'variant' => $variant,
            'bodyClass' => $bodyClass,
            'direction' => $layoutConfig['direction'],
            'hasAppsSidebar' => $hasAppsSidebar,
            'hasSidebar' => $hasSidebar,
            'isAsideLayout' => $isAsideLayout,
            'isLightSurface' => $isLightSurface,
            'isRounder' => $isRounder,
            'usesSplitSidebar' => $usesSplitSidebar,
            'showSidebar' => $showSidebar,
            'usesFluidHeader' => $usesFluidHeader,
            'usesFixedHeader' => $usesFixedHeader,
            'usesThemeHeader' => $usesThemeHeader,
            'sidebarTarget' => $sidebarTarget,
            'mainWrapClass' => $mainWrapClass,
            'sidebarStyle' => $sidebarStyle,
            'sidebarStyleClass' => $sidebarStyleClass,
            'appsSidebarClass' => $appsSidebarClass,
            'headerClass' => $headerClass,
            'headerContainerClass' => $headerContainerClass,
            'contentContainerClass' => $contentContainerClass,
        ];
    }

    public static function variantView($variant)
    {
        $views = self::variantViews();
        return $views[$variant] ?? $views['demo3'];
    }

    protected static function normalizeBodyClass($variant, $configuredBodyClass, $defaultBodyClass)
    {
        $layoutFlags = self::layoutFlagsByVariant()[$variant] ?? '';
        $bodyClassWithoutLayout = preg_replace(
            '/\b(has-apps-sidebar|has-aside|has-sidebar-short|has-sidebar)\b/',
            '',
            $configuredBodyClass
        );

        $normalized = trim((string) preg_replace(
            '/\s+/',
            ' ',
            trim((string) $bodyClassWithoutLayout).' '.$layoutFlags
        ));

        return $normalized !== '' ? $normalized : $defaultBodyClass;
    }

    protected static function layoutConfig()
    {
        $config = [
            'direction' => 'ltr',
            'ui_style' => 'default',
            'sidebar_style' => 'auto',
            'skin_mode' => 'light',
        ];

        if (!function_exists('kitukizuriLayoutConfig')) {
            return $config;
        }

        $settings = kitukizuriLayoutConfig();
        if (!is_array($settings)) {
            return $config;
        }

        $direction = strtolower(trim((string) ($settings['direction'] ?? 'ltr')));
        if (in_array($direction, ['ltr', 'rtl'], true)) {
            $config['direction'] = $direction;
        }

        $uiStyle = strtolower(trim((string) ($settings['ui_style'] ?? 'default')));
        if (in_array($uiStyle, ['default', 'bordered'], true)) {
            $config['ui_style'] = $uiStyle;
        }

        $sidebarStyle = strtolower(trim((string) ($settings['sidebar_style'] ?? 'auto')));
        if (in_array($sidebarStyle, ['auto', 'white', 'dark', 'theme'], true)) {
            $config['sidebar_style'] = $sidebarStyle;
        }

        $skinMode = strtolower(trim((string) ($settings['skin_mode'] ?? 'light')));
        if (in_array($skinMode, ['light', 'dark'], true)) {
            $config['skin_mode'] = $skinMode;
        }

        return $config;
    }

    protected static function applyVisualPreferences($bodyClass, $layoutConfig)
    {
        $classList = array_values(array_filter(
            explode(' ', preg_replace('/\s+/', ' ', trim((string) $bodyClass)))
        ));

        $classList = array_values(array_filter($classList, function ($class) {
            return !in_array($class, ['ui-bordered', 'has-rtl', 'dark-mode'], true);
        }));

        if (($layoutConfig['ui_style'] ?? 'default') === 'bordered') {
            $classList[] = 'ui-bordered';
        }

        if (($layoutConfig['direction'] ?? 'ltr') === 'rtl') {
            $classList[] = 'has-rtl';
        }

        if (($layoutConfig['skin_mode'] ?? 'light') === 'dark') {
            $classList[] = 'dark-mode';
        }

        return trim(implode(' ', array_values(array_unique($classList))));
    }

    protected static function resolveSidebarStyle($variant, $requestedStyle)
    {
        if ($requestedStyle !== 'auto') {
            return $requestedStyle;
        }

        return self::sidebarDefaultStyleByVariant()[$variant] ?? 'auto';
    }

    protected static function mapSidebarStyleClass($sidebarStyle)
    {
        return [
            'white' => 'is-light',
            'dark' => 'is-dark',
            'theme' => 'is-theme',
        ][$sidebarStyle] ?? '';
    }

    protected static function sidebarDefaultStyleByVariant()
    {
        return [
            'demo1' => 'dark',
            'demo2' => 'white',
            'demo3' => 'auto',
            'demo4' => 'white',
            'demo5' => 'auto',
            'demo6' => 'auto',
            'demo7' => 'white',
            'demo8' => 'auto',
            'demo9' => 'white',
            'covid' => 'white',
        ];
    }

    protected static function layoutFlagsByVariant()
    {
        return [
            'demo1' => 'has-sidebar',
            'demo2' => 'has-sidebar',
            'demo3' => 'has-apps-sidebar has-sidebar',
            'demo4' => 'has-aside',
            'demo5' => 'has-sidebar',
            'demo6' => '',
            'demo7' => 'has-sidebar',
            'demo8' => '',
            'demo9' => 'has-sidebar',
            'covid' => 'has-sidebar has-sidebar-short',
        ];
    }

    protected static function variantViews()
    {
        return [
            'demo1' => 'layouts.dashlite.variants.demo1',
            'demo2' => 'layouts.dashlite.variants.demo2',
            'demo3' => 'layouts.dashlite.variants.demo3',
            'demo4' => 'layouts.dashlite.variants.demo4',
            'demo5' => 'layouts.dashlite.variants.demo5',
            'demo6' => 'layouts.dashlite.variants.demo6',
            'demo7' => 'layouts.dashlite.variants.demo7',
            'demo8' => 'layouts.dashlite.variants.demo8',
            'demo9' => 'layouts.dashlite.variants.demo9',
            'covid' => 'layouts.dashlite.variants.covid',
        ];
    }
}
