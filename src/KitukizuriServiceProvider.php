<?php

namespace Icebearsoft\Kitukizuri;

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
    public function boot(Router $router)
    {
        // if (!$this->app->routesAreCached()) {
        //     require __DIR__.'/Http/routes.php';
        // }
        
        $this->loadViewsFrom(__DIR__.'/resources/views', 'kitukizuri');
        
        AliasLoader::getInstance()->alias('Kitukizuri', 'Icebearsoft\Kitukizuri\KituKizuri');
        AliasLoader::getInstance()->alias('Krud', 'Icebearsoft\Kitukizuri\Krud');
        
        $router->aliasMiddleware('kitukizuri', 'Icebearsoft\Kitukizuri\Http\Middleware\KituKizurimd');
        
        $this->publishes([
            __DIR__.'/database/migrations' => $this->app->databasePath() . '/migrations',
        ], 'migrations');
        
        $this->publishes([
            __DIR__.'/database/seeds' => $this->app->databasePath() . '/seeds',
        ], 'seeds');
        
        $this->publishes([
             __DIR__.'/resources/views/krud' => $this->app->basePath() . '/resources/views/krud',
        ]);
        
        $this->publishes([
             __DIR__.'/resources/views/errors' => $this->app->basePath() . '/resources/views/errors',
        ]);

        $this->publishes([
             __DIR__.'/Http/Controllers' => $this->app->basePath() . '/app/Http/Controllers/Kitukizuri',
        ]);

        $this->publishes([
             __DIR__.'/Http/routes' => $this->app->basePath() . '/app/routes/Kitukizuri',
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('kitukizuri', function ($app) {
            return new KituKizuri;
        });
    }
}
