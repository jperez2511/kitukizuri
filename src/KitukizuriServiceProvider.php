<?php

namespace Icebearsoft\Kitukizuri;

use Illuminate\Routing\Router;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

use Icebearsoft\Kitukizuri\App\Http\Middleware\Tenant;
use Icebearsoft\Kitukizuri\App\Providers\RouteRegistrar;
use Icebearsoft\Kitukizuri\App\Providers\CommandRegistrar;
use Icebearsoft\Kitukizuri\App\Providers\MiddlewareRegistrar;
use Icebearsoft\Kitukizuri\App\Providers\BladeServiceProvider;

class KitukizuriServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router, Kernel $kernel)
    {
        $kernel->pushMiddleware(Tenant::class);
        MiddlewareRegistrar::register($router);
        RouteRegistrar::register();
        
        AliasLoader::getInstance()->alias('Kitukizuri', 'Icebearsoft\Kitukizuri\KituKizuri');
        AliasLoader::getInstance()->alias('Krud', 'Icebearsoft\Kitukizuri\Krud');

        $this->configurePublishing();        
    }

    /**
     * configurePublishing
     *
     * @return void
     */
    protected function configurePublishing()
    {
        $databasePath = $this->app->databasePath();
        $basePath     = $this->app->basePath();

        $publishes = [
            ['from' => '/database/migrations',      'to' => $databasePath.'/migrations',           'tag' => 'krud-migrations'],
            ['from' => '/database/seeders',         'to' => $databasePath.'/seeders',              'tag' => 'krud-seeders'],
            ['from' => '/resources/views/errors',   'to' => $basePath.'/resources/views/errors',   'tag' => 'krud-error'],
            ['from' => '/resources/views/krud',     'to' => $basePath.'/resources/views/krud',     'tag' => 'krud-views'],
            ['from' => '/resources/views/app',      'to' => $basePath.'/resources/views/app',      'tag' => 'krud-app'],
            ['from' => '/config',                   'to' => $basePath.'/config',                   'tag' => 'krud-config'],
            ['from' => '/public',                   'to' => $basePath.'/public',                   'tag' => 'krud-public'],
        ];

        foreach ($publishes as $publish) {
            $this->publishes([__DIR__. $publish['from'] => $publish['to'],], $publish['tag']);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(CommandRegistrar::class);
        $this->app->register(BladeServiceProvider::class);

        $this->app->singleton(Tenant::class);
        $this->app->singleton('kitukizuri', function ($app) {
            return new KituKizuri;
        });
    }
}
