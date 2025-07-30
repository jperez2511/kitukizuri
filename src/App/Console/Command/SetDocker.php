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

use function Laravel\Prompts\multiselect;
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

        $baseDockerCompose = base_path('docker-compose.yml');

        $this->copyDirectory(__DIR__.'/../../../stubs/Docker/dockerfiles/php', base_path('/dockerfiles/php'));
        $this->copyDirectory(__DIR__.'/../../../stubs/Docker/dockerfiles/nginx', base_path('/dockerfiles/nginx'));
        $this->copyDirectory(__DIR__.'/../../../stubs/Docker/dockerfiles/mongo', base_path('/dockerfiles/mongo'));
        copy(__DIR__.'/../../../stubs/Docker/docker-compose.yml', $baseDockerCompose);
        \unlink(base_path('/dockerfiles/php/php.docker.alldb'));

        $dbs = [
            'MySQL',
            'PostgreSQL',
            'SQLServer'
        ];

        $databaseEngine = select('¿Que gestor de base de datos se va a utilizar?', $dbs);

        $databaseFiles = [
            'MySQL' => [
                __DIR__.'/../../../stubs/Docker/dockerfiles/mysql',
                base_path('/dockerfiles/mysql'),
                'mysql',
                'vol_ms',
                base_path('/dockerfiles/php/php.docker.mysql'),
                [
                    '"MYSQL_DATABASE=DataBaseName"',
                    '"MYSQL_DATABASE=ReplaceDataBaseName"',
                    $baseDockerCompose
                ],
                [
                    '"MYSQL_ROOT_PASSWORD=rootPassword"',
                    '"MYSQL_ROOT_PASSWORD=ReplaceRootPassword"',
                    $baseDockerCompose
                ],
                'DB_HOST=mysql',
                [
                    '"3306:3306"', '"replacePort:3306"', $baseDockerCompose
                ]
            ],
            'PostgreSQL' => [
                __DIR__.'/../../../stubs/Docker/dockerfiles/postgresql',
                base_path('/dockerfiles/postgresql'),
                'postgres',
                'vol_pg',
                base_path('/dockerfiles/php/php.docker.postgres'),
                [
                    '"POSTGRES_DB=DataBaseName"',
                    '"POSTGRES_DB=ReplaceDataBaseName"',
                    $baseDockerCompose
                ],
                [
                    '"POSTGRES_PASSWORD=rootPassword"',
                    '"POSTGRES_PASSWORD=ReplaceRootPassword"',
                    $baseDockerCompose
                ],
                'DB_HOST=pgsql',
                [
                    '"5432:5432"', '"replacePort:5432"', $baseDockerCompose
                ]
            ],
            'SQLServer' => [
                __DIR__.'/../../../stubs/Docker/dockerfiles/sqlserver',
                base_path('/dockerfiles/sqlserver'),
                'sqlserver',
                'vol_sql',
                base_path('/dockerfiles/php/php.docker.sqlserver'),
                [
                    '"MSSQL_DATABASE=DataBaseName"',
                    '"MSSQL_DATABASE=ReplaceDataBaseName"',
                    $baseDockerCompose
                ],
                [
                    '"SA_PASSWORD=rootPassword"',
                    '"SA_PASSWORD=ReplaceRootPassword"',
                    $baseDockerCompose
                ],
                'DB_HOST=sqlsrv',
                [
                    '"1433:1433"', '"replacePort:1433"', $baseDockerCompose
                ]
            ]
        ];

        // 2. copiando los archivos de configuración de docker
        $this->copyDirectory($databaseFiles[$databaseEngine][0], $databaseFiles[$databaseEngine][1]);

        foreach ($dbs as $db) {
            if($db != $databaseEngine) {
                $this->removeDockerBlock($baseDockerCompose, $databaseFiles[$db][2]);
                $this->removeDockerBlock($baseDockerCompose, $databaseFiles[$db][3]);
            }
        }
        
        \rename($databaseFiles[$databaseEngine][4], base_path('/dockerfiles/php/php.docker'));

        // 3. eliminando archivos innecesarios
        foreach ($databaseFiles as $key => $value) {
            if($key == $databaseEngine) {
                continue;
            }
            \unlink($value[4]);
        }

        info('Archivos configurados correctamente!');


        // 4 configurando base de datos principal
        if(confirm('¿Configurar base de datos?')) {           

            $database = text('Nombre: ');
            $pass = text('Contraseña: ');

            $databaseFiles[$databaseEngine][5][1] = str_replace('ReplaceDataBaseName', $database, $databaseFiles[$databaseEngine][5][1]);
            $databaseFiles[$databaseEngine][6][1] = str_replace('ReplaceRootPassword', $database, $databaseFiles[$databaseEngine][6][1]);

            // Update docker-compose.yml
            $this->replaceInFile(...$databaseFiles[$databaseEngine][5]);
            $this->replaceInFile(...$databaseFiles[$databaseEngine][6]);


            $this->replaceInFile('"MONGO_INITDB_ROOT_PASSWORD=rootPassword"', '"MONGO_INITDB_ROOT_PASSWORD='.$pass.'"', base_path('docker-compose.yml'));
            $this->replaceInFile('"MONGO_ROOT_PASSWORD=rootPassword"', '"MONGO_ROOT_PASSWORD='.$pass.'"', base_path('docker-compose.yml'));

            // Update .env y .env.example
            if(file_exists(base_path('.env.example'))) {

                $this->replaceInFile('DB_HOST=127.0.0.1', $databaseFiles[$databaseEngine][7], base_path('.env.example'));

                $this->replaceInFile('DB_DATABASE=laravel', 'DB_DATABASE='.$database, base_path('.env.example'));
                $this->replaceInFile('DB_PASSWORD=', 'DB_PASSWORD='.$pass, base_path('.env.example'));
            }

            if(file_exists(base_path('.env'))) {
                $this->replaceInFile('DB_HOST=127.0.0.1', $databaseFiles[$databaseEngine][7], base_path('.env'));

                $this->replaceInFile('DB_DATABASE=laravel', 'DB_DATABASE='.$database, base_path('.env'));
                $this->replaceInFile('DB_PASSWORD=', 'DB_PASSWORD='.$pass, base_path('.env'));
            }
            
            info('La base de datos se ha configurado correctamente!');
        }

        // 5. configurando otros clientes 
        if(confirm('¿Configurar otros clientes de DB?')) {

            $dbClients = $dbs;
            unset($dbClients[array_search($databaseEngine, $dbClients)]);

            $dbClientsSelected = multiselect('¿Que gestor de base de datos se va a utilizar?', $dbClients);
            $dbClientsSelected[] = $databaseEngine;

            $phpDockerFile = base_path('/dockerfiles/php/php.docker');

            \unlink($phpDockerFile);
            copy(__DIR__.'/../../../stubs/Docker/dockerfiles/php/php.docker.alldb', $phpDockerFile);

            foreach ($dbs as $db) {
                if(!in_array($db, $dbClientsSelected)) {
                    $this->removeDockerBlock($phpDockerFile, $databaseFiles[$db][2]);
                }
            }
        }

        // 6. configurando puertos
        if(confirm('¿Configurar puertos?')) {
            $http  = text('HTTP: ');
            $this->replaceInFile('"80:80"', '"'.$http.':80"', base_path('docker-compose.yml'));

            $portDB = text($databaseEngine.': ');

            $databaseFiles[$databaseEngine][8][1] = str_replace('replacePort', $portDB, $databaseFiles[$databaseEngine][8][1]);
            $this->replaceInFile(...$databaseFiles[$databaseEngine][8]);

            $mongo = text('Mongo: ');
            $this->replaceInFile('"27017:27017"', '"'.$mongo.':27017"', base_path('docker-compose.yml'));
            
            info('Los puertos se han configurado correctamente!');
        }
    }
}