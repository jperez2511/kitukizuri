<?php

namespace Icebearsoft\Kitukizuri\App\Traits\Krud;

use Auth;
use Illuminate\Database\Query\Expression;

trait TableTrait
{
    protected function showTable($id, $request)
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
}