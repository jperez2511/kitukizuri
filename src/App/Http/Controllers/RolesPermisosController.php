<?php

namespace Icebearsoft\Kitukizuri\App\Http\Controllers;

use Illuminate\Http\Request;

use Crypt;
use Auth;
use App\Http\Controllers\Controller;

//Models
use Icebearsoft\Kitukizuri\App\Models\{
    Modulo,
    UsuarioRol,
    RolModuloPermiso
};

class RolesPermisosController extends Controller
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
        $rolid = $request->get('id');
        $modulos = Modulo::with('modulopermiso', 'modulopermiso.permisos')->orderBy('nombre')->get();
        $rmp = RolModuloPermiso::where('rolid', $rolid)->select('modulopermisoid')->pluck('modulopermisoid')->toArray();
        return view('kitukizuri::modulopermisos', [
            'modulos' => $modulos,
            'rmp'     => $rmp,
            'layout'  => 'krud::layout',
            'titulo'  => 'Permisos',
            'dash'    => true,
            'kmenu'   => true,
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
            $id = Crypt::decrypt($request->get('id'));
        } catch (Exception $e) {
            dd($e);
        }

        $permisos = $request->get('permisos');

        RolModuloPermiso::where('rolid', $id)->delete();

        if(!empty($permisos)){
            foreach ($permisos as $p) {
                $tmp = new RolModuloPermiso;
                $tmp->rolid = $id;
                $tmp->modulopermisoid = $p;
                $tmp->save();
            }
        }

        $roles = UsuarioRol::where('usuarioid', Auth::id())->get();

        return redirect()->route('roles.index');
    }
}
