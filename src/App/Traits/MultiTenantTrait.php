<?php

namespace Icebearsoft\Kitukizuri\App\Traits;

use File;

trait MultiTenantTrait
{
    protected function configMultiTenant()
    {
        if(file_exists(base_path('.env'))) {
            $this->addTenantEnv('.env');
            $this->replaceInFile('SESSION_DRIVER=database', 'SESSION_DRIVER=file', base_path('.env.example'));
            $this->replaceInFile('CACHE_STORE=database', 'CACHE_STORE=file', base_path('.env.example'));
        }
        
        if(file_exists(base_path('.env.example'))) {
            $this->addTenantEnv('.env.example');
            $this->replaceInFile('SESSION_DRIVER=database', 'SESSION_DRIVER=file', base_path('.env.example'));
            $this->replaceInFile('CACHE_STORE=database', 'CACHE_STORE=file', base_path('.env.example'));
            
        }

        $this->setDatabaseConfigTenant();
    }

    private function addTenantEnv($file)
    {
        $envPath = base_path($file);
        $envContent = file_get_contents($envPath);

        // Verifica si las configuraciones de Mongo ya existen para evitar duplicados
        if (!str_contains($envContent, 'TENANTS_CONNECTION')) {
            $config = "\n" .
                "TENANTS_CONNECTION=tenants\n" .
                "TENANTS_HOST=mysql\n" .
                "TENANTS_PORT=3306\n" .
                "TENANTS_DATABASE=tenants\n" .
                "TENANTS_USERNAME=root\n" .
                "TENANTS_PASSWORD=GestionHotelera2024.\n";

            // Agrega las configuraciones al final del archivo .env
            file_put_contents($envPath, $envContent . $config);

            $this->info('Configuraciones multi tenants agregadas al '. $file);
        } else {
            $this->info('Las configuraciones multi tenants ya existen en el '.$file);
        }
    }

    private function setDatabaseConfigTenant()
    {
        $path      = config_path('database.php');
        $contents  = File::get($path);

        if(strpos($contents, 'tenants')) {
            $this->info('La configuración multi tenant ya existe en el archivo database.php');
            return;
        }

        $newConfig = <<<EOD
                \n
                'tenants' => [
                    'driver'         => 'mysql',
                    'url'            => env('DATABASE_URL_TENANTS'),
                    'host'           => env('TENANTS_HOST', '127.0.0.1'),
                    'port'           => env('TENANTS_PORT', '3306'),
                    'database'       => env('TENANTS_DATABASE', 'forge'),
                    'username'       => env('TENANTS_USERNAME', 'forge'),
                    'password'       => env('TENANTS_PASSWORD', ''),
                    'unix_socket'    => env('TENANTS_SOCKET', ''),
                    'charset'        => 'utf8mb4',
                    'collation'      => 'utf8mb4_unicode_ci',
                    'prefix'         => '',
                    'prefix_indexes' => true,
                    'strict'         => true,
                    'engine'         => null,
                    'options'        => extension_loaded('pdo_mysql') ? array_filter([
                        PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
                    ]) : [],
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