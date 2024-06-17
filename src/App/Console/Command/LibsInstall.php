<?php

namespace Icebearsoft\Kitukizuri\App\Console\Command;

use DB;
use Config;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;

use Icebearsoft\Kitukizuri\App\Traits\{
    UtilityTrait
};

class LibsInstall extends Command
{
    use UtilityTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "krud:libs-install";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Instala y configura las dependencias de Javascript que utiliza el proyecto";

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
        // instalación de dependencias Krud
        $this->runCommands(['npm install'], __DIR__.'/../../../');
        $this->runCommands(['npm install --save-dev gulp'], __DIR__.'/../../../');
        $this->runCommands(['npx gulp build'], __DIR__.'/../../../');
        $this->deleteDirectory(__DIR__.'/../../../node_modules');
        $this->info('instalación de dependencias Kitu Kizuri terminada exitosamente');
        $this->artisanCommand('vendor:publish','--tag=krud-public');
    }
}