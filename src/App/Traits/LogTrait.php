<?php

namespace Icebearsoft\Kitukizuri\App\Traits;

use File;

trait LogTrait
{
    protected function configLogChanel()
    {
        $path = config_path('logging.php');
        $contents = File::get($path);

        if (str_contains($contents, 'Icebearsoft\\Kitukizuri\\App\\Logging\\DatabaseLogger::class')) {
            $this->info('los logs ya fueron agregados en el config para almacenarse en la base de datos');
        } else {
            $newConfig = <<<EOD

        'database' => [
            'driver' => 'custom',
            'via' => Icebearsoft\Kitukizuri\App\Logging\DatabaseLogger::class,
            'level' => env('LOG_LEVEL', 'debug'),
        ],

EOD;

            $position = strpos($contents, "'channels' => [");
            if ($position !== false) {
                $position += strlen("'channels' => [");
                $contents = substr_replace($contents, $newConfig, $position, 0);
                File::put($path, $contents);
            }
        }

        $this->setLogChannelEnv('database');

        // configuración de tablas en base de datos
        $this->artisanCommand('vendor:publish', '--tag=krud-migrations');
        $this->artisanCommand('migrate');
        $this->artisanCommand('config:clear');

        $this->info('Configuración de base de datos actualizada con éxito.');
    }

    protected function setLogChannelEnv($channel)
    {
        $envPath = base_path('.env');

        if (!file_exists($envPath)) {
            return;
        }

        $contents = File::get($envPath);

        if (preg_match('/^LOG_CHANNEL=.*$/m', $contents) === 1) {
            $contents = preg_replace('/^LOG_CHANNEL=.*$/m', 'LOG_CHANNEL='.$channel, $contents, 1);
        } else {
            $contents = rtrim($contents).PHP_EOL.'LOG_CHANNEL='.$channel.PHP_EOL;
        }

        File::put($envPath, $contents);
    }
}
