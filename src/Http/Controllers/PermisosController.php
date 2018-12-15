<?php

namespace Icebearsoft\Kitukizuri\Http\Controllers;

use Mockery\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

// Contorllers
use App\Http\Controllers\Controller;

//Models
use Icebearsoft\Kitukizuri\Models\ModuloPermiso;
use Icebearsoft\Kitukizuri\Models\Permiso;

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
		$permisos = Permiso::get();
		$mp = ModuloPermiso::where('moduloid', $id)->pluck('permisoid')->toArray();
		return view('kitukizuri.permisos',[
			'modulo'   => Crypt::encrypt($id),
			'permisos' => $permisos,
			'mp'       => $mp
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

		ModuloPermiso::where('moduloid',$modulo)->delete();
		foreach($request->get('permisos') as $p){
			$mp = new ModuloPermiso;
			$mp->moduloid  = $modulo;
			$mp->permisoid = $p;
			$mp->save();
		}
		return redirect('/kk/modulos');
	}
}
