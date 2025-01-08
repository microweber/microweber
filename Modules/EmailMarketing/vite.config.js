import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import {copyFolderSyncVite} from "vite-plugin-copy-folder"
import path from 'path';


export default defineConfig({
    build: {
        outDir: __dirname + '/resources/assets/dist',
        emptyOutDir: true,
        manifest: "manifest.json",
    },
    plugins: [
        laravel({
            input: [
                __dirname + '/resources/assets/sass/app.scss',
                __dirname + '/resources/assets/js/app.js'
            ],
            refresh: true,
        }),
        copyFolderSyncVite(__dirname+ '/resources/assets/', __dirname+ '/../../public/modules/emailmarketing/'),
    ],
});

//export const paths = [
//    'Modules/EmailMarketing/resources/assets/sass/app.scss',
//    'Modules/EmailMarketing/resources/assets/js/app.js',
//];
