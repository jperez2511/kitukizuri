import { fileURLToPath, URL } from 'url';
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';

const packageJson = JSON.parse(fs.readFileSync(new URL('./package.json', import.meta.url), 'utf8'));
const projectPackages = {
    ...packageJson.dependencies,
    ...packageJson.devDependencies,
};
const hasProjectPackage = (packageName) => Object.prototype.hasOwnProperty.call(projectPackages, packageName);

export default defineConfig(async () => {
    const plugins = [];
    const inputs = ['resources/js/app.js']; // Siempre se incluye

    if (fs.existsSync('./resources/sass/app.scss')) {
        inputs.unshift('resources/sass/app.scss');
    } else if (fs.existsSync('./resources/css/app.css')) {
        inputs.unshift('resources/css/app.css');
    }

    // Carga Vue si existe
    if (hasProjectPackage('vue') && hasProjectPackage('@vitejs/plugin-vue')) {
        const vue = (await import('@vitejs/plugin-vue')).default;
        plugins.push(vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }));
    }

    // Carga React si existe
    if (hasProjectPackage('react') && hasProjectPackage('@vitejs/plugin-react')) {
        const react = (await import('@vitejs/plugin-react')).default;
        plugins.push(react());

        // Agrega app.jsx sólo si existe
        if (fs.existsSync('./resources/js/app.jsx')) {
            inputs.push('resources/js/app.jsx');
        }
    }

    // Laravel Vite Plugin
    plugins.push(
        laravel({
            input: inputs,
            refresh: true,
        })
    );

    return {
        plugins,
        resolve: {
            alias: {
                vue: 'vue/dist/vue.esm-bundler.js',
                '@': fileURLToPath(new URL('./resources', import.meta.url)),
            },
        },
        optimizeDeps: {
            entries: ['./resources/**/*.vue', './resources/**/*.jsx']
        }
    };
});
