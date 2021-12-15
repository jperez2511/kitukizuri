<?php

namespace Icebearsoft\Kitukizuri\Http\Controllers;

use Auth;

// Models
use Icebearsoft\Kitukizuri\Models\Menu;
use Icebearsoft\Kitukizuri\Models\UsuarioRol;

class MenuController
{
    // String para almacenar el menu Final
    private $tree = null;

    // String para conocer la version de bootstrap
    private $vBootstrap = null;
    
    // Array para los modulos que tiene acceso el usuario
    private $permisos = [];

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        //abriendo tag ul con los estilos personalizables
        $this->tree .= '<ul class="'.config('kitukizuri.menu.ul.class').'" id="'.config('kitukizuri.menu.ul.id').'">';
        $this->vBootstrap = config('kitukizuri.vBootstrap');
    }

    /**
     * construirMenu
     *
     * @return void
     */
    public function construirMenu()
    {
        // Obteniendo los permisos del rol
        $rolModulo = usuarioRol::with('modulos')
            ->where('usuarioid', Auth::id())
            ->get();
        
        // poblando array permisos
        foreach ($rolModulo as $rol) {
            foreach ($rol->modulos as $modulo) {
                if (!in_array($modulo->modulopermisoid, $this->permisos)) {
                    $this->permisos[] = $modulo->modulopermisoid;
                }
            }
        }

        // obteniendo los elementos del menu que no tienen padreid
        $nodos = Menu::getPapas();
        
        // recorriendo para hacer las validaciones
        foreach ($nodos as $nodo) {
            if(in_array($nodo->modulopermisoid, $this->permisos) || $nodo->modulopermisoid == null) {
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
     * Metodo que construye el menu con el estilo predeterminado
     * 
     * @return void
     */
    public function getNodos($nodo) 
    {
        // obteniendo los hijos del elemento actual
        $hijos = Menu::getHijos($nodo->menuid);

        if ($hijos->count() > 0 && $nodo->padreid == null) {
            
            if ($nodo->modulopermisoid == null) {
                // valida si alguno de los hijos tiene permisos
                $result = $this->hijosPermisos($nodo);
                if ($result != 1) {
                    return 0;    
                }
            }

            //aplicando formato para el li cuando es padre
            $formato = config('kitukizuri.menu.li-parent.layout');
                
            // remplazando url
            $formato = str_replace('{{url}}', ($hijos->count() > 0 ? '#' : route($nodo->ruta.'.index')), $formato);

            // remplazando icono
            $formato = str_replace('{{icono}}', $nodo->icono, $formato );

            // remplazando label
            $formato = str_replace('{{label}}', $nodo->etiqueta, $formato);

            $formato = str_replace('{{target}}', $nodo->menuid, $formato);

            $this->tree .= '<li class="'.config('kitukizuri.menu.li-parent.class').'">'.$formato;
            
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
                        if ($hijo->modulopermisoid != null && in_array($hijo->modulopermisoid, $this->permisos)) {
                            $this->getNodos($hijo);
                        }
                    }
                    
                    // Cerrando las etiquetas
                    $this->tree .= '</div>';
                    $this->tree .= '</div>';
                } else {
                    if ($this->vBootstrap == '4.1') {
                        
                    } else {
                        // Agregando ul para los hijos
                        $this->tree .= '<ul ';
                        foreach (config('kitukizuri.menu.ul-jr') as $key => $value) {
                            $this->tree .= $key.'="'.$value.'"';
                        }
                        $this->tree .= '>';
                        
                        foreach ($hijos as $hijo) {
                            if ($hijo->modulopermisoid != null && in_array($hijo->modulopermisoid, $this->permisos)) {
                                $this->getNodos($hijo);
                            }
                        }
                        
                        // Cerrando las etiquetas
                        $this->tree .= '</ul>';
                    }
                }
            }
            
            $this->tree .= '</li>';
        } else if ($hijos->count() < 1 && $nodo->padreid == null) {
            if ($nodo->modulopermisoid == null) {
                return 0;
            }
             //aplicando formato para el li cuando es padre
             $formato = config('kitukizuri.menu.li-parent.layout');
                
             // remplazando url
             $formato = str_replace('{{url}}', ($hijos->count() > 0 ? '#' : route($nodo->ruta.'.index')), $formato);
 
             // remplazando icono
             $formato = str_replace('{{icono}}', $nodo->icono, $formato );
 
             // remplazando label
             $formato = str_replace('{{label}}', $nodo->etiqueta, $formato);
 
             $formato = str_replace('{{target}}', $nodo->menuid, $formato);
 
             $this->tree .= '<li class="'.config('kitukizuri.menu.li-parent.class').'">'.$formato;
        }else {
            if ($nodo->modulopermisoid == null) {
                return 0;
            }
            //aplicando formato para el li cuando es hijo
            $formato = config('kitukizuri.menu.li-jr.layout');
                
            // remplazando url
            $formato = str_replace('{{url}}', $nodo->ruta == '/' ? $nodo->ruta : route($nodo->ruta.'.index'), $formato);

            // remplazando icono
            $formato = str_replace('{{icono}}', $nodo->icono, $formato );

            // remplazando label
            $formato = str_replace('{{label}}', $nodo->etiqueta, $formato);

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
        
        // recorrido y validacion de permisos de los hijos 
        foreach ($hijos as $hijo) {
            if ($hijo->modulopermisoid != null && in_array($hijo->modulopermisoid, $this->permisos)) {
                $permiso = 1;
                break;
            }
        }

        return $permiso;
    }
}
