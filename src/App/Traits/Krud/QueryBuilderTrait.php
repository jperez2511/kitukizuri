<?php

namespace Icebearsoft\Kitukizuri\App\Traits\Krud;

trait QueryBuilderTrait
{
    protected $model        = null;
    protected $queryBuilder = null;
    protected $tableName    = null;
    protected $keyName      = null;
    protected $externalData = [];
 
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
     * setExternalData
     * Permite hacer un merge entre datos externos a la consulta actual
     *
     * @param  mixed $relation
     * @param  mixed $colName
     * @param  mixed $data
     * @return void
     */
    protected function setExternalData($relation, $colName, $data){
        $this->externalData[] = [
            'relation' => $relation,
            'colName'  => $colName,
            'data'     => $data
        ];
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
    
    /**
     * setWhereIn
     * Establece un where basado en una lista de valores o una función
     *
     * @param  mixed $column
     * @param  mixed $data
     * @param  mixed $boolean
     * @param  mixed $not
     * @return void
     */
    protected function setWhereIn($column, $data, $boolean = 'and', $not = false)
    {
        $this->queryBuilder = $this->queryBuilder->whereIn($column, $data, $boolean, $not);
    }

    /**
     * setOrWhere
     *
     * @param  mixed $column
     * @param  mixed $op
     * @param  mixed $column2
     *
     * @return void
     */
    protected function setOrWhere($column, $op = null, $column2 = null)
    {
        if (func_num_args() === 2) {
            $column2 = $op;
            $op = '=';
        }

        $this->allowed($op, $this->allowedOperator, $this->typeError[12]);

        $this->queryBuilder = $this->queryBuilder->orWhere($column, $op, $column2);
    }

        
    /**
     * setOrderBy
     * Define el orden para obtener la data que se mostrara en la vista index.
     *
     * @param  mixed $column
     * @param  mixed $orientation
     * @return void
     */
    protected function setOrderBy($column, $orientation = 'asc')
    {
        $this->queryBuilder = $this->queryBuilder->orderBy($column, $orientation);
    }

    /**
     * setGroupBy
     * Define como agrupar los elementos de la consulta
     *
     * @param  mixed $column
     *
     * @return void
     */
    protected function setGroupBy($column)
    {
        $this->queryBuilder = $this->queryBuilder->groupBy($column);   
    }
}