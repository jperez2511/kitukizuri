<?php

namespace Icebearsoft\Kitukizuri\Http\Controllers;

use Krud;

//Models
use Icebearsoft\Kitukizuri\Models\Sucursal;

class SucursalesController extends Krud
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->setModel(new Sucursal);
        $this->setTitulo('Sucursal');
        $this->setCampo(['nombre'=>'Nombre', 'campo'=>'nombre', 'columnClass' => 'col-12']);
        $this->setParents('empresaid', 'parent', true);
        $this->setLayout('krud::layout');

        // agregando campos custom
        $camposCustom = config('kitukizuri.sucursalesCustomField') ?? null;
        if($camposCustom != null) {
            foreach($camposCustom as $campo) {
                $this->setCampo($campo);
            }
        }
    }
}
