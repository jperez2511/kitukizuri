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
        copy(__DIR__ . '/../../stubs/tsconfig.json', base_path('tsconfig.json'));
        copy(__DIR__ . '/../../stubs/tsconfig.vite-config.json', base_path('tsconfig.vite-config.json'));
    }

    protected function addViteConfig()
    {
        $this->replaceInFile('vite build', 'vue-tsc --noEmit && vite build', base_path('package.json'));

        $filePath = base_path('vite.config.js');
        $fileContent = file_get_contents($filePath);

        $newInput       = "                \n'resources/js/app.ts',";
        $pattern        = '/(input: \[[^\]]+)(\s*\])/';
        $replacement    = '$1' . $newInput . '$2';
        $newFileContent = preg_replace($pattern, $replacement, $fileContent);

        file_put_contents($filePath, $newFileContent);

        $this->info('La configuración de TypeScript en Vite fue agregada exitosamente');
    }
 }