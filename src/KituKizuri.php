<?php

namespace Icebearsoft\Kitukizuri;

use Route;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

//== Models
use Icebearsoft\Kitukizuri\Models\Modulo;
use Icebearsoft\Kitukizuri\Models\Permiso;
use Icebearsoft\Kitukizuri\Models\UsuarioRol;
use Icebearsoft\Kitukizuri\Models\ModuloPermiso;
use Icebearsoft\Kitukizuri\Models\ModuloEmpresas;
use Icebearsoft\Kitukizuri\Models\RolModuloPermiso;

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
        $moduloID = Modulo::where('ruta', $moduloNombre)->value('moduloid');
        
        if(!empty($moduloID)){
            $estado = UsuarioRol::getPermisosAsignados(Auth::id(), $moduloID, $arrayAccion);
        }

        return $estado;
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
        $estado = false;

        if (!empty(Auth::user()->empresaid)) {
            $empresaID = Auth::user()->empresaid;
            $ruta      = explode('.', $ruta);
            $moduloID  = Modulo::where('ruta', $ruta[0])->value('moduloid');
            $estado    = ModuloEmpresas::where('empresaid', $empresaID)
                ->where('moduloid', $moduloID)
                ->exists();
        } else {
            $estado = true;
        }

        return $estado;
    }
}
