import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: [
                'resources/views/**',
                'routes/**',
                'app/**',
            ],
        }),
    ],
    server: {
        host: '127.0.0.1',
        port: 5173,
        strictPort: false,
        hmr: {
            host: '127.0.0.1',
        },
    },
    build: {
        // Silence the 500kb warning (ApexCharts + Flowbite are large)
        chunkSizeWarningLimit: 1500,
        rollupOptions: {
            output: {
                manualChunks: {
                    'flowbite': ['flowbite'],
                    'charts': ['apexcharts'],
                },
            },
        },
    },
});