<?php

namespace Icebearsoft\Kitukizuri;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

use Icebearsoft\Kitukizuri\Console\Command\MakeModule;

class KitukizuriServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        AliasLoader::getInstance()->alias('Kitukizuri', 'Icebearsoft\Kitukizuri\KituKizuri');
        AliasLoader::getInstance()->alias('Krud', 'Icebearsoft\Kitukizuri\Krud');
        
        $router->aliasMiddleware('kitukizuri', 'Icebearsoft\Kitukizuri\Http\Middleware\KituKizurimd');
        $router->aliasMiddleware('kmenu', 'Icebearsoft\Kitukizuri\Http\Middleware\Menu');

        $this->configureViewsBladeComponents();            
        $this->configureCommands();
        $this->configurePublishing();
        $this->configureRoutes();
    }
            
    /**
     * configureBladeComponents
     *
     * @return void
     */
    protected function configureViewsBladeComponents()
    {
        $this->loadViewsFrom(__DIR__.'/resources/views/krud', 'krud');
        $this->loadViewsFrom(__DIR__.'/resources/views/kitukizuri', 'kitukizuri');

        $this->callAfterResolving(BladeCompiler::class, function () {
            $this->registerComponent('input');
            $this->registerComponent('select');
            $this->registerComponent('password');
            $this->registerComponent('textarea');
        });
    }

    /**
     * configureCommands
     *
     * @return void
     */
    protected function configureCommands()
    {
        if($this->app->runningInConsole()) {
            $this->commands([
                MakeModule::class
            ]);
        }
    }


    /**
     * configureRoutes
     *
     * @return void
     */
    protected function configureRoutes()
    {
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
     * configurePublishing
     *
     * @return void
     */
    protected function configurePublishing()
    {
        $databasePath = $this->app->databasePath();
        $basePath     = $this->app->basePath();

        $publishes = [
            ['from' => '/database/migrations',      'to' => $databasePath.'/migrations',           'tag' => 'migrations'],
            ['from' => '/database/seeders',         'to' => $databasePath.'/seeders',              'tag' => 'seeders'],
            ['from' => '/resources/views/errors',   'to' => $basePath.'/resources/views/errors',   'tag' => 'vError'],
            ['from' => '/resources/views/krud',     'to' => $basePath.'/resources/views/krud',     'tag' => 'vKrud'],
            ['from' => '/config',                   'to' => $basePath.'/config',                   'tag' => 'config'],
            ['from' => '/public',                   'to' => $basePath.'/public',                   'tag' => 'public'],
        ];

        foreach ($publishes as $publish) {
            $this->publishes([__DIR__. $publish['from'] => $publish['to'],], $publish['tag']);    
        }
    }
    
    /**
     * registerComponent
     *
     * @param  mixed $component
     * @return void
     */
    protected function registerComponent($component)
    {
        Blade::component('krud::components.'.$component, 'krud-'.$component);
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
