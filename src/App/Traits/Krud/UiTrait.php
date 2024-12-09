<?php

namespace Icebearsoft\Kitukizuri\App\Traits\Krud;

use Illuminate\Database\Query\Expression;

trait UiTrait
{
    protected $layout       = null;
    protected $storeMsg     = null;
    protected $titulo       = null;
    protected $view         = null;
    protected $viewOptions  = [];
    protected $botones      = [];
    protected $botonesDT    = [];
    protected $defaultBtnDT = [];
    protected $validations  = [];
    protected $indexEmbed   = [];
    protected $template     = [
        'datatable',
    ];

    /**
     * getHelp
     * Genera una vista de ayuda para usar el paquete
     *
     * @return void
     */
    protected function help()
    {
        $this->viewHelp = true;
    }

    /**
     * setLayout
     * Define el layout a utilizar en el controller
     *
     * @param  mixed $layout
     *
     * @return void
     */
    protected function setLayout($layout)
    {
        $this->layout = $layout;
    }

    /**
     * getLayout
     * Define el layout predeterminado
     *
     * @return void
     */
    protected function getLayout()
    {
        return $this->layout ?? config('kitukizuri.layout');
    }

    /**
     * getStoreMSG
     *
     * @return void
     */
    protected function getStoreMSG()
    {
        return $this->storeMsg ?? config('kitukizuri.storemsg');
    }

    /**
     * setStoreMSG
     *
     * @return void
     */
    protected function setStoreMSG($msg)
    {
        $this->storeMsg = $msg;
    }

    /**
     * setTemplate
     * Define las librerías a utilizar por ejemplo DataTable, FontAwesome ,etc.
     *
     * @param  mixed $templates
     *
     * @return void
     */
    protected function setTemplate($templates)
    {
        foreach ($templates as $t) {
            $this->template[] = $t;
        }
    }

    /**
     * setTitulo
     * Define el titulo que se mostrara en pantalla index
     *
     * @param  mixed $titulo
     *
     * @return void
     */
    protected function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    #[Alias('setTitle')] 
    protected function setTitle($title)
    {
        $this->setTitulo($title);
    }

    /**
     * setView
     * Define el tipo de vista que se quiere utilizar
     *
     * @param  mixed $view
     * @param  mixed $options
     *
     * @return void
     */
    protected function setView($view, $options = [])
    {
        $allowed = ['table', 'calendar'];

        if(!in_array($view, $allowed)){
            $this->errors = ['tipo' => $this->typeError[5], 'bad' => $view, 'permitidos' => $allowed];
        }

        if ($view == 'calendar' && !empty($options)) {
            $allowedOptions = ['public'];
            $this->allowed($options, $allowedOptions, $this->typeError[3]);
            $this->viewOptions = $options;
        }

        $this->view = $view;
    }

    /**
     * setCalendarDefaultView
     * Define la vista default que mostrara el calendario
     *
     * @param  mixed $view
     *
     * @return void
     */
    protected function setCalendarDefaultView($view)
    {
        $allowed = ['day','week', 'month'];

        if(!in_array($view, $allowed)){
            $this->errors = ['tipo' => $this->typeError[6], 'bad' => $view, 'permitidos' => $allowed];
        }

        $this->defaultCalendarView = $view;
    }

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

        $allowed = [
            'campo',         // Campo de la base de datos
            'field',         // Alias de campo
            'campoReal',     // Campo real de la base de datos donde se almacenará la DATA
            'realField',     // Alias de campo real
            'column',        // Para el tipo combobox permite seleccionar las columnas a utilizar
            'columnClass',   // clase para columnas en html (bootstrap)
            'collect',       // Colección de datos para el campo combobox
            'edit',          // Valor boolean, muestra o no el input en el edit
            'enumArray',     // array de datos para el tipo de dato enum
            'filepath',      // dirección donde se almacenaran los archivos
            'format',        // establece el formato para diferentes tipos de campos
            'htmlType',      // establece el tipo de dato a utilizar en un input siempre que aplique
            'htmlAttr',      // Agrega atributos HTML a los campos definidos para editar
            'inputClass',    // añade clases CSS a campo a agregar.
            'nombre',        // es el label del campo que queremos que se muestre en la pantalla
            'name',          // Alias de nombre
            'show',          // visibilidad del campo en la tabla de la vista index
            'tipo',          // Define el tipo de campo a utilizar
            'type',          // Alias de tipo
            'target',        // Para los campos URL establece el target
            'unique',        // valida que el valor ingresado se único
            'value',         // Valor definido o predeterminado.
            'validation',    // Valida los campos según la nomenclatura de laravel,
            'columnParent',  // Aplica cuando es un select o un select 2 multiple
            'multiple',      // Aplica cuando es un select o un select 2
        ];
        $tipos = [
            'bool',          // Muestra un checkbox en el edit y un si o no en el index
            'combobox',      // Muestra un select simple
            'select2',
            'date',          // Input con formato tipo fecha
            'datetime',      // Input en formato fecha y hora
            'enum',          // Select con valores determinados
            'file',          // Guarda un archivo en una ubicación definida
            'file64',        // Guarda un archivo codificado en base64
            'hidden',        // Muestra un campo hidden en el formulario edit.
            'icono',         // Muestra un campo para seleccionar un icono
            'image',         // Guarda una imagen en formato Base64
            'numeric',       // Muestra un campo de tipo number en HTML y le da formato en el index
            'password',      // Muestra dos campos contraseña y confirmar contraseña
            'string',        // Tipo por defecto muestra un input tipo text
            'text',          // La misma definición de string
            'textarea',      // Muestra un campo textArea en el formulario edit
            'url',           // Establece  una url con parámetros personalizados.
        ];
        $component = [      // Nombre del componente a utilizar
            'combobox' => 'select',
            'enum'     => 'select',
            'select2'  => 'select2',
            'password' => 'password',
            'textarea' => 'textarea',
        ];
        $htmlType = [       // relación entre tipos de datos HTML y locales
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

        // validando datos permitidos
        $this->allowed($params, $allowed, $this->typeError[2]);
        if (!array_key_exists('campo', $params) && !array_key_exists('field', $params)) {
            return $this->errors = ['tipo' => $this->typeError[1]];
        }

        $params['campo'] = $params['campo'] ?? $params['field'];

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

        $params['nombre']      = $params['nombre'] ?? $params['name'] ?? str_replace('_', ' ', ucfirst($params['campo']));
        $params['edit']        = $params['edit'] ?? true;
        $params['show']        = $params['show'] ?? true;
        $params['tipo']        = $params['tipo'] ?? $params['type'] ?? 'string';
        $params['decimales']   = $params['decimales'] ?? 0;
        $params['format']      = $params['format'] ?? '';
        $params['unique']      = $params['unique'] ?? false;
        $params['input']       = $component[$params['tipo']] ?? 'input';
        $params['inputName']   = $params['campoReal'] ?? $params['campo'];
        $params['component']   = "krud-".$params['input'];
        $params['columnClass'] = $params['columnClass'] ?? 'col-md-6';
        $params['inputClass']  = $params['inputClass'] ?? null ;
        $params['editClass']   = $params['edit'] == false ? 'hide' : '';
        $params['collect']     = $params['collect'] ?? null;
        $params['value']       = $params['value'] ?? null;
        $params['validation']  = $params['validation'] ?? null;

        if(empty($params['htmlType'])){
            $params['htmlType'] = $htmlType[$params['tipo']] ?? 'text';
        }

        if(!empty($params['htmlAttr'])){
            if(is_array($params['htmlAttr'])){
                $params['htmlAttr'] = collect($params['htmlAttr']);
            } else if(is_string($params['htmlAttr'])) {
                $params['htmlAttr'] = collect([$params['htmlAttr'] => true]);
            }

            if($params['htmlAttr']->has('multiple')){
                $params['inputId']   = $params['inputName'];
                $params['inputName'] = $params['inputName'].'[]';
            }
        } else {
            $params['htmlAttr'] = null;
        }

        // validando tipo y longitud de columns
        if ($params['tipo'] == 'combobox') {

            if(!empty($params['column'])) {
                $tipo = \is_array($params['column']);
                $longitud = count($params['column']) == 2;

                if(!$tipo || !$longitud) {
                    return $this->errors = ['tipo' => $this->typeError[1]];
                }
            }

            // valiando si es un select multiple con diferente ubicación para 
            if(
                !empty($params['htmlAttr']->has('multiple')) && 
                $params['htmlAttr']->has('multiple') && 
                $params['htmlAttr']['multiple'] == true && 
                ($params['campo'] instanceof Expression) == false &&
                strrpos($params['campo'], '.') != false
            ) {
                $locationTable =  explode('.', $params['campo'])[0];
                $localTable = $this->model->getTable();
                if($locationTable != $localTable && empty($params['columnParent'])) {
                    return $this->errors = ['tipo' => $this->typeError[17]];
                }
            }
        }

        if (!in_array($params['tipo'], $tipos)) {
            $this->errors = ['tipo' => $this->typeError[2], 'bad' => $params['tipo'], 'permitidos' => $tipos];
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
     * setValidationItem
     * Define las reglas que debe cumplir el input que se está validando
     *
     * @param  string $name
     * @param  string|array $rule
     * @return void
     */
    protected function setValidationItem($name, $rule)
    {
        $this->validations[$name] = $rule;
    }

    /**
     * transformData
     * Transforma la data según el estilo visual de bootstrap.
     *
     * @param  mixed $data
     *
     * @return void
     */
    protected function transformData($data, $prefix = null)
    {
        $i = 0;
        $prefixDefault = $this->getDefaultPrefix();

        foreach ($data as &$a) {
            foreach ($a as $k => &$v) {
                $v = htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
                foreach ($this->campos as $cn => $cv) {
                    if($cv['campo'] instanceof Expression) {
                        $cv['campo'] = $cv['campoReal'];
                    } else if (strrpos($cv['campo'], '.') != false) {
                        $array = explode('.', $cv['campo']);
                        $cv['campo'] = $array[count($array) - 1];
                    }

                    if ($k == $cv['campo']) {
                        // agregando estilo visual a los campos booleanos
                        if ($cv['tipo'] == 'bool') {
                            $v = '<span class="'.($prefix != null && $prefix == $prefixDefault ? 'label label' : config('kitukizuri.badge')).'-'.($v ? 'success' : 'default').'">'.($v ? __('Si') : 'No').'</span>';
                        } else if ($cv['tipo'] == 'url') {
                            if($cv['format'] != '') {
                                $v = str_replace('{value}', $v, $cv['format']);
                            }
                            $v = '<a href="'.$v.'">'.$v.'</a>';
                        } else if($cv['tipo'] == 'date' || $cv['tipo'] == 'datetime') {
                            if(!empty($v)){
                                $time = strtotime($v);
                                $v = $cv['format'] != '' ? date($cv['format'], $time) : date('d/m/Y', $time);
                            }
                        }
                    }
                }
            }
            $i++;
        }
        return $data;
    }

    /**
     * setBoton
     * Define los botones personalizados disponibles a utilizar en la tabla de la vista index.
     *
     * @param  mixed $params
     *
     * @return void
     */
    protected function setBoton($params)
    {
        $allowed = ['nombre', 'url', 'class', 'icon'];
        $this->allowed($params, $allowed, $this->typeError[4]);
        $this->botones[] = $params;
    }

    #[Alias('setBoton')]
    protected function setButton($params)
    {
        $this->setBoton($params);
    }

    /**
     * setBotonDT
     * Permite definir botones adicionales en data table
     *
     * @param  mixed $params
     * @return void
     */
    protected function setBotonDT($params)
    {
        $allowed = ['text', 'class', 'action'];
        $this->allowed($params, $allowed, $this->typeError[4]);
        $this->botonesDT[] = $params;
    }

    #[Alias('setBotonDT')]
    protected function setButtonDT($params)
    {
        $this->setBotonDT($params);
    }

    /**
     * setDefaultBotonDT
     * Visibilidad a los botones por defecto de dataTable
     *
     * @param  mixed $params
     * @return void
     */
    protected function setDefaultBotonDT($params)
    {
        $allowed = ['name'];
        $this->allowed($params, $allowed, $this->typeError[4]);
        $this->defaultBtnDT[] = $params['name'];
    }

    #[Alias('setDefaultBotonDT')]
    protected function setDefaultButtonDT($params)
    {
        $this->setDefaultBotonDT($params);
    }

    protected function setCalendarView($prefix, $layout)
    {
        $view  = 'krud.calendar';
        $kmenu = false;
        $prefixDefault = $this->getDefaultPrefix();

        if ($prefix != null && $prefix == $prefixDefault) {
            $view = 'krud::calendar';
            $kmenu = true;
        }

        $permisos = $this->getPermisos(Auth::id());

        if (!empty($this->viewOptions)) {
            if (in_array('public', $this->viewOptions) && $this->viewOptions['public'] == true) {
                $permisos = [
                    'create',
                    'show',
                    'edit',
                    'destroy'
                ];
            }
        }

        return view($view, [
            'layout'      => $layout,
            'titulo'      => $this->titulo,
            'permisos'    => $permisos,
            'defaultView' => $this->defaultCalendarView,
            'action'      => Route::currentRouteName(),
            'campos'      => $this->campos,
            'kmenu'       => $kmenu,
        ]);
    }

    protected function setTableView($prefix, $layout)
    {
        $botones       = json_encode($this->botones);
        $ruta          = $this->getModuloRuta();
        $view          = 'krud.index';
        $dtBtnAdd      = config('kitukizuri.dtBtnAdd');
        $dtBtnLiner    = config('kitukizuri.dtBtnLiner');
        $kmenu         = false;
        $vBootstrap    = config('kitukizuri.vBootstrap');
        $prefixDefault = $this->getDefaultPrefix();

        if ($prefix != null && $prefix == $prefixDefault) {
            $vars       = usePrevUi('default');
            $view       = $vars['kitukizuri'];
            $dtBtnAdd   = 'btn btn-outline-success';
            $dtBtnLiner = 'btn btn-outline-secondary';
            $kmenu      = true;
            $vBootstrap = 5;
        }

        // Validando versión de bootstrap
        if(!empty($vBootstrap)) {
            $vBootstrap = str_contains($vBootstrap, '.') ? explode('.', $vBootstrap)[0] : $vBootstrap;
        } else {
            $vBootstrap = 5;
        }

        $permisos = $this->getPermisos(Auth::id());

        if(!empty($this->removePermisos)) {
            $permisos = array_values(array_diff($permisos, $this->removePermisos));
        }

        return view($view, [
            'titulo'       => $this->titulo,
            'columnas'     => $this->getColumnas($this->getSelectShow()),
            'botones'      => $botones,
            'permisos'     => $permisos,
            'ruta'         => $ruta,
            'template'     => $this->template,
            'layout'       => $layout,
            'dtBtnAdd'     => $dtBtnAdd,
            'dtBtnLiner'   => $dtBtnLiner,
            'embed'        => $this->indexEmbed,
            'kmenu'        => $kmenu,
            'vBootstrap'   => $vBootstrap,
            'botonesDT'    => $this->botonesDT,
            'defaultBtnDT' => $this->defaultBtnDT,
        ]);
    }

    protected function buildMsg($type, $msg)
    {
        if(config('kitukizuri.prevUi') === true ) {
            Session::flash('type', $type);
            Session::flash('msg', $msg);
            return redirect()->back();
        } else if(empty(config('kitikizuri.preUi')) || config('kitukizuri.prevUi') == false) {
            return response()->json([
                'type' => $type,
                'msg' => $msg
            ]);
        }
    }

    /**
     * embedIndexView
     * Agrega una vista dentro del index de la pagina.
     *
     * @param  mixed $view
     * @return void
     */
    public function embedIndexView($view, $position, $script = null, $values = [])
    {
        $this->indexEmbed[] = [
            'view'     => $view,
            'position' => $position,
            'script'   => $script,
            'values'   => $values,
        ];
    }
}