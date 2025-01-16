<?php

namespace Icebearsoft\Kitukizuri\App\Providers;

use Illuminate\Routing\Router;

class MiddlewareRegistrar
{
    /**
     * Register the application's middleware.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public static function register(Router $router)
    {
        $router->aliasMiddleware('kitukizuri', 'Icebearsoft\Kitukizuri\App\Http\Middleware\KituKizurimd');
        $router->aliasMiddleware('kmenu', 'Icebearsoft\Kitukizuri\App\Http\Middleware\Menu');
        $router->aliasMiddleware('klang', 'Icebearsoft\Kitukizuri\App\Http\Middleware\SetLang');
    }
}