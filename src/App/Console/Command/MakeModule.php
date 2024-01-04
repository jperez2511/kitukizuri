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

        $this->warn('Al crear un módulo se modificarán los siguientes archivos: Seeders, Controller, Models, Routes. Por favor asegúrese de tener un respaldo de estos archivos antes de continuar.');

        $módulo          = [];
        $modelRoute      = null;
        $controllerRoute = null;

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

        $makeModel = $this->confirm('¿Desea crear el modelo del módulo?');
        if($makeModel) {
            $modelRoute = $this->ask('Ruta del modelo');
            $this->artisanCommand('make:model', $modelRoute);
        }

        $makeController = $this->confirm('¿Desea crear el controlador del módulo?');
        if($makeController) {
            $controllerRoute = $this->ask('Ruta del controlador');
            $this->artisanCommand('make:controller', $controllerRoute);

            $makeWebRoute = $this->confirm('¿Crear las rutas web?');
            if($makeWebRoute) {
                $this->addRouteWeb($ruta, $controllerRoute);
            }
        }


        


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

    private function addRouteWeb($ruta, $controllerRoute)
    {
        $webRoutePath    = base_path('routes/web.php');
        $webRouteContent = file_get_contents($webRoutePath);
        $flag            = '// Automatic injection routes don\'t remove this line';
        $flagUse         = '// Automatic injection controllers don\'t remove this line';


        if(!str_contains($webRouteContent, 'Automatic injection routes don\'t remove this line')) {
            $this->error('No se pudo encontrar la posición para agregar la ruta. por favor agregue la siguiente línea en el archivo routes/web.php: // don\'t remove this line');
            return false;
        }

        $flagPosition    = strpos($webRouteContent, $flag);
        $flagUsePosition = strpos($webRouteContent, $flagUse);

        $posicionInsertar    = strpos($webRouteContent, "\n", $flagPosition) - (strlen($flag)+1);
        $posicionUseInsertar = strpos($webRouteContent, "\n", $flagUsePosition) - (strlen($flagUse)+1);

        $nuevaLinea = "\tRoute::resource('".$ruta."', ".$controllerRoute."::class);\n";
        $nuevaUse   = "\nuse App\Http\Controllers\\".$controllerRoute.";";

        $nuevoContenido = substr($webRouteContent, 0, $posicionInsertar) . $nuevaLinea . substr($webRouteContent, $posicionInsertar);
        $nuevoContenido = substr($nuevoContenido, 0, $posicionUseInsertar) . $nuevaUse . substr($nuevoContenido, $posicionUseInsertar);

        file_put_contents($webRoutePath, $nuevoContenido);
    }
}