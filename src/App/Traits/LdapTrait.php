<?php

namespace Icebearsoft\Kitukizuri\App\Traits;

trait LdapTrait
{
    protected function configLdap()
    {
        $this->composerInstall('directorytree/ldaprecord-laravel');
        $this->addLdapEnv('.env');
        $this->addLdapEnv('.env.example');

        $this->replaceInFile('Features::registration()', '// Features::registration()', base_path('config/fortify.php'));
        $this->replaceInFile('Features::resetPasswords()', '// Features::resetPasswords()', base_path('config/fortify.php'));
        $this->replaceInFile('Features::updateProfileInformation()', '// Features::updateProfileInformation()', base_path('config/fortify.php'));
        $this->replaceInFile('Features::updatePasswords()', '// Features::updatePasswords()', base_path('config/fortify.php'));

        if (file_exists(base_path('config/auth.php'))) {
            unlink(base_path('config/auth.php'));
        }
        copy(__DIR__ . '/../../stubs/Config/ldap.php', base_path('config/auth.php'));

        if (file_exists(base_path('app/Providers/AuthServiceProvider.php'))) {
            unlink(base_path('app/Providers/AuthServiceProvider.php'));
        }
        copy(__DIR__ . '/../../stubs/Ldap/AuthServiceProvider.stub', base_path('app/Providers/AuthServiceProvider.php'));
        $this->registerLdapAuthProvider();

        if (file_exists(base_path('app/Models/User.php'))) {
            unlink(base_path('app/Models/User.php'));
        }
        copy(__DIR__ . '/../../stubs/Ldap/User.stub', base_path('app/Models/User.php'));
        $this->configureLdapPasskeys();

        copy(__DIR__ . '/../../stubs/Database/2023_08_03_214449_add_ldap_columns_to_users_table.php', base_path('database/migrations/2023_08_03_214449_add_ldap_columns_to_users_table.php'));

        $this->artisanCommand('vendor:publish','--tag=ldap-config');

        if (!file_exists(base_path('app/Ldap/User.php'))) {
            $this->artisanCommand('ldap:make:model', 'User');
        }

    }


    protected function configureLdapPasskeys(): void
    {
        if (!class_exists(\Laravel\Passkeys\Passkeys::class)
            && !file_exists(base_path('vendor/laravel/passkeys/src/Passkeys.php'))) {
            return;
        }

        $userPath = base_path('app/Models/User.php');
        $content = file_get_contents($userPath);
        if (str_contains($content, '\\Laravel\\Passkeys\\Contracts\\PasskeyUser')) {
            return;
        }

        $content = str_replace(
            'implements LdapAuthenticatable',
            'implements LdapAuthenticatable, \\Laravel\\Passkeys\\Contracts\\PasskeyUser',
            $content
        );
        $content = str_replace(
            '    use AuthenticatesWithLdap;',
            "    use AuthenticatesWithLdap;\n    use \\Laravel\\Passkeys\\PasskeyAuthenticatable;",
            $content
        );
        file_put_contents($userPath, $content);

        $this->info('Passkeys configurado para el usuario local con autenticación LDAP');
    }


    protected function registerLdapAuthProvider()
    {
        $providersPath = base_path('bootstrap/providers.php');
        $provider = 'App\\Providers\\AuthServiceProvider::class';

        if (!file_exists($providersPath)) {
            return;
        }

        $content = file_get_contents($providersPath);

        if (str_contains($content, $provider)) {
            return;
        }

        $appProvider = "    App\\Providers\\AppServiceProvider::class,\n";
        $ldapProvider = "    {$provider},\n";

        if (str_contains($content, $appProvider)) {
            $content = str_replace($appProvider, $appProvider . $ldapProvider, $content);
        } else {
            $content = preg_replace('/\];\s*$/', $ldapProvider . '];' . PHP_EOL, $content, 1, $count);

            if (empty($count)) {
                $this->warn('No se pudo registrar AuthServiceProvider LDAP en bootstrap/providers.php');
                return;
            }
        }

        file_put_contents($providersPath, $content);
        $this->info('AuthServiceProvider LDAP registrado en bootstrap/providers.php');
    }

    protected function addLdapEnv($file)
    {
        $envPath = base_path($file);

        if (!file_exists($envPath)) {
            return;
        }

        $envContent = file_get_contents($envPath);

        // Verifica si las configuraciones LDAP ya existen para evitar duplicados
        if (!str_contains($envContent, 'LDAP_LOGGING')) {
            $ldapConfig = "\n" .
                "LDAP_LOGGING=true\n" .
                "LDAP_CONNECTION=default\n" .
                "LDAP_DIRECTORY_TYPE=activedirectory\n" .
                "LDAP_HOST=10.10.10.10\n" .
                "LDAP_USERNAME=\"user@domain.com\"\n" .
                "LDAP_PASSWORD=\"PassWorD\"\n" .
                "LDAP_PORT=389\n" .
                "LDAP_BASE_DN=\"DC=replace,DC=replace\"\n" .
                "LDAP_TIMEOUT=5\n" .
                "LDAP_SSL=false\n" .
                "LDAP_TLS=false\n" .
                "LDAP_SASL=false\n" .
                "LDAP_DEFAULT_ROLE_ID=2\n";

            // Agrega las configuraciones al final del archivo .env
            file_put_contents($envPath, $envContent . $ldapConfig);

            $this->info('Configuraciones LDAP agregadas al '. $file);
        } else {
            $this->info('Las configuraciones LDAP ya existen en el '.$file);
        }
    }
}
