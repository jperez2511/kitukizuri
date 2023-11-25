<?php

namespace Icebearsoft\Kitukizuri\App\Console\Command;

use DB;
use Config;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;

use Icebearsoft\Kitukizuri\App\Traits\{
    LdapTrait,
    UtilityTrait,
    VueTrait,
    MongoTrait,
    TrinoTrait
};

class KrudInstall extends Command
{
    use UtilityTrait, LdapTrait, VueTrait, MongoTrait, TrinoTrait;

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
        $this->info('instalación de Jetstream terminada exitosamente !');

        // instalación de Vue en proyecto
        $this->configVue();

        // Configuración de MongoDB
        $this->configMongoDB();

        // Configuración de Trino
        $this->configTrino();

        // instalación de dependencias Krud
        $this->runCommands(['npm install'], __DIR__.'/../../../');
        $this->runCommands(['npm install --save-dev gulp'], __DIR__.'/../../../');
        $this->runCommands(['npx gulp build'], __DIR__.'/../../../');
        $this->deleteDirectory(__DIR__.'/../../../node_modules');
        $this->info('instalación de dependencias kitukizuri termianda exitosamente !');

        // publicación de krud
        $this->artisanCommand('vendor:publish','--tag=krud-migrations');
        $this->artisanCommand('vendor:publish','--tag=krud-seeders');
        $this->artisanCommand('vendor:publish','--tag=krud-error');
        $this->artisanCommand('vendor:publish','--tag=krud-views');
        $this->artisanCommand('vendor:publish','--tag=krud-app');
        $this->artisanCommand('vendor:publish','--tag=krud-config');
        $this->artisanCommand('vendor:publish','--tag=krud-public');

        unlink(base_path('routes/web.php'));
        copy(__DIR__ . '/../../../stubs/Routes/web.php', base_path('routes/web.php'));

        copy(__DIR__ . '/../../../stubs/Controllers/HomeController.php', base_path('app/Http/Controllers/HomeController.php'));
        $this->info('Configuración de archivos terminada exitosamente !');

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