import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
export default defineConfig({
    build: {
        assetsInlineLimit: 0,
        outDir: './public/build'
    },
    plugins: [
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        laravel({
            input: [
               // 'resources/css/app.css',
              // 'resources/js/app.js',
              //  'userfiles/modules/microweber/api/live-edit-app/index.js',
               // 'userfiles/modules/microweber/api/live-edit-app/app.js',
                'src/MicroweberPackages/LiveEdit/resources/js/ui/live-edit-app.js',
                'src/MicroweberPackages/LiveEdit/resources/js/ui/live-edit-page-scripts.js',
                'src/MicroweberPackages/LiveEdit/resources/js/ui/admin-app.js',
               /// 'resources/css/microweber-admin-filament.css',
            ],
            publicDirectory: "public",
            refresh: true,
        })
    ]
});
