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
use Icebearsoft\Kitukizuri\Models\RolModuloPermiso;

class KituKizuri extends Controller
{
    //== Valida los premisos segun una ruta
    public static function permiso($ruta)
    {
        $ruta = explode('.', $ruta);
        $rNombre = $ruta[count($ruta)-1];
        unset($ruta[count($ruta)-1]);
        $mNombre = implode('.', $ruta);
        $roles = UsuarioRol::where('usuarioid', Auth::id())->get();
        if ($roles->isEmpty()) {
            return false;
        }
        $mi = Modulo::where('ruta', $mNombre)->first(); //modulo id
        if (empty($mi)) {
            return false;
        }
        if ($rNombre == 'index') {
            $rNombre = ['show'];
        } elseif ($rNombre == 'store') {
            $rNombre = ['create','edit'];
        } else {
            $rNombre = [$rNombre];
        }
        foreach ($rNombre as $val) {
            $p = Permiso::where('nombreLaravel', $val)->first();
            $mp = ModuloPermiso::where('moduloid', $mi->moduloid)->where('permisoid', $p->permisoid)->first(); //modulo permiso
            if (empty($mp)) {
                continue;
            }
            foreach ($roles as $r) {
                $rmp = RolModuloPermiso::where('rolid', $r->rolid)->where('modulopermisoid', $mp->modulopermisoid)->first();
                if ($rmp != null) {
                    return true;
                }
            }
        }
        return false;
    }

    //== Retorna un array con los permisos segun usuario y/o ruta
    public static function getPermisos($uid=null, $currentRoute = null)
    {
        if (empty($currentRoute)) {
            $ruta = Route::currentRouteName();
        } else {
            $ruta = $currentRoute;
        }
        
        $ruta = explode('.', $ruta);
        $ruta = $ruta[0];
        $aprobados = [];
        $mi = Modulo::where('ruta', $ruta)->first(); //modulo id
        $mp = ModuloPermiso::where('moduloid', $mi->moduloid)->select('modulopermisoid')->pluck('modulopermisoid')->toArray(); //modulo permiso
        $roles = UsuarioRol::where('usuarioid', $uid != null ? $uid : Auth::id())->get();
        foreach ($roles as $r) {
            $rmp = RolModuloPermiso::with('rol', 'modulopermiso', 'modulopermiso.permisos')->where('rolid', $r->rolid)->whereIn('modulopermisoid', $mp)->get();
            foreach ($rmp as $value) {
                $mp = $value->modulopermiso()->first();
                $p = $mp->permisos()->first();
                array_push($aprobados, $p->nombreLaravel);
            }
        }
        return $aprobados;
    }

    public static function validar($ruta)
    {
        $permiso = false; 

        //== validando si los usuarios tienen empresa id 
        if (empty(Auth::user()->empresaid)) {
            return $permiso;
        }

        //dividiendo la ruta
        $ruta = explode('.', $ruta);
        //obteniendo moduloid
        $modulo = Modulo::where('ruta', $ruta[0])->first();
        //obteniendo la empresa id a traves del usuarioid
        $empresaModulo = ModuloEmpresas::where('empresaid', Auth::user()->empresaid)
            ->where('moduloid', $modulo->moduloid)->first();

        if (!empty($empresaModulo)) {
            $permiso = true;
        } 
        
        return $permiso;
    }
}
