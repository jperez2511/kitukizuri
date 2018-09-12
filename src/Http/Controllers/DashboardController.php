<?php

namespace Icebearsoft\Kitukizuri\Http\Controllers;

use App\Http\Controllers\Controller;

//Models
use Icebearsoft\Kitukizuri\Models\Empresa;

class DashboardController extends Controller
{
    public function index() {
        return view('kitukizuri.dashboard', []);
    }
}
