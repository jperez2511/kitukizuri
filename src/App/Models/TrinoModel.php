<?php

namespace Icebearsoft\Kitukizuri\App\Models;

use Icebearsoft\Kitukizuri\App\Database\TrinoConnection;

class Trino
{
    protected $connection;

    public function __construct()
    {
        $this->connection = new TrinoConnection();
    }

    public function select($sql)
    {
        return $this->connection->execute($sql);
    }
}
