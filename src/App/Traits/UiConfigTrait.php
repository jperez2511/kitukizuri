<?php

namespace Icebearsoft\Kitukizuri\App\Traits;

use Illuminate\Filesystem\Filesystem;

trait UiConfigTrait
{
    protected function configBootstrap()
    {
        // Instalación de paquetes para Bootstrap
        $components = [
            'bootstrap@5.3.3', 
            '@popperjs/core', 
            'clipboard', 
            'jquery', 
            'datatables.net',
            'datatables.net-bs4',
            'datatables.net-buttons-bs4',
            'datatables.net-responsive',
            'datatables.net-responsive-bs4',
            'datatables.net-buttons',
            'datatables.net-buttons-bs4',
            'jszip',
            'pdfmake',
            'select2',
        ];

        $this->runCommands(['npm install '.implode(' ',$components).' --save'], base_path());
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
        (new Filesystem)->copyDirectory(__DIR__.'/../../resources/views', base_path('resources/views/'));

        $this->runCommands(['npm run build'], base_path());
    }

    protected function configPrevUI()
    {
        // instalación de dependencias Krud
        $this->runCommands(['npm install'], __DIR__.'/../../../');
        $this->runCommands(['npm install --save-dev gulp'], __DIR__.'/../../../');
        $this->runCommands(['npx gulp build'], __DIR__.'/../../../');
        $this->deleteDirectory(__DIR__.'/../../../node_modules');

        $this->info('instalación de dependencias Kitu Kizuri terminada exitosamente');

        $this->artisanCommand('vendor:publish','--tag=krud-views');
        $this->artisanCommand('vendor:publish','--tag=krud-app');
        $this->artisanCommand('vendor:publish','--tag=krud-public');
    }
}