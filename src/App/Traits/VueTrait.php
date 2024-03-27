<?php

namespace Icebearsoft\Kitukizuri\App\Traits;

use Illuminate\Filesystem\Filesystem;

trait VueTrait
{
    protected function configVue()
    {
        $this->runCommands(['npm install --save vue@latest @vitejs/plugin-vue'], base_path());
        $this->addVueConfig();
        $this->addViteConfig();
    }

    protected function addVueUI()
    {
        $dependencies = [
            'vuetify@3.5.3',
            'vite-plugin-vuetify@2.0.1',
            'vue3-perfect-scrollbar@1.6.1',
            'vue-tabler-icons@2.21.0',
            '@mdi/font@7.4.47',
            'sass@1.70.0',
        ];

        $this->runCommands(['npm install --save '. implode(' ', $dependencies)], base_path());

        copy(__DIR__ . '/../../stubs/resources/ts/app.ui.ts', base_path('resources/ts/app.ts'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/resources/ts/plugins', base_path('resources/ts/plugins'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/resources/ts/Components', base_path('resources/ts/Components'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/resources/scss', base_path('resources/scss'));
    }

    protected function addVueConfig()
    {
        $filePath    = base_path('resources/js/app.js');
        $fileContent = file_get_contents($filePath);

        // Verifica si las configuraciones LDAP ya existen para evitar duplicados
        if (!str_contains($fileContent, 'import { createApp } from \'vue\';')) {
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
        $this->info('La configuración de Vue en Vite fue agregada exitosamente');
    }
 }