<?php

namespace Icebearsoft\Kitukizuri;

// Dependencias
use DB;
use Route;
use Session;
use Carbon\Carbon;
use Mockery\Exception;

// librerías del framework
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\QueryException;

// Controllers 
use App\Http\Controllers\Controller;

// Models
use App\Models\Municipio;

class Krud extends Controller
{
    // Variable remplazo id  
    private $id       = '__id__';

    // Variables de valor único
    private $view     = null;
    private $model    = null;
    private $titulo   = null;
    private $editId   = null;
    private $layout   = null;
    private $parentid = null;
    private $storeMsg = null;
    private $viewHelp = null;

    // Variables en array
    private $rt          = [];
    private $joins       = [];
    private $embed       = [];
    private $wheres      = [];
    private $campos      = [];
    private $botones     = [];
    private $parents     = [];
    private $orderBy     = [];
    private $orWheres    = [];
    private $leftJoins   = [];
    private $errors       = [];
    private $whereAndFn  = [];
    private $viewOptions = [];
    private $template  = [
        'datatable',
    ];
    private $allowedOperator = [
        '=', '<', '>', '<=', '>=', '<>', '!=', '<=>',
        'like', 'like binary', 'not like', 'ilike',
        '&', '|', '^', '<<', '>>', '&~',
        'rlike', 'not rlike', 'regexp', 'not regexp',
        '~', '~*', '!~', '!~*', 'similar to',
        'not similar to', 'not ilike', '~~*', '!~~*',
    ];
    private $typeError = [
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
        'badColumnDefinition'   // 16
    ];

    // viables únicas para vista calendario
    private $defaultCalendarView = null;

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
    private function getLayout()
    {
        return $this->layout ?? config('kitukizuri.layout');
    }

    /**
     * getStoreMSG
     *
     * @return void
     */
    private function getStoreMSG()
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
    private function setTemplate($templates)
    {
        foreach ($templates as $t) {
            $this->template[] = $t;
        }
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
    protected function setParents($nombre, $value)
    {
        array_push($this->parents, ['nombre' => $nombre, 'value'=>$value]);
    }

    /**
     * getPermisos
     * Define los permisos a los que tiene acceso el controller
     *
     * @param  mixed $id
     *
     * @return void
     */
    protected function getPermisos($id)
    {
        $kitukizuri = new KituKizuri;
        return $kitukizuri->getPermisos($id);
    }

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
        $allowed = [
            'campo', // Campo de la base de datos  
            'column', // Para el tipo combobox permite seleccionar las columnas a utilizar
            'columnClass', // clase para columnas en html (bootstrap)
            'collect', // Colección de datos para el campo combobox
            'default', 
            'decimales',
            'edit', 'enumArray', 
            'filepath', 'fileWidth', 'fileHeight', 'format',
            'htmlType', 'htmlAttr',
            'inputClass', 
            'nombre', 
            'reglas', 'reglasMensaje', 
            'show', 
            'tipo', 'target', 
            'unique', 
            'value', 
        ];
        $tipos = [
            'bool',
            'combobox', 
            'date', 'datetime', 
            'enum',
            'file', 'file64',
            'hidden', 
            'icono', 'image', 
            'numeric', 
            'password',
            'string',
            'text',
            'textarea',
            'url',
        ];
        $component = [
            'bool'     => 'bool',
            'combobox' => 'select',
            'numeric'  => 'input',
        ];
        $htmlType = [
            'string'  => 'text',
            'bool'    => 'checkbox',
            'date'    => 'date',
            'numeric' => 'number',
            
        ];

        $this->allowed($params, $allowed, $this->typeError[2]);

        if (!array_key_exists('campo', $params)) {
            return $this->errors = ['tipo' => $this->typeError[1]];
        }

        // validando tipo y longitud de columns 
        if ($params['tipo'] == 'combobox' && !empty($params['column'])) {
            $tipo = \is_array($params['column']);
            $longitud = count($params['column']) == 2;

            if(!$tipo || !$longitud) {
                return $this->errors = ['tipo' => $this->typeError[1]];
            }
            
        }

        $params['nombre']    = $params['nombre'] ?? str_replace('_', ' ', ucfirst($params['campo']));
        $params['edit']      = $params['edit'] ?? true;
        $params['show']      = $params['show'] ?? true;
        $params['tipo']      = $params['tipo'] ?? 'string';
        $params['decimales'] = $params['decimales'] ?? 0;
        $params['format']    = $params['format'] ?? '';
        $params['unique']    = $params['unique'] ?? false;
        $params['input']     = $component[$params['tipo']] ?? 'input';
        $params['htmlType']  = $htmlType[$params['tipo']] ?? 'text';
        $params['htmlAttr']  = $params['htmlAttr'] ?? null;
        
        






        if (!in_array($params['tipo'], $tipos)) {
            $this->errors = ['tipo' => $this->typeError[2], 'bad' => $params['tipo'], 'permitidos' => $tipos];
        } else {
            $tipo = $params['tipo'];
            if ($tipo == 'datetime' || $tipo == 'date') {
                $this->setTemplate(['datetimepicker']);
            } else if ($tipo == 'icono') {
                $this->setTemplate(['iconpicker']);
            } else if($tipo == 'combobox') {
                if (empty($params['collect'])) {
                    $this->errors = ['tipo' => $this->typeError[7]];
                } else {
                    $columns = ['id', 'value'];
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
            } else if ($tipo == 'file' && empty($params['filepath'])) {
                $this->errors = ['tipo' => $this->typeError[9]];
            } else if ($tipo == 'enum' && count($params['enumarray']) == 0) {
                $this->errors = ['tipo' => $this->typeError[10]];
            } else if ($tipo == 'hidden' && empty($params['value'])) {
                $this->errors = ['tipo' => $this->typeError[11]];
            }
        }
        
        if (!strpos($params['campo'], ')')) {
            $arr = explode('.', $params['campo']);
            if (count($arr)>=2) {
                $params['campoReal'] = end($arr);
            }
        }

        $this->campos[] = $params;
    }

    /**
     * setJoin
     * Define las relaciones a utilizar entre las tablas y que estaran disponbles para mostrar 
     * en la tabla de la vista index.
     *
     * @param  mixed $tabla
     * @param  mixed $v1
     * @param  mixed $operador
     * @param  mixed $v2
     *
     * @return void
     */
    public function setJoin($tabla, $v1, $operador = null, $v2 = null)
    {
        if (func_num_args() === 3) {
            $v2 = $operador;
            $operador = '=';
        }

        $this->allowed($operador, $this->allowedOperator, $this->typeError[12]);

        $this->joins[] = ['tabla'=>$tabla,'value1'=>$v1,'operador'=>$operador, 'value2'=>$v2];
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

        $this->leftJoins[] = ['tabla'=>$tabla,'value1'=>$v1,'operador'=>$operador, 'value2'=>$v2];
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
    public function setWhere($column, $op = null, $column2 = null)
    {
        if (func_num_args() === 2) {
            $column2 = $op;
            $op = '=';
        }

        $this->allowed($op, $this->allowedOperator, $this->typeError[12]);

        $this->wheres[] =  [$column, $op, $column2];
    }

    public function setWhereAndFn($conditions)
    {
        $this->whereAndFn[] = $conditions;
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
    public function setOrWhere($column, $op = null, $column2 = null)
    {
        if (func_num_args() === 2) {
            $column2 = $op;
            $op = '=';
        }

        $this->allowed($op, $this->allowedOperator, $this->typeError[12]);

        $this->orWheres[] = [$column, $op, $column2];
    }

    /**
     * setOrderBy
     * Define el orden para obtener la data que se mostrara en la vista index.
     *
     * @param  mixed $column
     *
     * @return void
     */
    public function setOrderBy($column, $orientation = null)
    {
        $this->orderBy[] = [$column, $orientation ?? 'asc'];
    }

    /**
     * setBoton
     * Define los botones personalizados disponibles a utilizar en la tabla de la vista index.
     *
     * @param  mixed $params
     *
     * @return void
     */
    public function setBoton($params)
    {
        $allowed = ['nombre', 'url', 'class', 'icon'];
        $this->allowed($params, $allowed, $this->typeError[4]);
        $this->botones[] = $params;
    }

    /**
     * embedView
     * Pretende incluir una vista dentro de una vista AUN EN DESARROLLO
     *
     * @param  mixed $view
     * @param  mixed $model
     * @param  mixed $idRelation
     * @param  mixed $campos
     *
     * @return void
     */
    public function embedView($view, $model, $idRelation, $campos)
    {
        array_push($this->embed, [view($view, ['id' => Crypt::encrypt($this->editId)]), $model, $idRelation, $campos]);
    }

    /**
     * setParentId
     * Define el nombre del id padre al que pertenece el controller
     * para usarlo el controller padre debe de tener en la URL ?parent={id}
     * 
     * @param  mixed $text
     *
     * @return void
     */
    public function setParentId($text)
    {
        $this->parentid = $text;
    }

    /**
     * getMunicipios
     * Obtiene una lista de los municipios ingresados en al tabla municipios
     *
     * @param  mixed $departamentoid
     *
     * @return void
     */
    public function getMunicipios($departamentoid)
    {
        $municipios = Municipio::select('municipioid', 'nombre')->where('departamentoid', $departamentoid)->get();
        return $municipios;
    }

    
    /**
     * transformData
     * Transforma la data según el estilo visual de bootstrap.
     *
     * @param  mixed $data
     *
     * @return void
     */
    private function transformData($data, $prefix = null)
    {
        $i = 0;
        foreach ($data as $a) {
            foreach ($a as $k => $v) {
                foreach ($this->campos as $cn => $cv) {
                    
                    if (strrpos($cv['campo'], '.') != false) {
                        $array = explode('.', $cv['campo']);
                        $cv['campo'] = $array[count($array) - 1];
                    }

                    if ($k == $cv['campo']) {
                        // agregando estilo visual a los campos booleanos
                        if ($cv['tipo'] == 'bool') {
                            $v = '<span class="'.($prefix != null && $prefix == 'kk' ? 'label label' : config('kitukizuri.badge')).'-'.($v ? 'success' : 'default').'">'.($v ? 'Si' : 'No').'</span>';
                            $data[$i][$k] = $v;
                        } else if ($cv['tipo'] == 'url') {
                            if($cv['format'] != '') {
                                $v = str_replace('{value}', $v, $cv['format']);
                            }
                            $v = '<a href="'.$v.'">'.$v.'</a>';
                            $data[$i][$k] = $v;
                        } else if($cv['tipo'] == 'date' || $cv['tipo'] == 'datetime') {
                            $time = strtotime($v);
                            $v = $cv['format'] != '' ? date($cv['format'], $time) : date('d/m/Y', $time);
                            $data[$i][$k] = $v;
                        }
                    }
                }
            }
            $i++;
        }
        return $data;
    }
    
    /**
     * getData
     * Obtiene la data que se mostrara en la tabla consolidando joins, where y, orderby
     * 
     * @return void
     */
    private function getData($limit = null, $offset = null)
    {
        // lista de campos a mostrar en la tabla
        $campos = $this->getSelectShow();

        //consultando al modelo los campos a mostrar en la tabla 
        $data = $this->model->select($this->getSelect($campos));

        // Obteniendo el id de la tabla
        $data->addSelect($this->model->getTable().'.'.$this->model->getKeyName().' as '.$this->id);

        //agregando joins a la consulta
        foreach ($this->joins as $join) {
            $data->join($join['tabla'], $join['value1'], $join['operador'], $join['value2']);
        }

        //agregando leftJoins a la consulta
        foreach ($this->leftJoins as $leftJoin) {
            $data->leftJoin($leftJoin['tabla'], $leftJoin['value1'], $leftJoin['operador'], $leftJoin['value2']);
        }

        // Agregando wehres a la consulta
        foreach ($this->wheres as $where) {
            $data->where($where[0], $where[1], $where[2]);
        }
        
        // Agregando orWhere a la onsulta general
        foreach ($this->orWheres as $orWhere) {
            $data->orWhere($orWhere[0], $orWhere[1], $orWhere[2]);
        }
        
        // Agrupando Or en And como funcion
        if (!empty($this->whereAndFn)) {
            $data->where(function($q){
                foreach ($this->whereAndFn as $where) {
                    $q->orWhere(...$where);
                }
            });
        }

        // Agregando el orden para mostrar los datos
        foreach ($this->orderBy as $column) {
            $data->orderBy(...$column);
        }

        $count = $data->count();

        // validando si existe un limite para obtenr los datos
        $data->take($limit);

        // validando si hay un offset a utilizar
        $data->skip($offset);
        
        return [$data->get(), $count];
    }

    /**
     * getSelectShow
     * valida y clasifica los campos a mostrar y los que no.
     *
     * @return void
     */
    private function getSelectShow()
    {
        $campos = $this->campos;
        for ($i = 0; $i<count($campos); $i++) {
            if ($campos[$i]['show'] == false) {
                unset($campos[$i]);
            }
        }
        return array_values($campos);
    }

    /**
     * getSelect
     * retorna una lista de los campos a mostrar.
     *
     * @param  mixed $campos
     *
     * @return void
     */
    private function getSelect($campos)
    {
        $s = [];
        for ($i = 0; $i <count($campos); $i++) {
            if ($campos[$i]['show'] == true) {
                array_push($s, $campos[$i]);
            }
        }
        return array_map(function ($c) {
            return DB::raw($c['campo']);
        }, $s);
    }

    /**
     * getColumnas
     *
     * @param  mixed $campos
     *
     * @return void
     */
    private function getColumnas($campos, $onlyCampos = false)
    {
        $s = [];
        for ($i = 0; $i <count($campos); $i++) {
            if ($campos[$i]['show'] == true) {
                array_push($s, $campos[$i]);
            }
        }
        return array_map(function ($c) use ($onlyCampos) {
            if ($onlyCampos == true) {
                return $c['campo'];
            } else {
                return $c['nombre'];
            }
        }, $s);
    }

    /**
     * makeArrayData
     *
     * @param  mixed $data
     *
     * @return void
     */
    private function makeArrayData($data)
    {
        for ($i = 0; $i< count($this->campos); $i++) {
            $this->campos[$i]['value'] = $data->{(array_key_exists('campoReal', $this->campos[$i]) ? $this->campos[$i]['campoReal'] : $this->campos[$i]['campo'])};
        }
    }

    /**
     * getUrl
     *
     * @param  mixed $url
     *
     * @return void
     */
    private function getUrl($url)
    {
        $url = explode('/', $url);
        if ($url[count($url)-1] == 'create') {
            unset($url[count($url)-1]);
        } else {
            unset($url[count($url)-1]);
            unset($url[count($url)-1]);
        }

        $url = implode('/', $url);
        
        return $url;
    }

    /**
     * getModuloRuta
     *
     * @return void
     */
    public function getModuloRuta()
    {
        $ruta       = [];
        $nombreRuta = explode('.', Route::currentRouteName())[0];
        $uri        = explode('/', Route::getCurrentRoute()->uri);
        
        foreach ($uri as $v) {
            array_push($ruta, $v);
            if ($v == $nombreRuta) {
                break;
            }
        }

        return route(end($ruta).'.index');
    }

    /**
     * getHelp
     * Genera una vista de ayuda para usar el paquete
     *
     * @return void
     */
    public function help()
    {
        $this->viewHelp = true;
    }

    /**
     * index
     * Genera la vista principal del catalogo
     *
     * @return void
     */
    public function index()
    {
        $vista = [];

        if($this->viewHelp == true) {
            $vista = view('krud::training', ['tipo' => 'help']);
        }

        if ($this->model == null) {
            $vista = view('krud::training', ['tipo' => $this->typeError[0]]);
        }

        if (!empty($this->errors)) {
            $vista = view('krud::training', $this->errors);
        }

        if(empty($vista)) {
            $prefix = Route::current()->action['prefix'];
            $layout = $this->getLayout();

            if (!empty($this->view) && $this->view == 'calendar') {
                $vista = $this->setCalendarView($prefix, $layout);
            } else {
                $vista = $this->setTableView($prefix, $layout);
            }
        }
        
        return $vista;
    }

    private function setCalendarView($prefix, $layout)
    {
        $view  = 'krud.calendar';
        $kmenu = false;
        
        if ($prefix != null && $prefix == 'kk') {
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
            'kmenu'       => $kmenu
        ]);
    }

    private function setTableView($prefix, $layout)
    {
        $botones    = json_encode($this->botones);
        $ruta       = $this->getModuloRuta();
        $view       = 'krud.index';
        $dtBtnAdd   = config('kitukizuri.dtBtnAdd');
        $dtBtnLiner = config('kitukizuri.dtBtnLiner');
        $kmenu      = false;
        $vBootstrap = config('kitukizuri.vBootstrap');

        if ($prefix != null && $prefix == 'kk') {
            $view       = 'krud::index';
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

        return view($view, [
            'titulo'     => $this->titulo,
            'columnas'   => $this->getColumnas($this->getSelectShow()),
            'botones'    => $botones,
            'permisos'   => $this->getPermisos(Auth::id()),
            'ruta'       => $ruta,
            'template'   => $this->template,
            'layout'     => $layout,
            'dtBtnAdd'   => $dtBtnAdd,
            'dtBtnLiner' => $dtBtnLiner,
            'kmenu'      => $kmenu,
            'vBootstrap' => $vBootstrap
        ]);
    }

    /**
     * show
     * Obteniendo la data que se mostrara en la tabla principal
     * del catalogo
     *
     * @param  mixed $id
     * @param  mixed $request
     *
     * @return void
     */
    public function show($id, Request $request) 
    {
        $icnEdit         = config('kitukizuri.edit');
        $icnDelete       = config('kitukizuri.delete');
        $icnOptions      = config('kitukizuri.options');
        $classBtnEdit    = config('kitukizuri.classBtnEdit');
        $classBtnDelete  = config('kitukizuri.classBtnDelete');
        $classBtnOptions = config('kitukizuri.classBtnOptions');
        
        $prefix = Route::current()->action['prefix'];

        if ($prefix != null && $prefix == 'kk') {
            $icnEdit         = 'mdi mdi-pencil-outline';
            $icnDelete       = 'mdi mdi-trash-can-outline';
            $icnOptions      = 'mdi mdi-plus';
            $classBtnEdit    = 'btn-outline-primary';
            $classBtnDelete  = 'btn-outline-danger';
            $classBtnOptions = 'btn-outline-warning';
        }

        $response = [];
        $permisos = $this->getPermisos(Auth::id());

        //Contador de datos a renderizar
        $response['draw'] = intval($request->draw);
        
        // Datos para paginacion
        $limit   = $request->length != '' ? $request->length : 10;
        $offset  = $request->start ? $request->start : 0;
        $columns = $this->getColumnas($this->getSelectShow(), true);
        
        if (!empty($request->search['value'])) {
            foreach ($columns as $column) {
                if (strpos($column, 'as')) {
                    $column = trim(explode('as', $column)[0]);
                }
                $this->setWhereAndFn([$column, 'like', '%'.$request->search['value'].'%']);
            }   
        }

        // Obteniendo los datos de la tabla
        $data = $this->getData($limit, $offset);

        //total de datos obtenidos
        $response['data']            = [];
        $response['recordsTotal']    = $data[1];
        $response['recordsFiltered'] = $response['recordsTotal'];

        $data = $this->transformData($data[0]->toArray(), $prefix);

        foreach ($data as $item) {
            // string de botones a imprimir
            $btns = '';
            
            // Validando si los botones son mas de uno para renderdizar modal
            if (!empty($this->botones) && count($this->botones) > 1) {
                //recorriendo todos los botones extras
                $btns .= '<a
                    data-toggle="tooltip" data-placement="left" title="Mas opciones" 
                    href="javascript:void(0)" 
                    class="btn btn-xs btn-sm '.$classBtnOptions.'" 
                    onclick="opciones('.$item['__id__'].')">
                        <span class="'.$icnOptions.'"></span>
                    </a>';
            } else {
                foreach($this->botones as $boton) {
                    //dd($boton);
                    $boton['url'] = str_replace('{id}', $item['__id__'], $boton['url']);
                    $btns .= '<a 
                        data-toggle="tooltip" data-placement="left" title="'.$boton['nombre'].'" 
                        href="'.$boton['url'].'" 
                        class="btn btn-xs btn-sm btn-'.$boton['class'].'">
                            <span class="'.$boton['icon'].'"></span>
                        </a>';
                }
            }

            //Agregando boton para Editar
            if(in_array('edit', $permisos)) {
                $btns .= '<a 
                    href="javascript:void(0)"
                    data-toggle="tooltip" data-placement="left" title="Editar" 
                    onclick="edit(\''.Crypt::encrypt($item['__id__']).'\')" 
                    class="btn btn-xs btn-sm '.$classBtnEdit.'">
                        <span class="'.$icnEdit.'"></span>
                    </a>';
            }

            if(in_array('destroy', $permisos)) {
                $btns .= '<a 
                    href="javascript:void(0)"
                    data-toggle="tooltip" data-placement="left" title="Eliminar" 
                    onclick="destroy(\''.Crypt::encrypt($item['__id__']).'\')" 
                    class="btn btn-xs btn-sm '.$classBtnDelete.'">
                        <span class="'.$icnDelete.'"></span>
                    </a>';
            }
            
            $item['btn'] = $btns;

            unset($item['__id__']);

            $response['data'][] = array_values($item);
        }        
        
        return response()->json($response);

    }

    /**
     * create
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function create(Request $request)
    {
        return $this->edit(Crypt::encrypt(0), $request);
    }

    /**
     * edit
     *
     * @param  mixed $id
     * @param  mixed $request
     *
     * @return void
     */
    public function edit($id, Request $request)
    {
        try {
            $id = Crypt::decrypt($id);
            $parentid= $request->get('parent');
        } catch (Exception $e) {
            dd($e);
        }
        
        $this->editId = $id;

        if ($id != 0) {
            $data = $this->model->find($id);
            $titulo = 'Editar '.$this->titulo;
            $this->makeArrayData($data);
        } else {
            $data = null;
            $titulo = 'Agregar '.$this->titulo;
        }

        $url    = $this->getUrl($request->url());
        $layout = $this->getLayout();

        $prefix = Route::current()->action['prefix'];
        $view   = 'krud.edit';
        $kmenu  = false;

        if ($prefix != null && $prefix == 'kk') {
            $view = 'krud::edit';
            $kmenu = true;
        }

        return view($view, [
            'titulo'   => $titulo,
            'campos'   => $this->campos,
            'action'   => $url,
            'id'       => Crypt::encrypt($id),
            'rt'       => $this->rt,
            'embed'    => $this->embed,
            'parent'   => $this->parentid,
            'parentid' => $parentid,
            'parents'  => $this->parents,
            'layout'   => $layout,
            'kmenu'    => $kmenu,
        ]);
    }

    /**
     * store
     * almacena la data segun el modelo utlizado
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function store(Request $request)
    {
        try {
            $id = Crypt::decrypt($request->input('id'));
        } catch (Exception $e) {
            dd($e);
        }

        if ($id != 0) {
            $this->model = $this->model->find($id);
        }

        // obteniendo valores enviados por el usuario
        $requestData     = $request->all();
        $camposDefinidos = collect($this->campos);

        foreach ($requestData as $nombreCampo => $valor) {
            // Excluyendo campos de control
            if($nombreCampo == '_token' || $nombreCampo == 'id') {
                continue;
            }

            $campo = $camposDefinidos->filter(function($item) use($nombreCampo) {
                if((!empty($item['campoReal']) && $item['campoReal'] == $nombreCampo) || $item['campo'] == $nombreCampo) {
                    return $item;
                }
            })->first();
            
            // validando el campo segun el tipo de dato
            if ($campo['unique'] == true && $campo['edit'] == true && $id == 0) {
                $validate = $this->model->where($nombreCampo, $valor)->exists();
                if($validate) {
                    Session::flash('type', 'danger');
                    Session::flash('msg', 'El valor del campo '.$c['nombre'].' ya se encuentra registrado en la base de datos.');
                    return redirect()->back();
                } 
            } else if ($campo['tipo'] == 'date') {
                $valor = $this->toDateMysql($valor);
            } else if ($campo['tipo'] == 'numeric') {
                $valor = str_replace(',', '', $valor);
            } else if ($campo['tipo'] == 'password') {
                $valor = !empty($valor) ? bcrypt($valor) : $this->model->{$nombreCampo};
            } else if ($campo['tipo'] == 'image' || $campo['tipo'] == 'file64') {
                if ($request->hasFile($nombreCampo)) {
                    $file  = $request->file($nombreCampo);
                    $file  = 'data:image/'.strtolower($file->getClientOriginalExtension()).';base64,'.base64_encode(file_get_contents($file));
                    $valor = $file;
                } else {
                    $valor = $this->model->{$k};
                }
            } else if ($campo['tipo'] == 'file') {
                if ($request->hasFile($nombreCampo)) {
                    $file     = $request->file($nombreCampo);
                    $filename = date('Ymdhis') . mt_rand(1, 1000) . '.' . strtolower($file->getClientOriginalExtension());
                    $path     = public_path() . $c['filepath'];
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                    }
                    $file->move($path, $filename);
                    $valor = $file;
                } else {
                    $valor = $this->model->{$k};
                }
            } else if ($campo['tipo'] == 'bool') {
                $valor = $valor == 'on';
            } else if ($campo['tipo'] == 'hidden') {
                $this->model->{$nombreCampo} = $valor == 'userid' ? Auth::id() : $valor;
            }
            $this->model->{$nombreCampo} = $valor;
        }

        // validando campos sin valor 
        foreach ($this->campos as $campo) {
            $campoReal = !empty($campo['campoReal']) ? $campo['campoReal'] : $campo['campo'];
            if (!array_key_exists($campoReal, $requestData)) {
                if ($campo['tipo'] == 'bool') {
                    $this->model->{$campoReal} = false;
                }
            }
        }

        try {
            $this->model->save();
            Session::flash('type', 'success');
            Session::flash('msg', $this->getStoreMSG());
        } catch (Exception $e) {
            Session::flash('type', 'danger');
            Session::flash('msg', $e);
        }

        // Retornando mensaje de guardado 
        $url = $request->url();

        return redirect($url);
    }

    /**
     * destroy
     * Elimina un reigstro seleccionado 
     *
     * @param  mixed $id
     * @param  mixed $request
     *
     * @return void
     */
    public function destroy($id, Request $request)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (Exception $e) {
            return $e;
        }
        try {
            $this->model->destroy($id);
            Session::flash('type', 'success');
            Session::flash('msg', $this->getStoreMSG());
        } catch (QueryException $e) {
            return $e;
        }

        return 1;
    }

    /**
     * toDateMysql
     * transforma una fecha a formato mysql
     *
     * @param  mixed $date
     *
     * @return void
     */
    public function toDateMysql($date)
    {
        $date = str_replace('/', '-', $date);
        $date = date('Y-m-d H:s:i', strtotime($date));
        return $date;
    }

    /**
     * mesesEntreFechas
     * Calcula cuantos meses hay entre dos fecha
     *
     * @param  mixed $aFechaInicio
     * @param  mixed $aFechaFin
     *
     * @return void
     */
    public function mesesEntreFechas($aFechaInicio, $aFechaFin)
    {
        $fi = new Carbon($aFechaInicio);
        $ff = new Carbon($aFechaFin);

        return $ff->diffInMonths($fi);
    }

    /**
     * allowed
     * verifica los parametros permitidos segun la lista enviada
     *
     * @param  mixed $params
     * @param  mixed $allowed
     *
     * @return void
     */
    private function allowed($params, $allowed, $badType)
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
}
