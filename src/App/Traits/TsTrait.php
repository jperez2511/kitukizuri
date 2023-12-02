<?php

namespace Icebearsoft\Kitukizuri\App\Traits;

trait TsTrait
{
    protected function configTs()
    {
        $this->runCommands(['npm install typescript vue-tsc @vitejs/plugin-vue-jsx @vue/compiler-sfc --save-dev'], base_path());
        $this->addTsConfig();
        $this->addViteConfig();
    }

    protected function addTsConfig()
    {
        copy(__DIR__ . '/../../stubs/tsconfig.json', base_path('tsconfig.json'));
    }

    protected function addViteConfig()
    {
        $filePath    = base_path('vite.config.js');
        $fileContent = file_get_contents($filePath);

        // agregando nuevo import para TS
        $newConfig      = "\nimport vueJsx from '@vitejs/plugin-vue-jsx';\n";
        $pattern        = '/(import[^;]+;\s*)(?!.*import[^;]+;\s*)/s';
        $replacement    = '$1' . $newConfig;
        $newFileContent = preg_replace($pattern, $replacement, $fileContent, 1);

        file_put_contents($filePath, $newFileContent);

        $this->info('La configuración de Vue en Vite fue agregada exitosamente');
    }
 }