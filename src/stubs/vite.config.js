import { fileURLToPath, URL } from 'url';
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';

const plugins = [];
const inputs = [
    'resources/css/app.css',
    'resources/js/app.js' // Siempre se incluye
];

// Carga Vue si existe
if (fs.existsSync('./node_modules/vue')) {
    const vue = require('@vitejs/plugin-vue').default;
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
if (fs.existsSync('./node_modules/react')) {
    const react = require('@vitejs/plugin-react').default;
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

export default defineConfig({
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
});
