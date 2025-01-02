<?php

namespace Icebearsoft\Kitukizuri\App\Traits\Krud;

trait FieldTrait
{
    protected $parents      = [];
    protected $fieldOptions = [
        'campo',         // Campo de la base de datos
        'campoReal',     // Campo real de la base de datos donde se almacenará la DATA
        'column',        // Para el tipo combobox permite seleccionar las columnas a utilizar
        'columnClass',   // clase para columnas en html (bootstrap)
        'collect',       // Colección de datos para el campo combobox
        'columnParent',  // Aplica cuando es un select o un select 2 multiple
        'dependencies',  // Dependencias de un campo
        'edit',          // Valor boolean, muestra o no el input en el edit
        'enumArray',     // array de datos para el tipo de dato enum
        'field',         // Alias de campo
        'filepath',      // dirección donde se almacenaran los archivos
        'format',        // establece el formato para diferentes tipos de campos
        'htmlType',      // establece el tipo de dato a utilizar en un input siempre que aplique
        'htmlAttr',      // Agrega atributos HTML a los campos definidos para editar
        'inputClass',    // añade clases CSS a campo a agregar.
        'inputId',       // Identificador único para el campo
        'multiple',      // Aplica cuando es un select o un select 2
        'nombre',        // es el label del campo que queremos que se muestre en la pantalla
        'name',          // Alias de nombre
        'realField',     // Alias de campo real
        'show',          // visibilidad del campo en la tabla de la vista index
        'tipo',          // Define el tipo de campo a utilizar
        'type',          // Alias de tipo
        'target',        // Para los campos URL establece el target
        'unique',        // valida que el valor ingresado se único
        'value',         // Valor definido o predeterminado.
        'validation',    // Valida los campos según la nomenclatura de laravel,
    ];
    protected $fieldTypes   = [
        'bool',          // Muestra un checkbox en el edit y un si o no en el index
        'combobox',      // Muestra un select simple
        'date',          // Input con formato tipo fecha
        'datetime',      // Input en formato fecha y hora
        'enum',          // Select con valores determinados
        'file',          // Guarda un archivo en una ubicación definida
        'file64',        // Guarda un archivo codificado en base64
        'h1',            // Muestra un h1 en la vista edit
        'h2',            // Muestra un h2 en la vista edit
        'h3',            // Muestra un h3 en la vista edit
        'h4',            // Muestra un h4 en la vista edit
        'hidden',        // Muestra un campo hidden en el formulario edit.
        'icono',         // Muestra un campo para seleccionar un icono
        'image',         // Guarda una imagen en formato Base64
        'numeric',       // Muestra un campo de tipo number en HTML y le da formato en el index
        'password',      // Muestra dos campos contraseña y confirmar contraseña
        'select2',
        'string',        // Tipo por defecto muestra un input tipo text
        'strong',        // Muestra un strong en la vista edit
        'text',          // La misma definición de string
        'textarea',      // Muestra un campo textArea en el formulario edit
        'table',         // Muestra una tabla con filas y columnas para que los usuarios ingresen información,
        'url',           // Establece  una url con parámetros personalizados.
    ];
    protected $components   = [
        'combobox' => 'select',
        'enum'     => 'select',
        'select2'  => 'select2',
        'password' => 'password',
        'textarea' => 'textarea',
        'table'    => 'table',
        'h1'       => 'title',
        'h2'       => 'title',
        'h3'       => 'title',
        'h4'       => 'title',
        'strong'   => 'title',
    ];
    protected $htmlTypes    = [
        'string'   => 'text',
        'bool'     => 'checkbox',
        'date'     => 'date',
        'numeric'  => 'number',
        'image'    => 'file',
        'file'     => 'file',
        'file64'   => 'file',
        'hidden'   => 'hidden',
        'password' => 'password'
    ];

    /**
     * setCampo
     * Define las propiedades de un campo a utilizar en el controller,
     * Vista y posteriormente almacenado desde el modelo.
     *
     * @param  mixed $params
     *
     * @return void
     */
    protected function setCampo($params)
    {
        if(!is_array($params)) {
            return $this->errors = ['tipo' => $this->typeError[1]];
        }

        // validando datos permitidos
        $this->allowed($params, $this->fieldOptions, $this->typeError[2]);

        $params['tipo']      = $params['tipo'] ?? $params['type'] ?? 'string';
        $params['campo']     = $params['campo'] ?? $params['field'] ?? null;

        $excludeField   = ['h1', 'h2', 'h3', 'h4', 'strong'];


        if (!in_array($params['tipo'], $excludeField) && (!array_key_exists('campo', $params) && !array_key_exists('field', $params))) {
            return $this->errors = ['tipo' => $this->typeError[1]];
        }

         // capturando el nombre real del campo
         if($params['campo'] instanceof Expression) {
            if(!array_key_exists('campoReal', $params) && !array_key_exists('realField', $params)) {
                return $this->errors = ['tipo' => $this->typeError[17]];
            }
            $params['campoReal'] = $params['campoReal'] ?? $params['realField'];
        } else if (!strpos($params['campo'], ')')) {
            $arr = explode('.', $params['campo']);
            if (count($arr)>=2) {
                $params['campoReal'] = end($arr);
            }
        }

        $params['inputName'] = $params['campoReal'] ?? $params['campo'];
        $params['inputId']   =  $params['inputId'] ?? $params['inputName'];

        // validando si existen dependencias
        if(!empty($params['dependencies'])) {
            $typeArray = \typeArray($params['dependencies']);
            if($typeArray === false) {
                return $this->errors = ['tipo' => $this->typeError[16]];
            } else {
                $params['dependencies'] = \normalizeArray($params['dependencies'], ($params['inputId'] ?? $params['inputName']));
            }

        } else {
            $params['dependencies'] = null;
        }

        $params['nombre']      = $params['nombre'] ?? $params['name'] ?? str_replace('_', ' ', ucfirst($params['campo']));
        $params['edit']        = $params['edit'] ?? true;
        $params['show']        = $params['show'] ?? true;
        $params['decimales']   = $params['decimales'] ?? 0;
        $params['format']      = $params['format'] ?? '';
        $params['unique']      = $params['unique'] ?? false;
        $params['input']       = $this->components[$params['tipo']] ?? 'input';
        $params['component']   = "krud-".$params['input'];
        $params['columnClass'] = $params['columnClass'] ?? 'col-md-6';
        $params['inputClass']  = $params['inputClass'] ?? null ;
        $params['editClass']   = $params['edit'] == false ? 'hide' : '';
        $params['collect']     = $params['collect'] ?? null;
        $params['value']       = $params['value'] ?? null;
        $params['validation']  = $params['validation'] ?? null;

        if(empty($params['htmlType'])){
            $params['htmlType'] = $this->htmlTypes[$params['tipo']] ?? 'text';
        }

        if(!empty($params['htmlAttr'])){
            if(is_array($params['htmlAttr'])){
                $params['htmlAttr'] = collect($params['htmlAttr']);
            } else if(is_string($params['htmlAttr'])) {
                $params['htmlAttr'] = collect([$params['htmlAttr'] => true]);
            }

            if($params['htmlAttr']->has('multiple')){
                $params['inputName'] = $params['inputName'].'[]';
            }
        } else {
            $params['htmlAttr'] = null;
        }

        // validando tipo y longitud de columns
        if ($params['tipo'] == 'combobox' || $params['tipo'] == 'select2') {

            if(!empty($params['column'])) {
                $tipo = \is_array($params['column']);
                $longitud = count($params['column']) == 2;

                if(!$tipo || !$longitud) {
                    return $this->errors = ['tipo' => $this->typeError[1]];
                }
            }

            // valiando si es un select multiple con diferente ubicación para 
            if(
                !empty($params['htmlAttr']) && 
                $params['htmlAttr']->has('multiple') && 
                $params['htmlAttr']['multiple'] == true && 
                ($params['campo'] instanceof Expression) == false &&
                strrpos($params['campo'], '.') != false
            ) {
                $locationTable =  explode('.', $params['campo'])[0];
                $localTable = $this->model->getTable();
                if($locationTable != $localTable && empty($params['columnParent'])) {
                    return $this->errors = ['tipo' => $this->typeError[17]];
                } else if($locationTable != $localTable) {
                    $params['format'] = 'table';
                } else if($locationTable == $localTable) {
                    $params['format'] = 'json';
                }

                $params['format'] = 'table';
            } 
        }

        if (!in_array($params['tipo'], $this->fieldTypes)) {
            $this->errors = ['tipo' => $this->typeError[2], 'bad' => $params['tipo'], 'permitidos' => $this->fieldTypes];
        } else {
            $tipo = $params['tipo'];
            if ($tipo == 'datetime' || $tipo == 'date') {
                $this->setTemplate(['datetimepicker']);
            } else if ($tipo == 'icono') {
                $this->setTemplate(['iconpicker']);
            } else if($tipo == 'combobox' || $tipo == 'select2') {
                if (empty($params['collect'])) {
                    $this->errors = ['tipo' => $this->typeError[7]];
                } else {
                    $columns = !empty($params['column']) ? $params['column'] : ['id', 'value'];

                    if($params['collect']->isNotEmpty()){
                        $hasID    = !empty($params['collect']->first()[$columns[0]]);
                        $hasValue = !empty($params['collect']->first()[$columns[1]]);
                        if($hasID && $hasValue){
                            $params['options'] = $params['collect']->map(function($item){
                                return array_values($item->toArray());
                            })->toArray();
                            $params['show'] = false;
                        } else {
                            $this->errors = ['tipo' => $this->typeError[8], 'permitidos' => $columns];
                        }
                    }
                }
            } else if ($tipo == 'file' && empty($params['filepath'])) {
                $this->errors = ['tipo' => $this->typeError[9]];
            } else if ($tipo == 'enum' && count($params['enumarray']) == 0) {
                $this->errors = ['tipo' => $this->typeError[10]];
            } else if ($tipo == 'hidden' && empty($params['value'])) {
                $this->errors = ['tipo' => $this->typeError[11]];
            }
        }
        if(!empty($params['validation'])) {
            $this->setValidationItem($params['inputName'], $params['validation']);
        }
        $this->campos[] = $params;
    }
    
    #[Alias('setCampo')] 
    protected function setField($params)
    {
        $this->setCampo($params);
    }

    #[Alias('setControl')] 
    protected function setControl($params)
    {
        $this->setCampo($params);
    }

    /**
     * setParents
     * Define el valor padre por url para recibirlo un controller hijo
     *
     * @param  mixed $nombre
     * @param  mixed $value
     *
     * @return void
     */
    protected function setParents($nombre, $value, $editable = null)
    {
        $editable = $editable === true;
        $this->parents[] = ['nombre' => $nombre, 'value'=>$value, 'editable' => $editable];
    }
}