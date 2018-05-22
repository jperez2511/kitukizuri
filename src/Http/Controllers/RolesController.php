<?php

namespace App\Http\Controllers\KituKizuri;

use Krud;
use Illuminate\Support\Facades\Auth;

//== Models
use Icebearsoft\Kitukizuri\Models\Rol;

class RolesController extends Krud
{
    public function __construct()
    {
        $this->setModel(new Rol());
        $this->setTitulo('Roles');
        $this->setCampo(['nombre'=>'Nombre', 'campo'=>'nombre']);
        $this->setCampo(['nombre'=>'Descripcion', 'campo'=>'descripcion']);
        $this->setBoton(['nombre'=>'Asignar Permisos', 'url'=>'/rolpermisos?id={id}', 'class'=>'warning', 'icon'=>'lock']);
        $this->middleware(function ($request, $next) {
            if (!empty(Auth::user()->empresaid)) {
                $empresaid = Auth::user()->empresaid;
                $this->setCampo(['nombre'=>'empresaid', 'campo'=>'empresaid', 'tipo' => 'hidden', 'value'=>$empresaid, 'show'=>false]);
                $this->setWhere('empresaid', '=', $empresaid);
            }
            return $next($request);
        });
    }
}
