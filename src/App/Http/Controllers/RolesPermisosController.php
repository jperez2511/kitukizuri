<?php

namespace Icebearsoft\Kitukizuri\App\Http\Controllers;

use Illuminate\Http\Request;

use Log;
use Auth;
use Crypt;
use App\Http\Controllers\Controller;

//Models
use Icebearsoft\Kitukizuri\App\Models\{
    Rol,
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

        try {
            $rolid = Crypt::decrypt($request->id);
        } catch (Exception $e) {
            Log::error($e);
            abort(404);
        }

        $modulos = Modulo::with('modulopermiso', 'modulopermiso.permisos')->orderBy('nombre')->get();
        $rmp     = RolModuloPermiso::where('rolid', $rolid)->select('modulopermisoid')->pluck('modulopermisoid')->toArray();
        $rolName = Rol::find($rolid)->nombre;

        return view('kitukizuri::modulopermisos', [
            'modulos' => $modulos,
            'rmp'     => $rmp,
            'layout'  => 'krud::layout',
            'titulo'  => __('Asignar permisos a: ').$rolName,
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
