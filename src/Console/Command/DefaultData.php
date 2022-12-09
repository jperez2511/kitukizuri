<?php

namespace Icebearsoft\Kitukizuri\Console\Command;

use DB;
use Config;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;

class DefaultData extends Command
{    
    /**
     * The name and signature of the console command. 
     *
     * @var string
     */
    protected $signature = "krud:default-data";
        
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Crea al estructura de base de datos y establce los datos iniciales";
    
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
        // validando conexion a base de datos
        try {
            DB::connection()->getPDO();
            
            // construyendo estructura de la base de datos
            $this->artisanCommand('migrate');
        
            // poblando datos de configuración
            $this->artisanCommand('db:seed', '--class=PermisosSeeder');
            $this->artisanCommand('db:seed', '--class=ModulosSeeder');
            $this->artisanCommand('db:seed', '--class=InicialSeeder');
            
        } catch (\Exception $e) {
            $this->error('
                La base de datos no está configurada correctamente.

                Una vez que la base de datos funcione correctamente ejecute el comando
                php artisan krud:default-data
            ');
        }
    }

    protected function artisanCommand($action, $option = null)
    {
        $command = [$this->phpBinary(), 'artisan', $action];
        
        if (!empty($option) && is_array($option)) {
            $command = \array_merge($command, $option);
        } else if (!empty($option)){
            $command[] = $option;
        }

        $process = new Process($command, base_path());
        $process->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            });
    }

    protected function phpBinary()
    {
        return (new PhpExecutableFinder())->find(false) ?: 'php';
    }
}