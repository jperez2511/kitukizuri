<?php

namespace Icebearsoft\Kitukizuri\App\Traits;

trait TsTrait
{
    protected function configTs()
    {
        $this->runCommands(['npm install typescript vue-tsc @vue/tsconfig @vue/compiler-sfc --save-dev'], base_path());
        $this->addTsConfig();
        $this->addViteConfig();
    }

    protected function addTsConfig()
    {
        $destinationPath = base_path('resources/ts');
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        copy(__DIR__ . '/../../stubs/resources/ts/app.ts', $destinationPath . '/app.ts');
        copy(__DIR__ . '/../../stubs/tsconfig.json', base_path('tsconfig.json'));
        copy(__DIR__ . '/../../stubs/tsconfig.vite-config.json', base_path('tsconfig.vite-config.json'));
    }

    protected function addViteConfig()
    {
        $this->replaceInFile('vite build', 'vue-tsc --noEmit && vite build', base_path('package.json'));

        $this->replaceInFile("'resources/css/app.css', 'resources/js/app.js'", "'resources/css/app.css', 'resources/js/app.js', 'resources/ts/app.ts'", base_path('vite.config.js'));

        $this->info('La configuración de TypeScript en Vite fue agregada exitosamente');
    }
 }