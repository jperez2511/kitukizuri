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

        $this->syncPublishedModulosSeeder();

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
     * Sincroniza ModulosSeeder publicado sin sobrescribir personalizaciones.
     *
     * @return void
     */
    private function syncPublishedModulosSeeder()
    {
        $targetPath = base_path('database/seeders/ModulosSeeder.php');
        $sourcePath = __DIR__.'/../../../database/seeders/ModulosSeeder.php';

        if (!file_exists($sourcePath)) {
            $this->warn('No se encontro el ModulosSeeder fuente del paquete.');
            return;
        }

        if (!file_exists($targetPath)) {
            $this->warn('No se encontro ModulosSeeder publicado para sincronizar.');
            return;
        }

        $sourceContent = file_get_contents($sourcePath);
        $targetContent = file_get_contents($targetPath);

        if ($sourceContent === false || $targetContent === false) {
            $this->warn('No fue posible leer ModulosSeeder para sincronizar.');
            return;
        }

        $sourceModuleLines = $this->extractModuleSeederLines($sourceContent);
        if (empty($sourceModuleLines)) {
            $this->warn('No se detectaron modulos en ModulosSeeder del paquete.');
            return;
        }

        $targetRoutes = $this->extractSeederRoutes($targetContent);
        $missingLines = [];

        foreach ($sourceModuleLines as $line) {
            $route = $this->extractSeederRouteFromLine($line);

            if (empty($route) || in_array($route, $targetRoutes, true)) {
                continue;
            }

            $missingLines[] = $line;
            $targetRoutes[] = $route;
        }

        if (empty($missingLines)) {
            $this->line('ModulosSeeder ya estaba sincronizado.');
            return;
        }

        $lineBreak = str_contains($targetContent, "\r\n") ? "\r\n" : "\n";
        $insertBlock = implode($lineBreak, $missingLines).$lineBreak.$lineBreak;

        $newContent = preg_replace('/(\$this->saveModuleData\(\$modulos\);\s*)/', $insertBlock.'$1', $targetContent, 1, $count);

        if ($count !== 1 || $newContent === null) {
            $this->warn('No se pudo insertar modulos faltantes en ModulosSeeder automaticamente.');
            return;
        }

        file_put_contents($targetPath, $newContent);
        $this->info('ModulosSeeder sincronizado: '.count($missingLines).' modulo(s) agregado(s).');
    }

    /**
     * Obtiene lineas de modulo del seeder.
     *
     * @param  string  $content
     * @return array
     */
    private function extractModuleSeederLines($content)
    {
        preg_match_all('/^\s*\$modulos\[\]\s*=\s*\[.*?\];\s*$/m', $content, $matches);

        return $matches[0] ?? [];
    }

    /**
     * Obtiene las rutas ya declaradas en un seeder.
     *
     * @param  string  $content
     * @return array
     */
    private function extractSeederRoutes($content)
    {
        preg_match_all("/'ruta'\\s*=>\\s*'([^']+)'/", $content, $matches);

        return array_values(array_unique($matches[1] ?? []));
    }

    /**
     * Obtiene la ruta declarada en una linea de modulo.
     *
     * @param  string  $line
     * @return string|null
     */
    private function extractSeederRouteFromLine($line)
    {
        preg_match("/'ruta'\\s*=>\\s*'([^']+)'/", $line, $matches);

        return $matches[1] ?? null;
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
