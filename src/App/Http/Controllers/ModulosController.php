<?php

namespace Icebearsoft\Kitukizuri\App\Http\Controllers;

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
        $this->setCampo(['nombre'=>'Nombre', 'campo'=> 'nombre']);
        $this->setCampo(['nombre'=>'Ruta', 'campo'=> 'ruta']);
        $this->setBoton(['nombre'=>'Permisos', 'url'=> route('permisos.index').'?id={id}', 'class'=>'outline-success', 'icon'=>'mdi mdi-lock-open-variant-outline']);
        $this->setLayout('krud::layout');
    }
}
