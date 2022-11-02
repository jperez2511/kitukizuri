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
    /**
     * __construct
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $collect = Rol::select('rolid as id', 'nombre as value')->get();
        
        $this->setModel(new UsuarioRol);
        $this->setTitulo('Roles');
        $this->setCampo(['nombre'=>'Rol', 'campo'=>'r.nombre', 'edit' => false]);
        $this->setCampo(['nombre'=>'Descripcion', 'campo'=>'r.descripcion', 'edit'=>false]);
        $this->setCampo(['nombre'=>'Rol', 'campo'=>'usuarioRol.rolid', 'tipo'=>'combobox', 'collect'=>$collect, 'show'=>false]);
        $this->setJoin('roles as r', 'r.rolid', '=', 'usuarioRol.rolid');
        $this->setWhere('usuarioid', '=', $request->get('parent'));
        $this->setParents('usuarioid', 'parent', true);
        $this->setLayout('krud::layout');
    }
}
