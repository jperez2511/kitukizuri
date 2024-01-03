<?php

namespace Icebearsoft\Kitukizuri\App\Console\Command;

use DB;
use Config;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

use Icebearsoft\Kitukizuri\Models\Modulo;
use Icebearsoft\Kitukizuri\Models\Permiso;
use Icebearsoft\Kitukizuri\Models\ModuloPermiso;

class MakeModule extends Command
{    
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

        // validando que la ruta no exista en la base de datos
        $rutaExiste = Modulo::where('ruta', $ruta)->exists();
        if($rutaExiste) {
            $this->error('la ruta ya existe para otro módulo');
            return;
        }

        $icono  = $this->ask('Icono del modulo');
        
        $orden  = $this->ask('Orden del modulo');

    }

    private function validateRoute($ruta)
    {
        if($ruta === null) {
            $ruta = $this->ask('Ruta del módulo');
            return $this->validateRoute($ruta);
        } else {
            if($route == '') { // validando que el nombre de la ruta no este vacía
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
    }

}