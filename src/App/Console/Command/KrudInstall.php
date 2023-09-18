<?php

namespace Icebearsoft\Kitukizuri\App\Console\Command;

use DB;
use Config;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;

use Icebearsoft\Kitukizuri\App\Traits\LdapTrait;
use Icebearsoft\Kitukizuri\App\Traits\UtilityTrait;

class KrudInstall extends Command
{    
    use UtilityTrait, LdapTrait;

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
        $ldapLogin = $this->confirm('¿Login con LDAP?');

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

        if($ldapLogin) {
            $this->configLdap();
        }

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
}