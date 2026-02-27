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
    UiConfigTrait
};

use function Laravel\Prompts\select;

class UiConfig extends Command
{
    use UtilityTrait, UiConfigTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "krud:ui-config";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Configura el entorno visual más actualizado";

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
        $options = [
            'New UI (Bootstrap 5)',
            'Switch DashLite demo/layout',
            'Old UI',
        ];

        $selectedUi = select('Which visual environment do you want to configure?', $options);

        if ($selectedUi === 'New UI (Bootstrap 5)') {
            $this->configBootstrap();
            return;
        }

        if ($selectedUi === 'Switch DashLite demo/layout') {
            $this->switchDashliteDemo();
            return;
        }

        if ($selectedUi === 'Old UI') {
            $this->configPrevUI();
            return;
        }
    }
}
