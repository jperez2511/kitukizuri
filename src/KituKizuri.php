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
     * Valida los premisos segun una ruta
     *
     * @param  mixed $ruta
     *
     * @return void
     */
    public static function permiso($ruta)
    {
        $estado = false;

        // Obteniendo nombre de la ruta
        $ruta         = explode('.', $ruta);
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
     * Retorna un array con los permisos segun usuario y/o ruta
     *
     * @param  mixed $uid
     * @param  mixed $currentRoute
     *
     * @return void
     */
    public static function getPermisos($uid=null, $currentRoute = null)
    {
        // Variables utilitarias
        $aprobados = [];

        // Obteneindo datos de ruta
        $ruta = empty($currentRoute) ? Route::currentRouteName() : $currentRoute;
        $ruta = explode('.', $ruta);
        $ruta = $ruta[0];

        //Obteniendo informacion del modulo
        $mi = Modulo::select('moduloid')->where('ruta', $ruta)->value('moduloid'); //modulo id
        $mp = ModuloPermiso::where('moduloid', $mi)->select('modulopermisoid')->pluck('modulopermisoid')->toArray(); //modulo permiso
        
        // obteniendo datos de los reoles
        $roles = UsuarioRol::select('rolid')->where('usuarioid', $uid != null ? $uid : Auth::id())->pluck('rolid')->toArray();
        $rmp   = RolModuloPermiso::with('rol', 'modulopermiso', 'modulopermiso.permisos')
            ->select('modulopermisoid')
            ->whereIn('rolid', $roles)
            ->whereIn('modulopermisoid', $mp)
            ->groupBy('modulopermisoid')->get();

        // Recorriendo permisos
        foreach ($rmp as $value) {
            $mp = $value->modulopermiso()->first();
            $p  = $mp->permisos()->first();
            array_push($aprobados, $p->nombreLaravel);
        }

        return $aprobados;
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
        //== validando si los usuarios tienen empresa id 
        if (empty(Auth::user()->empresaid)) {
            return true;
        }

        //dividiendo la ruta
        $ruta = explode('.', $ruta);
        //obteniendo moduloid
        $modulo = Modulo::where('ruta', $ruta[0])->first();
        if (empty($modulo)) {
            return false;
        }
        //obteniendo la empresa id a traves del usuarioid
        $empresaModulo = ModuloEmpresas::where('empresaid', Auth::user()->empresaid)
            ->where('moduloid', $modulo->moduloid)->first();

        if (empty($empresaModulo)) {
            return false;
        }

        return true;
    }
}
