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
            $kitukizuri = 'kitukizuri_prev::dashboard';
            $krud       = 'krud_prev::layout';
        } else {
            $kitukizuri = 'kitukizuri::dashboard.index';
            $krud       = 'krud::layout';
        }


        return view($kitukizuri, [
            'layout'   => $krud,
            'titulo'   => __('Control Panel'),
            'dash'     => true,
            'kmenu'    => true,
        ]);
    }
}
