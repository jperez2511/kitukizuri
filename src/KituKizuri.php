<?php

namespace Icebearsoft\Kitukizuri;

use App\Models\Modulo;
use App\Models\ModuloPermiso;
use App\Models\Permiso;
use App\Models\RolModuloPermiso;
use App\Models\UsuarioRol;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Route;

class KituKizuri extends Controller {
	public static function permiso($ruta){
		$ruta = explode('.', $ruta);
		$rNombre = $ruta[count($ruta)-1];
		unset($ruta[count($ruta)-1]);
		$mNombre = implode('.', $ruta);
		$roles = UsuarioRol::where('usuarioid', Auth::id())->get();
		if ($roles->isEmpty()) return false;
		$mi = Modulo::where('ruta', $mNombre)->first(); //modulo id
		if(empty($mi)){
			return false;
		} 
		if ($rNombre == 'index'){
			$rNombre = ['show'];
		}
		elseif ($rNombre == 'store'){
			$rNombre = ['create','edit'];
		}else {
			$rNombre = [$rNombre];
		}
		foreach ($rNombre as $val) {
			$p = Permiso::where('nombreLaravel', $val)->first();
			$mp = ModuloPermiso::where('moduloid', $mi->moduloid)->where('permisoid', $p->permisoid)->first(); //modulo permiso
			if(empty($mp)) {
				continue;
			}
			foreach($roles as $r ) {
				$rmp = RolModuloPermiso::where('rolid',$r->rolid)->where('modulopermisoid',$mp->modulopermisoid)->first();
				if($rmp != null){
					return true;
				}
			}			
		}
		return false;
	}
	public static function getPermisos($uid=null){
		$ruta = Route::currentRouteName();
		$ruta = explode('.', $ruta);
		$ruta = $ruta[0];
		$aprobados = [];
		$mi = Modulo::where('ruta', $ruta)->first(); //modulo id
		$mp = ModuloPermiso::where('moduloid', $mi->moduloid)->select('modulopermisoid')->pluck('modulopermisoid')->toArray(); //modulo permiso
		$roles = UsuarioRol::where('usuarioid', $uid != null ? $uid : Auth::id())->get();
		foreach($roles as $r ){
			$rmp = RolModuloPermiso::with('rol', 'modulopermiso', 'modulopermiso.permisos')->where('rolid',$r->rolid)->whereIn('modulopermisoid', $mp)->get();
			foreach($rmp as $value){
				$mp = $value->modulopermiso()->first();
				$p = $mp->permisos()->first();
				array_push($aprobados, $p->nombreLaravel);
			}
		}
		return $aprobados;
	}
}
