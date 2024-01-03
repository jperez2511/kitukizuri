<?php

namespace Icebearsoft\Kitukizuri\App\Console\Command;

use DB;
use Config;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

use Icebearsoft\Kitukizuri\App\Models\Modulo;
use Icebearsoft\Kitukizuri\App\Models\Permiso;
use Icebearsoft\Kitukizuri\App\Models\ModuloPermiso;

use Icebearsoft\Kitukizuri\App\Traits\UtilityTrait;

class MakeModule extends Command
{    

    use UtilityTrait;

    /**
     * The name and signature of the console command. 
     *
     * @var string
     */
    protected $signature = "krud:make {--module}";
        
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Permite generar elementos funcionales para el KRUD";
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Execute te console command.
     *
     * @return void
     */
    public function handle() 
    {
        if ($this->option('module')) {
            $this->makeModule();
        }
    }

    private function makeModule()
    {
        $módulo = [];

        // validando nombre del módulo 
        $nombre = $this->ask('Nombre del módulo');

        if($nombre == '') {
            $this->error('El nombre del módulo es obligatorio');
            return;
        }

        $modulo['nombre'] = $nombre;
        
        // validando la ruta del módulo
        $ruta = $this->validateRoute(null);
        $modulo['ruta'] = $ruta;

        $permisosLista    = Permiso::pluck('nombre', 'permisoid')->toArray();
        $permisosLista[0] = 'Todos';

        $permisos = $this->choice(
            'Selecciones los permisos del módulo',
            $permisosLista,
            null,
            null, 
            true
        );

        if (in_array('Todos', $permisos)) {
            $permisos = Permiso::pluck('permisoid')->toArray();
        } else {
            $permisos = Permiso::whereIn('nombre', $permisos)->pluck('permisoid')->toArray();
        }

        $modulo['permisos'] = $permisos;

        $this->addModuleSeeder($modulo);

        if(Config::get('kitukizuri.multiTenants') === true) {
            $this->info('El módulo se ha creado exitosamente, recuerde ejecutar el comando de artisan para crear el módulo en cada tenant');
        } else {
            $this->artisanCommand('db:seed', '--class=ModulosSeeder');
            $this->info('El módulo se ha creado exitosamente.');
        }
    }

    private function validateRoute($ruta)
    {
        if($ruta === null) {
            $ruta = $this->ask('Ruta del módulo');
            return $this->validateRoute($ruta);
        } else {
            if($ruta == '') { // validando que el nombre de la ruta no este vacía
                $this->error('La ruta del módulo es obligatoria');
                return $this->validateRoute(null);
            } 
            if (!preg_match('/^[a-zA-Z0-9]+$/', $ruta)) { // validando que la ruta contenga solo caracteres alfanuméricos
                $this->error('La ruta del módulo solo puede contener caracteres alfanuméricos');
                return $this->validateRoute(null);
            }
    
            // validando que la ruta no exista en la base de datos
            $rutaExiste = Modulo::where('ruta', $ruta)->exists();
            if($rutaExiste) {
                $this->error('la ruta ya existe para otro módulo');
                return $this->validateRoute(null);
            }
        }

        return $ruta;
    }

    private function addModuleSeeder($modulo)
    {
        $seederPath = base_path('database/seeders/ModulosSeeder.php');
        $seederContent = file_get_contents($seederPath);

        $oldMethod = '$this->saveData();';
        $newMethod = '$this->saveModuleData();';

        // validando la versión del seeder 
        if(str_contains($seederContent, $oldMethod)) {
            $posicion = strpos($seederContent, $oldMethod);
            $prefix   = 'this->';
            $method   = $oldMethod;
        } else if (str_contains($seederContent, $newMethod)){
            $posicion = strpos($seederContent, $newMethod);
            $prefix   = '';
            $method   = $newMethod;
        }

        if($posicion === false) {
            $this->error('No se pudo encontrar la posición para agregar el módulo');
            return false;
        }

        $posicionInsertar = strpos($seederContent, "\n", $posicion) - (strlen($method)+1);

        $nuevaLinea = "\$".$prefix."modulos[] = ['nombre' => '".$modulo['nombre']."', 'ruta' => '".$modulo['ruta']."', 'permisos' => [".implode(',', $modulo['permisos'])."]];\n\t\t";
        $nuevoContenido = substr($seederContent, 0, $posicionInsertar) . $nuevaLinea . substr($seederContent, $posicionInsertar);
        file_put_contents($seederPath, $nuevoContenido);

    }
}