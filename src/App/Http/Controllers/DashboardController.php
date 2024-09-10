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

        if(!empty(config('kitukizuri.prevUi')) && config('kitukizuri.prevUi') == true) {
            $kitukizuri = 'kitukizuri_prev';
            $krud       = 'krud_prev';
        } else {
            $kitukizuri = 'kitukizuri';
            $krud       = 'krud';
        }


        return view($kitukizuri.'::dashboard.index', [
            'layout'   => $krud.'::layout',
            'titulo'   => __('Control Panel'),
            'dash'     => true,
            'kmenu'    => true,
        ]);
    }
}
