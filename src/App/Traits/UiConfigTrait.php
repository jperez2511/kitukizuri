<?php

namespace Icebearsoft\Kitukizuri\App\Traits;

use Illuminate\Filesystem\Filesystem;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

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
            'highcharts',
        ];

        $this->runCommands(['npm install '.implode(' ',$components).' --save'], base_path());
        $this->runCommands(['npm install -D sass-embedded'], base_path());
        $this->runCommands(['npm install sass @vitejs/plugin-legacy --save-dev'], base_path());

        // configurando SASS
        $this->replaceInFile('resources/css/app.css', 'resources/sass/app.scss', base_path('vite.config.js'));
        $this->replaceInFile('@vite([\'resources/css/app.css\', \'resources/js/app.js\'])', '@vite([\'resources/sass/app.scss\', \'resources/js/app.js\'])', base_path('resources/views/layouts/app.blade.php'));
        $this->replaceInFile('@vite([\'resources/css/app.css\', \'resources/js/app.js\'])', '@vite([\'resources/sass/app.scss\', \'resources/js/app.js\'])', base_path('resources/views/layouts/guest.blade.php'));

        // Eliminando Tailwind
        $this->replaceInFile('tailwindcss: {},', '', base_path('postcss.config.js'));
        
        if(file_exists(base_path('tailwind.config.js'))) {
            \unlink(base_path('tailwind.config.js'));
        }

        (new Filesystem)->copyDirectory(__DIR__.'/../../resources/js', base_path('resources/js/'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../resources/sass', base_path('resources/sass/'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../resources/fonts', base_path('resources/fonts/'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../resources/views', base_path('resources/views/'));

        $this->configureDashliteDemoAndLayout();

        $this->runViteBuild();
    }

    protected function switchDashliteDemo()
    {
        $configured = $this->configureDashliteDemoAndLayout();

        if ($configured && confirm('¿Compilar assets ahora?', true)) {
            $this->runViteBuild();
        }
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

    protected function configureDashliteDemoAndLayout()
    {
        $variant = $this->promptDashliteVariant();

        if (!$this->validateDashliteVariant($variant)) {
            $this->warn('La variante seleccionada no cumple la estructura esperada de DashLite.');
            return false;
        }

        if (!$this->syncDashliteScssVariant($variant)) {
            $this->warn('No fue posible sincronizar los SCSS del demo seleccionado.');
            return false;
        }

        $selectedClass = $this->configureDashliteLayoutPreset($variant);

        $variantValue = str_replace("'", "\\'", $variant);
        $this->writeKitukizuriConfigValue('dashliteVariant', "'".$variantValue."'");

        $this->info('DashLite demo configured: '.$variant);
        $this->info('DashLite layout configured: '.$selectedClass);

        return true;
    }

    protected function promptDashliteVariant()
    {
        $available = $this->getAvailableDashliteVariants();
        if (empty($available)) {
            $this->warn('No se encontraron variantes SCSS de DashLite empaquetadas, se usará demo3.');
            return 'demo3';
        }

        $labels = $this->buildDashliteVariantOptions($available);
        $selectedLabel = select('Selecciona el demo de DashLite', array_keys($labels));

        return $labels[$selectedLabel];
    }

    protected function getDashliteVariantsPath()
    {
        return __DIR__.'/../../resources/dashlite-scss';
    }

    protected function getDashliteVariantCatalog()
    {
        return [
            'demo1' => 'Demo 1 - General Sidebar',
            'demo2' => 'Demo 2 - Default Sidebar',
            'demo3' => 'Demo 3 - Apps Sidebar',
            'demo4' => 'Demo 4 - Aside Layout',
            'demo5' => 'Demo 5 - White/Crypto',
            'covid' => 'Covid - Clean Rounder',
        ];
    }

    protected function getAvailableDashliteVariants()
    {
        $variants = [];
        $base = $this->getDashliteVariantsPath();

        foreach (array_keys($this->getDashliteVariantCatalog()) as $variant) {
            if (file_exists($base.'/'.$variant.'/dashlite.scss')) {
                $variants[] = $variant;
            }
        }

        return $variants;
    }

    protected function buildDashliteVariantOptions($variants)
    {
        $labels = [];
        $preset = $this->dashliteVariantDefaults();
        $catalog = $this->getDashliteVariantCatalog();

        foreach ($variants as $variant) {
            $bodyClass = $preset[$variant] ?? 'npc-default has-sidebar';
            $label = ($catalog[$variant] ?? $variant).' ('.$bodyClass.')';
            $labels[$label] = $variant;
        }

        return $labels;
    }

    protected function getDashliteVariantDisplayName($variant)
    {
        $catalog = $this->getDashliteVariantCatalog();
        return $catalog[$variant] ?? $variant;
    }

    protected function syncDashliteScssVariant($variant)
    {
        $source = $this->getDashliteVariantsPath().'/'.$variant;

        if (!is_dir($source)) {
            return false;
        }

        $target = base_path('resources/sass');
        $filesystem = new Filesystem;

        if ($filesystem->isDirectory($target)) {
            $filesystem->deleteDirectory($target);
        }

        $filesystem->copyDirectory($source, $target);
        $this->normalizeDashliteBootstrapImports($target);

        $entryFile = $target.'/app.scss';
        $dashliteEntry = $target.'/dashlite.scss';

        if (!file_exists($entryFile) && file_exists($dashliteEntry)) {
            copy($dashliteEntry, $entryFile);
        }

        if (!file_exists($entryFile)) {
            return false;
        }

        $customSource = __DIR__.'/../../resources/sass/_custom.scss';
        if (file_exists($customSource)) {
            copy($customSource, $target.'/_custom.scss');
        }

        $this->ensureCustomImportInAppScss($entryFile);

        return true;
    }

    protected function normalizeDashliteBootstrapImports($sassPath)
    {
        if (!is_dir($sassPath)) {
            return;
        }

        $filesystem = new Filesystem;

        foreach ($filesystem->allFiles($sassPath) as $file) {
            $path = $file->getPathname();

            if (pathinfo($path, PATHINFO_EXTENSION) !== 'scss') {
                continue;
            }

            $content = file_get_contents($path);
            if ($content === false || !str_contains($content, 'node_modules/bootstrap/scss/')) {
                continue;
            }

            $normalized = preg_replace(
                '/([\'"])(?:\.\.\/)+node_modules\/bootstrap\/scss\//',
                '$1bootstrap/scss/',
                $content
            );

            if ($normalized !== null && $normalized !== $content) {
                file_put_contents($path, $normalized);
            }
        }
    }

    protected function validateDashliteVariant($variant)
    {
        $base = $this->getDashliteVariantsPath().'/'.$variant;
        $required = [
            'dashlite.scss',
            'core/_style.scss',
            'core/_layouts.scss',
            'vendors/bundle.scss',
            'extend/bootstrap/_variables.scss',
        ];

        $missing = [];

        foreach ($required as $file) {
            if (!file_exists($base.'/'.$file)) {
                $missing[] = $file;
            }
        }

        $hasDashliteVars = file_exists($base.'/dashlite_variables.scss') || file_exists($base.'/_dashlite_variables.scss');
        if (!$hasDashliteVars) {
            $missing[] = 'dashlite_variables.scss |_dashlite_variables.scss';
        }

        if (!empty($missing)) {
            $this->warn('Archivos faltantes en la variante '.$variant.': '.implode(', ', $missing));
            return false;
        }

        return true;
    }

    protected function ensureCustomImportInAppScss($appScssPath)
    {
        $content = file_get_contents($appScssPath);

        if (preg_match('/@import\s+[\'"]custom[\'"]\s*;/', $content)) {
            return;
        }

        $bootstrapImport = '@import "extend/bootstrap/variables";';
        if (str_contains($content, $bootstrapImport)) {
            $content = str_replace($bootstrapImport, '@import "custom";'."\n".$bootstrapImport, $content);
            file_put_contents($appScssPath, $content);
            return;
        }

        if (preg_match('/@import\s+[\'"]_?dashlite_variables[\'"]\s*;/', $content, $match)) {
            $content = preg_replace(
                '/'.preg_quote($match[0], '/').'/',
                $match[0]."\n".'@import "custom";',
                $content,
                1
            );
        } else {
            $content = '@import "custom";'."\n".$content;
        }

        file_put_contents($appScssPath, $content);
    }

    protected function runViteBuild()
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            $this->runCommands(['npm run build'], base_path());
            return;
        }

        $commands = [
            'if [ -f node_modules/.bin/vite ]; then chmod +x node_modules/.bin/vite; fi',
            'if [ -f node_modules/vite/bin/vite.js ]; then chmod +x node_modules/vite/bin/vite.js; fi',
            'npm run build || node ./node_modules/vite/bin/vite.js build',
        ];

        $this->runCommands($commands, base_path());
    }

    protected function configureDashliteLayoutPreset($variant)
    {
        $displayName = $this->getDashliteVariantDisplayName($variant);
        $defaultPreset = 'Recomendado para '.$displayName;
        $customOption  = 'Clases personalizadas';
        $presets       = $this->dashliteLayoutPresets($variant);

        $options = array_keys($presets);
        $options[] = $customOption;

        $selectedPreset = select('Selecciona clases <body> para DashLite', $options);
        $selectedClass  = $presets[$selectedPreset] ?? '';

        if ($selectedPreset === $customOption) {
            $selectedClass = trim((string) text('Escribe clases personalizadas para <body>', '', $presets[$defaultPreset]));
            if ($selectedClass === '') {
                $selectedClass = $presets[$defaultPreset];
            }
        }

        $selectedClass = preg_replace('/\s+/', ' ', trim($selectedClass));
        $selectedClass = str_replace("'", "\\'", $selectedClass);

        $this->writeKitukizuriConfigValue('dashliteBodyClass', "'".$selectedClass."'");

        return $selectedClass;
    }

    protected function dashliteLayoutPresets($variant)
    {
        $defaults = $this->dashliteVariantDefaults();
        $recommended = $defaults[$variant] ?? 'npc-default has-sidebar';
        $displayName = $this->getDashliteVariantDisplayName($variant);

        return [
            'Recomendado para '.$displayName => $recommended,
            'Default + Apps Sidebar' => 'npc-default has-apps-sidebar has-sidebar',
            'Default Sidebar' => 'bg-lighter npc-default has-sidebar',
            'General Sidebar' => 'bg-lighter npc-general has-sidebar',
            'White Sidebar' => 'bg-white has-sidebar',
            'Rounder Sidebar' => 'ui-rounder npc-default has-sidebar',
            'Demo4 / Aside' => 'bg-white npc-default has-aside',
            'Covid Clean' => 'npc-covid has-sidebar has-sidebar-short ui-clean ui-rounder',
        ];
    }

    protected function dashliteVariantDefaults()
    {
        return [
            'covid' => 'npc-covid has-sidebar has-sidebar-short ui-clean ui-rounder',
            'demo1' => 'bg-lighter npc-general has-sidebar',
            'demo2' => 'bg-lighter npc-default has-sidebar',
            'demo3' => 'npc-default has-apps-sidebar has-sidebar',
            'demo4' => 'bg-white npc-default has-aside',
            'demo5' => 'bg-white has-sidebar',
        ];
    }

    protected function writeKitukizuriConfigValue($key, $value)
    {
        $configPath = base_path('config/kitukizuri.php');
        if (!file_exists($configPath)) {
            $this->warn('No se encontró config/kitukizuri.php para guardar el layout de DashLite.');
            return;
        }

        $content = file_get_contents($configPath);
        $pattern = "/('".preg_quote($key, '/')."'\\s*=>\\s*)([^\\n,]+)(,)/";

        if (preg_match($pattern, $content) === 1) {
            $content = preg_replace($pattern, '$1'.$value.'$3', $content, 1);
        } else {
            $newItem = "    '".$key."' => ".$value.",\n";
            $anchor = "'preUi'  => false,";

            if (str_contains($content, $anchor)) {
                $content = str_replace($anchor, $anchor."\n\n".$newItem, $content);
            } else {
                $content = preg_replace('/\n\];\s*$/', "\n".$newItem."];", $content, 1);
            }
        }

        file_put_contents($configPath, $content);
    }
}
