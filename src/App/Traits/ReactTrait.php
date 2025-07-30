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
    }

    protected function addReactConfig()
    {
        // 1. integrando archivos app.jsx y vite.config.js 
        if (file_exists(base_path('resources/js/app.jsx'))) {
            unlink(base_path('resources/js/app.jsx'));
        }

        copy(__DIR__ . '/../../stubs/resources/js/app.jsx', base_path('resources/js/app.jsx'));
        
        if(file_exists(base_path('vite.config.js'))) {
            unlink(base_path('vite.config.js'));
        }

        copy(__DIR__ . '/../../stubs/vite.config.js', base_path('vite.config.js'));
        
        // 2. Integrando a template de app.blade.php
        $filePath = base_path('resources/views/layouts/app.blade.php');

        $this->replaceInFile($filePath, "@vite(['resources/sass/app.scss', 'resources/js/app.js'])", "@viteReactRefresh\n@vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/js/app.jsx'])");

        $this->info('La configuración para React fue agregada exitosamente');
    }
}



 