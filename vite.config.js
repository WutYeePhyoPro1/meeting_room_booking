import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: [
                ...refreshPaths,
                'app/Livewire/**',
            ],

        }),
    ],
    build:{ chunkSizeWarningLimit : 1000 },
    resolve: {
        alias: {
            '$': 'jQuery'
        },
    },
    // server: {
    //     port: 3000,
    //     open: true,
    // },
});
