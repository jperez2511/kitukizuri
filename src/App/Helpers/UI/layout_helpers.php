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
        ];

        if (!$withDashlite) {
            return $context;
        }

        $dashliteVariant = trim((string) config('kitukizuri.dashliteVariant', 'demo3'));
        $dashliteBodyClass = trim((string) config('kitukizuri.dashliteBodyClass', $defaultBodyClass));

        $context['dashliteVariant'] = $dashliteVariant === '' ? 'demo3' : $dashliteVariant;
        $context['dashliteBodyClass'] = $dashliteBodyClass === '' ? $defaultBodyClass : $dashliteBodyClass;

        return $context;
    }
}

if (!function_exists('krudErrorLayoutContext')) {
    function krudErrorLayoutContext(): array
    {
        return [
            'isDarkSuffix' => !empty(config('kitukizuri.dark')) ? '-dark' : null,
            'dashliteVariant' => trim((string) config('kitukizuri.dashliteVariant', 'demo3')),
            'dashliteBodyClass' => trim((string) config('kitukizuri.dashliteBodyClass', 'bg-lighter npc-general')),
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
