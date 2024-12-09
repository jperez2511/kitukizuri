<?php

namespace Icebearsoft\Kitukizuri\App\Models;

use DB;

class SelectValues 
{
    public static function exists($idParent, $table, $column)
    {
        return DB::table($table)->where($column, $idParent)->exists();
    }

    public static function destroy($idParent, $table, $column)
    {
        return DB::table($table)->where($column, $idParent)->delete();
    }

    public static function save($idParent, $table, $columnParent, $columnLocal, $values) 
    {
        //Generate data structure for insert many
        $data = array_map(function($value) use ($idParent, $columnLocal, $columnParent) {
            return [
                $columnParent => $idParent,
                $columnLocal => $value
            ];
        }, $values);

        try {
            DB::table($table)->insert($data);
        } catch (\Exception $e) {
            Log::error($e);
            dd($e);
        }
    }
}
