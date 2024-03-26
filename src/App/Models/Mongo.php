<?php

namespace Icebearsoft\Kitukizuri\App\Models;

use DB;
use MongoDB\Laravel\Eloquent\Model;

class Mongo extends Model
{
    private static $driver = 'mongodb';

    public static function getTables($schema)
    {
        $tables      = [];
        $collections = DB::connection(self::$driver)->listCollections();

        foreach ($collections as $collectionInfo) {
            $tables[] = (object) ['name' => $collectionInfo->getName()];
        }

        return $tables;
    }

    public static function getData($database, $tableName)
    {
        $data = DB::connection(self::$driver)->table($tableName)->take(1)->get();
        return $data;
    }
}
