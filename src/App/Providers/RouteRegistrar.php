<?php

namespace Icebearsoft\Kitukizuri\App\Providers;

use Illuminate\Support\Facades\Route;

class RouteRegistrar
{
    /**
     * Register the application's routes.
     *
     * @return void
     */
    public static function register()
    {
        $prefix = config('kitukizuri.routePrefix') ?? 'krud';

        Route::group([
            'prefix'     => $prefix,
            'namespace'  => 'Icebearsoft\\Kitukizuri\\App\\Http\\Controllers',
            'middleware' => ['web', 'auth', 'kitukizuri', 'kmenu']
        ], function () {
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
}