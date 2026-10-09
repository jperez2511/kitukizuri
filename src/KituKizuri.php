<?php

namespace Icebearsoft\Kitukizuri;

use Route;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

//== Models
use Icebearsoft\Kitukizuri\App\Models\Modulo;
use Icebearsoft\Kitukizuri\App\Models\Permiso;
use Icebearsoft\Kitukizuri\App\Models\UsuarioRol;
use Icebearsoft\Kitukizuri\App\Models\ModuloPermiso;
use Icebearsoft\Kitukizuri\App\Models\ModuloEmpresas;
use Icebearsoft\Kitukizuri\App\Models\RolModuloPermiso;

class KituKizuri extends Controller
{
    /**
     * permiso
     * Valida los premisos según una ruta
     *
     * @param  mixed $ruta
     *
     * @return void
     */
    public static function permiso($ruta)
    {
        $estado = false;

        // Obteniendo nombre de la ruta
        $ruta = explode('.', $ruta);

        if(count($ruta) != 2) {
            dd('falta agregar un nombre a la ruta');
        }

        $nombreRuta   = $ruta[1];
        $moduloNombre = $ruta[0];

        $acciones = [
            'index' => ['show'],
            'store' => ['create', 'edit'],
            'update' => ['create', 'edit'],
        ];

        $arrayAccion = !empty($acciones[$nombreRuta]) ? $acciones[$nombreRuta] : [$nombreRuta];
        return (new \Icebearsoft\Kitukizuri\Authorization\ModuleAccess)
            ->permits(Auth::user(), $moduloNombre, $arrayAccion);
    }

    /**
     * getPermisos
     * Retorna un array con los permisos según usuario y/o ruta
     *
     * @param  mixed $uid
     * @param  mixed $currentRoute
     *
     * @return void
     */
    public static function getPermisos($uid=null, $currentRoute = null)
    {
        // Obteniendo datos de ruta
        $ruta = $currentRoute ?? Route::currentRouteName();
        $nombreRuta = explode('.', $ruta);

        // Obteniendo permisos como array
        $moduloID = Modulo::where('ruta', $nombreRuta)->value('moduloid');
        $permisos = UsuarioRol::getPermisosAsignados(Auth::id(), $moduloID)
            ->pluck('nombreLaravel')
            ->toArray();

        return $permisos;
    }

    /**
     * validar
     *
     * @param  mixed $ruta
     *
     * @return void
     */
    public static function validar($ruta)
    {
        return (new \Icebearsoft\Kitukizuri\Authorization\ModuleAccess)
            ->companyAllows(Auth::user(), explode('.', $ruta)[0]);
    }
}
