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
    ReactTrait
};

class ReactInstall extends Command
{
    use UtilityTrait, ReactTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "krud:react-install {--vite-config}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Instala y configura las dependencias de React para Laravel";

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
        if($this->option('vite-config')) {
            $this->addReactConfig();
        } else {
            $this->configReact();
        }
    }
}