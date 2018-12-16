<?php

namespace Icebearsoft\Kitukizuri\Http\Controllers;

use Krud;
use Auth;
use Illuminate\Http\Request;

//=== Models
use Icebearsoft\Kitukizuri\Models\Rol;
use Icebearsoft\Kitukizuri\Models\UsuarioRol;

class UsuarioRolController extends Krud
{
    public function __construct(Request $request)
    {
        $this->setModel(new UsuarioRol);
        $this->setTitulo('Asignar Roles');
        $this->setCampo(['nombre'=>'Rol', 'campo'=>'r.nombre', 'edit'=>false]);
        $this->setCampo(['nombre'=>'Descripcion', 'campo'=>'r.descripcion', 'edit'=>false]);
        $this->middleware(function ($request, $next) {
            $collect    = Rol::select('rolid', 'nombre')->get();
            $usuarioRol = UsuarioRol::where('usuarioid', Auth::id())->get();
            if ($usuarioRol->find(['rolid'=>1])->isEmpty()) {
                if (!empty(Auth::user()->empresaid)) {
                    $collect->where('empresaid', Auth::user()->empresaid);
                }
            }
            $collect = $collect->get();
            $this->setCampo(['nombre'=>'Rol', 'campo'=>'usuarioRol.rolid', 'tipo'=>'combobox', 'collect'=>$collect, 'show'=>false]);
            return $next($request);
        });
        $this->setJoin('roles as r', 'r.rolid', '=', 'usuarioRol.rolid');
        $this->setwhere('usuarioid', '=', $request->get('parent'));
        $this->setParentId('usuarioid');
        $this->setLayout('krud.layout');
    }
}
