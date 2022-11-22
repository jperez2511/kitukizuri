<?php

namespace Icebearsoft\Kitukizuri\Http\Controllers;

use Crypt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Icebearsoft\Kitukizuri\Models\Mysql;
use Icebearsoft\Kitukizuri\Models\Tenants;
use Icebearsoft\Kitukizuri\Models\Connection;

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

    public function store(Request $request)
    {
        $function = [
            'getTableData'
        ];

        if(!$request->has('option') || !is_int($request->option)){
            abort('404');
        }

        return $this->{$function[$request->option -1]}();
    }

    private function getTableData($request)
    {

    }

    private function viewConnection($request)
    {
        try {
            $connectionName = Crypt::decrypt($request->c);
        } catch (Exception $e) {
            dd($e);
        }   

        $connection = config('database.connections.'.$connectionName);

        Connection::setConnection($connection);

        if($connection['driver'] == 'mysql') {
            $tables = Mysql::getTables($connection['database']);
        }

        return view('kitukizuri::database.info', [
            'tables' => $tables,
            'layout' => 'krud::layout',
            'titulo' => 'Gestior de base de datos',
            'dash'   => true,
            'kmenu'  => true,
        ]);

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

        if($driver == 'mysql'){
            $connection = [
                'driver'   => $driver,
                'database' => $connectionData->db,
                'host'     => $connectionData->db_host,
                'username' => $connectionData->db_username,
                'password' => $connectionData->db_password,
            ];
        }


        Connection::setConnection($connection);

        $tables = Mysql::getTables($connectionData->db);
        
        return view('kitukizuri::database.info', [
            'tables' => $tables,
            'layout' => 'krud::layout',
            'titulo' => 'Gestior de base de datos',
            'dash'   => true,
            'kmenu'  => true,
        ]);
    }

    private function getConnections()
    {
        $tenants = config('kitukizuri.multiTenants');
        $colors = [
            'mysql'  => 'primary',
            'sqlite' => 'secondary',
            'pgsql'  => 'info',
            'mongo'  => 'success',
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