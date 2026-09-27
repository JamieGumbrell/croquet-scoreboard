import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/default.css',
                'resources/css/palette.css',
                'resources/css/countries.css',
                'resources/css/form.css',
                'resources/css/gateball.css',
                'resources/css/home.css',
                'resources/css/players.css',
                'resources/css/preview.css',
                'resources/css/primary.css',
                'resources/css/scoreboard.css',
                'resources/css/scoreboards.css',
                'resources/css/secondary.css',
                'resources/css/simple_darkLayout.css',
                'resources/css/simple_lightLayout.css',
                'resources/js/app.js',
                'resources/js/scoreboard-show.ts',
            ],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
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
