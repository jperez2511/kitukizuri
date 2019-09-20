<?php

namespace Icebearsoft\Kitukizuri\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

// Controllers
use App\Http\Controllers\Controller;

//Models
use Icebearsoft\Kitukizuri\Models\ModuloEmpresas;
use Icebearsoft\Kitukizuri\Models\Modulo;

class ModuloEmpresasController extends Controller
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
    	$select = ModuloEmpresas::select('moduloid')->where('empresaid', $request->parent)->pluck('moduloid');
    	$modulos = Modulo::orderBy('nombre')->get();

    	return view('kitukizuri::moduloempresas', [
            'layout'           => 'krud.layout',
            'titulo'           => 'Modulos asignados a la Empresa',
            'modulos'          => $modulos,
            'empresa'          => $request->parent,
			'moduloEmpresas'   => $select->toArray(),
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
    	ModuloEmpresas::where('empresaid', $request->empresa)->delete();
		foreach ($request->get('modulos', []) as $modulo) {
			$me = new ModuloEmpresas;
			$me->moduloid = $modulo;
			$me->empresaid = $request->empresa;
			$me->save();
         }    	
         $ruta = route('moduloempresas.index');
    	return redirect($ruta.'?parent='.$request->empresa);
    }

    /**
     * validar
     *
     * @param  mixed $ruta
     *
     * @return void
     */
    public function validar($ruta)
    {
        //dividiendo la ruta
        $ruta = explode('.', $ruta);

        //obteniendo moduloid
        $modulo = Modulo::where('ruta', $ruta[0])->first();
        //obteniendo la empresa id a traves del usuarioid
        $empresaModulo = ModuloEmpresas::where('empresaid', Auth::user()->empresaid)
            ->where('moduloid', $modulo->moduloid)->first();

        if (empty($empresaModulo)) {
            return abort(401);    
        }
    }
}
