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
        
        $publishes = [
            ['from' => '/database/migrations',      'to' => $this->app->databasePath().'/migrations',           'tag' => 'migrations'],
            ['from' => '/database/seeders',         'to' => $this->app->databasePath().'/seeders',              'tag' => 'seeders'],
            ['from' => '/resources/views/krud',     'to' => $this->app->basePath().'/resources/views/krud',     'tag' => 'vKrud'],
            ['from' => '/resources/views/errors',   'to' => $this->app->basePath().'/resources/views/errors',   'tag' => 'vError'],
            ['from' => '/config',                   'to' => $this->app->basePath().'/config',                   'tag' => 'config'],
            ['from' => '/public',                   'to' => $this->app->basePath().'/public',                   'tag' => 'public'],
        ];

        foreach ($publishes as $publish) {
            $this->publishes([__DIR__. $publish['from'] => $publish['to'],], $publish['tag']);    
        }

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
