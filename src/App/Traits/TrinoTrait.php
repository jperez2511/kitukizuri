<?php

namespace Icebearsoft\Kitukizuri\App\Traits;

trait TrinoTrait
{
    protected function configTrino()
    {
        $this->addTrinoEnv('.env');
        $this->addTrinoEnv('.env.example');

        unlink(base_path('config/services.php'));
        copy(__DIR__ . '/../../stubs/Config/services.php', base_path('config/services.php'));
    }

    protected function addTrinoEnv($file)
    {
        $envPath = base_path($file);
        $envContent = file_get_contents($envPath);

        // Verifica si las configuraciones LDAP ya existen para evitar duplicados
        if (!str_contains($envContent, 'TRINO_HOST')) {
            $ldapConfig = "\n" .
                "TRINO_HOST=trino\n" .
                "TRINO_PORT=8080\n";

            // Agrega las configuraciones al final del archivo .env
            file_put_contents($envPath, $envContent . $ldapConfig);

            $this->info('Configuración de Trino agregadas al '. $file);
        } else {
            $this->info('Las configuración de Trino ya existen en el '.$file);
        }
    }
}