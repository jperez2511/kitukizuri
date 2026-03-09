<?php

namespace Icebearsoft\Kitukizuri\App\Console\Command;

use DB;
use Illuminate\Console\Command;
use Icebearsoft\Kitukizuri\App\Traits\UtilityTrait;

class KrudUpdate extends Command
{
    use UtilityTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "krud:update
                            {--force : Sobrescribe archivos publicados}
                            {--skip-publish : Omite vendor:publish}
                            {--skip-migrate : Omite migraciones}
                            {--skip-seed : Omite sincronizacion de seeders}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Actualiza una instalacion existente de KRUD agregando elementos faltantes";

    /**
     * Execute te console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->info('Iniciando actualizacion de KRUD...');

        if (!$this->option('skip-publish')) {
            $this->publishPackageResources();
        }

        if (!$this->isDataBaseReady()) {
            return;
        }

        if (!$this->option('skip-migrate')) {
            $this->runMigrations();
        }

        if (!$this->option('skip-seed')) {
            $this->syncSeeders();
        }

        $this->info('Actualizacion finalizada.');
    }

    /**
     * Publica recursos del paquete.
     *
     * @return void
     */
    private function publishPackageResources()
    {
        $tags = [
            'krud-migrations',
            'krud-seeders',
            'krud-error',
            'krud-views',
            'krud-app',
            'krud-config',
            'krud-public',
        ];

        foreach ($tags as $tag) {
            $this->line('Publicando: '.$tag);

            $options = ['--tag='.$tag];
            if ($this->option('force')) {
                $options[] = '--force';
            }

            $this->artisanCommand('vendor:publish', $options);
        }
    }

    /**
     * valida conexion a base de datos.
     *
     * @return bool
     */
    private function isDataBaseReady()
    {
        try {
            DB::connection()->getPDO();
            return true;
        } catch (\Exception $e) {
            $this->error('
                La base de datos no esta configurada correctamente.

                Corrige la conexion y vuelve a ejecutar:
                php artisan krud:update
            ');
            return false;
        }
    }

    /**
     * Ejecuta migraciones pendientes.
     *
     * @return void
     */
    private function runMigrations()
    {
        $this->line('Ejecutando migraciones pendientes...');
        $this->artisanCommand('migrate');
    }

    /**
     * Sincroniza datos base de KRUD.
     *
     * @return void
     */
    private function syncSeeders()
    {
        $seeders = [
            'ModulosSeeder',
            'RolModuloPermisosSeeder',
            'EmpresamodulosSeeder',
        ];

        foreach ($seeders as $seeder) {
            if (!file_exists(base_path('database/seeders/'.$seeder.'.php'))) {
                $this->warn('Seeder no encontrado, se omite: '.$seeder);
                continue;
            }

            $this->line('Sincronizando: '.$seeder);
            $this->artisanCommand('db:seed', '--class='.$seeder);
        }
    }
}
