<?php

namespace Icebearsoft\Kitukizuri\App\Console\Command;

use DB;
use Config;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;

use Icebearsoft\Kitukizuri\App\Traits\{
    TsTrait,
    VueTrait,
    LogTrait,
    LdapTrait,
    MongoTrait,
    UtilityTrait,
    MultiTenantTrait
};

use function Laravel\Prompts\confirm;

class KrudInstall extends Command
{
    use UtilityTrait, LdapTrait, VueTrait, MongoTrait, LogTrait, TsTrait, MultiTenantTrait;

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
        $multiTenant = confirm('¿Configurar aplicación para multi tenants?');

        $ldapLogin   = confirm('¿Login con LDAP?');
        $vueConfig   = confirm('¿Configurar Vue?');

        $mongoConfig = confirm('¿Configurar MongoDB?');
        $logConfig   = confirm('¿Guardar Logs en base de datos?');

        // instalación de jetstream
        $installed = $this->isPackageInstalled('laravel/jetstream');
        if(!$installed) {
            $this->composerInstall('laravel/jetstream');
            $this->artisanCommand('jetstream:install', 'livewire');
            $this->info('instalación de Jetstream terminada exitosamente !');
        } else {
            $this->info('Jetstream ya está instalado');
        }

        if ($multiTenant == true) {
            $this->configMultiTenant();
        }

        if($vueConfig == true) {
            // instalación de Vue en proyecto
            $this->configVue();
        }

        if($mongoConfig == true) {
            // Configuración de MongoDB
            $this->configMongoDB();
        }

        if($logConfig == true) {
            // Configuración de Log
            $this->configLogChanel();
        }


        // publicación de krud
        $this->artisanCommand('vendor:publish','--tag=krud-migrations');
        $this->artisanCommand('vendor:publish','--tag=krud-seeders');
        $this->artisanCommand('vendor:publish','--tag=krud-error');
        $this->artisanCommand('vendor:publish','--tag=krud-config');
        

        unlink(base_path('routes/web.php'));
        copy(__DIR__ . '/../../../stubs/Routes/web.php', base_path('routes/web.php'));

        copy(__DIR__ . '/../../../stubs/Controllers/HomeController.php', base_path('app/Http/Controllers/HomeController.php'));
        $this->info('Configuración de archivos terminada exitosamente !');

        if($ldapLogin) {
            $this->configLdap();
        }

        $this->artisanCommand('krud:ui-config');

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