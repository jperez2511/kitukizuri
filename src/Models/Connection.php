<?php

namespace App\Models;

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
        Config::set("database.connections.".$connection['driver'], $connection);
    }
}
