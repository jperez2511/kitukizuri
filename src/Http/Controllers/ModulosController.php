<?php

namespace Icebearsoft\Kitukizuri\Http\Controllers;

use Icebearsoft\Kitukizuri\Models\Modulo;
use Krud;

class ModulosController extends Krud
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->setModel(new Modulo);
        $this->setTitulo('Modulos');
        $this->setCampo(['nombre'=>'Nombre', 'campo'=>'nombre']);
        $this->setCampo(['nombre'=>'Ruta', 'campo'=>'ruta']);
        $this->setBoton(['nombre'=>'Permisos', 'url'=>'/kk/permisos?id={id}', 'class'=>'success', 'icon'=>'zmdi zmdi-globe-lock']);
        $this->setLayout('krud.layout');
    }
}
