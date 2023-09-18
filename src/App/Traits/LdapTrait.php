<?php

namespace Icebearsoft\Kitukizuri\App\Traits;

trait LdapTrait 
{
    protected function configLdap()
    {
        $this->composerInstall('directorytree/ldaprecord-laravel');
        $this->artisanCommand('vendor:publish','--provider="LdapRecord\Laravel\LdapServiceProvider"'); 
        $this->addLdapEnv('.env');
        $this->addLdapEnv('.env.example');
        unlink(base_path('app/Providers/AuthServiceProvider.php'));
        copy(__DIR__ . '/../../../stubs/Ldap/AuthServiceProvider.stub', base_path('app/Providers/AuthServiceProvider.php'));
    }

    protected function addLdapEnv($file) 
    {
        $envPath = base_path($file);
        $envContent = file_get_contents($envPath);

        // Verifica si las configuraciones LDAP ya existen para evitar duplicados
        if (!str_contains($envContent, 'LDAP_LOGGING')) {
            $ldapConfig = "\n" .
                "LDAP_LOGGING=true\n" .
                "LDAP_CONNECTION=default\n" .
                "LDAP_HOST=10.10.10.10\n" .
                "LDAP_USERNAME=\"user@domain.com\"\n" .
                "LDAP_PASSWORD=\"PassWorD\"\n" .
                "LDAP_PORT=389\n" .
                "LDAP_BASE_DN=\"DC=replace,DC=replace\"\n" .
                "LDAP_TIMEOUT=5\n" .
                "LDAP_SSL=false\n" .
                "LDAP_TLS=false\n" .
                "LDAP_SASL=false\n";

            // Agrega las configuraciones al final del archivo .env
            file_put_contents($envPath, $envContent . $ldapConfig);

            $this->info('Configuraciones LDAP agregadas al .env');
        } else {
            $this->info('Las configuraciones LDAP ya existen en el .env');
        }
    }
}