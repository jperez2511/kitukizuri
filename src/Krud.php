<?php

namespace Icebearsoft\Kitukizuri;

// Dependencias
use DB;
use Route;
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
    private $model    = null;
    private $titulo   = null;
    private $editId   = null;
    private $layout   = null;
    private $parentid = null;

    // variales en array
    private $rt       = [];
    private $joins    = [];
    private $embed    = [];
    private $wheres   = [];
    private $campos   = [];
    private $botones  = [];
    private $parents  = [];
    private $orderBy  = [];
    private $template = [
        'datatable',
    ];

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
            'campo','nombre','edit','show','tipo','class','default','reglas','reglasmensaje', 'decimales',
            'collect','enumarray','filepath', 'filewidth','fileheight','target', 'value'
        ];

        $tipos = [
            'string','numeric', 'date','datetime', 'bool','combobox', 'password','enum',
            'file','file64','image', 'textarea','url','summernote', 'hidden', 'icono'
        ];

        $this->allowed($params, $allowed);

        $params['nombre']    = (!array_key_exists('nombre', $params) ? str_replace('_', ' ', ucfirst($params['campo'])) : $params['nombre']);
        $params['edit']      = (!array_key_exists('edit', $params) ? true : $params['edit']);
        $params['show']      = (!array_key_exists('show', $params) ? true : $params['show']);
        $params['tipo']      = (!array_key_exists('tipo', $params) ? 'string' : $params['tipo']);
        $params['decimales'] = (!array_key_exists('decimales', $params) ? 0 : $params['decimales']);
        $params['collect']   = (!array_key_exists('collect', $params) ? '' : $params['collect']);
        $params['filepath']  = (!array_key_exists('filepath', $params) ? '' : $params['filepath']);
        $params['value']     = (!array_key_exists('value', $params) ? '' : $params['value']);

        if (!array_key_exists('campo', $params)) {
            dd('"campo" es un parametro requerido');
        }

        if ($params['tipo'] == 'datetime' || $params['tipo'] == 'date') {
            $this->setTemplate(['datetimepicker']);
        }

        if ($params['tipo'] == 'icono') {
            $this->setTemplate(['iconpicker']);
        }


        if (!in_array($params['tipo'], $tipos)) {
            dd('El tipo configurado (' . $params['tipo'] . ') no existe! solamente se permiten: ' . implode(', ', $tipos));
        }

        if ($params['tipo'] == 'combobox' && ($params['collect'] == '')) {
            dd('Para el tipo combobox el collection es requerido');
        }
        if ($params['tipo'] == 'combobox') {
            $params['show'] = false;
        }
        if ($params['tipo'] == 'file' && $params['filepath'] == '') {
            dd('Para el tipo file falta parametro filepath');
        }
        //if($params['tipo'] == 'image' && $params['filepath'] == '') dd('Para el tipo image para parametro filepath');
        if ($params['tipo'] == 'enum' && count($params['enumarray']) == 0) {
            dd('Para el tipo enum el enumarray es requerido');
        }
        if ($params['tipo'] == 'hidden' && $params['value'] == '') {
            dd('Para el tipo hidden requiere agregar value');
        }

        if (!strpos($params['campo'], ')')) {
            $arr = explode('.', $params['campo']);
            if (count($arr)>=2) {
                $params['campoReal'] = $arr[count($arr) - 1];
            }
        }

        //validando si las llaves existen
        if (!array_key_exists('show', $params)) {
            $params['show'] = true;
        }

        if (array_key_exists('tipo', $params)) {
            if (!in_array($params['tipo'], $tipos)) {
                dd('tipo: ' . $params['tipo'] . ' no es un tipo valido.');
            } elseif (in_array($params['tipo'], $tipos) && $params['tipo'] == 'combobox') {
                $warning = !array_key_exists('collect', $params) ? dd('comobox requiere del campo collect') : null;
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
            }
        } else {
            $params['tipo'] = 'string';
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
     * setWhere
     * Define las condiciones al momento de obtener la data y mostrarla en la vista index.
     *
     * @param  mixed $column
     * @param  mixed $op
     * @param  mixed $column2
     *
     * @return void
     */
    public function setWhere($column, $op='=', $column2)
    {
        array_push($this->wheres, [$column, $op, $column2]);
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
        $this->allowed($params, $allowed);
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
    private function transformData($data)
    {
        $i = 0;
        foreach ($data as $a) {
            foreach ($a as $k => $v) {
                foreach ($this->campos as $cn => $cv) {
                    if ($k == $cv['campo']) {
                        // agregnado estilo visual a los campos booleanos
                        if ($cv['tipo'] == 'bool') {
                            $v = '<span class="label label-'.($v ? 'success' : 'default').'">'.($v ? 'Si' : 'No').'</span>';
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

        // Agregando wehres a la consulta
        foreach ($this->wheres as $where) {
            $data->where($where[0], $where[1], $where[2]);
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
    private function getColumnas($campos)
    {
        $s = [];
        for ($i = 0; $i <count($campos); $i++) {
            if ($campos[$i]['show'] == true) {
                array_push($s, $campos[$i]);
            }
        }
        return array_map(function ($c) {
            return $c['nombre'];
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
        return implode('/', $url);
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

        return implode('/', $ruta);
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
            dd('El Modelo es requerido');
        }

        $botones = $this->botones;
        if (count($botones) > 1) {
            $botones = json_encode($botones);
        }
        
        $ruta = $this->getModuloRuta();
        
         $layout = $this->getLayout();

        return view('krud.index', [
            'titulo'   => $this->titulo,
            'columnas' => $this->getColumnas($this->getSelectShow()),
            'botones'  => $botones,
            'permisos' => $this->setPermisos(Auth::id()),
            'ruta'     => $ruta,
            'template' => $this->template,
            'layout'   => $layout
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
        $icnEdit    = config('kitukizuri.edit');
        $icnDelete  = config('kitukizuri.delete')
        $icnOptions = config('kitukizuri.options');
        
        $prefix = Route::current()->action['prefix'];

        if ($prefix != null && $prefix == 'kk') {
            $icnEdit    = 'zmdi zmdi-edit';
            $icnDelete  = 'zmdi zmdi-delete';
            $icnOptions = 'fa fa-plus';
        }

        $response = [];
        $permisos = $this->setPermisos(Auth::id());

        //Contador de datos a renderizar
        $response['draw'] = intval($request->draw);
        
        // Datos para paginacion
        $limit   = $request->length != '' ? $request->length : 10;
        $offset  = $request->start ? $request->start : 0;
        $columns = $this->getColumnas($this->getSelectShow());

        // Obteniendo los datos de la tabla
        $data = $this->getData($limit, $offset);

        //total de datos obtenidos
        $response['data'] = [];
        $response['recordsTotal'] = $data[1];
        $response['recordsFiltered'] = $response['recordsTotal'];

        $data = $this->transformData($data[0]->toArray());

        foreach ($data as $item) {
            // string de botones a imprimir
            $btns = '';
            
            // Validando si los botones son mas de uno para renderdizar modal
            if (!empty($this->botones) && count($this->botones) > 1) {
                //recorriendo todos los botones extras
                foreach($this->botones as $boton) {
                    $boton['url'] = str_replace('{id}', $item['__id__'], $boton['url']);
                    $btns .= '<a href="'.$boton['url'].'" class="btn btn-xs btn-sm btn-'.$boton['class'].'"><span class="'.$boton['icon'].'"></span></a>';
                }
            } else {
                $btns .= '<a 
                    href="javascript:void(0)" 
                    class="btn btn-xs btn-sm btn-warning" 
                    onclick="opciones('.$item['__id__'].')">
                        <span class="'.$icnOptions.'"></span>
                    </a>';
            }

            //Agregando boton para Editar
            if(in_array('edit', $permisos)) {
                $btns .= '<a 
                    href="javascript:void(0)" 
                    onclick="edit(\''.Crypt::encrypt($item['__id__']).'\')" 
                    class="btn btn-xs btn-sm btn-primary">
                        <span class="'.$icnEdit.'"></span>
                    </a>';
            }

            if(in_array('destroy', $permisos)) {
                $btns .= '<a 
                    href="javascript:void(0)" 
                    onclick="destroy(\''.Crypt::encrypt($item['__id__']).'\')" 
                    class="btn btn-xs btn-sm btn-danger">
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

        $url = $this->getUrl($request->url());

        $layout = $this->getLayout();

        return view('krud.edit', [
            'titulo'   => $titulo,
            'campos'   => $this->campos,
            'action'   => $url,
            'id'       => Crypt::encrypt($id),
            'rt'       => $this->rt,
            'embed'    => $this->embed,
            'parent'   => $this->parentid,
            'parentid' => $parentid,
            'parents'  => $this->parents,
            'layout'   => $layout            
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
        foreach ($request->all() as $k => $v) {
            if ($k != '_token' && $k != 'id') {
                foreach ($this->campos as $c) {
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
                            
                            //$filename = date('Ymdhis') . mt_rand(1, 1000) . '.' . strtolower($file->getClientOriginalExtension());
                            //$path     = public_path() . $campo['filepath'];

                            // if (!file_exists($path)) {
                            // mkdir($path, 0777, true);
                            // }

                            // $file->move($path, $filename);
                            $v = $file;
                        } else {
                            $v = $this->model->{$k};
                        }
                    }

                    if ($c['campo'] == $k && $c['tipo'] == 'file') {
                        if ($request->hasFile($c['campo'])) {
                            $file = $request->file($c['campo']);
                            // $file = 'data:image/'.strtolower($file->getClientOriginalExtension()).';base64,'.base64_encode(file_get_contents($file));
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
            if ($c['tipo'] == 'hidden') {
                $this->model->{$c['campo']} = $c['value'] == 'userid' ? Auth::id() : $c['value'];
            }
        }

        $this->model->save();
        return redirect($request->url().$param);
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
    private function allowed($params, $allowed)
    {
        foreach ($params as $key => $val) { //Validamos que todas las variables del array son permitidas.
            if (!in_array($key, $allowed)) {
                dd($key . ' no es un parametro permitido ' . implode(', ', $allowed));
            }
        }
    }
}
