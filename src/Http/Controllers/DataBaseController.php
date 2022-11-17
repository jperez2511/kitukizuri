<?php

namespace Icebearsoft\Kitukizuri\Http\Controllers;

use App\Http\Controllers\Controller;
use Icebearsoft\Kitukizuri\Models\Tenants;

class DataBaseController extends Controller
{
    public function index() 
    {
        if($tenants == true) {
            $tenantsConnections = Tenants::all();
        }

        $connections = config('database.connections');

        return view('kitukizuri::database', [
            'connections' => $connections,
            'tenants'     => $tenantsConnections ?? null,
            'layout'      => 'krud::layout',
        ]);
    }
}