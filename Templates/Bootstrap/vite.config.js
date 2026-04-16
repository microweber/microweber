import { defineConfig } from 'vite';
import path from 'path';
import fs from 'fs-extra';

const outputDir = 'resources/assets/dist/build';
const publicDest = '../../public/templates/bootstrap';

export default defineConfig({
    build: {
        outDir: outputDir,
        emptyOutDir: true,
        sourcemap: true,
        cssCodeSplit: true,
        rollupOptions: {
            input: {
                app: path.resolve(__dirname, 'resources/assets/js/app.js'),
                'app-css': path.resolve(__dirname, 'resources/assets/sass/app.scss'),
                'app-rtl': path.resolve(__dirname, 'resources/assets/sass/app-rtl.scss'),
            },
            output: {
                entryFileNames: '[name].js',
                chunkFileNames: '[name].js',
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
    plugins: [
        {
            name: 'rename-and-copy',
            closeBundle() {
                const buildDir = path.resolve(__dirname, outputDir);
                // Rename app-css.css → app.css to match blade template references
                const src = path.join(buildDir, 'app-css.css');
                const dest = path.join(buildDir, 'app.css');
                if (fs.existsSync(src)) {
                    fs.renameSync(src, dest);
                    // Also rename sourcemap if present
                    const srcMap = src + '.map';
                    const destMap = dest + '.map';
                    if (fs.existsSync(srcMap)) {
                        fs.renameSync(srcMap, destMap);
                    }
                }
                // Copy entire assets dir to public
                const srcDir = path.resolve(__dirname, 'resources/assets');
                const destDir = path.resolve(__dirname, publicDest);
                fs.copySync(srcDir, destDir);
                console.log('Copied assets to', destDir);
            },
        },
    ],
});
