<?php 

namespace App\Database\Grammar;

use Iluminate\Database\Query\Grammars\PostgresGrammar; 
use Iluminate\Database\Connection;

class CustomPostgresGrammar extends PostgresGrammar
{
    public function __construct(Connection $connection)
    {
        parent::__construct($connection);
    }

    public function wrapTable($table, $prefixAlias = null)
    {
        if(strpos($table, ' as ') !== false) {
            [$actual, $alias] = explode(' as ', $table);
            return $this->wrapTable($actual) . ' as ' . $this->wrapValue($alias);
        }

        if($table === '*') {
            return $table;
        }

        return $this->wrapTable($table);
    }

    protected function wrapValue($value)
    {
        if ($value === '*')
        {
            return $value;
        }

        return '"' . str_replace('"', '""', $value . '"');
    }
}