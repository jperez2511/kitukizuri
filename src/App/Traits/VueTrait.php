<?php

namespace Icebearsoft\Kitukizuri\App\Traits;

trait VueTrait
{
    protected function configVue()
    {
        $this->runCommands(['npm install --save vue@latest'], base_path());
        $this->runCommands(['npm install --save @vitejs/plugin-vue'], base_path());
        $this->addVueConfig();
        $this->addViteConfig();
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

            // Agrega las configuraciones al final del archivo app.js
            file_put_contents($filePath, $fileContent . $vueConfig);

            $this->info('La configuración para Vue fue agregada exitosamente');
        } else {
            $this->info('Las configuración para Vue ya existen en el app.js');
        }
    }

    protected function addViteConfig()
    {
        $filePath    = base_path('vite.config.js');
        $fileContent = file_get_contents($filePath);

        $lines = explode("\n", $fileContent);

        if(count($lines) >= 3) {

            $lines[2] = "import vue from '@vitejs/plugin-vue';\n" . $lines[2];

            $pluginContent  = "        vue({\n";
            $pluginContent .= "            template: {\n";
            $pluginContent .= "                transformAssetUrls: {\n";
            $pluginContent .= "                    base: null,\n";
            $pluginContent .= "                    includeAbsolute: false,\n";
            $pluginContent .= "                },\n";
            $pluginContent .= "            },\n";
            $pluginContent .= "        }),\n";

            $lines[5] = $pluginContent . $lines[5];

            $pluginContent  = "    resolve: {\n";
            $pluginContent .= "        alias: {\n";
            $pluginContent .= "            vue: 'vue/dist/vue.esm-bundler.js'\n";
            $pluginContent .= "        },\n";
            $pluginContent .= "    },\n";

            $lines[16] = $pluginContent . $lines[16];

            file_put_contents($filePath, implode("\n", $lines));
        } else {
            $this->error('El archivo vite.config.js contiene la configuración previa');
            return false;
        }

        $this->info('La configuración de Vue en Vite fue agregada exitosamente');
    }
 }