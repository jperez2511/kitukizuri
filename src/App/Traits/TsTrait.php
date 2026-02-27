<?php

namespace Icebearsoft\Kitukizuri\App\Traits;

trait TsTrait
{
    protected function configTs()
    {
        $this->runCommands(['npm install typescript vue-tsc @vue/tsconfig @vue/compiler-sfc --save-dev'], base_path());
        $this->addTsConfig();
        $this->addViteConfigTs();
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

    protected function addViteConfigTs()
    {
        $this->replaceInFile('vite build', 'vue-tsc --noEmit && vite build', base_path('package.json'));
        $this->ensureTsViteInput();

        $this->info('La configuración de TypeScript en Vite fue agregada exitosamente');
    }

    protected function ensureTsViteInput()
    {
        $vitePath = base_path('vite.config.js');
        if (!file_exists($vitePath)) {
            return;
        }

        $content = file_get_contents($vitePath);
        if ($content === false || str_contains($content, 'resources/ts/app.ts')) {
            return;
        }

        $updated = preg_replace_callback(
            '/input\s*:\s*\[([^\]]*)\]/s',
            static function ($matches) {
                $inner = $matches[1];
                $trimmed = trim($inner);

                if ($trimmed === '') {
                    return "input: ['resources/ts/app.ts']";
                }

                if (preg_match('/,\s*$/', $inner) === 1) {
                    return "input: [".$inner." 'resources/ts/app.ts']";
                }

                return "input: [".$inner.", 'resources/ts/app.ts']";
            },
            $content,
            1,
            $staticCount
        );

        if ($updated !== null && $staticCount > 0 && $updated !== $content) {
            file_put_contents($vitePath, $updated);
            return;
        }

        $updated = preg_replace_callback(
            '/const\s+inputs\s*=\s*\[([^\]]*)\]\s*;/s',
            static function ($matches) {
                $inner = $matches[1];
                $trimmed = trim($inner);

                if ($trimmed === '') {
                    return "const inputs = ['resources/ts/app.ts'];";
                }

                if (preg_match('/,\s*$/', $inner) === 1) {
                    return "const inputs = [".$inner." 'resources/ts/app.ts'];";
                }

                return "const inputs = [".$inner.", 'resources/ts/app.ts'];";
            },
            $content,
            1,
            $dynamicCount
        );

        if ($updated !== null && $dynamicCount > 0 && $updated !== $content) {
            file_put_contents($vitePath, $updated);
        }
    }
 }
