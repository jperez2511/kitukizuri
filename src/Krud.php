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

// librerías para base de datos
use Illuminate\Database\QueryException;

// Controllers
use App\Http\Controllers\Controller;

// Models
use App\Models\Municipio;

use Icebearsoft\Kitukizuri\App\Traits\Krud\{
    QueryBuilderTrait,
    UiTrait,
    HelpTrait
};

use Illuminate\Contracts\Encryption\DecryptException;

class Krud extends Controller
{

    use QueryBuilderTrait, UiTrait, HelpTrait;

    // Variable remplazo id
    private $id       = '__id__';

    // Variables de valor único
    private $editId   = null;
    private $parentid = null;
    private $viewHelp = null;

    // Variables en array
    private $rt             = [];
    private $editEmbed      = [];
    private $indexEmbed     = [];
    private $campos         = [];
    private $removePermisos = [];
    private $parents        = [];
    private $orderBy        = [];
    private $groupBy        = [];
    private $orWheres       = [];
    private $whereIn        = [];
    private $whereFn        = [];
    private $whereOrFn      = [];
    private $whereAndFn     = [];
    private $externalData   = [];

    // viables únicas para vista calendario
    private $defaultCalendarView = null;

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
     * removePermisos
     * Según la lista de permisos ingresados, así los remueve de la vista
     * pero las rutas siguen existiendo
     *
     * @param  mixed $permisos
     * @return void
     */
    protected function removePermisos($permisos)
    {
        $this->removePermisos = $permisos;
    }

    /**
     * setWhereIn
     *
     * @param  mixed $column
     * @param  mixed $data
     * @return void
     */
    public function setWhereIn($column, $data)
    {
        $this->whereIn[] = [$column, $data];
    }

    /**
     * setWhereAndFn
     *
     * @param  mixed $conditions
     * @return void
     */
    public function setWhereAndFn($conditions)
    {
        $this->whereAndFn[] = $conditions;
    }

    public function setWhereFn($whereAndOr, $conditions)
    {
        $this->whereFn[] = [$whereAndOr, $conditions];
    }

    /**
     * setWhereOrFn
     *
     * @param  mixed $conditions
     * @return void
     */
    public function setWhereOrFn($conditions)
    {
        $this->whereOrFn[] = $conditions;
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
     * setGroupBy
     * Define como agrupar los elementos de la consulta
     *
     * @param  mixed $column
     *
     * @return void
     */
    public function setGroupBy($column)
    {
        if(is_array($column)) {
            $this->groupBy = $column;
        } else {
            $this->groupBy[] = $column;
        }
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
     * setBotonDT
     * Permite definir botones adicionales en data table
     *
     * @param  mixed $params
     * @return void
     */
    public function setBotonDT($params)
    {
        $allowed = ['text', 'class', 'action'];
        $this->allowed($params, $allowed, $this->typeError[4]);
        $this->botonesDT[] = $params;
    }
    
    /**
     * setDefaultBotonDT
     * Visibilidad a los botones por defecto de dataTable
     *
     * @param  mixed $params
     * @return void
     */
    public function setDefaultBotonDT($params) 
    {
        $allowed = ['name'];
        $this->allowed($params, $allowed, $this->typeError[4]);
        $this->defaultBtnDT[] = $params['name'];
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
    public function embedEditView($controller, $relation, $request)
    {
       $this->editEmbed[] = [$controller, $relation, $request];
    }


    
    /**
     * embedIndexView
     * 
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
     * getDefaultPrefix
     *
     * @return void
     */
    public function getDefaultPrefix()
    {
        return config('kitukizuri.routePrefix') ?? 'krud';
    }

    public function setExternalData($relation, $colName, $data){
        $this->externalData[] = [
            'relation' => $relation,
            'colName'  => $colName,
            'data'     => $data
        ];
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
        $data->addSelect($this->tableName.'.'.$this->keyName.' as '.$this->id);

        // Agregando wehres a la consulta
        // foreach ($this->wheres as $where) {
        //     $data->where($where[0], $where[1], $where[2]);
        // }

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

        // Agrupando And en Or como funcion
        if (!empty($this->whereOrFn)) {
            $data->orWhere(function($q){
                foreach ($this->whereOrFn as $where) {
                    $q->where(...$where);
                }
            });
        }

        // Agrupando funcion
        if (!empty($this->whereFn)) {
            foreach ($this->whereFn as $args) {
                $conditions = $args[1];
                $data->{$args[0]}(function($q) use($conditions) {
                    foreach($conditions as $condition) {
                        $q->{$condition[0]}(...$condition[1]);
                    }
                });
            }
        }

        // generando filtro por whereIn
        foreach($this->whereIn as $whereIn) {
            $data->whereIn($whereIn[0], $whereIn[1]);
        }

        // Agregando el orden para mostrar los datos
        foreach ($this->orderBy as $column) {
            $data->orderBy(...$column);
        }

        if(!empty($this->groupBy)) {
            $data->groupBy($this->groupBy);
        }

        $count = $data->count();

        // validando si existe un limite para obtenr los datos
        $data->take($limit);

        // validando si hay un offset a utilizar
        $data->skip($offset);

        $data = $data->get();

        if(!empty($this->externalData)) {
            foreach ($data as $value) {
                foreach($this->externalData as $extData){
                    $relation = $extData['relation'];
                    $tmp      = $extData['data']->firstWhere($relation, $value->{$relation});
                    $value->{$extData['colName']} = $tmp[$extData['colName']] ?? '';
                }
            }
        }

        return [$data, $count];
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
    private function makeArrayData($data = null)
    {
        for ($i = 0; $i< count($this->campos); $i++) {
            
            $isSelect2 = $this->campos[$i]['tipo'] == 'select2';
            $isSelect  = $this->campos[$i]['tipo'] == 'select';
            $campoReal = array_key_exists('campoReal', $this->campos[$i]) ? $this->campos[$i]['campoReal'] : $this->campos[$i]['campo'];
            
            // validando si es un select
            if($isSelect2 == true || $isSelect == true) {
                // validando si tiene multiple o no
                if($this->campos[$i]['htmlAttr'] !== null && $this->campos[$i]['htmlAttr']->has('multiple')) {
                    // validando el formato de los valores
                    if($this->campos[$i]['format'] == 'json') {
                        if($data != null){
                            $value = $data->{$campoReal};
                            $this->campos[$i]['value'] =  json_encode($value);
                        }
                    } else if ($this->campos[$i]['format'] == 'table') {
                        $table = $this->campos[$i]['destination'];
                    }
                }
            } else if($data != null) {
                $value = $data->{$campoReal};
                $this->campos[$i]['value'] = is_array($value) ? json_encode($value) : $value;
            }
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

    private function setTableView($prefix, $layout)
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

        $permisos = $this->getPermisos(Auth::id());

        if(!empty($this->removePermisos)) {
            $permisos = array_values(array_diff($permisos, $this->removePermisos));
        }

        return view($view, [
            'titulo'     => $this->titulo,
            'columnas'   => $this->getColumnas($this->getSelectShow()),
            'botones'    => $botones,
            'permisos'   => $permisos,
            'ruta'       => $ruta,
            'template'   => $this->template,
            'layout'     => $layout,
            'dtBtnAdd'   => $dtBtnAdd,
            'dtBtnLiner' => $dtBtnLiner,
            'embed'      => $this->indexEmbed,
            'kmenu'      => $kmenu,
            'vBootstrap' => $vBootstrap,
            'botonesDT'  => $this->botonesDT
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
        
        $prefix        = Route::current()->action['prefix'];
        $prefixDefault = $this->getDefaultPrefix();

        if ($prefix != null && $prefix == $prefixDefault) {
            $icnEdit         = 'mdi mdi-pencil-outline';
            $icnDelete       = 'mdi mdi-trash-can-outline';
            $icnOptions      = 'mdi mdi-plus';
            $classBtnEdit    = 'btn-outline-primary';
            $classBtnDelete  = 'btn-outline-danger';
            $classBtnOptions = 'btn-outline-warning';
        }

        $response = [];
        $permisos = $this->getPermisos(Auth::id());
        if(!empty($this->removePermisos)) {
            $permisos = array_values(array_diff($permisos, $this->removePermisos));
        }


        //Contador de datos para el render
        $response['draw'] = intval($request->draw);
        
        // Datos para paginado de datos
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
                    $boton['url'] = str_replace('{id}', Crypt::encrypt($item['__id__']), $boton['url']);
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
                    data-toggle="tooltip" data-placement="left" title="'.__('Editar').'" 
                    onclick="edit(\''.Crypt::encrypt($item['__id__']).'\')" 
                    class="btn btn-xs btn-sm '.$classBtnEdit.'">
                        <span class="'.$icnEdit.'"></span>
                    </a>';
            }

            if(in_array('destroy', $permisos)) {
                $btns .= '<a 
                    href="javascript:void(0)"
                    data-toggle="tooltip" data-placement="left" title="'.__('Eliminar').'" 
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
            $id       = Crypt::decrypt($id);
            $parentid = $request->get('parent');
        } catch (Exception $e) {
            dd($e);
        }
        
        $this->editId = $id;

        if ($id != 0) {
            $data   = $this->model->find($id);
            $titulo = 'Editar '.$this->titulo;
            $this->makeArrayData($data);
        } else {
            $data   = null;
            $titulo = 'Agregar '.$this->titulo;
        }

        $url    = $this->getUrl($request->url());
        $layout = $this->getLayout();

        $prefix        = Route::current()->action['prefix'];
        $view          = 'krud.edit';
        $kmenu         = false;
        $prefixDefault = $this->getDefaultPrefix();

        if ($prefix != null && $prefix == $prefixDefault) {
            $view  = 'krud::edit';
            $kmenu = true;
        }

        $uriQuery = '?';
        $uriItems = [];
        foreach($this->parents as $parent) {
            if($parent['editable'] !== true) {
                $uriItems[] = $parent['nombre'].'='.$request->{$parent['value']};
            }
        }

        $uriQuery .= implode('&', $uriItems);

        $urlBack = $url;
        if(!empty($uriItems)) {
            $urlBack .= $uriQuery;
        }

        return view($view, [
            'titulo'   => $titulo,
            'campos'   => $this->campos,
            'action'   => $url,
            'id'       => Crypt::encrypt($id),
            'rt'       => $this->rt,
            'embed'    => $this->editEmbed,
            'parent'   => $this->parentid,
            'parentid' => $parentid,
            'parents'  => $this->parents,
            'layout'   => $layout,
            'kmenu'    => $kmenu,
            'urlBack'  => $urlBack,
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

        // validando campos
        $request->validate($this->validations);

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

            if(empty($campo)) {
                continue;
            }

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
                    $valor = $this->model->{$nombreCampo};
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
                    $valor = $this->model->{$nombreCampo};
                }
            } else if ($campo['tipo'] == 'bool') {
                $valor = $valor == 'on' || $valor == 0 || $valor == 1 ;
            } else if ($campo['tipo'] == 'hidden') {
                $this->model->{$nombreCampo} = $valor == 'userid' ? Auth::id() : $valor;
            } else if ($campo['tipo'] == 'select' || $campo['tipo'] == 'select2' || $campo['tipo'] == 'comobox') {
                if($campo['htmlAttr']->has('multiple')) {
                    if($campo['format'] == 'json') {
                        $valor = array_map('intval', $valor);
                    }
                }
            }
            $this->model->{$nombreCampo} = $valor;
        }

        // validando campos sin valor 
        foreach ($this->campos as $campo) {
            if (!is_string($campo['campo'])) {
                continue;
            }

            $campoReal = !empty($campo['campoReal']) ? $campo['campoReal'] : $campo['campo'];
            if (!array_key_exists($campoReal, $requestData)) {
                if ($campo['tipo'] == 'bool') {
                    $this->model->{$campoReal} = false;
                } else if ($campo['tipo'] == 'select' || $campo['tipo'] == 'select2' || $campo['tipo'] == 'comobox') {
                    if($campo['htmlAttr']->has('multiple')) {
                        if($campo['format'] == 'json') {
                            $this->model->{$campoReal} = null;
                        }
                    }
                }
            }
        }

        // recorriendo parents para agregar a la base de datos
        $uriQuery = '?';
        $uriItems = [];

        foreach($this->parents as $parent) {
            if($parent['editable'] === true) {

                function isValueEncrypted($value) {
                    try {
                        // Intentar desencriptar y ver si no hay errores
                        $value = Crypt::decrypt($request->{$parent['nombre']});
                    } catch (DecryptException $e) {
                        // Si hay un error, probablemente el valor no está encriptado
                        $value = $request->{$parent['nombre']};
                    }
                }

                $this->model->{$parent['nombre']} = $value;
            }
            
            $uriItems[] = $parent['value'].'='.$request->{$parent['nombre']};;
        }

        $uriQuery .= implode('&', $uriItems);

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
        if(!empty($uriItems)) {
            $url .= $uriQuery;
        }

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
