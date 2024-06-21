<?php

namespace Icebearsoft\Kitukizuri\App\Traits;

trait UiConfigTrait
{
    protected function configBootstrap()
    {
        // Instalación de paquetes para Bootstrap
        $this->runCommands(['npm install bootstrap@5.3.0 @popperjs/core --save'], base_path());
        $this->runCommands(['npm install sass --save-dev'], base_path());

        // configurando SASS
        $this->replaceInFile('resources/css/app.css', 'resources/sass/app.scss', base_path('vite.config.js'));

        // Eliminando Tailwind
        $this->replaceInFile('tailwindcss: {},', '', base_path('postcss.config.js'));
        \unlink(base_path('tailwind.config.js'));

    }
}