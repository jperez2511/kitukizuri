<?php

namespace Icebearsoft\Kitukizuri;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class KitukizuriServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadViewsFrom(__DIR__.'/resources/views/krud', 'krud');
        $this->loadViewsFrom(__DIR__.'/resources/views/kitukizuri', 'kitukizuri');
        
        AliasLoader::getInstance()->alias('Kitukizuri', 'Icebearsoft\Kitukizuri\KituKizuri');
        AliasLoader::getInstance()->alias('Krud', 'Icebearsoft\Kitukizuri\Krud');
        
        $router->aliasMiddleware('kitukizuri', 'Icebearsoft\Kitukizuri\Http\Middleware\KituKizurimd');
        $router->aliasMiddleware('kmenu', 'Icebearsoft\Kitukizuri\Http\Middleware\Menu');
        
        $this->publishes([
            __DIR__.'/database/migrations' => $this->app->databasePath() . '/migrations',
        ], 'migrations');

        $this->publishes([
            __DIR__.'/database/migrations' => $this->app->databasePath() . '/migrations',
        ], 'migrations');
        
        $this->publishes([
            __DIR__.'/database/seeders' => $this->app->databasePath() . '/seeders',
        ], 'seeds');
        
        $this->publishes([
             __DIR__.'/resources/views/krud' => $this->app->basePath() . '/resources/views/krud',
        ]);
        
        $this->publishes([
             __DIR__.'/resources/views/errors' => $this->app->basePath() . '/resources/views/errors',
        ]);

        $this->publishes([
             __DIR__.'/config' => $this->app->basePath() . '/config',
        ]);
        
        $this->publishes([
            __DIR__.'/public' => $this->app->basePath() . '/public',
       ]);

       Route::group(['prefix' => 'kk','namespace' =>'Icebearsoft\\Kitukizuri\\Http\\Controllers', 'middleware' => ['web', 'auth', 'kitukizuri']], function () {
            Route::get('/', 'DashboardController@index')->name('dashboard.index');
            Route::resource('roles', 'RolesController');
            Route::resource('modulos', 'ModulosController');
            Route::resource('usuarios', 'UsuariosController');
            Route::resource('asignarpermiso', 'UsuarioRolController');
            Route::resource('permisos', 'PermisosController', ['only'=>['index', 'store']]);
            Route::resource('rolpermisos', 'RolesPermisosController', ['only'=>['index', 'store']]);
            Route::resource('empresas', 'EmpresasController');
            Route::resource('moduloempresas', 'ModuloEmpresasController'); 
       });
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
