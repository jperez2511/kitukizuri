<?php

namespace Icebearsoft\Kitukizuri\App\Traits;

use File;

trait LogTrait
{
    protected function configLogChanel()
    {
        $path      = config_path('logging.php');
        $contents  = File::get($path);

        if(strpos($contents, 'database')) {
            $this->info('los logs ya fueron agregados en el config para almacenarse en la base de datos');
        } else {
            $newConfig = <<<EOD
                \n
                    'database' => [
                        'driver' => 'custom',
                        'via' => Icebearsoft\Kitukizuri\App\Logging\DatabaseLogger::class,
                    ],
            EOD;

            $position = strpos($contents, "'channels' => [");
            if ($position !== false) {
                $position += strlen("'channels' => [");
                $contents = substr_replace($contents, $newConfig, $position, 0);
                // Guarda el archivo modificado
                File::put($path, $contents);
            }
        }

        $this->replaceInFile('LOG_CHANNEL=stack', 'LOG_CHANNEL=database', base_path('.env'));

        // configuración de tablas en base de datos
        $this->artisanCommand('vendor:publish','--tag=krud-migrations');
        $this->artisanCommand('migrate');

        $this->info('Configuración de base de datos actualizada con éxito.');
    }
}