<?php

namespace Icebearsoft\Kitukizuri\Http\Controllers;

//Models
use Icebearsoft\Kitukizuri\Models\Empresa;

class DashboardController extends Krud
{
    public function index() {
        return view('kitukizuri.dashboard', []);
    }
}
