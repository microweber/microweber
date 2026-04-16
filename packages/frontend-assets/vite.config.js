import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import path from 'path';
import fs from 'fs-extra';

const outputDir = 'resources/dist/build';
const publicDest = '../../public/vendor/microweber-packages/frontend-assets/build';

// CSS rename map: Vite output name → expected name by PHP/blade templates
const cssRenames = {
    'admin-css.css': 'admin.css',
    'element-style-editor-scss.css': 'element-style-editor-app.css',
};

export default defineConfig({
    plugins: [
        vue(),
        {
            name: 'rename-css-and-copy',
            closeBundle() {
                const buildDir = path.resolve(__dirname, outputDir);
                // Rename CSS files to match what PHP/blade templates expect
                for (const [from, to] of Object.entries(cssRenames)) {
                    const srcFile = path.join(buildDir, from);
                    const destFile = path.join(buildDir, to);
                    if (fs.existsSync(srcFile)) {
                        fs.renameSync(srcFile, destFile);
                        const srcMap = srcFile + '.map';
                        const destMap = destFile + '.map';
                        if (fs.existsSync(srcMap)) {
                            fs.renameSync(srcMap, destMap);
                        }
                    }
                }
                // Copy to public
                const src = path.resolve(__dirname, outputDir);
                const dest = path.resolve(__dirname, publicDest);
                fs.copySync(src, dest);
                console.log('Copied build to', dest);
            },
        },
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
        },
        extensions: ['.js', '.json', '.vue', '.scss', '.css'],
    },
    build: {
        outDir: outputDir,
        emptyOutDir: true,
        sourcemap: false,
        cssCodeSplit: true,
        rollupOptions: {
            input: {
                'live-edit-app': path.resolve(__dirname, 'resources/assets/ui/live-edit-app.js'),
                'element-style-editor-app': path.resolve(__dirname, 'resources/assets/ui/apps/ElementStyleEditor/element-style-editor-app.js'),
                'element-style-editor-scss': path.resolve(__dirname, 'resources/assets/ui/apps/ElementStyleEditor/element-style-editor-app.scss'),
                'liveedit': path.resolve(__dirname, 'resources/assets/css/scss/liveedit.scss'),
                'default': path.resolve(__dirname, 'resources/assets/css/microweber/css/default.css'),
                'mw-grid': path.resolve(__dirname, 'resources/assets/css/microweber/css/mw-grid.css'),
                'core': path.resolve(__dirname, 'resources/assets/js/core.js'),
                'admin': path.resolve(__dirname, 'resources/assets/js/admin.js'),
                'live-edit-page-scripts': path.resolve(__dirname, 'resources/assets/live-edit/live-edit-page-scripts.js'),
                'frontend': path.resolve(__dirname, 'resources/assets/js/frontend.js'),
                'admin-filament-libs': path.resolve(__dirname, 'resources/assets/js/admin-filament-libs.js'),
                'admin-css': path.resolve(__dirname, 'resources/assets/css/admin.scss'),
                'install': path.resolve(__dirname, 'resources/assets/css/install.scss'),
            },
            output: {
                entryFileNames: '[name].js',
                chunkFileNames: 'chunks/[name]-[hash].js',
                assetFileNames: '[name][extname]',
            },
        },
    },
    css: {
        preprocessorOptions: {
            scss: {
                silenceDeprecations: ['import'],
            },
        },
    },
});
