import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import {copyFolderSyncVite} from "vite-plugin-copy-folder"


import sass from 'sass';

export default defineConfig({


    build: {
        rollupOptions: {
            output: {
                entryFileNames: `assets/[name].js`,
                chunkFileNames: `assets/[name].js`,
                assetFileNames: `assets/[name].[ext]`,

            }
        },
        open: true,
        port: 3000,
        assetsInlineLimit: 0,
        outDir: './resources/dist/build',
        manifest: "manifest.json",

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
        // liveReload('resources/assets/**/*.*'),

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
                'resources/assets/css/scss/liveedit.scss',
                'resources/assets/ui/live-edit-app.js',
                'resources/assets/ui/apps/ElementStyleEditor/element-style-editor-app.js',
            ],
            publicDirectory: "resources/assets/",
            //  refresh: true,
            //   publicDirectory: "public",
            refresh: true,
        }),

        copyFolderSyncVite(__dirname + '/resources/dist/build', __dirname + '/../../public/vendor/microweber-packages/frontend-assets/build/'),


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
