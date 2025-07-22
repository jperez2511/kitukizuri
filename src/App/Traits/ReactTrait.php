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
        $filePath    = base_path('resources/js/app.js');
        $fileContent = file_get_contents($filePath);

        // Verifica si las configuraciones LDAP ya existen para evitar duplicados
        if (!str_contains($fileContent, 'import react from \'@vitejs/plugin-react\';')) {
            $vueConfig = "\n" .
                "import { createApp } from 'vue';\n" .
                "\n" .
                "const app  = createApp({});\n" .
                "\n" .
                "// app.component('tag-component', ComponentName);\n" .
                "\n" .
                "// don't remove this line\n" .
                "\n" .
                "app.mount(\"#vue\");\n";

            // Agrega las configuraciones al final del archivo app.js
            file_put_contents($filePath, $fileContent . $vueConfig);

            $this->info('La configuración para Vue fue agregada exitosamente');
        } else {
            $this->info('Las configuración para Vue ya existen en el app.js');
        }
    }

    protected function addViteConfig()
    {
        unlink(base_path('vite.config.js'));
        copy(__DIR__ . '/../../stubs/vite.config.js', base_path('vite.config.js'));

        $this->replaceInFile('import vue from \'@vitejs/plugin-vue\';', 'import react from \'@vitejs/plugin-react\';', base_path('vite.config.js'));



        $this->info('La configuración de React en Vite fue agregada exitosamente');
    }
 }



 