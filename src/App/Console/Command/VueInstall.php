<?php

namespace Icebearsoft\Kitukizuri\App\Console\Command;

use DB;
use Config;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;

use Icebearsoft\Kitukizuri\App\Traits\{
    LdapTrait,
    UtilityTrait,
    VueTrait,
    MongoTrait,
    TrinoTrait
};

class VueInstall extends Command
{
    use UtilityTrait, LdapTrait, VueTrait, MongoTrait, TrinoTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "krud:vue-install";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Instala y configura las dependencias de VUE para Laravel";

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
        $this->configVue();
    }
}