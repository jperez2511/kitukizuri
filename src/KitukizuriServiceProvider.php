<?php

namespace Icebearsoft\Kitukizuri;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

use Icebearsoft\Kitukizuri\App\Console\Command\{
    MakeModule,
    KrudInstall,
    VueInstall,
    TsInstall,
    LogInstall,
    DefaultData,
    SetDocker,
    LibsInstall,
    UiConfig
};

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

        $router->aliasMiddleware('kitukizuri', 'Icebearsoft\Kitukizuri\App\Http\Middleware\KituKizurimd');
        $router->aliasMiddleware('kmenu', 'Icebearsoft\Kitukizuri\App\Http\Middleware\Menu');
        $router->aliasMiddleware('klang', 'Icebearsoft\Kitukizuri\App\Http\Middleware\SetLang');

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
            $this->registerComponent('select2');
            $this->registerComponent('password');
            $this->registerComponent('textarea');
            $this->registerComponent('table');
            $this->registerComponent('index-tree');
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
                MakeModule::class,
                KrudInstall::class,
                VueInstall::class,
                TsInstall::class,
                LogInstall::class,
                DefaultData::class,
                SetDocker::class,
                LibsInstall::class,
                UiConfig::class
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
        $prefix = config('kitukizuri.routePrefix') ?? 'krud';

        Route::group(['prefix' => $prefix,'namespace' =>'Icebearsoft\\Kitukizuri\\App\\Http\\Controllers', 'middleware' => ['web', 'auth', 'kitukizuri', 'kmenu']], function () {
            Route::get('/', 'DashboardController@index')->name('dashboard.index');
            Route::resource('roles', 'RolesController');
            Route::resource('modulos', 'ModulosController');
            Route::resource('usuarios', 'UsuariosController');
            Route::resource('asignarpermiso', 'UsuarioRolController');
            Route::resource('permisos', 'PermisosController', ['only'=>['index', 'store']]);
            Route::resource('rolpermisos', 'RolesPermisosController', ['only'=>['index', 'store']]);
            Route::resource('empresas', 'EmpresasController');
            Route::resource('sucursales', 'SucursalesController');
            Route::resource('moduloempresas', 'ModuloEmpresasController');
            Route::resource('database', 'DataBaseController');
            Route::resource('logs', 'LogController');
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
