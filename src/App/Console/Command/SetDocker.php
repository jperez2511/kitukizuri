<?php

namespace Icebearsoft\Kitukizuri\App\Console\Command;

use DB;
use Config;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;

use Icebearsoft\Kitukizuri\App\Traits\UtilityTrait;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;
use function Laravel\Prompts\info;

class SetDocker extends Command
{
    use UtilityTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "krud:set-docker";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Genera los archivos necesarios para utilizar docker en el proyecto";

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

        // 1. preguntando que base de datos se va a utilizar
        info('Configurando Docker...');

        $this->copyDirectory(__DIR__.'/../../../stubs/Docker/dockerfiles/php', base_path('/dockerfiles/php'));
        $this->copyDirectory(__DIR__.'/../../../stubs/Docker/dockerfiles/nginx', base_path('/dockerfiles/nginx'));
        $this->copyDirectory(__DIR__.'/../../../stubs/Docker/dockerfiles/mongo', base_path('/dockerfiles/mongo'));
        copy(__DIR__.'/../../../stubs/Docker/docker-compose.yml', base_path('docker-compose.yml'));

        $databaseEngine = select('¿Que gestor de base de datos se va a utilizar?', [
            'MySQL',
            'PostgreSQL',
            'SQLServer'
        ]);

        $databaseFiles = [
            'MySQL' => [
                __DIR__.'/../../../stubs/Docker/dockerfiles/mysql',
                base_path('/dockerfiles/mysql'),
                'mysql',
                'vol_ms',
                base_path('/dockerfiles/php/php.docker.mysql'),
            ],
            'PostgreSQL' => [
                __DIR__.'/../../../stubs/Docker/dockerfiles/postgresql',
                base_path('/dockerfiles/postgresql'),
                'postgres',
                'vol_pg',
                base_path('/dockerfiles/php/php.docker.postgres'),
            ],
            'SQLServer' => [
                __DIR__.'/../../../stubs/Docker/dockerfiles/sqlserver',
                base_path('/dockerfiles/sqlserver'),
                'sqlserver',
                'vol_sql',
                base_path('/dockerfiles/php/php.docker.sqlserver'),
            ]
        ];

        // 2. copiando los archivos de configuración de docker
        $this->copyDirectory($databaseEngineFiles[$databaseEngine][0], $databaseEngineFiles[$databaseEngine][1]);
        $this->removeDockerBlock(base_path('docker-compose.yml'), $databaseEngineFiles[$databaseEngine][2]);
        $this->removeDockerBlock(base_path('docker-compose.yml'), $databaseEngineFiles[$databaseEngine][3]);
        \rename($databaseEngineFiles[$databaseEngine][4], base_path('/dockerfiles/php/php.docker'));

        // 3. eliminando archivos innecesarios
        foreach ($dataFiles as $key => $value) {
            if($key == $databaseEngine) {
                continue;
            }
            \unlink($value[4]);
        }

        info('Archivos configurados correctamente!');

        if(confirm('¿Configurar base de datos?')) {           

            $database = text('Nombre: ');
            $pass = text('Contraseña: ');

            // Update docker-compose.yml
            if($databaseEngine === 'MySQL') {
                $this->replaceInFile('"MYSQL_DATABASE=DataBaseName"', '"MYSQL_DATABASE='.$database.'"', base_path('docker-compose.yml'));
                $this->replaceInFile('"MYSQL_ROOT_PASSWORD=rootPassword"', '"MYSQL_ROOT_PASSWORD='.$pass.'"', base_path('docker-compose.yml'));
            } else {
                $this->replaceInFile('"POSTGRES_DB=DataBaseName"', '"POSTGRES_DB='.$database.'"', base_path('docker-compose.yml'));
                $this->replaceInFile('"POSTGRES_PASSWORD=rootPassword"', '"POSTGRES_PASSWORD='.$pass.'"', base_path('docker-compose.yml'));
            }

            $this->replaceInFile('"MONGO_INITDB_ROOT_PASSWORD=rootPassword"', '"MONGO_INITDB_ROOT_PASSWORD='.$pass.'"', base_path('docker-compose.yml'));
            $this->replaceInFile('"MONGO_ROOT_PASSWORD=rootPassword"', '"MONGO_ROOT_PASSWORD='.$pass.'"', base_path('docker-compose.yml'));

            // Update .env y .env.example
            if(file_exists(base_path('.env.example'))) {

                if($databaseEngine === 'MySQL') {
                    $this->replaceInFile('DB_CONNECTION=mysql', 'DB_CONNECTION=mysql', base_path('.env.example'));
                    $this->replaceInFile('DB_HOST=127.0.0.1', 'DB_HOST=mysql', base_path('.env.example'));
                } else {
                    $this->replaceInFile('DB_CONNECTION=pgsql', 'DB_CONNECTION=pgsql', base_path('.env.example'));
                    $this->replaceInFile('DB_HOST=127.0.0.1', 'DB_HOST=pgsql', base_path('.env.example'));
                }
                
                $this->replaceInFile('DB_DATABASE=laravel', 'DB_DATABASE='.$database, base_path('.env.example'));
                $this->replaceInFile('DB_PASSWORD=', 'DB_PASSWORD='.$pass, base_path('.env.example'));
            }

            if(file_exists(base_path('.env'))) {
                if($databaseEngine === 'MySQL') {
                    $this->replaceInFile('DB_CONNECTION=mysql', 'DB_CONNECTION=mysql', base_path('.env'));
                    $this->replaceInFile('DB_HOST=127.0.0.1', 'DB_HOST=mysql', base_path('.env'));
                } else {
                    $this->replaceInFile('DB_CONNECTION=pgsql', 'DB_CONNECTION=pgsql', base_path('.env'));
                    $this->replaceInFile('DB_HOST=127.0.0.1', 'DB_HOST=pgsql', base_path('.env'));
                }

                $this->replaceInFile('DB_DATABASE=laravel', 'DB_DATABASE='.$database, base_path('.env'));
                $this->replaceInFile('DB_PASSWORD=', 'DB_PASSWORD='.$pass, base_path('.env'));
            }
            
            info('La base de datos se ha configurado correctamente!');
        }

        if(confirm('¿Configurar puertos?')) {
            $http  = text('HTTP: ');
            $this->replaceInFile('"80:80"', '"'.$http.':80"', base_path('docker-compose.yml'));

            if($databaseEngine === 'MySQL') {
                $mysql = text('MySQL: ');
                $this->replaceInFile('"3306:3306"', '"'.$mysql.':3306"', base_path('docker-compose.yml'));
            } else {
                $mysql = text('PostgreSQL: ');
                $this->replaceInFile('"5432:5432"', '"'.$mysql.':5432"', base_path('docker-compose.yml'));
            }

            $mongo = text('Mongo: ');
            $this->replaceInFile('"27017:27017"', '"'.$mongo.':27017"', base_path('docker-compose.yml'));
            
            info('Los puertos se han configurado correctamente!');
        }
    }
}