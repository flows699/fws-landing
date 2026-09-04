import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: [
                // A `latin-ext` subset nélkül az ő és ű fallback fontról renderelődne.
                bunny('Space Grotesk', {
                    weights: [500, 700],
                    subsets: ['latin', 'latin-ext'],
                }),
                bunny('IBM Plex Sans', {
                    weights: [400],
                    subsets: ['latin', 'latin-ext'],
                }),
                bunny('IBM Plex Mono', {
                    weights: [400, 500],
                    subsets: ['latin', 'latin-ext'],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
