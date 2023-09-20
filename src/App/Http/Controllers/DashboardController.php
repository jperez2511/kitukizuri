<?php

namespace Icebearsoft\Kitukizuri\App\Http\Controllers;

use App\Http\Controllers\Controller;

//Models
use Icebearsoft\Kitukizuri\App\Models\{
    Rol,
    Modulo,
    Usuario,
    Empresa
};

class DashboardController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index() 
    {
        return view('kitukizuri::dashboard', [
            'layout'   => 'krud::layout',
            'titulo'   => __('Panel de administración'),
            'dash'     => true,
            'kmenu'    => true,
        ]);
    }
}
