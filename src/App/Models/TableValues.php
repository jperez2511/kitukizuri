<?php

namespace Icebearsoft\Kitukizuri\App\Models;

use DB;

class TableValues 
{
    public static function values($idParent, $columnParent, $table, $column)
    {
        return DB::table($table)
            ->select($column)
            ->where($columnParent, $idParent)
            ->get()
            ->toArray();
    }
}