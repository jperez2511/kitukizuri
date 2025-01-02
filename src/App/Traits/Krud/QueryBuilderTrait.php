<?php

namespace Icebearsoft\Kitukizuri\App\Traits\Krud;

use DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

trait QueryBuilderTrait
{
    protected $model        = null;
    protected $queryBuilder = null;
    protected $tableName    = null;
    protected $keyName      = null;
    protected $searchInED   = [];
    protected $externalData = [];
    protected $campos       = [];

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
        $this->queryBuilder = $model->newQuery();
        $this->tableName    = $model->getTable();
        $this->keyName      = $model->getKeyName();
    }

    /**
     * getKeyName
     * Retorna la llave primaria de la tabla
     *
     * @return void
     */
    protected function getKeyName() {
        return $this->keyName;
    }
    
    /**
     * __call
     * Permite llamar a métodos del modelo y del queryBuilder
     *
     * @param  mixed $method
     * @param  mixed $args
     * @return void
     */
    public function __call($method, $args)
    {
        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], $args);
        }

        // Luego, delega al queryBuilder si el método existe allí
        if (method_exists($this->queryBuilder, $method)) {
            $this->queryBuilder = call_user_func_array([$this->queryBuilder, $method], $args);
            return $this; // Mantener el encadenamiento de métodos
        }

       // Luego, intenta como un método dinámico de Eloquent en el modelo
        try {
            $result = call_user_func_array([$this->model, $method], $args);
            if ($result instanceof \Illuminate\Database\Eloquent\Builder) {
                $this->queryBuilder = $this->queryBuilder->{$method}(...$args);
            }
            return $this;
        } catch (\BadMethodCallException $e) {
            // Si el método no existe en ninguno, lanza una excepción
            throw new \BadMethodCallException("Método {$method} no se encontró en la clase " . get_class($this));
        }
    }
    
    /**
     * searchBy
     * Le indica al query builder que campos se utilizaran para buscar la data
     *
     * @param  mixed $column
     * @return void
     */
    protected function searchBy($column)
    {
        if(is_array($column)) {

            // validando que no sea un array asosiativo
            if(array_values($column) !== $column){
                $this->searchBy = $column;
            } else {
                // retornando mensaje de error
                return $this->allowed($column, ['Array Simple'], ['Array asociativo no permitido']);
            }
        } else {
            $this->searchBy = [$column];
        }
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
    protected function setExternalData($relation, $colName, $data)
    {
        $this->externalData[] = [
            'relation' => $relation,
            'colName'  => $colName,
            'data'     => $data
        ];
    }

    protected function searchInExternalData($colName, $value)
    {
        $this->searchInED[] = ['colName' => $colName, 'value' => $value];
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

     /**
     * getData
     * Obtiene la data que se mostrara en la tabla consolidando joins, wheres, etc.
     *
     * @return void
     */
    protected function getData($limit = null, $offset = null)
    {
        // lista de campos a mostrar en la tabla
        $campos = $this->getSelectShow();

        //consultando al modelo los campos a mostrar en la tabla
        $data = $this->queryBuilder->select($this->getSelect($campos));
        $drivers = ['mysql', 'sqlite'];

        if (in_array($data->getConnection()->getDriverName(), $drivers)) {
            // Obteniendo el id de la tabla
            $data->addSelect($this->tableName.'.'.$this->keyName);
            // obteniendo la cantidad total de elementos en la tabla
            $dataQuery = clone $data;
            $count     = DB::table(DB::raw("({$dataQuery->toRawSql()}) as subquery"))->count();
        } else {
            $count = $data->count();
        }

        // validando si existen data externa para adjuntar a la información obtenida en base de datos
        if(!empty($this->externalData)) {
            $data = $data->get();
            foreach ($data as $value) {
                foreach($this->externalData as $extData) {
                    $relation = $extData['relation'];
                    $tmp      = $extData['data']->firstWhere($relation, $value->{$relation});
                    if(!empty($value->{$extData['colName']})) {
                        $value->{$extData['colName']} = $tmp[$extData['colName']] ?? '';
                    }
                }
            }
        }

        // validnado si existe algun elemento a filtrar que sea parte de external data
        if(!empty($this->searchInED)) { 
            $data  = $this->filterExternalData($data, $offset, $limit);
            $count = $data['count'];
            $data  = $data['data'];
        } else {
            // validando si hay un offset a utilizar
            $data = $data->skip($offset);
            // validando si existe un limite para obtenr los datos
            $data = $data->take($limit);
            $data = $data instanceof Collection ? $data : $data->get();
        }

        return [$data, $count];
    }

    /**
     * getSelectShow
     * valida y clasifica los campos a mostrar y los que no.
     *
     * @return void
     */
    protected function getSelectShow()
    {
        if(!empty($this->searchInED)) {
            return $this->campos = array_values(array_filter($this->campos, fn($campo) => $campo['show'] || $campo['show'] === 'soft'));
        }

        return array_values(array_filter($this->campos, fn($campo) => $campo['show'] === true));
    }
        
    /**
     * getForeignKeys
     *
     * @param  mixed $model
     * @return void
     */
    protected function getForeignKeys($model)
    {
        $table = $model->getTable(); // Obtener la tabla asociada al modelo
        $connection = Schema::getConnection()->getDoctrineSchemaManager();
        $connection->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

        // Obtener claves foráneas de la tabla
        $foreignKeys = $connection->listTableForeignKeys($table);

        $relations = [];
        foreach ($foreignKeys as $foreignKey) {
            $relations[] = [
                'table' => $foreignKey->getForeignTableName(),
                'foreign_key' => $foreignKey->getLocalColumns()[0],
            ];
        }

        return $relations;
    }


    /**
     * filterExternalData
     *
     * @param  mixed $data
     * @param  mixed $offset
     * @param  mixed $limit
     * @return void
     */
    private function filterExternalData($data, $offset, $limit)
    {
        $control = $this->searchInED[0];
        $data    = $data->filter(function($item) use ($control) {
            return $item->{$control['colName']} == $control['value']; 
        })->values();

        foreach($data as &$value) {
            unset($value->{$control['colName']});
        }

        $count = $data->count();
        $data  = $data->skip($offset);
        $data  = $data->take($limit);

        return [
            'data'  => $data,
            'count' => $count,
        ];
    }
}
