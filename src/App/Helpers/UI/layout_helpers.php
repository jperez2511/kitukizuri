<?php

if (!function_exists('dashliteLayoutContext')) {
    function dashliteLayoutContext($dash = false): array
    {
        $isDashPage = !empty($dash) && $dash == true;
        $layout = \Icebearsoft\Kitukizuri\App\Support\DashliteLayoutState::build($isDashPage);

        return [
            'layout' => $layout,
            'variantView' => \Icebearsoft\Kitukizuri\App\Support\DashliteLayoutState::variantView($layout['variant']),
        ];
    }
}

if (!function_exists('dashliteGuestLayoutContext')) {
    function dashliteGuestLayoutContext(): array
    {
        $layout = \Icebearsoft\Kitukizuri\App\Support\DashliteLayoutState::build(false);

        $guestBodyClass = preg_replace(
            '/\b(has-apps-sidebar|has-aside|has-sidebar-short|has-sidebar)\b/',
            '',
            (string) ($layout['bodyClass'] ?? '')
        );

        $guestBodyClass = trim((string) preg_replace('/\s+/', ' ', (string) $guestBodyClass));
        if ($guestBodyClass === '') {
            $guestBodyClass = 'bg-lighter npc-general';
        }

        return [
            'bodyClass' => trim($guestBodyClass.' pg-auth'),
            'variant' => (string) ($layout['variant'] ?? 'demo3'),
            'direction' => (string) ($layout['direction'] ?? 'ltr'),
        ];
    }
}

if (!function_exists('krudLayoutContext')) {
    function krudLayoutContext($withDashlite = true, $defaultBodyClass = 'npc-default has-sidebar'): array
    {
        $isDarkSuffix = !empty(config('kitukizuri.dark')) ? '-dark' : null;
        $sideBar = (!empty($isDarkSuffix) || !empty(config('kitukizuri.darkSideBar'))) ? 'dark' : null;

        $context = [
            'isDarkSuffix' => $isDarkSuffix,
            'sideBar' => $sideBar,
            'dashliteVariant' => null,
            'dashliteBodyClass' => null,
            'direction' => 'ltr',
        ];

        if (!$withDashlite) {
            if (function_exists('kitukizuriLayoutConfig')) {
                $layoutConfig = kitukizuriLayoutConfig();
                $context['direction'] = (string) ($layoutConfig['direction'] ?? 'ltr');
            }
            return $context;
        }

        $layout = \Icebearsoft\Kitukizuri\App\Support\DashliteLayoutState::build(false);

        $context['dashliteVariant'] = (string) ($layout['variant'] ?? 'demo3');
        $context['dashliteBodyClass'] = trim((string) ($layout['bodyClass'] ?? $defaultBodyClass));
        $context['direction'] = (string) ($layout['direction'] ?? 'ltr');

        return $context;
    }
}

if (!function_exists('krudErrorLayoutContext')) {
    function krudErrorLayoutContext(): array
    {
        $layout = \Icebearsoft\Kitukizuri\App\Support\DashliteLayoutState::build(false);

        return [
            'isDarkSuffix' => !empty(config('kitukizuri.dark')) ? '-dark' : null,
            'dashliteVariant' => (string) ($layout['variant'] ?? 'demo3'),
            'dashliteBodyClass' => trim((string) ($layout['bodyClass'] ?? 'bg-lighter npc-general')),
            'direction' => (string) ($layout['direction'] ?? 'ltr'),
        ];
    }
}

if (!function_exists('dashliteNavigationContext')) {
    function dashliteNavigationContext(): array
    {
        $layout = \Icebearsoft\Kitukizuri\App\Support\DashliteLayoutState::build(false);
        $variant = $layout['variant'];

        return [
            'layout' => $layout,
            'variant' => $variant,
            'isDemo1' => $variant === 'demo1',
            'isDemo2' => $variant === 'demo2',
            'isDemo5' => $variant === 'demo5',
            'isDemo6' => $variant === 'demo6',
            'isDemo8' => $variant === 'demo8',
            'isDemo9' => $variant === 'demo9',
            'isAsideLayout' => $layout['isAsideLayout'],
            'menuTarget' => $layout['sidebarTarget'],
            'triggerClass' => $layout['isAsideLayout'] ? 'd-lg-none ms-n1' : 'd-xl-none ms-n1',
        ];
    }
}
