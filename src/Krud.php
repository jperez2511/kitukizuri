<?php

namespace Icebearsoft\Kitukizuri;

// Dependencias
use DB;
use Route;
use Session;
use Carbon\Carbon;
use Mockery\Exception;

// librerias del framwork
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

    // Variales de valor unico
    private $view     = null;
    private $model    = null;
    private $titulo   = null;
    private $editId   = null;
    private $layout   = null;
    private $parentid = null;
    private $storemsg = null;

    // variales en array
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
    private $erros       = [];
    private $whereAndFn  = [];
    private $viewOptions = [];
    private $template  = [
        'datatable',
    ];

    // viables unicas para vista calendario
    private $defaultCalendarView = null;

    /**
     * getLayout
     * Defnine el layout predeterminado
     * 
     * @return void
     */
    private function getLayout()
    {
        if (empty($this->layout)) {
            $this->layout = config('kitukizuri.layout');
        }
        return $this->layout;
    }

    /**
     * getStoreMSG
     *
     * @return void
     */
    private function getStoreMSG()
    {
        if(empty($this->storemsg)) {
            $this->storemsg = config('kitukizuri.storemsg');
        }
        return $this->storemsg;
    }
    
    /**
     * setStoreMSG
     *
     * @return void
     */
    protected function setStoreMSG($msg)
    {
        $this->storemsg = $msg;
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
     * setTemplate
     * Define las librerias a utilizar por ejemplo datatable, fontawesome ,etc.
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
    public function setParents($nombre, $value)
    {
        array_push($this->parents, ['nombre' => $nombre, 'value'=>$value]);
    }

    /**
     * setPermisos
     * Define los permisos a los que tiene acceso el controller
     *
     * @param  mixed $id
     *
     * @return void
     */
    public function setPermisos($id)
    {
        $kitukizuri = new KituKizuri;
        return $kitukizuri->getPermisos($id);
    }

    /**
     * setModel
     * Define el modelo donde se obtendran y almacenaran los datos
     *
     * @param  mixed $model
     *
     * @return void
     */
    public function setModel($model)
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
    public function setTitulo($titulo)
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
    public function setView($view, $options = [])
    {
        $allowed = ['catalogo', 'calendar'];

        if(!in_array($view, $allowed)){
            $this->errors = ['tipo' => 'badView', 'bad' => $view, 'permitidos' => $allowed];
        }

        if ($view == 'calendar' && !empty($options)) {
            $allowedOptions = ['public'];
            $this->allowed($options, $allowedOptions, 'badOptionsView');
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
    public function setCalendarDefaultView($view)
    {
        $allowed = ['day','week', 'month'];

        if(!in_array($view, $allowed)){
            $this->errors = ['tipo' => 'badCalendarView', 'bad' => $view, 'permitidos' => $allowed];
        }

        $this->defaultCalendarView = $view;
    }

    /**
     * setCampo
     * Define las propiedades de un campo a utilizar en el controller,
     * Viesta y posteriormente almacenado desde el modelo.
     *
     * @param  mixed $params
     *
     * @return void
     */
    public function setCampo($params)
    {
        $allowed = [
            'campo', 'column', 'columnclass', 'collect',
            'default', 'decimales',
            'edit', 'enumarray',
            'filepath', 'filewidth', 'fileheight', 'format',
            'inputclass',
            'nombre',
            'reglas', 'reglasmensaje', 
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
            'string', 'summernote', 
            'textarea',
            'url',
        ];

        $this->allowed($params, $allowed, 'badType');

        if (!array_key_exists('campo', $params)) {
            return $this->errors = ['tipo' => 'setCampo'];
        }

        $params['nombre']    = (!array_key_exists('nombre', $params) ? str_replace('_', ' ', ucfirst($params['campo'])) : $params['nombre']);
        $params['edit']      = (!array_key_exists('edit', $params) ? true : $params['edit']);
        $params['show']      = (!array_key_exists('show', $params) ? true : $params['show']);
        $params['tipo']      = (!array_key_exists('tipo', $params) ? 'string' : $params['tipo']);
        $params['decimales'] = (!array_key_exists('decimales', $params) ? 0 : $params['decimales']);
        $params['format']    = (!array_key_exists('format', $params) ? '' : $params['format']);
        $params['unique']    = (!array_key_exists('unique', $params) ? false : $params['unique']);

        if (!in_array($params['tipo'], $tipos)) {
            $this->errors = ['tipo' => 'badType', 'bad' => $params['tipo'], 'permitidos' => $tipos];
        }

        if ($params['tipo'] == 'datetime' || $params['tipo'] == 'date') {
            $this->setTemplate(['datetimepicker']);
        }

        if ($params['tipo'] == 'icono') {
            $this->setTemplate(['iconpicker']);
        }

        if ($params['tipo'] == 'combobox' && empty($params['collect'])) {
            $this->errors = ['tipo' => 'typeCombo'];
        } else if ($params['tipo'] == 'combobox' && !empty($params['collect'])) {
            $collect = $params['collect']->toArray();
            $options = [];
            foreach ($collect as $k) {
                $option =[];
                foreach ($k as $v) {
                    array_push($option, $v);
                }
                array_push($options, $option);
            }
            $params['options'] = $options;
            $params['show']    = false;
        }
        
        if ($params['tipo'] == 'file' && empty($params['filepath'])) {
            $this->errors = ['tipo' => 'filepath'];
        }
        
        if ($params['tipo'] == 'enum' && count($params['enumarray']) == 0) {
            $this->errors = ['tipo' => 'enum'];
        }

        if ($params['tipo'] == 'hidden' && empty($params['value'])) {
            $this->errors = ['tipo' => 'value'];
        }
        
        if (!strpos($params['campo'], ')')) {
            $arr = explode('.', $params['campo']);
            if (count($arr)>=2) {
                $params['campoReal'] = $arr[count($arr) - 1];
            }
        }

        array_push($this->campos, $params);
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
    public function setJoin($tabla, $v1, $operador, $v2)
    {
        array_push($this->joins, ['tabla'=>$tabla,'value1'=>$v1,'operador'=>$operador, 'value2'=>$v2]);
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
    public function setLeftJoin($tabla, $v1, $operador, $v2)
    {
        array_push($this->leftJoins, ['tabla'=>$tabla,'value1'=>$v1,'operador'=>$operador, 'value2'=>$v2]);
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
    public function setWhere($column, $op, $column2)
    {
        array_push($this->wheres, [$column, $op, $column2]);
    }

    public function setWhereAndFn($conditions)
    {
        array_push($this->whereAndFn, $conditions);
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
    public function setOrWhere($column, $op, $column2)
    {
        array_push($this->orWheres, [$column, $op, $column2]);
    }

    /**
     * setOrderBy
     * Define el orden para obtener la data que se mostrara en la vista index.
     *
     * @param  mixed $column
     *
     * @return void
     */
    public function setOrderBy($column)
    {
        array_push($this->orderBy, $column);
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
        $this->allowed($params, $allowed, 'badTypeButton');
        array_push($this->botones, $params);
    }

    /**
     * embedView
     * Pretende embedir una vista dentro de una vista AUN EN DESARROLLO
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
     * Transforma la data segun el estilo visual de boostrap.
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
                        // agregnado estilo visual a los campos booleanos
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
            $data->orderBy($column);
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

        if(!empty(env('APP_FORCE_HTTPS')) && env('APP_FORCE_HTTPS') == true) { 
            $url =  str_replace('http', 'https', $url);
        }
        
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
        return view('krud::training', ['tipo' => 'help']);
    }

    /**
     * index
     * Genera la vista principal del catalogo
     *
     * @return void
     */
    public function index()
    {
        if ($this->model == null) {
            return view('krud::training', ['tipo' => 'setModelo']);
        }

        if (!empty($this->errors)) {
            return view('krud::training', $this->errors);
        }

        $prefix = Route::current()->action['prefix'];
        $layout = $this->getLayout();

        if (!empty($this->view) && $this->view == 'calendar') {
            return $this->setCalendarView($prefix, $layout);
        }

        return $this->setTableView($prefix, $layout);
    }

    private function setCalendarView($prefix, $layout)
    {
        $view  = 'krud.calendar';
        $kmenu = false;
        
        if ($prefix != null && $prefix == 'kk') {
            $view = 'krud::calendar';
            $kmenu = true;
        }

        $permisos = $this->setPermisos(Auth::id());

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

        if ($prefix != null && $prefix == 'kk') {
            $view       = 'krud::index';
            $dtBtnAdd   = 'btn btn-outline-success';
            $dtBtnLiner = 'btn btn-outline-secondary';
            $kmenu      = true;
        }

        return view($view, [
            'titulo'     => $this->titulo,
            'columnas'   => $this->getColumnas($this->getSelectShow()),
            'botones'    => $botones,
            'permisos'   => $this->setPermisos(Auth::id()),
            'ruta'       => $ruta,
            'template'   => $this->template,
            'layout'     => $layout,
            'dtBtnAdd'   => $dtBtnAdd,
            'dtBtnLiner' => $dtBtnLiner,
            'kmenu'      => $kmenu,
        ]);
    }

    /**
     * show
     * Obteniene la data que se mostrara en la tabla principal
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
        $permisos = $this->setPermisos(Auth::id());

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
        $param = null;

        try {
            $id = Crypt::decrypt($request->input('id'));
        } catch (Exception $e) {
            dd($e);
        }

        if ($id != 0) {
            $this->model = $this->model->find($id);
        }
        
        // inputs 
        $in = $request->all();

        // validando los campos que vienen
        foreach ($in as $k => $v) {
            if ($k != '_token' && $k != 'id') {
                
                foreach ($this->campos as $c) {
                    if ($c['campo'] == $k && $c['unique'] == true && $c['edit'] == true && $id == 0) {
                        $validate = $this->model->where($c['campo'], $v)->first();
                        if($validate != null) {
                            Session::flash('type', 'danger');
                            Session::flash('msg', 'El valor del campo '.$c['nombre'].' ya se encuentra registrado en la base de datos.');
                            return redirect()->back();
                        }
                    }

                    if ($c['campo'] == $k && $c['tipo'] == 'date') {
                        $v = $this->toDateMysql($v);
                    }
                    if ($c['campo'] == $k && $c['tipo'] == 'numeric') {
                        $v = str_replace(',', '', $v);
                    }
                    if ($c['campo'] == $k && $c['tipo'] == 'password') {
                        if (!empty($v)) {
                            $v = bcrypt($v);
                        } else {
                            $v = $this->model->{$k};
                        }
                    }
                    if ($c['campo'] == $k && ($c['tipo'] == 'image' || $c['tipo'] == 'file64')) {
                        if ($request->hasFile($c['campo'])) {
                            $file = $request->file($c['campo']);
                            $file = 'data:image/'.strtolower($file->getClientOriginalExtension()).';base64,'.base64_encode(file_get_contents($file));
                            $v    = $file;
                        } else {
                            $v = $this->model->{$k};
                        }
                    }
                    if ($c['campo'] == $k && $c['tipo'] == 'file') {
                        if ($request->hasFile($c['campo'])) {
                            $file     = $request->file($c['campo']);
                            $filename = date('Ymdhis') . mt_rand(1, 1000) . '.' . strtolower($file->getClientOriginalExtension());
                            $path     = public_path() . $c['filepath'];
                            if (!file_exists($path)) {
                                mkdir($path, 0777, true);
                            }
                            $file->move($path, $filename);
                            $v = $file;
                        } else {
                            $v = $this->model->{$k};
                        }
                    }
                    if ($c['campo'] == $k && $c['tipo'] == 'bool') {
                        $v = $v == 'on' ?  true : false;
                    }    
                }
                $this->model->{$k} = $v;
            }
            if ($this->parentid == $k) {
                $param = '?parent='.$v;
            }
        }

        foreach ($this->campos as $c) {
            if (!array_key_exists($c['campo'], $in)) {
                if ($c['tipo'] == 'bool') {
                    $this->model->{$c['campo']} = false;
                }
            }

            if ($c['tipo'] == 'hidden') {
                $this->model->{$c['campo']} = $c['value'] == 'userid' ? Auth::id() : $c['value'];
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

        if(!empty(env('APP_FORCE_HTTPS')) && env('APP_FORCE_HTTPS') == true) {
            $url = str_replace('http', 'https', $request->url());
        }

        return redirect($url.$param);
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
        foreach ($params as $key => $val) { //Validamos que todas las variables del array son permitidas.
            if (!in_array($key, $allowed)) {
                $this->errors = ['tipo' => $badType, 'bad' => $key, 'permitidos' => $allowed];
                break;
            }
        }
    }
}
