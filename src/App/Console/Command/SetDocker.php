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
        $this->info('Configurando Docker...');

        $this->copyDirectory(__DIR__.'/../../../stubs/Docker/dockerfiles/php', base_path('/dockerfiles/php'));
        $this->copyDirectory(__DIR__.'/../../../stubs/Docker/dockerfiles/nginx', base_path('/dockerfiles/nginx'));
        $this->copyDirectory(__DIR__.'/../../../stubs/Docker/dockerfiles/mongo', base_path('/dockerfiles/mongo'));
        copy(__DIR__.'/../../../stubs/Docker/docker-compose.yml', base_path('docker-compose.yml'));

        $databaseEngine = $this->choice('¿Qué base de datos se va a utilizar?', [
            'MySQL',
            'PostgreSQL',
        ]);

        if($databaseEngine === 'MySQL') {
            $this->copyDirectory(__DIR__.'/../../../stubs/Docker/dockerfiles/mysql', base_path('/dockerfiles/mysql'));
            $this->removeDockerBlock(base_path('docker-compose.yml'), 'postgres');
            $this->removeDockerBlock(base_path('docker-compose.yml'), 'postgres_vol');
            \unlink(base_path('/dockerfiles/php/php.docker.postgres'));
            \rename(base_path('/dockerfiles/php/php.docker.mysql'), base_path('/dockerfiles/php/php.docker'));
        } else if($databaseEngine === 'PostgreSQL') {
            $this->copyDirectory(__DIR__.'/../../../stubs/Docker/dockerfiles/postgresql', base_path('/dockerfiles/postgresql'));
            $this->removeDockerBlock(base_path('docker-compose.yml'), 'mysql');
            $this->removeDockerBlock(base_path('docker-compose.yml'), 'mysql_vol');
            \unlink(base_path('/dockerfiles/php/php.docker.mysql'));
            \rename(base_path('/dockerfiles/php/php.docker.postgres'), base_path('/dockerfiles/php/php.docker'));
        }

        $this->info('Archivos configurados correctamente!');

        if($this->confirm('¿Configurar base de datos?')) {           

            $database = $this->ask('Nombre: ');
            $pass = $this->ask('Contraseña: ');

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
            
            $this->info('La base de datos se ha configurado correctamente!');
        }

        if($this->confirm('¿Configurar puertos?')) {
            $http  = $this->ask('HTTP: ');
            $this->replaceInFile('"80:80"', '"'.$http.':80"', base_path('docker-compose.yml'));

            if($databaseEngine === 'MySQL') {
                $mysql = $this->ask('MySQL: ');
                $this->replaceInFile('"3306:3306"', '"'.$mysql.':3306"', base_path('docker-compose.yml'));
            } else {
                $mysql = $this->ask('PostgreSQL: ');
                $this->replaceInFile('"5432:5432"', '"'.$mysql.':5432"', base_path('docker-compose.yml'));
            }

            $mongo = $this->ask('Mongo: ');
            $this->replaceInFile('"27017:27017"', '"'.$mongo.':27017"', base_path('docker-compose.yml'));
            
            $this->info('Los puertos se han configurado correctamente!');
        }
    }
}