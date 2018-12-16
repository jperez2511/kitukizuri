<?php

namespace Icebearsoft\Kitukizuri\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Http\Controllers\Controller;

use Icebearsoft\Kitukizuri\Models\Modulo;
use Icebearsoft\Kitukizuri\Models\UsuarioRol;
use Icebearsoft\Kitukizuri\Models\RolModuloPermiso;

class RolesPermisosController extends Controller
{
    public function index(Request $request)
    {
        $rolid = $request->get('id');
        $modulos = Modulo::with('modulopermiso', 'modulopermiso.permisos')->orderBy('nombre')->get();
        $rmp = RolModuloPermiso::where('rolid', $rolid)->select('modulopermisoid')->pluck('modulopermisoid')->toArray();
        return view('kitukizuri.modulopermisos', [
            'modulos'=>$modulos,
            'rmp' => $rmp,
            'layout' => 'krud.layout',
            'titulo' => 'Permisos'
        ]);
    }

    public function store(Request $request)
    {
        $id = $request->get('id');
        $permisos = $request->get('permisos');
        
        try {
            RolModuloPermiso::where('rolid', $id)->delete();
        } catch (Exception $e) {
            dd('ahorita no joven');
        }

        
        
        foreach ($permisos as $p) {
            $tmp = new RolModuloPermiso;
            $tmp->rolid = $id;
            $tmp->modulopermisoid = $p;
            $tmp->save();
        }
        
        $roles = UsuarioRol::where('usuarioid', Auth::id())->get();

        return redirect('/kk/roles');
    }
}
