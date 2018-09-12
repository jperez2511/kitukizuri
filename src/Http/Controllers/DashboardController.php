<?php

namespace App\Http\Controllers\KituKizuri;

//Models
use Icebearsoft\Kitukizuri\Models\Empresa;

class DashboardController extends Krud
{
    public function index() {
        return view('kitukizuri.dashboard', []);
    }
}
