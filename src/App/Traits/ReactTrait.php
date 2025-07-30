<?php

namespace Icebearsoft\Kitukizuri\App\Traits;

use Illuminate\Filesystem\Filesystem;

trait ReactTrait
{
    protected function configReact()
    {
        $this->runCommands(['npm install --save react react-dom'], base_path());
        $this->runCommands(['npm install --save-dev @vitejs/plugin-react'], base_path());
        $this->addReactConfig();
        $this->addViteConfig();
    }

    protected function addReactConfig()
    {
       copy(__DIR__ . '/../../stubs/resources/js/app.jsx', base_path('resources/js/app.jsx'));
        $this->info('La configuración para React fue agregada exitosamente');
    }
}



 