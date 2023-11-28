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
            for ($i=0; $i < count($lines); $i++) {
                if($i == 2) {
                    array_splice($lines, $i + 1, 0, "import vue from '@vitejs/plugin-vue';\n");
                } else if (\str_contains($i == 4)) {
                    $pluginContent = str_pad("vue({\n", 8, " ", STR_PAD_LEFT);
                    $pluginContent .= str_pad("template: {\n", 12, " ", STR_PAD_LEFT);
                    $pluginContent .= str_pad("transformAssetUrls: {\n", 16, " ", STR_PAD_LEFT);
                    $pluginContent .= str_pad("base:null,\n", 20, " ", STR_PAD_LEFT);
                    $pluginContent .= str_pad("includeAbsolute:false,\n", 20, " ", STR_PAD_LEFT);
                    $pluginContent .= str_pad("},\n", 16, " ", STR_PAD_LEFT);
                    $pluginContent .= str_pad("},\n", 12, " ", STR_PAD_LEFT);
                    $pluginContent .= str_pad("}),\n", 8, " ", STR_PAD_LEFT);

                    array_splice($lines, $i + 1, 0, $pluginContent);
                } else if ($i == 15) {
                    $pluginContent = str_pad("resolve: {\n", 4, " ", STR_PAD_LEFT);
                    $pluginContent .= str_pad("alias: {\n", 8, " ", STR_PAD_LEFT);
                    $pluginContent .= str_pad("vue: 'vue/dist/vue.esm-bundler.js'\n", 12, " ", STR_PAD_LEFT);
                    $pluginContent .= str_pad("},\n", 8, " ", STR_PAD_LEFT);
                    $pluginContent .= str_pad("},\n", 4, " ", STR_PAD_LEFT);
                    array_splice($lines, $i + 1, 0, $pluginContent);
                }
            }

            file_put_contents($filePath, implode("\n", $lines));
        } else {
            $this->error('El archivo vite.config.js contiene la configuración previa');
            return false;
        }

        $this->info('La configuración de Vue en Vite fue agregada exitosamente');
    }
 }