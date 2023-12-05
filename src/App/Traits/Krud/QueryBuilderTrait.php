<?php

namespace Icebearsoft\Kitukizuri\App\Traits\Krud;

trait QueryBuilderTrait
{
    protected $model        = null;
    protected $queryBuilder = null;
    protected $tableName    = null;
    protected $keyName      = null;
 
    protected $allowedOperator = [
        '=', '<', '>', '<=', '>=', '<>', '!=', '<=>',
        'like', 'like binary', 'not like', 'ilike',
        '&', '|', '^', '<<', '>>', '&~',
        'rlike', 'not rlike', 'regexp', 'not regexp',
        '~', '~*', '!~', '!~*', 'similar to',
        'not similar to', 'not ilike', '~~*', '!~~*',
    ];

    /**
     * setModel
     * Define el modelo donde se obtendrán y almacenaran los datos
     *
     * @param  mixed $model
     *
     * @return void
     */
    protected function setModel($model)
    {
        $this->model        = $model;
        $this->queryBuilder = $model;
        $this->tableName    = $model->getTable();
        $this->keyName      = $model->getKeyName();
    }

    /**
     * getSql
     * Genera la consulta en SQL
     *
     * @return void
     */
    protected function getSql()
    {
        $sql = $this->queryBuilder->toSql();
        $bindings = $this->queryBuilder->getBindings();
        foreach ($bindings as $binding) {
            $value = is_numeric($binding) ? $binding : "'".addslashes($binding)."'";
            $sql = preg_replace('/\?/', $value, $sql, 1);
        }

        dd($sql);
    }

    /**
     * setJoin
     * Define las relaciones a utilizar entre las tablas y que estarán disponibles para mostrar
     * en la tabla de la vista index.
     *
     * @param  mixed $tabla
     * @param  mixed $v1
     * @param  mixed $operador
     * @param  mixed $v2
     *
     * @return void
     */
    protected function setJoin($tabla, $v1, $operador = null, $v2 = null)
    {
        if (func_num_args() === 3) {
            $v2 = $operador;
            $operador = '=';
        }

        $this->allowed($operador, $this->allowedOperator, $this->typeError[12]);

        $this->queryBuilder = $this->queryBuilder->join($tabla, $v1, $operador, $v2);
    }

    /**
     * setLeftJoin
     *
     * @param  mixed $tabla
     * @param  mixed $v1
     * @param  mixed $operador
     * @param  mixed $v2
     *
     * @return void
     */
    protected function setLeftJoin($tabla, $v1, $operador = null, $v2 = null)
    {
        if (func_num_args() === 3) {
            $v2 = $operador;
            $operador = '=';
        }

        $this->allowed($operador, $this->allowedOperator, $this->typeError[12]);

        $this->queryBuilder = $this->queryBuilder->leftJoin($tabla, $v1, $operador, $v2);
    }

    /**
     * setWhere
     * Define las condiciones al momento de obtener la data y mostrarla en la vista index.
     *
     * @param  mixed $column
     * @param  mixed $op
     * @param  mixed $column2
     *
     * @return void
     */
    protected function setWhere($column, $op = null, $column2 = null)
    {

        if(is_callable($column)){
            $this->queryBuilder->where($column);
        } else {
            if (func_num_args() === 2) {
                $column2 = $op;
                $op = '=';
            }

            $this->allowed($op, $this->allowedOperator, $this->typeError[12]);

            $this->queryBuilder = $this->queryBuilder->where($column, $op, $column2);
        }
    }
}