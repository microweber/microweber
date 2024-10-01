import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import istanbul from 'vite-plugin-istanbul';

import sass from 'sass';



export default defineConfig({
    build: {
        open: true,
        port: 3000,
        assetsInlineLimit: 0,
       // outDir: './public/build',
        manifest: "manifest.json",
        // rollupOptions: {
        //     output: {
        //         inlineDynamicImports: true,
        //
        //         globals: {
        //             jquery: 'window.jQuery',
        //             $: 'window.$',
        //             mw: 'window.mw',
        //         }
        //     }
        // },

        target: 'es6'


    },
    css: {
        preprocessorOptions: {
            scss: {
                implementation: sass,
            }
        }
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
               // 'src/MicroweberPackages/LiveEdit/resources/front-end/js/core/mw-core.js',
                //'./packages/frontend-assets/resources/assets/core/mw-core.js',

             //    './packages/frontend-assets/resources/assets/css/scss/liveedit.scss',
                // './packages/frontend-assets/resources/assets/css/scss/liveedit.scss',
               //  'src/MicroweberPackages/LiveEdit/resources/js/ui/live-edit-app.js',
              //  './packages/frontend-assets/resources/assets/ui/live-edit-app.js',

                /// './packages/frontend-assets/resources/assets/ui/live-edit-app.js',


                //'./packages/frontend-assets/resources/assets/ui/live-edit-page-scripts.js',

                // './packages/frontend-assets/resources/assets/js/admin-filament-libs.js',
                // './packages/frontend-assets/resources/assets/js/admin-filament-app.js',
                // './packages/frontend-assets/resources/assets/js/admin.js',


               // './packages/frontend-assets/resources/assets/ui/css/admin-filament.scss',



             //   'packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/element-style-editor-app.js',



                'src/MicroweberPackages/Multilanguage/resources/js/filament-translatable.js',
                'src/MicroweberPackages/Filament/resources/js/tiny-editor.js',

              //  'resources/css/filament/admin/theme.css',
            ],
          // publicDirectory: "./public/build",
         //   publicDirectory: "public",
            refresh: true,
        }),


        // istanbul({
        //     include: 'src/MicroweberPackages/LiveEdit/*',
        //     exclude: ['node_modules', 'tests/'],
        //     extension: [ '.js', '.ts', '.vue' ],
        //     forceBuildInstrument: true,
        //     requireEnv: true,
        //     //requireEnv: false,
        // })

    ]
});
