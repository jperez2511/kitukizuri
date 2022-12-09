<?php

namespace Icebearsoft\Kitukizuri\Console\Command;

use DB;
use Config;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;

class KrudInstall extends Command
{    
    /**
     * The name and signature of the console command. 
     *
     * @var string
     */
    protected $signature = "krud:install";
        
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Genera los datos necesarios para inicializar un proyecto basado en KRUD";
    
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
        // instalación de jetstream
        $this->composerInstall('laravel/jetstream');
        $this->artisanCommand('jetstream:install', 'livewire');
        
        // publicación de krud
        $this->artisanCommand('vendor:publish','--tag=krud-migrations');
        $this->artisanCommand('vendor:publish','--tag=krud-seeders');
        $this->artisanCommand('vendor:publish','--tag=krud-error');
        $this->artisanCommand('vendor:publish','--tag=krud-views');
        $this->artisanCommand('vendor:publish','--tag=krud-config');
        $this->artisanCommand('vendor:publish','--tag=krud-public');    

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

    protected function composerInstall($package)
    {
        $command = ['composer', 'require', $package];
        
        $process = new Process($command, base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']);
        $process->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            });
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