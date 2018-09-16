<?php

namespace Icebearsoft\Kitukizuri\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//Models
use Icebearsoft\Kitukizuri\Models\ModuloEmpresas;
use Icebearsoft\Kitukizuri\Models\Modulo;
use Auth;

class ModuloEmpresasController extends Controller
{
    public function index(Request $request){
    	$select = ModuloEmpresas::select('moduloid')->where('empresaid', $request->parent)->pluck('moduloid');
    	$modulos = Modulo::orderBy('nombre')->get();

    	return view('empresa.moduloempresas.index', [
            'layout'           => 'krud.layout',
            'modulos'          => $modulos,
            'empresa'          => $request->parent,
			'moduloEmpresas'   => $select->toArray(),
    	]);
    }

    public function store(Request $request)
    {
    	ModuloEmpresas::where('empresaid', $request->empresa)->delete();
		foreach ($request->get('modulos', []) as $modulo) {
			$me = new ModuloEmpresas;
			$me->moduloid = $modulo;
			$me->empresaid = $request->empresa;
			$me->save();
	 	}    	
    	return redirect('/kk/moduloempresas?parent='.$request->empresa);
    }

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
