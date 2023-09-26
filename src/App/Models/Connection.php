<?php

namespace Icebearsoft\Kitukizuri\App\Models;

use DB;
use Config;
use Session;

class Connection
{
    /**
     * setConnection
     *
     * @param  mixed $connection
     * @return void
     */
    public static function setConnection($connection)
    {
        $stateConnection = [
            'status' => false,
            'msg' => null
        ];

        Config::set("database.connections.".$connection['driver'], $connection);

        try{
            $conexion = DB::connection($connection['driver'])->getPDO();
            $stateConnection['status'] = true;
        } catch (\Exception $e) {
           $stateConnection['msg'] = 'No se pudo conectar a la base de datos: '. $e->getMessage();
        }

        return $stateConnection;
    }
}
