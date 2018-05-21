<?php

namespace Icebearsoft\Kitukizuri\Controllers;

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
        return view('admin.modulopermisos', [
            'modulos'=>$modulos,
            'rmp' => $rmp
        ]);
    }

    public function store(Request $request)
    {
        $id = $request->get('id');
        $permisos = $request->get('permisos');
        RolModuloPermiso::where('rolid', $id)->delete();
        foreach ($permisos as $p) {
            $tmp = new RolModuloPermiso;
            $tmp->rolid = $id;
            $tmp->modulopermisoid = $p;
            $tmp->save();
        }
        
        $roles = UsuarioRol::where('usuarioid', Auth::id())->get();
        $isAdmin = false;
        foreach ($roles as $rol) {
            if ($rol->rolid == 1) {
                $isAdmin = true;
                break;
            }
        }
        return redirect($isAdmin ? '/admin-global/roles' : '/admin/admin-roles');
    }
}
