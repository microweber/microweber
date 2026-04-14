/**
 * Build script replacing laravel-mix (which is incompatible with Node.js v22+).
 * - Webpack bundles the JS files
 * - sass compiles SCSS imports to CSS
 * - @tailwindcss/postcss processes the final CSS
 */

const path = require('path');
const fs = require('fs-extra');
const postcss = require('postcss');
const sass = require('sass');
const { execSync } = require('child_process');
const os = require('os');
const crypto = require('crypto');

const outputPath = path.resolve(__dirname, 'resources/dist/build');
const publicPath = path.resolve(__dirname, '../../public/vendor/microweber-packages/microweber-filament-theme/build');

/** Get a deterministic temp CSS file name for an SCSS file */
function scssToTempCss(scssAbsPath, tempDir) {
    const hash = crypto.createHash('md5').update(scssAbsPath).digest('hex').slice(0, 8);
    const base = path.basename(scssAbsPath).replace(/\.s[ac]ss$/, '.css');
    return path.join(tempDir, hash + '-' + base);
}

/**
 * Resolve a CSS import path to an absolute file path.
 * Handles directory imports, extensionless imports, etc.
 */
function resolveCssImport(importPath, fromDir) {
    const candidates = [
        path.resolve(fromDir, importPath),
        path.resolve(fromDir, importPath + '.css'),
        path.resolve(fromDir, importPath, 'index.css'),
    ];
    for (const c of candidates) {
        if (fs.existsSync(c) && fs.statSync(c).isFile()) return c;
    }
    return null;
}

/**
 * Recursively compile all SCSS imports encountered in a CSS file chain.
 * Returns a Map: scssAbsPath -> compiledCssTempPath
 */
function collectAndCompileScss(cssFilePath, tempDir, visited = new Set(), scssMap = new Map()) {
    const absPath = path.resolve(cssFilePath);
    if (visited.has(absPath)) return scssMap;
    visited.add(absPath);

    if (!fs.existsSync(absPath)) return scssMap;

    const content = fs.readFileSync(absPath, 'utf8');
    const fileDir = path.dirname(absPath);
    const importRegex = /@import\s+['"]([^'"]+)['"]/g;
    let match;

    while ((match = importRegex.exec(content)) !== null) {
        const importPath = match[1];
        if (importPath === 'tailwindcss' || importPath.startsWith('tailwindcss/')) continue;

        const importAbsPath = path.resolve(fileDir, importPath);
        const isScss = importPath.endsWith('.scss') || importPath.endsWith('.sass');

        if (isScss) {
            if (!scssMap.has(importAbsPath)) {
                const tempCssPath = scssToTempCss(importAbsPath, tempDir);
                if (!fs.existsSync(tempCssPath)) {
                    if (fs.existsSync(importAbsPath)) {
                        try {
                            const result = sass.compile(importAbsPath, {
                                style: 'expanded',
                                loadPaths: [path.dirname(importAbsPath)],
                            });
                            fs.ensureDirSync(path.dirname(tempCssPath));
                            fs.writeFileSync(tempCssPath, result.css);
                        } catch (err) {
                            console.warn('SCSS compile warning for', importAbsPath, ':', err.message.split('\n')[0]);
                            fs.ensureDirSync(path.dirname(tempCssPath));
                            fs.writeFileSync(tempCssPath, '/* scss-compile-error */');
                        }
                    } else {
                        console.warn('SCSS file not found:', importAbsPath);
                        fs.ensureDirSync(path.dirname(tempCssPath));
                        fs.writeFileSync(tempCssPath, '');
                    }
                }
                scssMap.set(importAbsPath, tempCssPath);
            }
        } else {
            // Recurse into CSS imports (resolve dir/extensionless imports too)
            const resolved = resolveCssImport(importPath, fileDir);
            if (resolved) {
                collectAndCompileScss(resolved, tempDir, visited, scssMap);
            }
        }
    }

    return scssMap;
}

/**
 * Rewrite SCSS @imports in a CSS file's content to compiled temp paths.
 * Also rewrites directory imports to their full resolved path.
 * Also rewrites @config to use absolute path.
 * Only rewrites for the given file - does not recurse.
 */
function rewriteImports(cssFilePath, scssMap) {
    const absPath = path.resolve(cssFilePath);
    if (!fs.existsSync(absPath)) return fs.readFileSync(absPath, 'utf8');

    const content = fs.readFileSync(absPath, 'utf8');
    const fileDir = path.dirname(absPath);

    const rewritten = content
        .replace(/@import\s+(['"])([^'"]+)\1/g, (full, q, importPath) => {
            if (importPath === 'tailwindcss' || importPath.startsWith('tailwindcss/')) return full;

            const importAbsPath = path.resolve(fileDir, importPath);
            const isScss = importPath.endsWith('.scss') || importPath.endsWith('.sass');

            if (isScss) {
                const tempCssPath = scssMap.get(importAbsPath);
                if (tempCssPath) return '@import ' + q + tempCssPath + q;
            }

            // Resolve directory/extensionless CSS imports to their full path
            const resolved = resolveCssImport(importPath, fileDir);
            if (resolved && resolved !== importAbsPath) {
                // Directory or extensionless import that resolved to a different path
                return '@import ' + q + resolved + q;
            }

            return full;
        })
        .replace(/@config\s+(['"])([^'"]+)\1/g, (full, q, configPath) => {
            const configAbsPath = path.resolve(fileDir, configPath);
            return '@config ' + q + configAbsPath + q;
        });

    return rewritten;
}

/**
 * Create a copy of the entire in-package CSS file tree in a temp directory,
 * with all .scss imports replaced by their compiled CSS counterparts (absolute paths),
 * and directory/extensionless imports replaced with their resolved absolute paths,
 * and @config rewritten to absolute paths.
 *
 * External (out-of-package) CSS files are referenced by absolute path.
 * The temp dir structure mirrors the package's structure so relative imports still work.
 */
function buildTempTree(cssFilePath, tempDir, scssMap, visited = new Set()) {
    const absPath = path.resolve(cssFilePath);
    if (visited.has(absPath)) return;
    visited.add(absPath);

    if (!fs.existsSync(absPath)) return;

    const content = fs.readFileSync(absPath, 'utf8');
    const fileDir = path.dirname(absPath);
    const relFromBase = path.relative(__dirname, absPath);
    const tempFilePath = path.join(tempDir, relFromBase);
    fs.ensureDirSync(path.dirname(tempFilePath));

    const rewritten = content
        .replace(/@import\s+(['"])([^'"]+)\1/g, (full, q, importPath) => {
            if (importPath === 'tailwindcss' || importPath.startsWith('tailwindcss/')) return full;

            const importAbsPath = path.resolve(fileDir, importPath);
            const isScss = importPath.endsWith('.scss') || importPath.endsWith('.sass');

            if (isScss) {
                const tempCssPath = scssMap.get(importAbsPath);
                if (tempCssPath) return '@import ' + q + tempCssPath + q;
                return '/* scss not compiled: ' + importPath + ' */';
            }

            // Resolve directory/extensionless CSS imports
            const resolved = resolveCssImport(importPath, fileDir);
            const resolvedPath = resolved || importAbsPath;

            // Check if the resolved path is in-package
            if (resolvedPath.startsWith(__dirname)) {
                // Recursively process and return path to temp copy
                buildTempTree(resolvedPath, tempDir, scssMap, visited);
                const rel = path.relative(__dirname, resolvedPath);
                const tempResolved = path.join(tempDir, rel);
                return '@import ' + q + tempResolved + q;
            } else {
                // External: use absolute path
                return '@import ' + q + resolvedPath + q;
            }
        })
        .replace(/@config\s+(['"])([^'"]+)\1/g, (full, q, configPath) => {
            return '@config ' + q + path.resolve(fileDir, configPath) + q;
        });

    fs.writeFileSync(tempFilePath, rewritten);
}

async function processCss() {
    const cssInputPath = path.resolve(__dirname, 'resources/assets/css/microweber-filament-theme.css');
    const cssOutputPath = path.join(outputPath, 'microweber-filament-theme.css');

    const tempDir = path.join(os.tmpdir(), 'mw-scss-' + Date.now());
    fs.ensureDirSync(tempDir);

    try {
        // Step 1: Compile all SCSS imports
        const scssMap = collectAndCompileScss(cssInputPath, tempDir);
        console.log('Compiled', scssMap.size, 'SCSS files to temp CSS.');

        // Step 2: Build the temp tree with all imports rewritten
        buildTempTree(cssInputPath, tempDir, scssMap);

        // Step 3: Get the temp entry path
        const relFromBase = path.relative(__dirname, cssInputPath);
        const tempEntryPath = path.join(tempDir, relFromBase);

        // Step 4: Run Tailwind PostCSS from the TEMP entry (Tailwind reads files from disk)
        // Symlink node_modules into the temp dir so Tailwind can find 'tailwindcss'
        const tempNodeModules = path.join(tempDir, 'node_modules');
        if (!fs.existsSync(tempNodeModules)) {
            fs.symlinkSync(path.resolve(__dirname, 'node_modules'), tempNodeModules, 'dir');
        }
        // Also symlink a package.json so module resolution works
        const tempPkgJson = path.join(tempDir, 'package.json');
        if (!fs.existsSync(tempPkgJson)) {
            fs.symlinkSync(path.resolve(__dirname, 'package.json'), tempPkgJson);
        }

        const tailwindPostcss = require('@tailwindcss/postcss');
        const tempContent = fs.readFileSync(tempEntryPath, 'utf8');

        const result = await postcss([tailwindPostcss({ base: __dirname })]).process(tempContent, {
            from: tempEntryPath,
            to: cssOutputPath,
        });

        fs.ensureDirSync(outputPath);
        fs.writeFileSync(cssOutputPath, result.css);
        if (result.map) fs.writeFileSync(cssOutputPath + '.map', result.map.toString());

        console.log('CSS processed successfully:', cssOutputPath);
    } finally {
        fs.removeSync(tempDir);
    }
}

function runWebpack() {
    const isProd = process.env.NODE_ENV === 'production';
    const mode = isProd ? 'production' : 'development';
    console.log('Running webpack in ' + mode + ' mode...');
    const isWindows = os.platform() === 'win32';
    const webpackBin = isWindows
        ? path.join('node_modules', '.bin', 'webpack.cmd')
        : path.join('node_modules', '.bin', 'webpack');
    execSync(
        '"' + webpackBin + '" --config webpack.config.cjs --mode ' + mode,
        { cwd: __dirname, stdio: 'inherit' }
    );
}

function copyToPublic() {
    if (fs.existsSync(publicPath) && fs.lstatSync(publicPath).isSymbolicLink()) {
        console.log('Skipping copy, target is a symlink:', publicPath);
        return;
    }
    fs.copySync(outputPath, publicPath);
    console.log('Copied build to:', publicPath);
}

async function main() {
    try {
        runWebpack();
        await processCss();
        copyToPublic();
        console.log('Build complete.');
    } catch (err) {
        console.error('Build failed:', err);
        process.exit(1);
    }
}

main();
