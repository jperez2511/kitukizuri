<?php

namespace Icebearsoft\Kitukizuri\App\Http\Controllers;

use Krud;
use Auth;
use Crypt;
use Illuminate\Http\Request;
use Illuminate\Contracts\Encryption\DecryptException;

//=== Models
use Icebearsoft\Kitukizuri\App\Models\{
    Rol,
    UsuarioRol
};

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
        
        try {
            // Intentar desencriptar y ver si no hay errores
            $value = Crypt::decrypt($request->get('parent'));
        } catch (DecryptException $e) {
            // Si hay un error, probablemente el valor no está encriptado
            $value = $request->get('parent');
        }

        $this->setModel(new UsuarioRol);
        $this->setTitulo('Roles');
        $this->setCampo(['nombre'=>'Rol', 'campo'=>'r.nombre', 'edit' => false]);
        $this->setCampo(['nombre'=>'Descripcion', 'campo'=>'r.descripcion', 'edit'=>false]);
        $this->setCampo(['nombre'=>'Rol', 'campo'=>'usuarioRol.rolid', 'tipo'=>'combobox', 'collect'=>$collect, 'show'=>false]);
        $this->setJoin('roles as r', 'r.rolid', '=', 'usuarioRol.rolid');
        $this->setWhere('usuarioid', '=', $value);
        $this->setParents('usuarioid', 'parent', true);
        $this->setLayout('krud::layout');
    }
}
