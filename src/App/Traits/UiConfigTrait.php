<?php

namespace Icebearsoft\Kitukizuri\App\Traits;

use Illuminate\Filesystem\Filesystem;

trait UiConfigTrait
{
    protected function configBootstrap()
    {
        // Instalación de paquetes para Bootstrap
        $this->runCommands(['npm install bootstrap@5.3.0 @popperjs/core clipboard jquery --save'], base_path());
        $this->runCommands(['npm install sass @vitejs/plugin-legacy --save-dev'], base_path());

        // configurando SASS
        $this->replaceInFile('resources/css/app.css', 'resources/sass/app.scss', base_path('vite.config.js'));
        $this->replaceInFile('@vite([\'resources/css/app.css\', \'resources/js/app.js\'])', '@vite([\'resources/sass/app.scss\', \'resources/js/app.js\'])', base_path('resources/views/layouts/app.blade.php'));
        $this->replaceInFile('@vite([\'resources/css/app.css\', \'resources/js/app.js\'])', '@vite([\'resources/sass/app.scss\', \'resources/js/app.js\'])', base_path('resources/views/layouts/guest.blade.php'));

        // Eliminando Tailwind
        $this->replaceInFile('tailwindcss: {},', '', base_path('postcss.config.js'));
        \unlink(base_path('tailwind.config.js'));

        (new Filesystem)->copyDirectory(__DIR__.'/../../resources/js', base_path('resources/js/'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../resources/sass', base_path('resources/sass/'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../resources/fonts', base_path('resources/fonts/'));

        $this->runCommands(['npm run build'], base_path());
    }
}