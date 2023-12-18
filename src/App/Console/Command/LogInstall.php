<?php

namespace Icebearsoft\Kitukizuri\App\Console\Command;

use DB;
use Config;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;

use Icebearsoft\Kitukizuri\App\Traits\{
    UtilityTrait,
    LogTrait
};

class LogInstall extends Command
{
    use UtilityTrait, LogTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "krud:log-install";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Genera la configuración para almacenar los logs en base de datos";

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
        // instalación de Vue en proyecto
        $this->configLogChanel();
    }
}