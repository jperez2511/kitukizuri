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

    private $colors = [
        'mysql'  => ['color' => 'primary',   'icono' => 'fa-duotone fa-dolphin'],
        'sqlite' => ['color' => 'tertiary',  'icono' => 'fa-duotone fa-feather'],
        'pgsql'  => ['color' => 'info',      'icono' => 'fa-duotone fa-elephant'],
        'mongo'  => ['color' => 'success',   'icono' => 'fa-duotone fa-leaf'],
        'sqlsrv' => ['color' => 'secondary', 'icono' => 'fa-brands fa-microsoft'],
    ];

    public function index(Request $request) 
    {
        if($request->has('opcion')) {
            $function = [
                'getTables'
            ];
    
            $result = $this->{$function[(int) $request->opcion-1]}($request);
        } else {
            $result = $this->showView($request);
        }

        return $result;
    }

    public function store(Request $request)
    {
        $function = [
            'getTableInfo',
            'getTableData',
            'executeQuery',
        ];

        if(!$request->has('opcion')){
            abort(404);
        }

        return $this->{$function[(int) $request->opcion-1]}($request);
    }

    private function showView($request) 
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

    private function executeQuery($request)
    {

        dd($request->all());

        if($request->driver == 'mysql') {
            Mysql::excuteQuery($request->query);
        }
    }

    private function getTables($request)
    {
        try {
            $database = Crypt::decrypt($request->db);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        $treeTable = [];

        if($request->drv == 'mysql') {
            $tables = Mysql::getTables($database);
        }

        if(!empty($tables)){
            foreach ($tables as $table) {
                $treeTable[] = [
                    'id' => $table->name,
                    'icon' => 'fa-light fa-table-columns',
                    'text' => $table->name
                ];
            }
        }

        return response()->json($treeTable);
    }

    private function getTableData($request) 
    {
        try {
            $database = Crypt::decrypt($request->database);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        $tableName = $request->table;
        
        if($request->driver == 'mysql') {
            $getAllData = Mysql::getData($database, $tableName);
        }

        return response()->json(['data' => $getAllData]);
        
    }

    private function getTableInfo($request)
    {
        try {
            $database = Crypt::decrypt($request->database);
        } catch (Exception $e) {
            dd($e);
        }

        $tableName = $request->table;

        if($request->driver == 'mysql') {
            $tableInformation = Mysql::getTableProperties($database, $tableName);
            $tableColumns     = Mysql::getColumns($database, $tableName);
        }

        return response()->json([
            'information' => $tableInformation,
            'columns'     => $tableColumns
        ]);

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
            'tables'   => $tables,
            'driver'   => $connection['driver'],
            'database' => $connection['database'],
            'layout'   => 'krud::layout',
            'titulo'   => 'Gestor de base de datos',
            'dash'     => true,
            'kmenu'    => true,
            'colors'   => $this->colors,
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
            'tables'   => $tables,
            'driver'   => $connection['driver'],
            'database' => $connection['database'],
            'layout'   => 'krud::layout',
            'titulo'   => 'Gestor de base de datos',
            'dash'     => true,
            'kmenu'    => true,
            'colors'   => $this->colors,
        ]);
    }

    private function getConnections()
    {
        $tenants = config('kitukizuri.multiTenants');

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
            'colors'      => $this->colors
        ]);
    }
}