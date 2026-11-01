import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

// Fonts live in resources/fonts and are bundled by Vite (laravel-vite-plugin turns off the
// public directory, so fonts under /public would not load while `npm run dev` is running).
// There is no external font service: the site has to load on slow connections.
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
