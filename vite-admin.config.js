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
                'resources/css/filament/admin/theme.css',
                'src/MicroweberPackages/Multilanguage/resources/js/filament-translatable.js',
                'src/MicroweberPackages/LiveEdit/resources/front-end/js/admin/admin-filament-libs.js',
                'src/MicroweberPackages/LiveEdit/resources/front-end/js/admin/admin-filament-app.js',
                'src/MicroweberPackages/Filament/resources/js/tiny-editor.js',
                // 'src/MicroweberPackages/LiveEdit/resources/js/ui/admin-filament-app.js',
                'src/MicroweberPackages/LiveEdit/resources/js/ui/css/admin-filament.scss',
            ],
            publicDirectory: "public",
            refresh: true,
        }),
    ]
});
