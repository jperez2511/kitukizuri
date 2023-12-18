<?php

namespace Icebearsoft\Kitukizuri\App\Traits;

trait LogTrait
{
    protected function configLogChanel()
    {
        $path      = config_path('logging.php');
        $contents  = File::get($path);

        if(strpos($contents, 'dblog')) {
            $this->info('los logs ya fueron configurados para almacenarse en la base de datos');
            return;
        }

        $newConfig = <<<EOD
            \n
            'database' => [
                'driver' => 'custom',
                'via' => Icebearsoft\Kitukizuri\App\Logging\DatabaseLogger::class,
            ],
        EOD;

        $this->replaceInFile('LOG_CHANNEL=stack', 'LOG_CHANNEL=database', base_path('.env'));
    }
}