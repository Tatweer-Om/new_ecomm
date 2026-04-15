import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
         laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'Modules/User/Resources/js/app.js', // add module assets here
                'Modules/User/Resources/css/css.js', // add module assets here
            ],
            refresh: true,
        }),
        vue(),
        tailwindcss(),
    ],
});
