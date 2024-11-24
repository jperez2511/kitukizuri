<?php

namespace Icebearsoft\Kitukizuri\App\Http\Controllers;

use Krud;

//Models
use Icebearsoft\Kitukizuri\App\Models\Empresa;

class EmpresasController extends Krud
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $vars = usePrevUi('default');

        $this->setModel(new Empresa);
        $this->setTitulo('Empresas');
        $this->setCampo(['nombre'=>'Nombre',    'campo'=>'nombre']);
        $this->setCampo(['nombre'=>'NIT',       'campo'=>'nit']);
        $this->setCampo(['nombre'=>'Telefono',  'campo'=>'telefono']);
        $this->setCampo(['nombre'=>'Correo',    'campo'=>'correo']);
        $this->setCampo(['nombre'=>'Direccion', 'campo'=>'direccion',   'tipo'=>'textarea', 'columnClass'=> 'col-md-12']);
        $this->setCampo(['nombre'=>'Logo',      'campo'=>'logo',        'tipo'=>'image',    'columnClass'=> 'col-md-12', 'show' => false]);
        $this->setCampo(['nombre'=>'Activa',    'campo'=>'activo',      'tipo'=>'bool']);
        $this->setBoton(['nombre'=>'Sucursales', 'url'=>route('sucursales.index').'?parent={id}', 'class'=>'outline-primary', 'icon'=>'fa-duotone fa-solid fa-store']);
        $this->setBoton([
            'nombre' => 'Modulos',
            'url'    => route('moduloempresas.index').'?parent={id}',
            'class'  => 'outline-success',
            'icon'   => 'fa-duotone fa-solid fa-layer-group'
        ]);
        
        $this->setLayout($vars['krud']);
    }
}
