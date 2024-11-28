<?php 

namespace Icebearsoft\Kitukizuri\App\Http\Controllers;

use Krud;

use Auth;

use Illuminate\Http\Request;
use Icebearsoft\Kitukizuri\App\Models\Usuario;
use Icebearsoft\Kitukizuri\App\Models\Rol;

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
        
        $this->setCampo([
            'nombre' => 'Email',
            'campo'  => 'email',
            'unique' => true
        ]);
        
        $this->setCampo([
            'nombre' => 'Nombre',
            'campo'  => 'name'
        ]);
        
        $this->setCampo([
            'nombre' => 'Constraseña',
            'campo'  => 'password',
            'tipo'   => 'password',
            'show'   => false
        ]);

        if(config('kitukizuri.preUi') === true) {
            $this->setBoton([
                'nombre' => 'Asignar roles',
                'url'    => route('asignarpermiso.index').'?parent={id}',
                'class'  => 'outline-success btn-sm',
                'icon'   => 'mdi mdi-lock-open-variant-outline'
            ]);
        } else {
            $roles = Rol::select('rolid as id', 'nombre as value')->get();
            $this->setCampo([
                'name'     => __('Roles'),
                'field'    => 'usuarioRol.rolid',
                'show'     => false,
                'type'     => 'select2',
                'collect'  => $roles,
                'htmlAttr' => [
                    'multiple' => true
                ]
            ]);
        }

        // agregando campos custom
        $camposCustom = config('kitukizuri.userCustomField') ?? null;
        if($camposCustom != null) {
            foreach($camposCustom as $campo) {
                $this->setCampo($campo);
            }
        }
    }
}
