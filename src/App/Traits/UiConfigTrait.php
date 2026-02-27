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
        $this->installDashliteRuntimeDependencies();
        $this->installDashliteBuildDependencies();
        $this->installViteConfigDependencies();
        $this->applyBootstrapBaseConfiguration(true);

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

    protected function verifyBootstrapSetup()
    {
        $this->info('Verificando configuración de New UI (Bootstrap 5)...');

        $this->installDashliteRuntimeDependencies();
        $this->installDashliteBuildDependencies();
        $this->installViteConfigDependencies();
        $this->applyBootstrapBaseConfiguration(false);
        $this->ensureDashliteConfigState();

        $this->info('Verificación completada. La configuración faltante fue aplicada cuando fue necesario.');

        if (confirm('¿Compilar assets ahora?', true)) {
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

    protected function dashliteRuntimeNpmPackages()
    {
        // Base tomada de package.json de DashLite v3.3.0 (demo1..demo9 y covid)
        // + extras usados por la integración del paquete (axios, highcharts, popper).
        return [
            '@fullcalendar/bootstrap5' => '^6.1.17',
            '@popperjs/core' => '^2.11.8',
            '@yaireo/tagify' => '^4.31.3',
            'axios' => '^1.6.8',
            'bootstrap' => '^5.3.6',
            'bootstrap-datepicker' => '^1.10.0',
            'chart.js' => '^4.4.8',
            'clipboard' => '^2.0.11',
            'code-prettify-google' => '^1.0.1',
            'datatables.net' => '^2.3.0',
            'datatables.net-bs4' => '^2.3.0',
            'datatables.net-buttons' => '^3.2.3',
            'datatables.net-buttons-bs4' => '^3.2.3',
            'datatables.net-responsive' => '^3.0.4',
            'datatables.net-responsive-bs4' => '^3.0.4',
            'dragula' => '^3.7.3',
            'dropzone' => '^5.9.3',
            'dual-listbox' => '^2.0.0',
            'fullcalendar' => '^6.1.17',
            'highcharts' => '^12.2.0',
            'isotope-layout' => '^3.0.6',
            'jkanban' => '^1.3.1',
            'jquery' => '^3.7.1',
            'jquery-form' => '^4.3.0',
            'jquery-timepicker' => '^1.3.3',
            'jquery-validation' => '^1.21.0',
            'jstree' => '^3.3.17',
            'jszip' => '^3.10.1',
            'knob' => '^1.1.0',
            'magnific-popup' => '^1.2.0',
            'nouislider' => '^15.8.1',
            'pdfmake' => '^0.2.20',
            'select2' => '^4.0.13',
            'simplebar' => '^6.3.1',
            'slick-carousel' => '^1.8.1',
            'summernote' => '^0.9.1',
            'sweetalert2' => '9.17.2',
            'themify-icons' => '^1.0.0',
            'tinymce' => '^7.9.0',
            'toastr' => '^2.1.4',
        ];
    }

    protected function dashliteBuildNpmPackages()
    {
        return [
            '@vitejs/plugin-legacy' => null,
            'sass' => '^1.64.0',
            'sass-embedded' => null,
        ];
    }

    protected function installDashliteRuntimeDependencies()
    {
        $this->installMissingNpmPackages($this->dashliteRuntimeNpmPackages(), false);
    }

    protected function installDashliteBuildDependencies()
    {
        $this->installMissingNpmPackages($this->dashliteBuildNpmPackages(), true);
    }

    protected function installViteConfigDependencies()
    {
        $viteConfigPath = base_path('vite.config.js');
        if (!file_exists($viteConfigPath)) {
            return;
        }

        $content = file_get_contents($viteConfigPath);
        if ($content === false) {
            return;
        }

        $fromMatches = [];
        $requireMatches = [];
        $dynamicImportMatches = [];
        preg_match_all('/from\s+[\'"]([^\'"]+)[\'"]/', $content, $fromMatches);
        preg_match_all('/require\(\s*[\'"]([^\'"]+)[\'"]\s*\)/', $content, $requireMatches);
        preg_match_all('/import\(\s*[\'"]([^\'"]+)[\'"]\s*\)/', $content, $dynamicImportMatches);

        $specifiers = array_unique(array_merge(
            $fromMatches[1] ?? [],
            $requireMatches[1] ?? [],
            $dynamicImportMatches[1] ?? []
        ));
        $vitePackages = [];

        foreach ($specifiers as $specifier) {
            $packageName = $this->normalizeImportSpecifierToPackage($specifier);
            if ($packageName === null || !$this->isViteRelatedPackage($packageName)) {
                continue;
            }

            $vitePackages[$packageName] = null;
        }

        if (empty($vitePackages)) {
            return;
        }

        $this->installMissingNpmPackages($vitePackages, true);
    }

    protected function normalizeImportSpecifierToPackage($specifier)
    {
        if (
            $specifier === ''
            || str_starts_with($specifier, '.')
            || str_starts_with($specifier, '/')
            || str_starts_with($specifier, 'node:')
            || str_starts_with($specifier, 'virtual:')
        ) {
            return null;
        }

        if (preg_match('/^@[^\/]+\/[^\/]+/', $specifier, $match) === 1) {
            return $match[0];
        }

        $parts = explode('/', $specifier);
        return $parts[0] ?? null;
    }

    protected function isViteRelatedPackage($packageName)
    {
        return $packageName === 'vite'
            || $packageName === 'laravel-vite-plugin'
            || str_starts_with($packageName, '@vitejs/')
            || str_starts_with($packageName, 'vite-plugin-');
    }

    protected function installMissingNpmPackages($requiredPackages, $devDependencies = false)
    {
        $packageData = $this->loadProjectPackageJson();
        if ($packageData === null) {
            $this->warn('No se pudo validar package.json, se omite la instalación automática de npm packages.');
            return;
        }

        $installedPackages = array_merge(
            $packageData['dependencies'] ?? [],
            $packageData['devDependencies'] ?? []
        );

        $missing = [];
        foreach ($requiredPackages as $packageName => $version) {
            if (array_key_exists($packageName, $installedPackages)) {
                continue;
            }

            $missing[$packageName] = $version;
        }

        if (empty($missing)) {
            $this->info('No hay '.($devDependencies ? 'devDependencies' : 'dependencies').' faltantes por instalar.');
            return;
        }

        $packagesToInstall = [];
        foreach ($missing as $packageName => $version) {
            $packagesToInstall[] = $version ? $packageName.'@'.$version : $packageName;
        }

        $saveFlag = $devDependencies ? '--save-dev' : '--save';
        $label = $devDependencies ? 'devDependencies' : 'dependencies';

        $this->info('Instalando '.$label.' faltantes: '.implode(', ', array_keys($missing)));
        $this->runCommands(['npm install '.implode(' ', $packagesToInstall).' '.$saveFlag], base_path());
    }

    protected function loadProjectPackageJson()
    {
        $packageJsonPath = base_path('package.json');
        if (!file_exists($packageJsonPath)) {
            return null;
        }

        $decoded = json_decode((string) file_get_contents($packageJsonPath), true);
        if (!is_array($decoded)) {
            return null;
        }

        return $decoded;
    }

    protected function applyBootstrapBaseConfiguration($forceCopy)
    {
        $this->ensureViteSassEntry();
        $this->replaceInFileIfPresent(base_path('resources/views/layouts/app.blade.php'), 'resources/css/app.css', 'resources/sass/app.scss');
        $this->replaceInFileIfPresent(base_path('resources/views/layouts/guest.blade.php'), 'resources/css/app.css', 'resources/sass/app.scss');
        $this->removePatternInFileIfPresent(base_path('postcss.config.js'), '/\s*tailwindcss\s*:\s*\{\s*\}\s*,?\s*/m');

        if (file_exists(base_path('tailwind.config.js'))) {
            \unlink(base_path('tailwind.config.js'));
        }

        $this->syncKitukizuriResourceDirectory(
            __DIR__.'/../../resources/js',
            base_path('resources/js/'),
            $forceCopy,
            ['app.js', 'app.jsx']
        );
        $this->ensureKrudJsEntryImports(base_path('resources/js/app.js'));
        $this->syncKitukizuriResourceDirectory(__DIR__.'/../../resources/sass', base_path('resources/sass/'), $forceCopy);
        $this->syncKitukizuriResourceDirectory(__DIR__.'/../../resources/fonts', base_path('resources/fonts/'), $forceCopy);
        $this->syncKitukizuriResourceDirectory(__DIR__.'/../../resources/fonts', public_path('fonts/'), $forceCopy);
        $this->syncKitukizuriResourceDirectory(__DIR__.'/../../resources/views', base_path('resources/views/'), $forceCopy);
    }

    protected function ensureViteSassEntry()
    {
        $vitePath = base_path('vite.config.js');

        if (!file_exists($vitePath)) {
            return;
        }

        $content = file_get_contents($vitePath);
        if ($content === false) {
            return;
        }

        if (str_contains($content, 'resources/sass/app.scss')) {
            return;
        }

        $updated = preg_replace(
            '/([\'"])resources\/css\/app\.css\1/',
            "'resources/sass/app.scss'",
            $content,
            1
        );

        if ($updated !== null && $updated !== $content) {
            file_put_contents($vitePath, $updated);
        }
    }

    protected function replaceInFileIfPresent($path, $search, $replace)
    {
        if (!file_exists($path)) {
            return false;
        }

        $content = file_get_contents($path);
        if ($content === false || !str_contains($content, $search)) {
            return false;
        }

        file_put_contents($path, str_replace($search, $replace, $content));

        return true;
    }

    protected function removePatternInFileIfPresent($path, $pattern)
    {
        if (!file_exists($path)) {
            return false;
        }

        $content = file_get_contents($path);
        if ($content === false || preg_match($pattern, $content) !== 1) {
            return false;
        }

        $updated = preg_replace($pattern, '', $content);
        if ($updated === null || $updated === $content) {
            return false;
        }

        file_put_contents($path, $updated);

        return true;
    }

    protected function syncKitukizuriResourceDirectory($source, $target, $forceCopy, $preserveRelativeFiles = [])
    {
        $filesystem = new Filesystem;

        if (!is_dir($source)) {
            return;
        }

        $normalizedPreserve = [];
        foreach ($preserveRelativeFiles as $relativePath) {
            $normalizedPreserve[] = ltrim(str_replace('\\', '/', $relativePath), '/');
        }

        if (!$filesystem->isDirectory($target)) {
            $filesystem->makeDirectory($target, 0755, true);
        }

        $sourceRoot = rtrim(str_replace('\\', '/', $source), '/');
        $targetRoot = rtrim(str_replace('\\', '/', $target), '/');

        foreach ($filesystem->allFiles($source) as $file) {
            $sourcePath = str_replace('\\', '/', $file->getPathname());
            $relative = ltrim(substr($sourcePath, strlen($sourceRoot)), '/');
            $targetPath = $targetRoot.'/'.$relative;
            $shouldPreserve = in_array($relative, $normalizedPreserve, true);

            if ($shouldPreserve && file_exists($targetPath)) {
                continue;
            }

            if (!$forceCopy && file_exists($targetPath)) {
                continue;
            }

            $targetDir = dirname($targetPath);
            if (!$filesystem->isDirectory($targetDir)) {
                $filesystem->makeDirectory($targetDir, 0755, true);
            }

            $filesystem->copy($sourcePath, $targetPath);
        }
    }

    protected function ensureKrudJsEntryImports($appJsPath)
    {
        if (!file_exists($appJsPath)) {
            return;
        }

        $content = file_get_contents($appJsPath);
        if ($content === false) {
            return;
        }

        $missingImports = [];

        if (preg_match('/^\s*import\s+[\'"]\.\/bootstrap(?:\.js)?[\'"]\s*;?/m', $content) !== 1) {
            $missingImports[] = "import './bootstrap';";
        }

        if (preg_match('/^\s*import\s+[\'"]\.\/init\.js[\'"]\s*;?/m', $content) !== 1) {
            $missingImports[] = "import './init.js';";
        }

        if (!empty($missingImports)) {
            $content = implode("\n", $missingImports)."\n".$content;
            file_put_contents($appJsPath, $content);
        }
    }

    protected function ensureDashliteConfigState()
    {
        $variant = $this->resolveConfiguredDashliteVariant();
        if (!$this->validateDashliteVariant($variant)) {
            $this->warn('No fue posible validar la variante DashLite configurada: '.$variant);
            return false;
        }

        $sassPath = base_path('resources/sass');
        $requiredScss = [
            'app.scss',
            'core/_style.scss',
            'core/_layouts.scss',
            'vendors/bundle.scss',
            'extend/bootstrap/_variables.scss',
        ];

        $requiresFullSync = false;
        foreach ($requiredScss as $file) {
            if (!file_exists($sassPath.'/'.$file)) {
                $requiresFullSync = true;
                break;
            }
        }

        if ($requiresFullSync) {
            if (!$this->syncDashliteScssVariant($variant)) {
                $this->warn('No fue posible sincronizar SCSS para la variante configurada: '.$variant);
                return false;
            }
        } else {
            $this->normalizeDashliteBootstrapImports($sassPath);
            $this->ensureCustomImportInAppScss($sassPath.'/app.scss');
        }

        $bodyClass = trim((string) config('kitukizuri.dashliteBodyClass', ''));
        if ($bodyClass === '') {
            $defaultClass = $this->dashliteVariantDefaults()[$variant] ?? 'npc-default has-sidebar';
            $this->writeKitukizuriConfigValue('dashliteBodyClass', "'".str_replace("'", "\\'", $defaultClass)."'");
            $this->info('dashliteBodyClass no estaba definido, se aplicó el preset por defecto de '.$variant.'.');
        }

        return true;
    }

    protected function resolveConfiguredDashliteVariant()
    {
        $available = $this->getAvailableDashliteVariants();
        if (empty($available)) {
            return 'demo3';
        }

        $configured = trim((string) config('kitukizuri.dashliteVariant', ''));
        if (in_array($configured, $available, true)) {
            return $configured;
        }

        $fallback = in_array('demo3', $available, true) ? 'demo3' : $available[0];
        $this->writeKitukizuriConfigValue('dashliteVariant', "'".$fallback."'");
        $this->info('dashliteVariant no estaba definido o era inválido. Se configuró automáticamente en '.$fallback.'.');

        return $fallback;
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
            'demo6' => 'Demo 6 - Invest',
            'demo7' => 'Demo 7 - Rounder',
            'demo8' => 'Demo 8 - No Sidebar',
            'demo9' => 'Demo 9 - Rounder Sidebar',
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
        $this->syncKitukizuriResourceDirectory(
            __DIR__.'/../../resources/fonts',
            base_path('resources/fonts/'),
            false
        );
        $this->syncKitukizuriResourceDirectory(
            __DIR__.'/../../resources/fonts',
            public_path('fonts/'),
            false
        );

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
            $normalizedPath = str_replace('\\', '/', $path);

            if (pathinfo($path, PATHINFO_EXTENSION) !== 'scss') {
                continue;
            }

            $content = file_get_contents($path);
            if ($content === false) {
                continue;
            }

            $normalized = $content;

            if (str_contains($normalized, 'node_modules/bootstrap/scss/')) {
                $normalized = preg_replace(
                '/([\'"])(?:\.\.\/)+node_modules\/bootstrap\/scss\//',
                '$1bootstrap/scss/',
                $normalized
                );
            }

            // DashLite core aggregate files reference "core/*" from inside /core.
            if (
                str_contains($normalizedPath, '/core/')
                && (str_contains($normalized, '@import "core/') || str_contains($normalized, "@import 'core/"))
            ) {
                $normalized = preg_replace(
                    '/(@import\s+[\'"])core\//',
                    '$1',
                    $normalized
                );
            }

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
        $this->installViteConfigDependencies();

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
            'demo6' => 'npc-invest bg-lighter',
            'demo7' => 'ui-rounder npc-default has-sidebar',
            'demo8' => 'bg-lighter',
            'demo9' => 'ui-rounder has-sidebar',
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
