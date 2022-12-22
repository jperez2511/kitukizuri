<?php 

namespace Icebearsoft\Kitukizuri\Http\Controllers;

use Krud;

use Auth;

use Icebearsoft\Kitukizuri\Models\Usuario;

class UsuariosController extends Krud
{
    protected $empresaid;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->setModel(new Usuario);
        $this->setTitulo('Usuarios');
        $this->setCampo(['nombre'=>'Email', 'campo'=>'email', 'unique' => true]);
        $this->setCampo(['nombre'=>'Nombre', 'campo'=>'name']);
        $this->setCampo(['nombre'=>'Constraseña', 'campo'=>'password', 'tipo'=>'password', 'show'=>false]);
        $this->setBoton(['nombre'=>'Asignar Permiso', 'url'=>route('asignarpermiso.index').'?parent={id}', 'class'=>'outline-success', 'icon'=>'mdi mdi-lock-open-variant-outline']);
        $this->setLayout('krud::layout');

        // agregando campos custom
        $camposCustom = config('kitukizuri.userCustomField') ?? null;
        if($camposCustom != null) {
            foreach($camposCustom as $campo) {
                $this->setCampo($campo);
            }
        }
    }
}
