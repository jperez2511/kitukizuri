<?php

namespace Icebearsoft\Kitukizuri\Http\Controllers;

use Mockery\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

// Contorllers
use App\Http\Controllers\Controller;

//Models
use Icebearsoft\Kitukizuri\Models\Permiso;
use Icebearsoft\Kitukizuri\Models\ModuloPermiso;
use Icebearsoft\Kitukizuri\Models\RolModuloPermiso;

class PermisosController extends Controller 
{
    /**
     * index
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function index(Request $request)
    {
		$id = $request->get('id');
		
		//obteniendo los permisos del modulo actualmente
		$mp = ModuloPermiso::where('moduloid', $id)->pluck('permisoid')->toArray();
		
		//validando si el permiso actual existe en algun rolModuloPermiso
		foreach ($mp as $id) {
			$permiso = RolModuloPermiso::where('modulopermisoid', $id)->first();
			if (!empty($permiso)) {
				return view('kitukizuri::error', [
					'msg'    => 'El modulo a editar ya tiene permisos asignados a los roles. Se recomienda ingresarlos desde los Seeders.',
					'type'   => 'warning',
					'layout' => 'krud.layout',
					'titulo' => 'Error',
				]);
			}
		}

		$permisos = Permiso::get();
		
		return view('kitukizuri::permisos',[
			'modulo'   => Crypt::encrypt($id),
			'permisos' => $permisos,
			'mp'       => $mp,
			'layout'   => 'krud.layout',
			'titulo'   => 'Permisos'
		]);
	}

    /**
     * store
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function store(Request $request)
    {
		try {
			$modulo = Crypt::decrypt($request->get('modulo'));
		} catch (Exception $e) {
			dd($e);
		}
		
		//Obteniendo los permisos seleccionados por el usuario
		$permisos = $request->get('permisos');

		ModuloPermiso::where('moduloid',$modulo)->delete();
		if(!empty($permisos)){
			foreach($permisos as $p){
				$mp = new ModuloPermiso;
				$mp->moduloid  = $modulo;
				$mp->permisoid = $p;
				$mp->save();
			}
		}
		return redirect()->route('modulos.index');
	}
}
