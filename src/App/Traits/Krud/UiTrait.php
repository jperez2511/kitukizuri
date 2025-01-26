<?php

namespace Icebearsoft\Kitukizuri\App\Traits\Krud;

use Auth;
use Illuminate\Database\Query\Expression;

trait UiTrait
{
    use FieldTrait, ChartTrait;
    
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
        $allowed = ['table', 'calendar', 'chart'];

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
        $prevUi = config('kitukizuri.prevUi');

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
                            if(!empty($prevUi) && $prevUi == true) {
                                $v = '<span class="'.($prefix != null && $prefix == $prefixDefault ? 'label label' : config('kitukizuri.badge')).'-'.($v ? 'success' : 'default').'">'.($v ? __('Si') : 'No').'</span>';
                            } else {
                                $v = '<span class="'.config('kitukizuri.badge').'-'.($v ? 'success' : 'gray').'">'.($v ? __('Si') : 'No').'</span>';
                            }
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
        $allowed = ['nombre', 'name', 'url', 'class', 'icon'];
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

    protected function setChartView($pefix, $layout)
    {
        $view = 'krud.chart';

        // filtrando los campos que filtros
        $campos = array_filter($this->campos, function($c) {
            return !empty($c['isFilter']) && $c['isFilter'] == true;
        });
        
        return view($view, [
            'layout' => $layout,
            'titulo' => $this->titulo,
            'campos' => $campos,
            'ruta'   => $this->getModuloRuta()
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