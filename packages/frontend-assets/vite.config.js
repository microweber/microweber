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

const copyPlugin = {
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
        // Copy to public.
        //
        // AI-109 / TICKET-BK (cycle-135 2026-05-09): the chunks/ folder
        // accumulates orphaned files across rebuilds because Rollup
        // emits hash-suffixed chunk names (`helpers-CJQHAVzg.js` etc.)
        // and copySync only writes new + same-name files. Stale chunks
        // were piling up in the public copy (4× helpers + 7× Lang) even
        // though the source build was clean. Clear chunks/ before copying
        // so the public output mirrors the source build exactly.
        const src = path.resolve(__dirname, outputDir);
        const dest = path.resolve(__dirname, publicDest);
        const destChunks = path.join(dest, 'chunks');
        if (fs.existsSync(destChunks)) {
            fs.rmSync(destChunks, { recursive: true, force: true });
        }
        fs.copySync(src, dest);
        console.log('Copied build to', dest);
    },
};

export default defineConfig(({ mode }) => {

    // Build frontend.js separately as IIFE (no ES module imports) so it can
    // be loaded as a regular <script> tag — templates use mw.require() etc.
    // in synchronous inline scripts that run before any deferred module.
    if (mode === 'frontend') {
        return {
            plugins: [
                {
                    name: 'copy-frontend',
                    closeBundle() {
                        const src = path.resolve(__dirname, outputDir, 'frontend.js');
                        const dest = path.resolve(__dirname, publicDest, 'frontend.js');
                        if (fs.existsSync(src)) {
                            fs.copySync(src, dest);
                            console.log('Copied frontend.js to', dest);
                        }
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
                emptyOutDir: false,
                sourcemap: false,
                rollupOptions: {
                    input: path.resolve(__dirname, 'resources/assets/js/frontend.js'),
                    output: {
                        format: 'iife',
                        entryFileNames: 'frontend.js',
                        inlineDynamicImports: true,
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
        };
    }

    // Default build: all other entry points as ES modules with code splitting
    return {
        plugins: [
            vue(),
            copyPlugin,
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
                    'design-system': path.resolve(__dirname, 'resources/assets/css/microweber/css/design-system.css'),
                    'mw-grid': path.resolve(__dirname, 'resources/assets/css/microweber/css/mw-grid.css'),
                    'core': path.resolve(__dirname, 'resources/assets/js/core.js'),
                    'admin': path.resolve(__dirname, 'resources/assets/js/admin.js'),
                    'live-edit-page-scripts': path.resolve(__dirname, 'resources/assets/live-edit/live-edit-page-scripts.js'),
                    'admin-filament-libs': path.resolve(__dirname, 'resources/assets/js/admin-filament-libs.js'),
                    'admin-css': path.resolve(__dirname, 'resources/assets/css/admin.scss'),
                    'install': path.resolve(__dirname, 'resources/assets/css/install.scss'),
                },
                output: {
                    entryFileNames: '[name].js',
                    chunkFileNames: 'chunks/[name]-[hash].js',
                    assetFileNames: '[name][extname]',
                    /*
                     * AI-109 / TICKET-BK (cycle-135 2026-05-09):
                     * coalesce shared vendor code into single named
                     * chunks so multiple Vue entry points
                     * (live-edit-app, element-style-editor-app, …) no
                     * longer each ship their own copy of the Vue
                     * runtime + reactivity + lodash + jquery, etc.
                     *
                     * Pre-fix output had 7× ~742KB "Lang-*.js" chunks
                     * (each one was the full Vue runtime, named after
                     * Lang.vue because that was the entry that
                     * triggered the split) and 4× ~1.26MB
                     * "helpers-*.js" chunks (each one was the full
                     * lodash/jquery helper bundle). Total wasted bytes:
                     * 6 × 742KB + 3 × 1.26MB ≈ 8.2 MB.
                     *
                     * Post-fix: vue + reactive runtime is in a single
                     * `vue-runtime` chunk; lodash + jquery + axios in a
                     * single `helpers` chunk; Vuetify in `vuetify`;
                     * everything else stays as Rollup's default code-
                     * splitting heuristic.
                     */
                    manualChunks: (id) => {
                        if (id.includes('node_modules')) {
                            if (id.includes('@vue/') || id.includes('node_modules/vue/')) {
                                return 'vue-runtime';
                            }
                            if (id.includes('vuetify')) {
                                return 'vuetify';
                            }
                            if (
                                id.includes('lodash') ||
                                id.includes('jquery') ||
                                id.includes('axios')
                            ) {
                                return 'helpers';
                            }
                        }
                        return undefined;
                    },
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
    };
});
