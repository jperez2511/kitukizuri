<?php

namespace Icebearsoft\Kitukizuri\Http\Controllers;

use Crypt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Icebearsoft\Kitukizuri\Models\Tenants;

class DataBaseController extends Controller
{
    public function index(Request $request) 
    {
        if($request->has('c')) {
            $function = 'viewConnection';
        } else if($request->has('ct') && $request->has('d')) {
            $function = 'viewTenantConnection';
        } else {
            $function = 'getConnections';
        }

        return $this->{$function}($request);
    }

    private function viewConnection($request)
    {
        try {
            $connectionName = Crypt::decrypt($request->c);
        } catch (Exception $e) {
            dd($e);
        }   

        dd($connection);
    }

    private function viewTenantConnection($request)
    {
        try {
            $tenantID = Crypt::decrypt($request->ct);
            $driver = Crypt::decrypt($request->d);
        } catch (Exception $e) {
            dd($e);
        }

        $connectionData = Tenants::find($tenantID);

        dd($connectionData);
    }

    private function getConnections()
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