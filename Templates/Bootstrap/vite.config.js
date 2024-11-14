import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import {copyFolderSyncVite} from "vite-plugin-copy-folder"
import path from 'path';


export default defineConfig({
    build: {
        open: false,
        rollupOptions: {
            output: {
                entryFileNames: `assets/[name].js`,
                chunkFileNames: `assets/[name].js`,
                assetFileNames: `assets/[name].[ext]`
            }
        },
        outDir: __dirname + '/resources/assets/dist',
        emptyOutDir: true,
        manifest: "manifest.json",
    },
    plugins: [
        laravel({
            publicDirectory: '/resources/assets/dist',

            input: [
                __dirname + '/resources/assets/sass/app.scss',
                __dirname + '/resources/assets/sass/app-rtl.scss',
                __dirname + '/resources/assets/js/app.js'
            ],

            refresh: ['resources/assets/**/*.scss', 'resources/assets/**/*.js'],

        }),
        copyFolderSyncVite(__dirname+ '/resources/assets/', __dirname+ '/../../public/templates/bootstrap/'),
    ],
});
