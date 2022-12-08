<?php

namespace Icebearsoft\Kitukizuri\Console\Command;

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
    protected $signature = "krud:make";
        
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Genera un modulo utilizable con Krud";
    
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

        $multiTenants = config('kitukizuri.multiTenants');
        $nombreModulo = $this->ask('Nombre del módulo:');
        $rutaDefault  = $this->strClean($nombreModulo);
        $opcionesRuta = [$rutaDefault, 'No, ingresar nombre'];

        $resultado = $this->choice(
            '¿ Utilizar nombre de ruta default ?',
            $opcionesRuta, 0, 2, false
        );

        if($opcionesRuta[1] == $resultado) {
            $nombreRuta = $this->ask('Ingrese el nombre de la ruta *(minúsculas sin espacios):');
        } else {
            $nombreRuta = $rutaDefault;
        }

        if($multiTenants === true) {
            $listDB = array_keys(config('database.connections'));
            $nombreDB = $this->choice(
                '¿ Seleccione la base de datos tenants ?',
                $listDB, 0, 2, false
            );

            $configuraciones = $this->getTenantsConnection($nombreDB);
            
            
            foreach ($configuraciones as $db) {
               $this->setConnectionDB($db);
                // validando la existencia del modulo 
                $existe = Modulo::where('ruta', $nombreRuta)->exists();
                if($existe) {
                    $this->error('-> El nombre de ruta ya existe.');
                    break;
                }

                if(empty($permisos)){
                    // Asignando valor default para los permisos
                    $permiso = ['0 - Todos'];
                    // Obteniendo los permisos para el módulo
                    $permisos = Permiso::select(DB::raw('concat_ws(\' - \', permisoid, nombre) as permiso'))
                        ->pluck('permiso')
                        ->toArray();
    
                    $permisos = array_merge($permiso, $permisos);
                }
                continue;
            }

            $permisoSeleccionado = $this->choice(
                '¿ Seleccioné los permisos disponibles para el módulo ?',
                $permisos, 0, 2, true
            );

            $allPermisos = $permisoSeleccionado;
            foreach ($permisoSeleccionado as $permiso) {
                $permiso = explode('-', $permiso);
                $id = trim($permiso[0]);
                if($id == 0) {
                    $allPermisos = $permisos;
                    break;
                }
            }

            // Guardando el modulo en todas las bases de datos
            foreach ($configuraciones as $db) {
                $this->setConnectionDB($db);
                DB::beginTransaction();
                
                try {
                    $modulo         = new Modulo;
                    $modulo->nombre = $nombreModulo;
                    $modulo->ruta   = $nombreRuta;
                    $modulo->save();

                    foreach ($allPermisos as $permiso) {
                        $permiso = explode('-', $permiso);
                        $id = trim($permiso[0]);

                        if($id == 0) {
                            continue;
                        }
                        
                        $moduloPermiso = new ModuloPermiso;
                        $moduloPermiso->moduloid = $modulo->moduloid;
                        $moduloPermiso->permisoid = $id;
                        $moduloPermiso->save();
                    }
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    $this->error($e->getMessage());
                }
            }
        } else {
            // Validando si existe el la ruta 
            $existe = Modulo::where('ruta', $nombreRuta)->exists();
            if($existe) {
                $this->error('-> El nombre de ruta ya existe.');
            }
            // Obteniendo los permisos
            $permiso  = ['0 - Todos'];
            $permisos = Permiso::select(DB::raw('concat_ws(\' - \', permisoid, nombre) as permiso'))
                ->pluck('permiso')
                ->toArray();
            
                $permisos = array_merge($permiso, $permisos);
        }

        

        
        

        
        



        

        
    }

    /**
     * getTenantsConnection
     *
     * @param  mixed $nombreDB
     * @return void
     */
    private function getTenantsConnection($nombreDB)
    {
        return DB::connection($nombreDB)
          ->table('tenants')
          ->select('db', 'db_password', 'db_username')
          ->where('activo', true)
          ->orderBy('tenant_id')
          ->get();
    }

    /**
     * setConnectionDB
     *
     * @param  mixed $db
     * @return void
     */
    private function setConnectionDB($db)
    {
        DB::purge('mysql');
        Config::set('database.connections.mysql.database',  $db->db);
        Config::set('database.connections.mysql.username',  $db->db_username);
        Config::set('database.connections.mysql.password',  $db->db_password);
        DB::reconnect('mysql');
    }

    /**
     * strClean
     *
     * @param  mixed $string
     * @return void
     */
    private function strClean($string)
    {
        $string = \str_replace(' ', '', $string);
        $string = \strtolower($string);

        return $string;
    }
}