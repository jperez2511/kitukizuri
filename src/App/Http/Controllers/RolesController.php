<?php

namespace Icebearsoft\Kitukizuri\App\Http\Controllers;

use Krud;
use Illuminate\Support\Facades\Auth;

//== Models
use Icebearsoft\Kitukizuri\App\Models\{
    Rol,
    UsuarioRol
};

class RolesController extends Krud
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->setModel(new Rol());
        $this->setTitulo('Roles');
        $this->setCampo(['nombre'=>'Nombre', 'campo'=>'nombre']);
        $this->setCampo(['nombre'=>'Descripcion', 'campo'=>'descripcion']);
        $this->setBoton(['nombre'=>'Asignar Permisos', 'url'=>route('rolpermisos.index').'?id={id}', 'class'=>'outline-warning btn-sm', 'icon'=>'fa-duotone fa-thin fa-lock']);
        $this->setLayout('krud::layout');
        
        if (!empty(Auth::user()->empresaid)) {
            $usuarioRol = UsuarioRol::where('usuarioid', Auth::id())->get();
            if ($usuarioRol->find(['rolid'=>1])->isEmpty()) {
                $empresaid = Auth::user()->empresaid;
                $this->setCampo(['nombre'=>'empresaid', 'campo'=>'empresaid', 'tipo' => 'hidden', 'value'=>$empresaid, 'show'=>false]);
            }
        }
    }
}
