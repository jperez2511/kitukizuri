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

use function Laravel\Prompts\text;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\multiselect;

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
    
    /**
     * makeModule
     *
     * @return void
     */
    private function makeModule()
    {

        $this->warn('Al crear un módulo se modificarán los siguientes archivos: Seeders, Controller, Models, Routes. Por favor asegúrese de tener un respaldo de estos archivos antes de continuar.');

        $módulo          = [];
        $modelRoute      = null;
        $controllerRoute = null;

        // validando nombre del módulo 
        $nombre = text(label: 'Nombre del módulo', required: true);

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

        $permisos = multiselect(
            'Selecciones los permisos del módulo',
            $permisosLista,
        );

        if (in_array('Todos', $permisos)) {
            $permisos = Permiso::pluck('permisoid')->toArray();
        }

        $modulo['permisos'] = $permisos;

        $this->addModuleSeeder($modulo);

        $makeModel = confirm('¿Desea crear el modelo del módulo?');
        if($makeModel) {
            $modelRoute = text(label: 'Ruta del modelo', required:true);
            $this->artisanCommand('make:model', $modelRoute);
            // agregando variables al modelo
            $modelFile = \file_get_contents(base_path('app/Models/'.$modelRoute.'.php'));
            $modelFile = str_replace('//', "protected \$table = '';\n\tprotected \$primaryKey = '';\n\tprotected \$guarded = [''];\n", $modelFile);
            \file_put_contents(base_path('app/Models/'.$modelRoute.'.php'), $modelFile);
        }

        $makeController = confirm('¿Desea crear el controlador del módulo?');
        if($makeController) {
            $controllerRoute = text(label: 'Ruta del controlador', required:true);
            $this->artisanCommand('make:controller', $controllerRoute);

            $modelName = \explode('/', $modelRoute);
            $modelName = end($modelName);

            $filePath = base_path('app/Http/Controllers/'.$controllerRoute.'.php');
            $construct = <<<EOD
                public function __construct()
                {
                    \$this->setModel(new $modelName);
                    \$this->setTitulo('{$modulo['nombre']}');
                }
            EOD;

            $modelInsertPath = str_replace('/', '\\', $modelRoute);
            $modelImport = "use Illuminate\Http\Request;\n\nuse App\Models\\".$modelInsertPath.";";
        
            $this->replaceInFile('use App\Http\Controllers\Controller;', 'use Krud;', $filePath);
            $this->replaceInFile('extends Controller', 'extends Krud', $filePath);
            $this->replaceInFile('//', $construct, $filePath);
            $this->replaceInFile('use Illuminate\Http\Request;', $modelImport, $filePath);

            $makeWebRoute = confirm('¿Crear las rutas web?');
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
    
    /**
     * validateRoute
     *
     * @param  mixed $ruta
     * @return void
     */
    private function validateRoute($ruta)
    {
        if($ruta === null) {
            $ruta = text(label: 'Ruta del módulo', required: true);
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
    
    /**
     * addModuleSeeder
     *
     * @param  mixed $modulo
     * @return void
     */
    private function addModuleSeeder($modulo)
    {
        $seederPath = base_path('database/seeders/ModulosSeeder.php');
        $seederContent = file_get_contents($seederPath);

        $oldMethod = '$this->saveData();';
        $newMethod = '$this->saveModuleData($modulos);';

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
    
    /**
     * addRouteWeb
     *
     * @param  mixed $ruta
     * @param  mixed $controllerRoute
     * @return void
     */
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

        // validando si la ruta contiene diagonal para dejar solo el ultimo elmeento
        $controllerRouteLast = null;
        if (str_contains($controllerRoute, '/')) {
            $tmp = explode('/', $controllerRoute);
            $controllerRouteLast = end($tmp);
        } else {
            $controllerRouteLast = $controllerRoute;
        }

        $nuevaLinea = "\tRoute::resource('".$ruta."', ".$controllerRouteLast."::class);\n";
        $nuevaUse   = "\nuse App\Http\Controllers\\".str_replace('/', '\\', $controllerRoute).";";

        $nuevoContenido = substr($webRouteContent, 0, $posicionInsertar) . $nuevaLinea . substr($webRouteContent, $posicionInsertar);
        $nuevoContenido = substr($nuevoContenido, 0, $posicionUseInsertar) . $nuevaUse . substr($nuevoContenido, $posicionUseInsertar);

        file_put_contents($webRoutePath, $nuevoContenido);
    }
}