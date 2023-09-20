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
        (new Filesystem)->copyDirectory(__DIR__.'/../../../stubs/Docker', base_path('/'));
        $this->info('Archivos configurados correctamente!');


        if($this->confirm('¿Configurar base de datos?')) {
            $database = $this->ask('Nombre: ');
            $pass = $this->ask('Contraseña: ');

            // Update docker-compose.yml
            $this->replaceInFile('"MYSQL_ROOT_PASSWORD=rootPassword"', '"MYSQL_ROOT_PASSWORD='.$pass.'"', base_path('docker-compose.yml'));
            $this->replaceInFile('"MYSQL_DATABASE=DataBaseName"', '"MYSQL_DATABASE='.$database.'"', base_path('docker-compose.yml'));
            $this->replaceInFile('"MONGO_INITDB_ROOT_PASSWORD=rootPassword"', '"MONGO_INITDB_ROOT_PASSWORD='.$pass.'"', base_path('docker-compose.yml'));

            // Update .env y .env.example
            $this->replaceInFile('DB_HOST=127.0.0.1', 'DB_HOST=mysql', base_path('.env'));
            $this->replaceInFile('DB_DATABASE=laravel', 'DB_DATABASE='.$database, base_path('.env'));
            $this->replaceInFile('DB_PASSWORD=', 'DB_PASSWORD='.$pass, base_path('.env'));

            $this->replaceInFile('DB_HOST=127.0.0.1', 'DB_HOST=mysql', base_path('.env.example'));
            $this->replaceInFile('DB_DATABASE=laravel', 'DB_DATABASE='.$database, base_path('.env.example'));
            $this->replaceInFile('DB_PASSWORD=', 'DB_PASSWORD='.$pass, base_path('.env.example'));

            $this->info('La base de datos se ha configurado correctamente!');
        }

        if($this->confirm('¿Configurar puertos?')) {
            $http  = $this->ask('HTTP: ');
            $mysql = $this->ask('MySQL: ');
            $mongo = $this->ask('Mongo: ');

            // Update docker-compose.yml
            $this->replaceInFile('"80:80"', '"'.$http.':80"', base_path('docker-compose.yml'));
            $this->replaceInFile('"3306:3306"', '"'.$mysql.':3306"', base_path('docker-compose.yml'));
            $this->replaceInFile('"27017:27017"', '"'.$mongo.':27017"', base_path('docker-compose.yml'));

            $this->info('Los puertos se han configurado correctamente!');
        }
    }
}