import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: [
               // 'resources/css/app.css',
               // 'resources/js/app.js'

                'userfiles/modules/microweber/api/live-edit-app/app.tsx',
                'resources/css/microweber-admin-filament.css',
            ],
            publicDirectory: "public",
            refresh: true,
        }),
        react( )
    ],
});
