<?php

namespace Icebearsoft\Kitukizuri\App\Http\Controllers;

use Auth;
use Route;

// Models
use Icebearsoft\Kitukizuri\App\Models\{
    Menu,
    UsuarioRol
};

class MenuController
{
    // String para almacenar el menu Final
    private $tree = null;

    // String para conocer la version de bootstrap
    private $vBootstrap = null;
    
    // Array para los módulos que tiene acceso el usuario
    private $permisos = [];

    // Template por defecto para el menu en Krud
    private $template = [
        'menu' => [
            'ul' => [
                'id' => 'side-menu',
                'class' => 'metismenu list-unstyled'
            ],
            'li-parent' => [
                'class' => '',
                'layout' => 
                    '<a href="{{url}}" class="has-arrow waves-effect" aria-expanded="false">
                        {{iconFormat}}
                        <span class="nav-title">{{label}}</span>
                    </a>',
                'layout-without-son' => 
                    '<a href="{{url}}" class="waves-effect">
                        {{iconFormat}}
                        <span class="nav-title">{{label}}</span>
                    </a>',
            ],
            'li-jr' => [
                'class' => '',
                'layout' => 
                    '<a href="{{url}}">
                        {{iconFormat}}
                        <span class="nav-title">{{label}}</span>
                    </a>',
            ],
            'ul-jr' => [
                'aria-expanded'=>'false',
                'class' => 'sub-menu mm-collapse'  
            ],
            'ul-jr-divStyle' => [
                'class' => 'sub-menu'
            ]
        ]    
    ];

    private $uiElements = [];


    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $prefix = Route::current()->action['prefix'];
        $defaultPrefix = $this->getDefaultPrefix();

        if($prefix == $defaultPrefix && config('kitukizuri.prevUi') == true) {
            $this->uiElements['ul.class']                     = $this->template['menu']['ul']['class'];
            $this->uiElements['ul.id']                        = $this->template['menu']['ul']['id'];
            $this->uiElements['ul-jr']                        = $this->template['menu']['ul-jr'];
            $this->uiElements['li-parent.layout']             = $this->template['menu']['li-parent']['layout'];
            $this->uiElements['li-parent.class']              = $this->template['menu']['li-parent']['class'];
            $this->uiElements['li-parent.layout-without-son'] = $this->template['menu']['li-parent']['layout-without-son'];
            $this->uiElements['li-jr.layout']                 = $this->template['menu']['li-jr']['layout'];
            $this->uiElements['li-jr.class']                  = $this->template['menu']['li-jr']['class'];
        } else {
            $this->uiElements['ul.class']                     = config('kitukizuri.menu.ul.class');
            $this->uiElements['ul.id']                        = config('kitukizuri.menu.ul.id');
            $this->uiElements['ul-jr']                        = config('kitukizuri.menu.ul-jr');
            $this->uiElements['li-parent.layout']             = config('kitukizuri.menu.li-parent.layout');
            $this->uiElements['li-parent.class']              = config('kitukizuri.menu.li-parent.class');
            $this->uiElements['li-parent.layout-without-son'] = config('kitukizuri.menu.li-parent.layout-without-son');
            $this->uiElements['li-jr.layout']                 = config('kitukizuri.menu.li-jr.layout');
            $this->uiElements['li-jr.class']                  = config('kitukizuri.menu.li-jr.class');

            $this->vBootstrap = config('kitukizuri.vBootstrap');
        }

        //abriendo tag ul con los estilos personalizables
        $this->tree .= '<ul class="'.$this->uiElements['ul.class'].'" id="'.$this->uiElements['ul.id'].'">';
        
    }


    private function getDefaultPrefix()
    {
        return config('kitukizuri.routePrefix') ?? 'krud';
    }

    /**
     * construirMenu
     *
     * @return void
     */
    public function construirMenu()
    {
        // Obteniendo los permisos del rol
        $this->permisos = usuarioRol::getModuloPermisoID(Auth::id(), 'show')
            ->pluck('modulopermisoid')
            ->toArray();        

        // obteniendo los elementos del menu que no tienen padreid
        $nodos = Menu::getPapas();
        
        // recorriendo para hacer las validaciones
        foreach ($nodos as $nodo) {
            $visibilidad = property_exists($nodo, 'show') ? $nodo->show : true;
            
            if((in_array($nodo->modulopermisoid, $this->permisos) || $nodo->modulopermisoid == null) && $visibilidad) {
                $this->getNodos($nodo);	
            }
        }
        
        // cerrando ul
        $this->tree .= '</ul>';

        // retornando el menu ya construido
        return $this->tree;
    }
    
    /**
     * getNodos
     * Método que construye el menu con el estilo predeterminado
     * 
     * @return void
     */
    public function getNodos($nodo) 
    {
        // obteniendo los hijos del elemento actual
        $hijos = Menu::getHijos($nodo->menuid);

        if ($hijos->count() > 0) {
            
            if ($nodo->modulopermisoid == null) {
                // valida si alguno de los hijos tiene permisos
                $result = $this->hijosPermisos($nodo);
                if ($result != 1) {
                    return 0;    
                }
            }

            //aplicando formato para el li cuando es padre
            $formato = $this->uiElements['li-parent.layout'];
                
            // remplazando url
            $formato = str_replace('{{url}}', ($hijos->count() > 0 ? '#' : route($nodo->ruta.'.index')), $formato);

            // remplazando icono
            if(!empty($nodo->icono)){
                if(!empty(config('kitukizuri.iconFormat'))){
                    $iconFormat = config('kitukizuri.iconFormat');
                    $iconFormat = str_replace('{{icono}}', $nodo->icono, $iconFormat);
                    $formato    = str_replace('{{iconFormat}}', $iconFormat, $formato );
                } else {
                    $formato = str_replace('{{icono}}', $nodo->icono, $formato );
                }
            } else {
                if(!empty(config('kitukizuri.iconFormat'))){
                    $formato    = str_replace('{{iconFormat}}', '', $formato );
                } else {
                    $formato = str_replace('{{icono}}', $nodo->icono, $formato );
                }
                $formato = str_replace('{{icono}}', $nodo->icono, $formato );
            }

            // remplazando label
            $formato = str_replace('{{label}}', __($nodo->etiqueta), $formato);

            $formato = str_replace('{{target}}', $nodo->menuid, $formato);

            $this->tree .= '<li class="'.$this->uiElements['li-parent.class'].'">'.$formato;
            
            if ($hijos->count() > 0) {
                
                if ($this->vBootstrap == '4.1') {
                    $this->tree .= '<div ';
                    foreach (config('kitukizuri.menu.ul-jr') as $key => $value) {
                        $value = str_replace('{{parent}}', '#'.$nodo->menuid, $value);
                        $value = str_replace('{{target}}', $nodo->menuid, $value);
                        $this->tree .= $key.'="'.$value.'"';
                    }
                    $this->tree .= '>';

                    $this->tree .= '<div ';
                    foreach (config('kitukizuri.menu.ul-jr-divStyle') as $key => $value) {
                        $this->tree .= $key.'="'.$value.'"';
                    }
                    $this->tree .= '>';
                    
                    foreach ($hijos as $hijo) {
                        if ($hijo->modulopermisoid == null || in_array($hijo->modulopermisoid, $this->permisos)) {
                            $this->getNodos($hijo);
                        }
                    }
                    
                    // Cerrando las etiquetas
                    $this->tree .= '</div>';
                    $this->tree .= '</div>';
                } else {
                    // Agregando ul para los hijos
                    $this->tree .= '<ul ';
                    foreach ($this->uiElements['ul-jr'] as $key => $value) {
                        $this->tree .= $key.'="'.$value.'"';
                    }
                    $this->tree .= '>';
                    
                    foreach ($hijos as $hijo) {
                        if ($hijo->modulopermisoid == null || in_array($hijo->modulopermisoid, $this->permisos)) {
                            $this->getNodos($hijo);
                        }
                    }
                    
                    // Cerrando las etiquetas
                    $this->tree .= '</ul>';
                }
            }
            
            $this->tree .= '</li>';
        } else if ($hijos->count() < 1 && $nodo->padreid == null) {
            if ($nodo->modulopermisoid == null) {
                return 0;
            }
             //aplicando formato para el li cuando es padre
             $formato = $this->uiElements['li-parent.layout-without-son'];
                
             // remplazando url
             $formato = str_replace('{{url}}', ($hijos->count() > 0 ? '#' : route($nodo->ruta.'.index')), $formato);
                
             // remplazando icono
             if(!empty($nodo->icono)){
                if(!empty(config('kitukizuri.iconFormat'))){
                    $iconFormat = config('kitukizuri.iconFormat');
                    $iconFormat = str_replace('{{icono}}', $nodo->icono, $iconFormat);
                    $formato    = str_replace('{{iconFormat}}', $iconFormat, $formato );
                } else {
                    $formato = str_replace('{{icono}}', $nodo->icono, $formato );
                }
            } else {
                if(!empty(config('kitukizuri.iconFormat'))){
                    $formato    = str_replace('{{iconFormat}}', '', $formato );
                } else {
                    $formato = str_replace('{{icono}}', $nodo->icono, $formato );
                }
                $formato = str_replace('{{icono}}', $nodo->icono, $formato );
            }
 
             // remplazando label
             $formato = str_replace('{{label}}', __($nodo->etiqueta), $formato);
 
             $formato = str_replace('{{target}}', $nodo->menuid, $formato);
 
             $this->tree .= '<li class="'.$this->uiElements['li-parent.class'].'">'.$formato;
        }else {
            if ($nodo->modulopermisoid == null) {
                return 0;
            }
            //aplicando formato para el li cuando es hijo
            $formato = $this->uiElements['li-jr.layout'];
                
            // remplazando url
            $formato = str_replace('{{url}}', $nodo->ruta == '/' ? $nodo->ruta : route($nodo->ruta.'.index'), $formato);

            // remplazando icono
            if(!empty($nodo->icono)){
                if(!empty(config('kitukizuri.iconFormat'))){
                    $iconFormat = config('kitukizuri.iconFormat');
                    $iconFormat = str_replace('{{icono}}', $nodo->icono, $iconFormat);
                    $formato    = str_replace('{{iconFormat}}', $iconFormat, $formato );
                } else {
                    $formato = str_replace('{{icono}}', $nodo->icono, $formato );
                }
            } else {
                if(!empty(config('kitukizuri.iconFormat'))){
                    $formato    = str_replace('{{iconFormat}}', '', $formato );
                } else {
                    $formato = str_replace('{{icono}}', $nodo->icono, $formato );
                }
                $formato = str_replace('{{icono}}', $nodo->icono, $formato );
            }

            // remplazando label
            $formato = str_replace('{{label}}', __($nodo->etiqueta), $formato);

            if ($this->vBootstrap == '4.1') {
                $this->tree .= $formato;    
            } else {
                $this->tree .= '<li class="'.config('kitukizuri.menu.li-jr.class').'">'.$formato;
                $this->tree .= '</li>';    
            }
        }
    }
    
    /**
     * hijosPermisos
     *
     * @param  mixed $nodo
     * @return void
     */
    public function hijosPermisos($nodo)
    {
        // estado inicial
        $permiso = 0;

        $hijos = Menu::getHijos($nodo->menuid);

        // recorrido y validación de permisos de los hijos 
        foreach ($hijos as $hijo) {
            if ($hijo->modulopermisoid != null && in_array($hijo->modulopermisoid, $this->permisos)) {
                $permiso = 1;
                break;
            }

            $permiso = $this->hijosPermisos($hijo);
        }

        return $permiso;
    }
}
