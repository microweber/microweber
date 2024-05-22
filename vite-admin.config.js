import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    build: {
        open: true,
        port: 3000,
        assetsInlineLimit: 0,
        outDir: './public/build',
        manifest: "manifest.json"

    },
    plugins: [
        laravel({
            input: [
                'resources/css/filament/admin/theme.css',
            ],
            publicDirectory: "public",
            refresh: true,
        }),
    ]
});
