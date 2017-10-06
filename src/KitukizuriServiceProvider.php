<?php

namespace Ibs\Kitukizuri;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;

class KitukizuriServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
         if (!$this->app->routesAreCached()) {
            require __DIR__.'/Http/routes.php';
        }
        $this->loadViewsFrom(__DIR__.'/resources/views', 'kitukizuri');
        AliasLoader::getInstance()->alias('Kitukizuri','Ibs\Kitukizuri\Kitukizuri');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('kitukizuri', function($app) {
            return new Kitukizuri;
        });
    }
}
