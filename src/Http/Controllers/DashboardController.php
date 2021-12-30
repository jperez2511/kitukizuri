<?php

namespace Icebearsoft\Kitukizuri\Http\Controllers;

use App\Http\Controllers\Controller;

//Models
use Icebearsoft\Kitukizuri\Models\Rol;
use Icebearsoft\Kitukizuri\Models\Modulo;
use Icebearsoft\Kitukizuri\Models\Usuario;
use Icebearsoft\Kitukizuri\Models\Empresa;

class DashboardController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index() 
    {
        // Obteniendo la cantidad de usuarios
        $roles    = Rol::count();
        $modulos  = Modulo::count();
        $usuarios = Usuario::count();
        $empresas = Empresa::count();

        return view('kitukizuri::dashboard', [
            'layout'   => 'krud::layout',
            'titulo'   => 'Dashboard',
            'roles'    => $roles,
            'modulos'  => $modulos,
            'usuarios' => $usuarios,
            'empresas' => $empresas,
            'dash'     => true,
            'kmenu'    => true,
        ]);
    }
}
