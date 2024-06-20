<?php

namespace Icebearsoft\Kitukizuri\App\Http\Controllers;

use Crypt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Icebearsoft\Kitukizuri\App\Models\{
    Mysql,
    Mongo,
    Tenants,
    Connection
};

class DataBaseController extends Controller
{

    private $colors = [
        'mysql'   => ['color' => 'primary',   'icono' => 'fa-duotone fa-dolphin'],
        'sqlite'  => ['color' => 'tertiary',  'icono' => 'fa-duotone fa-feather'],
        'pgsql'   => ['color' => 'info',      'icono' => 'fa-duotone fa-elephant'],
        'mongo'   => ['color' => 'success',   'icono' => 'fa-duotone fa-leaf'],
        'mongodb' => ['color' => 'success',   'icono' => 'fa-duotone fa-leaf'],
        'sqlsrv'  => ['color' => 'secondary', 'icono' => 'fa-brands fa-microsoft'],
        'mariadb' => ['color' => 'warning',   'icono' => 'fa-brands fa-mdb'],
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
            'getTableInfo', // 1
            'getTableData', // 2
            'executeQuery', // 3
            'generateReport', // 4
        ];

        if(!$request->has('opcion')){
            abort(404);
        }

        return $this->{$function[(int) $request->opcion-1]}($request);
    }

    private function generateReport($request)
    {
        dd($request->all());
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
        if($request->driver == 'mysql') {
            $results = Mysql::executeQuery($request->input('query'), $request->lang);
        }

        return response()->json(['results' => $results]);
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

        if($request->drv == 'mongo' || $request->drv == 'mongodb') {
            $tables = Mongo::getTables($database);
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

        if($request->driver == 'mongo' || $request->driver == 'mongodb') {
            $getAllData = Mongo::getData($database, $tableName);
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

        $tableInformation = [];
        $tableColumns     = [];
        $tableName        = $request->table;

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

        $connection       = config('database.connections.'.$connectionName);
        $statusConnection = Connection::setConnection($connection);

        $viewData = [
            'layout' => 'krud::layout',
            'dash'   => true,
            'kmenu'  => true,
        ];

        if($statusConnection['status'] == true) {
            $params = [
                'driver'   => $connection['driver'],
                'database' => $connection['database'],
                'titulo'   => 'Gestor de base de datos',
                'colors'   => $this->colors,
            ];

            $viewData = array_merge($viewData, $params);
            $viewName = 'kitukizuri::database.info';

        } else {

            $params = [
                'titulo' => 'Error de conexión',
                'msg'    => $statusConnection['msg']
            ];

            $viewData = array_merge($viewData, $params);
            $viewName = 'kitukizuri::database.error';
        }

        return view($viewName, $viewData);

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

        if($driver == 'mysql') {
            $connection = [
                'driver'   => $driver,
                'database' => $connectionData->db,
                'host'     => $connectionData->db_host,
                'username' => $connectionData->db_username,
                'password' => $connectionData->db_password,
            ];
        } else if($driver == 'mongo') {
            $connection = [
                'driver'   => $driver,
                'database' => $connectionData->mongo_db,
                'host'     => $connectionData->mongo_db_host,
                'username' => $connectionData->mongo_db_username,
                'password' => $connectionData->mongo_db_password,
            ];
        }

        Connection::setConnection($connection);

        return view('kitukizuri::database.info', [
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