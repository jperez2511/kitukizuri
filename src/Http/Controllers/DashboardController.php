<?php

namespace Icebearsoft\Kitukizuri\Http\Controllers;

use App\Http\Controllers\Controller;

//Models
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
        return view('kitukizuri.dashboard', [
            'layout' => 'krud.layout',
            'titulo' => 'Dashboard'
        ]);
    }
}
