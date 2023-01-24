<?php

namespace Icebearsoft\Kitukizuri\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Mysql extends Model
{
    private static $driver = 'mysql';

    public static function getSchemata()
    {
        return DB::connection(self::$driver)->select('SELECT SCHEMA_NAME AS `name` FROM INFORMATION_SCHEMA.SCHEMATA');
    }

    public static function getTables($schema)
    {
        return DB::connection(self::$driver)->select('SELECT table_name as name, table_type as type FROM information_schema.tables WHERE table_schema =\''.$schema.'\'');
    }

    public static function getTablesType($table)
    {
        $result = DB::connection(self::$driver)->select('SELECT table_type as type FROM information_schema.tables WHERE table_name =\''.$table.'\'');
        return $result[0]->type;
    }

    public static function getTableProperties($database, $table)
    {
        return DB::connection(self::$driver)->select('SELECT CCSA.character_set_name as charset, CCSA.collation_name as collation
            FROM information_schema.`TABLES` T, information_schema.`COLLATION_CHARACTER_SET_APPLICABILITY` CCSA
            WHERE CCSA.collation_name = T.table_collation AND T.table_schema = \''.$database.'\' AND T.table_name = \''.$table.'\'');
    }

    public static function getData($database, $table)
    {
        return DB::connection(self::$driver)->select('select * from '.$database.'.'.$table.' limit 50');
    }

    public static function getColumns($database, $table)
    {
        return DB::connection(self::$driver)->select('SELECT COLUMN_NAME as name, COLUMN_TYPE as type, IS_NULLABLE
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE table_schema = \''.$database.'\' AND table_name = \''.$table.'\'');
    }

    public static function getcollation()
    {
        return DB::connection(self::$driver)->select('SELECT COLLATION_NAME as name from information_schema.COLLATIONS');
    }

    public static function getcharset($schema)
    {
        return DB::connection(self::$driver)->select('SELECT DEFAULT_CHARACTER_SET_NAME as charset, DEFAULT_COLLATION_NAME as collation FROM information_schema.SCHEMATA where SCHEMA_NAME =\''.$schema.'\'');
    }

    public static function getCharsets()
    {
        return DB::connection(self::$driver)->select('SHOW CHARACTER SET');
    }

    public static function executeQuery($query, $lang) 
    {
        $results = null;
        
        if($lang == 'sql') {
            $results = DB::connection(self::$driver)->select(DB::raw($query));
        } else if($lang == 'php') {
            $driver = self::$driver;
            $query = self::cleanORM($query);
            $results = eval("return DB::connection('".$driver."')->".$query);
            $results = $results->get()->toArray();
        }

        return $results;
    }

    private static function cleanORM($query) 
    {
        $elements = [
            '<?', 
            'php', 
            '->toArray()',
            '->get()',
            '->first()',
            "\n",
            "\r"

        ];

        foreach ($elements as $value) {
            $query = str_replace($value, '', $query);
        }
        
        return $query;
    }

}