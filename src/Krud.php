<?php

namespace Icebearsoft\Kitukizuri;

// Dependencias
use DB;
use Log;
use Route;
use Session;
use Carbon\Carbon;
use Mockery\Exception;

// librerías del framework
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;


use MongoDB\Laravel\Connection as MC;

// librerías para base de datos
use Illuminate\Database\QueryException;
use \Illuminate\Database\Query\Expression;

// Controllers
use App\Http\Controllers\Controller;

// Models
use App\Models\Municipio;
use Icebearsoft\Kitukizuri\App\Models\{
    SelectValues,
    TableValues
};

use Icebearsoft\Kitukizuri\App\Traits\Krud\{
    QueryBuilderTrait,
    UiTrait,
    HelpTrait
};

use Illuminate\Contracts\Encryption\DecryptException;

class Krud extends Controller
{

    use QueryBuilderTrait, UiTrait, HelpTrait;

    // Variables de valor único
    private $editId   = null;
    private $parentid = null;
    private $viewHelp = null;

    // Variables en array
    private $editEmbed      = [];
    private $removePermisos = [];
    private $parents        = [];
    private $storeFunctions = [];

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
     * setStoreFunction
     *
     * @param  mixed $function
     * @return void
     */
    public function setStoreFunction(callable $function)
    {
        $this->storeFunctions[] = $function;
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
     * getDefaultPrefix
     *
     * @return void
     */
    public function getDefaultPrefix()
    {
        return config('kitukizuri.routePrefix') ?? 'krud';
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
            if ($campos[$i]['show'] == true || $campos[$i]['show'] == 'soft') {
                array_push($s, $campos[$i]);
            }
        }

        if($this->model->getConnection() instanceof MC){
            return array_map(function ($c) {
                return $c['campo'];
            }, $s);
        } else {
            return array_map(function ($c) {
                return DB::raw($c['campo']);
            }, $s);
        }
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
            if ($campos[$i]['show'] === true) {
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
                        $locationTableArray =  explode('.', $this->campos[$i]['campo']);
                        $args = [
                            $data->getKey(),
                            $this->campos[$i]['columnParent'],
                            $locationTableArray[0],
                            $locationTableArray[1],
                        ];

                        $values = SelectValues::values(...$args);
                        $this->campos[$i]['value'] = json_encode($values);
                    }
                }
            } else if($this->campos[$i]['tipo'] == 'table') {
                $locationTableArray =  explode('.', $this->campos[$i]['campo']);
                $args = [
                    $data->getKey(),
                    $this->campos[$i]['columnParent'],
                    $locationTableArray[0],
                    $locationTableArray[1],
                ];

                $values = TableValues::values(...$args);
                $this->campos[$i]['collect'] = json_encode($values);
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
            $icnEdit         = 'fa-duotone fa-solid fa-pencil';
            $icnDelete       = 'fa-duotone fa-solid fa-trash-can';
            $icnOptions      = 'fa-duotone fa-solid fa-grid';
            $classBtnEdit    = 'btn-sm btn-outline-primary';
            $classBtnDelete  = 'btn-sm btn-outline-danger';
            $classBtnOptions = 'btn-sm btn-outline-warning';
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
        
        if (!empty($request->search['value']) && empty($this->searchBy)) {
            $this->queryBuilder->where(function($q) use($columns, $request){
                foreach ($columns as $column) {
                    if (is_string($column) && strpos($column, 'as')) {
                        $column = trim(explode('as', $column)[0]);
                    } else if($column instanceof Expression){
                        $reflection = new \ReflectionObject($column);
                        $property = $reflection->getProperty('value');
                        $property->setAccessible(true); // Hace la propiedad accesible
                        $value = $property->getValue($column);

                        if(strpos($value, 'as')) {
                            $column = trim(explode('as', str_replace('\'', '\\\'', $value))[0]);
                        }

                    }
                    $q->orWhere($column, 'like', '%'.$request->search['value'].'%');
                }
            });   
        } else if(!empty($request->search['value']) && !empty($this->searchBy)){
            $this->queryBuilder->where(function($q) use($request){
                foreach ($this->searchBy as $column) {
                    $q->orWhere($column, 'like', '%'.$request->search['value'].'%');
                }
            });
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
                    data-toggle="tooltip" data-placement="left" title="'.__('Options').'" 
                    href="javascript:void(0)" 
                    class="btn '.$classBtnOptions.'" 
                    onclick="opciones(\''.Crypt::encrypt($item[$this->keyName]).'\')">
                        <i class="'.$icnOptions.'"></i>
                    </a>';
            } else {
                foreach($this->botones as $boton) {
                    $boton['url'] = str_replace('{id}', Crypt::encrypt($item[$this->keyName]), $boton['url']);
                    $btns .= '<a 
                        data-toggle="tooltip" data-placement="left" title="'.$boton['nombre'].'" 
                        href="'.$boton['url'].'" 
                        class="btn btn-'.$boton['class'].'">
                            <i class="'.$boton['icon'].'"></i>
                        </a>';
                }
            }

            //Agregando boton para Editar
            if(in_array('edit', $permisos)) {
                $btns .= '<a 
                    href="javascript:void(0)"
                    data-toggle="tooltip" data-placement="left" title="'.__('Edit').'" 
                    onclick="edit(\''.Crypt::encrypt($item[$this->keyName]).'\')" 
                    class="btn '.$classBtnEdit.'">
                        <i class="'.$icnEdit.'"></i>
                    </a>';
            }

            if(in_array('destroy', $permisos)) {
                $btns .= '<a 
                    href="javascript:void(0)"
                    data-toggle="tooltip" data-placement="left" title="'.__('Delete').'" 
                    onclick="destroy(\''.Crypt::encrypt($item[$this->keyName]).'\')" 
                    class="btn '.$classBtnDelete.'">
                        <i class="'.$icnDelete.'"></i>
                    </a>';
            }
            
            $item['btn'] = $btns;

            unset($item[$this->keyName]);

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
     * @return void
     */
    public function edit($id, Request $request)
    {
        try {
            $id       = Crypt::decrypt($id);
            $parentid = $request->get('parent');
        } catch (Exception $e) {
            Log::error($e);
            abort(404);
        }
        
        $this->editId = $id;

        if ($id != 0) {
            $data   = $this->model->find($id);
            $titulo = __('Edit').' '.$this->titulo;
            $this->makeArrayData($data);
        } else {
            $data   = null;
            $titulo = __('Create').' '.$this->titulo;
        }

        $url    = $this->getUrl($request->url());
        

        $prefix        = Route::current()->action['prefix'];
        $view          = 'krud.edit';
        $kmenu         = false;
        $prefixDefault = $this->getDefaultPrefix();

        if ($prefix != null && $prefix == $prefixDefault) {
            $view  = 'krud::edit';
            $kmenu = true;
        }

        if(config('kitukizuri.prevUi') === true) {
            $vars = \usePrevUi('edit');
            $view = $vars['kitukizuri'];
            $layout = $vars['krud'];
        } else {
            $layout = $this->getLayout();
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
        $dataOtherLocation = [];

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
                if($validate){
                    return $this->buildMsg('danger', 'El valor del campo '.$c['nombre'].' ya se encuentra registrado en la base de datos.');
                }
            } else if ($campo['tipo'] == 'date') {
                $valor = $this->toDateMysql($valor);
            } else if ($campo['tipo'] == 'numeric') {
                $valor = str_replace(',', '', $valor);
                if($valor == '') {
					$valor = null;
				}
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
                    // validando format
                    if($campo['format'] == 'json') {
                        $valor = array_map('intval', $valor);
                    }

                    if(
                        !empty($campo['htmlAttr']) && 
                        $campo['htmlAttr']->has('multiple') && 
                        $campo['htmlAttr']['multiple'] == true && 
                        ($campo['campo'] instanceof Expression) == false &&
                        strrpos($campo['campo'], '.') != false
                    ) {
                        $locationTableArray =  explode('.', $campo['campo']);
                        $localTable = $this->model->getTable();
                        //validando si las tablas son difrentes o son las mismas 
                        if($locationTableArray[0] != $localTable)
                        {
                            $dataOtherLocation[] = [
                                'value' => $valor, 
                                'table' => $locationTableArray[0],
                                'column' => $locationTableArray[1],
                                'columnParent' => $campo['columnParent']
                            ];
                            continue;
                        }
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

        if($this->parentid != null && ($request->has($this->parentid) && $request->{$this->parentid} != null)) {

            try {
                $parentValue = Crypt::decrypt($request->{$this->parentid});
            } catch (Exception $e) {
                \abort(404);
            }

            $this->model->{$this->parentid} = $parentValue;
            $uriItems[] = 'parent='.$request->{$this->parentid};
        }

        foreach($this->parents as $parent) {
            if($parent['editable'] === true) {

                try {
                    // Intentar desencriptar y ver si no hay errores
                    $value = Crypt::decrypt($request->{$parent['nombre']});
                } catch (DecryptException $e) {
                    // Si hay un error, probablemente el valor no está encriptado
                    $value = $request->{$parent['nombre']};
                }

                $this->model->{$parent['nombre']} = $value;
            }
            
            $uriItems[] = $parent['value'].'='.$request->{$parent['nombre']};;
        }

        $uriQuery .= implode('&', $uriItems);

        try {
            $this->model->save();

            if(!empty($dataOtherLocation)) {
                foreach($dataOtherLocation as $values) {
                    $args = [
                        $this->model->getKey(),
                        $values['table'],
                        $values['columnParent']
                    ];
                    $existsElements = SelectValues::exists(...$args);
                    if($existsElements === true){
                        SelectValues::destroy(...$args);
                    }
                    
                    $args[] = $values['column'];
                    $args[] = $values['value'];

                    SelectValues::save(...$args);
                }
            }

            if(!empty($this->storeFunctions)) {
                foreach($this->storeFunctions as $function) {
                    $function($this->model);
                }
            }

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

    
}
