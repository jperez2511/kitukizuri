<?php

namespace Icebearsoft\Kitukizuri\App\Traits;

use File;

trait MongoTrait
{
    protected function configMongoDB()
    {
        $this->composerInstall('mongodb/laravel-mongodb');

        $this->setDatabaseConfig();
    }

    private function setDatabaseConfig()
    {
        $path      = config_path('database.php');
        $contents  = File::get($path);
        $newConfig = <<<EOD
                'mongodb' => [
                    'driver'   => 'mongodb',
                    'host'     => env('DB_MONGO_HOST', '127.0.0.1'),
                    'port'     => env('DB_MONGO_PORT', 27017),
                    'database' => env('DB_MONGO_DATABASE', 'blog'),
                    'username' => env('DB_MONGO_USERNAME', ''),
                    'password' => env('DB_MONGO_PASSWORD', ''),
                    'options'  => [
                        'database' => 'admin'
                    ]
                ],
            EOD;

        // Encuentra la posición de 'connections' y agrega la nueva configuración
        $position = strpos($contents, "'connections' => [");
        if ($position !== false) {
            $position += strlen("'connections' => [");
            $contents = substr_replace($contents, $newConfig, $position, 0);
            // Guarda el archivo modificado
            File::put($path, $contents);
        }

        $this->info('Configuración de base de datos actualizada con éxito.');
    }
}