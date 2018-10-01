<?php

namespace Icebearsoft\Kitukizuri\Http\Controllers;

use Icebearsoft\Kitukizuri\Models\Modulo;
use Krud;

class ModulosController extends Krud
{
    public function __construct()
    {
        $this->setModel(new Modulo);
        $this->setTitulo('Modulos');
        $this->setCampo(['nombre'=>'Nombre', 'campo'=>'nombre']);
        $this->setCampo(['nombre'=>'Ruta', 'campo'=>'ruta']);
        $this->setBoton(['nombre'=>'Permisos', 'url'=>'/admin-global/permisos?id={id}', 'class'=>'success', 'icon'=>'globe-lock']);
    }
}
