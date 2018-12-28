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
            ->where('usuariorolid', Auth::id())
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
			$this->getNodos($nodo);	
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

        if ($hijos->count() > 0 || $nodo->padreid == null) {
            //aplicando formato para el li cuando es padre
            $formato = config('kitukizuri.menu.li-parent.layout');
                
            // remplazando url
            $formato = str_replace('{{url}}', ($hijos->count() > 0 ? '#' : $nodo->ruta), $formato);

            // remplazando icono
            $formato = str_replace('{{icono}}', $nodo->icono, $formato );

            // remplazando label
            $formato = str_replace('{{label}}', $nodo->etiqueta, $formato);

            $this->tree .= '<li class="'.config('kitukizuri.menu.li-parent.class').'">'.$formato;
            
            // Agregando ul para los hijos
            $this->tree .= '<ul ';
            foreach (config('kitukizuri.menu.ul-jr') as $key => $value) {
                $this->tree .= $key.'="'.$value.'"';
            }
            $this->tree .= '>';

            foreach ($hijos as $hijo) {
                $this->getNodos($hijo);
            }

            // Cerrando las etiquetas
            $this->tree .= '</ul></li>';  
        } else {
            //aplicando formato para el li cuando es hijo
            $formato = config('kitukizuri.menu.li-jr.layout');
                
            // remplazando url
            $formato = str_replace('{{url}}', $nodo->ruta, $formato);

            // remplazando label
            $formato = str_replace('{{label}}', $nodo->etiqueta, $formato);

            $this->tree .= '<li class="'.config('kitukizuri.menu.li-jr.class').'">'.$formato;
            $this->tree .= '</li>';
        }
    }
}
