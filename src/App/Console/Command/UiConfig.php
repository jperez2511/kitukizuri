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
        $environmentOptions = [
            'Kitukizuri UI (Bootstrap 5)',
            'Kitukizuri Legacy UI',
        ];

        $selectedEnvironment = select('Selecciona el entorno visual que quieres configurar', $environmentOptions);

        if ($selectedEnvironment === 'Kitukizuri Legacy UI') {
            $this->configPrevUI();
            return;
        }

        $kitukizuriUiActions = [
            'Instalar / Configurar Kitukizuri UI',
            'Verificar / Reparar Kitukizuri UI',
            'Cambiar layout de Kitukizuri',
        ];

        $selectedAction = select('Selecciona la acción para Kitukizuri UI', $kitukizuriUiActions);

        if ($selectedAction === 'Instalar / Configurar Kitukizuri UI') {
            $this->configBootstrap();
            return;
        }

        if ($selectedAction === 'Verificar / Reparar Kitukizuri UI') {
            $this->verifyBootstrapSetup();
            return;
        }

        if ($selectedAction === 'Cambiar layout de Kitukizuri') {
            $this->switchDashliteDemo();
            return;
        }
    }
}
