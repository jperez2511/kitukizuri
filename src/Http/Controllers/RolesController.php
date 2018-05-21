<?php

namespace Icebearsoft\Kitukizuri\Controllers;

use Krud;
use App\Models\Rol;
use Illuminate\Support\Facades\Auth;

class RolesController extends Krud
{
    public function __construct()
    {
        $this->setModel(new Rol());
        $this->setTitulo('Roles');
        $this->setCampo(['nombre'=>'Nombre', 'campo'=>'nombre']);
        $this->setCampo(['nombre'=>'Descripcion', 'campo'=>'descripcion']);
        $this->setBoton(['nombre'=>'Asignar Permisos', 'url'=>'/admin-global/rolpermisos?id={id}', 'class'=>'warning', 'icon'=>'lock-outline']);
        $this->middleware(function ($request, $next) {
            $empresaid = Auth::user()->empresaid;
            $this->setCampo(['nombre'=>'empresaid', 'campo'=>'empresaid', 'tipo' => 'hidden', 'value'=>$empresaid, 'show'=>false]);
            $this->setWhere('empresaid', '=', $empresaid);
            return $next($request);
        });
    }
}
