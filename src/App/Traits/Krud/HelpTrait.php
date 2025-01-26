<?php

namespace Icebearsoft\Kitukizuri\App\Traits\Krud;

trait HelpTrait
{
    protected $errors    = [];
    protected $typeError = [
        'setModelo',            // 0
        'setCampo',             // 1
        'badType',              // 2
        'badOptionsView',       // 3
        'badTypeButton',        // 4
        'badView',              // 5
        'badCalendarView',      // 6
        'typeCombo',            // 7
        'typeCollect',          // 8
        'filepath',             // 9
        'enum',                 // 10
        'value',                // 11
        'badJoinOperator',      // 12
        'badLeftJoinOperator',  // 13
        'badWhereOperator',     // 14
        'badOrWhereOperator',   // 15
        'badColumnDefinition',  // 16
        'needRealField',        // 17
        'needColumnParent',     // 18
        'badDependencies'       // 19  
    ];

    protected $auxInstance = [];
    
    /**
     * allowed
     * verifica los parametros permitidos segun la lista enviada
     *
     * @param  mixed $params
     * @param  mixed $allowed
     * @param  mixed $badType
     * @return void
     */
    protected function allowed($params, $allowed, $badType)
    {
        if(is_array($params)) {
            if(empty($params)) {
                return $this->errors = ['tipo' => $badType, 'bad' => 'Array Vacío', 'permitidos' => $allowed];
            }
            foreach ($params as $key => $val) { //Validamos que todas las variables del array son permitidas.
                if (!in_array($key, $allowed)) {
                    $this->errors = ['tipo' => $badType, 'bad' => $key, 'permitidos' => $allowed];
                    break;
                }
            }
        } else if(is_string($params)) {
            if (!in_array($params, $allowed)) {
                $this->errors = ['tipo' => $badType, 'bad' => $params, 'permitidos' => $allowed];
            }
        }
    }
    
    /**
     * showErrors
     *
     * @param  mixed $error
     * @return void
     */
    protected function showErrors($error)
    {
        return view('krud::training', $error);
    }
    
    /**
     * executeDynamicClassMethod
     *
     * @param  mixed $class
     * @return void
     */
    protected function executeDynamicClassMethod($class)
    {
        $methods  = get_class_methods($class);
        $instance = app($class);

        $childMethods = array_diff(
            $methods,
            get_class_methods(self::class)   // Métodos de la clase padre
        );

        foreach ($childMethods as $method) {
            $this->auxInstances[] = new $instance;
        }
    }
}