<?php

namespace Icebearsoft\Kitukizuri\App\Traits\Krud;

trait QueryBuilderTrait
{
    protected $model           = null;
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
        $this->model = $model;
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

        $this->model = $this->model->join($tabla, $v1, $operador, $v2);
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
    public function setLeftJoin($tabla, $v1, $operador = null, $v2 = null)
    {
        if (func_num_args() === 3) {
            $v2 = $operador;
            $operador = '=';
        }

        $this->allowed($operador, $this->allowedOperator, $this->typeError[12]);

        $this->model = $this->model->lefJoin($tabla, $v1, $operador, $v2);
    }
}