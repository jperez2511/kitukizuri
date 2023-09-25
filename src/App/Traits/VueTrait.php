<?php

namespace Icebearsoft\Kitukizuri\App\Traits;

trait VueTrait
{
    protected function configVue()
    {
        $this->runCommands(['npm install --save vue@latest'], base_path());
        $this->runCommands(['npm install --save @vitejs/plugin-vue'], base_path());
        $this->addVueConfig();
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
                "app.mount(\"#vue\");\n";

            // Agrega las configuraciones al final del archivo .env
            file_put_contents($filePath, $fileContent . $vueConfig);

            $this->info('La configuración para Vue fue agregada exitosamente');
        } else {
            $this->info('Las configuración para Vue ya existen en el app.js');
        }
    }
}