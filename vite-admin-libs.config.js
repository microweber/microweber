import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    build: {
        open: true,
        port: 3000,
        assetsInlineLimit: 0,
        outDir: './public/build',
        manifest: "manifest.json",
        target: 'esnext',
        rollupOptions: {
            output: {
                globals: {
                    jquery: 'window.jQuery',
                    $: 'window.$',
                    mw: 'window.mw',
                }
            }
        },

    },
    plugins: [
        laravel({
            input: [

                'src/MicroweberPackages/LiveEdit/resources/front-end/js/admin/admin-filament-libs.js',


            ],
            publicDirectory: "public",
            refresh: true,
        }),
    ]
});
