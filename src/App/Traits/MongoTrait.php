<?php

namespace Icebearsoft\Kitukizuri\App\Traits;

use File;

trait MongoTrait
{
    protected function configMongoDB()
    {
        $this->composerInstall('mongodb/laravel-mongodb:dev-master');
        $this->setDatabaseConfig();
        $this->addMongoEnv('.env');
        $this->addMongoEnv('.env.example');
    }

    private function setDatabaseConfig()
    {
        $path      = config_path('database.php');
        $contents  = File::get($path);
        $newConfig = <<<EOD
                \n
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

    private function addMongoEnv($file)
    {
        $envPath = base_path($file);
        $envContent = file_get_contents($envPath);

        // Verifica si las configuraciones LDAP ya existen para evitar duplicados
        if (!str_contains($envContent, 'DB_MONGO_CONNECTION')) {
            $ldapConfig = "\n" .
                "DB_MONGO_CONNECTION=mongodb\n" .
                "DB_MONGO_HOST=mongo\n" .
                "DB_MONGO_PORT=27017\n" .
                "DB_MONGO_DATABASE=\n" .
                "DB_MONGO_USERNAME=root\n" .
                "DB_MONGO_PASSWORD=\n";

            // Agrega las configuraciones al final del archivo .env
            file_put_contents($envPath, $envContent . $ldapConfig);

            $this->info('Configuraciones de MongoDB agregadas al '. $file);
        } else {
            $this->info('Las configuraciones de MongoDB ya existen en el '.$file);
        }
    }
}
}