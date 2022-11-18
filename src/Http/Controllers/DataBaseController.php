<?php

namespace Icebearsoft\Kitukizuri\Http\Controllers;

use App\Http\Controllers\Controller;
use Icebearsoft\Kitukizuri\Models\Tenants;

class DataBaseController extends Controller
{
    public function index() 
    {
        $tenants = config('kitukizuri.multiTenants');
        $colors = [
            'mysql' => 'primary',
            'sqlite' => 'secondary',
            'pgsql' => 'info',
            'mongo' => 'success',
            'sqlsrv' => 'secondary'
        ];

        if($tenants == true) {
            $tenantsConnections = Tenants::all();
        }

        $connections = config('database.connections');

        return view('kitukizuri::database', [
            'connections' => $connections,
            'tenants'     => $tenantsConnections ?? null,
            'layout'      => 'krud::layout',
            'titulo'      => 'Gestión de base de datos',
            'kmenu'       => true,
            'colors'      => $colors
        ]);
    }
}