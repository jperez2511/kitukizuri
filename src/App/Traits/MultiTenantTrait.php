<?php

namespace Icebearsoft\Kitukizuri\App\Traits;

use DB;
use File;
use Config;

use function Laravel\Prompts\text;

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

        // Verifica si las configuraciones ya existen para evitar duplicados
        if (!str_contains($envContent, 'TENANTS_CONNECTION')) {
            // Solo se declara lo que difiere de la base principal. Host, puerto y
            // credenciales se heredan de DB_*, igual que la base de la aplicacion.
            $config = "\n" .
                "# Multi tenants: la conexion 'tenants' hereda DB_HOST, DB_PORT, DB_USERNAME y DB_PASSWORD.\n" .
                "# Defina TENANTS_HOST, TENANTS_PORT, TENANTS_USERNAME o TENANTS_PASSWORD\n" .
                "# solo si la base central de tenants vive en otro servidor o con otras credenciales.\n" .
                "TENANTS_CONNECTION=tenants\n" .
                "TENANTS_DATABASE=tenants\n";

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
                    'host'           => env('TENANTS_HOST', env('DB_HOST', '127.0.0.1')),
                    'port'           => env('TENANTS_PORT', env('DB_PORT', '3306')),
                    'database'       => env('TENANTS_DATABASE', 'forge'),
                    'username'       => env('TENANTS_USERNAME', env('DB_USERNAME', 'forge')),
                    'password'       => env('TENANTS_PASSWORD', env('DB_PASSWORD', '')),
                    'unix_socket'    => env('TENANTS_SOCKET', env('DB_SOCKET', '')),
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

    /**
     * Lee pares CLAVE=VALOR de un archivo .env.
     */
    private function readEnvKeys(string $file): array
    {
        $values = [];

        foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#') || ! str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);

            $values[trim($key)] = trim(trim($value), "\"'");
        }

        return $values;
    }

    /**
     * Configuración de la conexión "tenants" resuelta para el proceso actual.
     *
     * Lee los valores del .env ya escrito porque env() sigue sirviendo el
     * repositorio cargado al arrancar, antes de que configMultiTenant() escribiera el archivo.
     */
    protected function tenantsConnectionConfig(): array
    {
        $file   = base_path('.env');
        $values = file_exists($file) ? $this->readEnvKeys($file) : [];

        $get = function (string $key, $default = null) use ($values) {
            return array_key_exists($key, $values) ? $values[$key] : env($key, $default);
        };

        return [
            'driver'         => 'mysql',
            'url'            => $get('DATABASE_URL_TENANTS'),
            'host'           => $get('TENANTS_HOST', $get('DB_HOST', '127.0.0.1')),
            'port'           => $get('TENANTS_PORT', $get('DB_PORT', '3306')),
            'database'       => $get('TENANTS_DATABASE', 'forge'),
            'username'       => $get('TENANTS_USERNAME', $get('DB_USERNAME', 'forge')),
            'password'       => $get('TENANTS_PASSWORD', $get('DB_PASSWORD', '')),
            'unix_socket'    => $get('TENANTS_SOCKET', $get('DB_SOCKET', '')),
            'charset'        => 'utf8mb4',
            'collation'      => 'utf8mb4_unicode_ci',
            'prefix'         => '',
            'prefix_indexes' => true,
            'strict'         => true,
            'engine'         => null,
            'options'        => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ];
    }

    /**
     * Crea la base de datos central de tenants si el servidor MySQL no la tiene.
     */
    private function ensureTenantsDatabase(): bool
    {
        $config = Config::get('database.connections.tenants', []);

        if (empty($config)) {
            $this->error('No se encontro la conexion "tenants" en config/database.php.');

            return false;
        }

        if (($config['driver'] ?? null) !== 'mysql') {
            $this->warn('La conexion "tenants" no es mysql, se omite la creacion automatica de la base de datos.');

            return true;
        }

        $database = $config['database'] ?? '';

        if (empty($database)) {
            $this->error('TENANTS_DATABASE esta vacio, define la base de datos central de tenants.');

            return false;
        }

        if (! preg_match('/^[A-Za-z0-9_$-]+$/', $database)) {
            $this->error('TENANTS_DATABASE tiene un nombre no valido: '.$database);

            return false;
        }

        $dsn = empty($config['unix_socket'])
            ? 'mysql:host='.($config['host'] ?? '127.0.0.1').';port='.($config['port'] ?? '3306')
            : 'mysql:unix_socket='.$config['unix_socket'];

        try {
            $pdo = new \PDO($dsn, $config['username'] ?? '', $config['password'] ?? '', [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            ]);
        } catch (\Exception $e) {
            $this->error('No se pudo conectar al servidor MySQL de tenants: '.$e->getMessage());

            return false;
        }

        $stmt = $pdo->prepare('SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?');
        $stmt->execute([$database]);

        if ($stmt->fetchColumn()) {
            $this->info('La base de datos de tenants "'.$database.'" ya existe.');

            return true;
        }

        $charset   = $config['charset'] ?? 'utf8mb4';
        $collation = $config['collation'] ?? 'utf8mb4_unicode_ci';

        if (! preg_match('/^[A-Za-z0-9_]+$/', $charset) || ! preg_match('/^[A-Za-z0-9_]+$/', $collation)) {
            $this->error('Charset o collation no validos en la conexion "tenants".');

            return false;
        }

        $pdo->exec('CREATE DATABASE `'.$database.'` CHARACTER SET '.$charset.' COLLATE '.$collation);

        $this->info('Base de datos de tenants "'.$database.'" creada correctamente.');

        return true;
    }

    /**
     * Crea la base central, aplica las migraciones de tenants y registra el primer
     * tenant apuntando a la base de datos recien migrada.
     */
    protected function bootstrapTenants(): bool
    {
        Config::set('database.connections.tenants', $this->tenantsConnectionConfig());

        if (Config::get('database.default') !== 'mysql') {
            $this->warn('La conexion por defecto no es mysql, el middleware de tenants solo resuelve conexiones mysql.');

            return false;
        }

        if (! $this->ensureTenantsDatabase()) {
            return false;
        }

        $this->artisanCommand('migrate:tts');

        try {
            $central = DB::connection('tenants');

            foreach (['empresas', 'tenants'] as $table) {
                if (! $central->getSchemaBuilder()->hasTable($table)) {
                    $this->error('La tabla "'.$table.'" no existe en la base de datos de tenants. Revise la salida de migrate:tts.');

                    return false;
                }
            }

            if ($central->table('tenants')->where('activo', true)->exists()) {
                $this->info('La base de datos de tenants ya tiene tenants activos, se omite crear el primero.');

                return $this->enableMultiTenantsConfig();
            }
        } catch (\Exception $e) {
            $this->error('No se pudo verificar la base de datos de tenants: '.$e->getMessage());

            return false;
        }

        if (! $this->createFirstTenant()) {
            return false;
        }

        return $this->enableMultiTenantsConfig();
    }

    /**
     * Activa "multiTenants" en la config publicada. Solo se llama cuando la base
     * central, las tablas y el tenant ya quedaron registrados.
     */
    private function enableMultiTenantsConfig(): bool
    {
        $path = config_path('kitukizuri.php');

        if (! file_exists($path)) {
            $this->error('No se encontro config/kitukizuri.php');

            return false;
        }

        $contents = File::get($path);

        if (preg_match("/'multiTenants'\s*=>\s*true/", $contents)) {
            $this->info('"multiTenants" ya estaba activo en config/kitukizuri.php.');

            return true;
        }

        $updated = preg_replace("/('multiTenants'\s*=>\s*)(?:true|false)/", '${1}true', $contents, 1);

        if ($updated === null || $updated === $contents) {
            $this->error('No se encontro la clave "multiTenants" en config/kitukizuri.php.');

            return false;
        }

        File::put($path, $updated);

        $this->info('"multiTenants" activado en config/kitukizuri.php.');

        if (app()->configurationIsCached()) {
            $this->warn('La configuracion esta cacheada, ejecute "php artisan config:clear" para que el cambio surta efecto.');
        }

        return true;
    }

    /**
     * Indica donde activar el modo multi tenants cuando la instalacion no quedo lista.
     */
    protected function multiTenantsHint(): void
    {
        $this->warn('El modo multi tenants quedo desactivado. Cuando la configuracion este lista,');
        $this->warn('active "multiTenants" => true en config/kitukizuri.php');
    }

    /**
     * Registra la empresa y el primer tenant.
     */
    private function createFirstTenant(): bool
    {
        $mysql = Config::get('database.connections.mysql', []);
        $mongo = Config::get('database.connections.mongodb', []);

        $dominio = trim((string) text('Dominio del primer tenant', 'ejemplo: miapp.com', 'localhost'));
        $dominio = (string) preg_replace('/:\d+$/', '', $dominio);

        if ($dominio === '') {
            $this->error('El dominio del tenant no puede quedar vacio.');

            return false;
        }

        $razonSocial    = trim((string) text('Razon social', '', 'Empresa'));
        $nit            = trim((string) text('NIT', '', 'C/F'));
        $nombreContacto = trim((string) text('Nombre de contacto', '', 'Administrador'));
        $emailContacto  = trim((string) text('Email de contacto', '', 'admin@mail.com'));
        $telefono       = trim((string) text('Telefono', '', '00000000'));
        $direccion      = trim((string) text('Direccion', '', 'Sin direccion'));

        try {
            $central = DB::connection('tenants');

            $empresaId = $central->table('empresas')->insertGetId([
                'razon_social'     => $razonSocial,
                'nit'              => $nit,
                'nombre_contacto'  => $nombreContacto,
                'email_contacto'   => $emailContacto,
                'telefono'         => $telefono,
                'direccion'        => $direccion,
                'activo'           => true,
            ]);

            $central->table('tenants')->insert([
                'empresa_id'         => $empresaId,
                'dominio'            => $dominio,
                'db'                 => $mysql['database'] ?? '',
                'db_host'            => $mysql['host'] ?? '',
                'db_username'        => $mysql['username'] ?? '',
                'db_password'        => $mysql['password'] ?? '',
                'mongo_db'           => $mongo['database'] ?? '',
                'mongo_db_host'      => $mongo['host'] ?? '',
                'mongo_db_username'  => $mongo['username'] ?? '',
                'mongo_db_password'  => $mongo['password'] ?? '',
                'activo'             => true,
            ]);
        } catch (\Exception $e) {
            $this->error('No se pudo crear el primer tenant: '.$e->getMessage());

            return false;
        }

        $this->info('Primer tenant "'.$dominio.'" creado sobre la base de datos '.($mysql['database'] ?? '').'.');

        return true;
    }
 }