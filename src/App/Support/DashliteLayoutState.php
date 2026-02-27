<?php

namespace Icebearsoft\Kitukizuri\App\Support;

class DashliteLayoutState
{
    public static function build($isDashPage = false)
    {
        $defaultBodyClass = 'npc-default has-apps-sidebar has-sidebar';
        $variant = trim((string) config('kitukizuri.dashliteVariant', 'demo3'));

        if (!array_key_exists($variant, self::variantViews())) {
            $variant = 'demo3';
        }

        $configuredBodyClass = trim((string) config('kitukizuri.dashliteBodyClass', $defaultBodyClass));
        $configuredBodyClass = trim((string) preg_replace('/\s+/', ' ', $configuredBodyClass));
        $bodyClass = self::normalizeBodyClass($variant, $configuredBodyClass, $defaultBodyClass);

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
        $appsSidebarClass = 'nk-apps-sidebar'.($isLightSurface ? ' is-light' : ' is-dark');
        $headerContainerClass = $isAsideLayout ? 'container-lg wide-xl' : 'container-fluid';
        $contentContainerClass = $isAsideLayout ? 'container wide-xl' : 'container-fluid';
        if ($variant === 'demo6') {
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
        if ($isRounder) {
            $headerClass .= ' border-bottom';
        }

        return [
            'variant' => $variant,
            'bodyClass' => $bodyClass,
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
