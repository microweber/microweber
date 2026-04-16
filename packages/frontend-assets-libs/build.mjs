/**
 * Build script for frontend-assets-libs.
 * Replaces laravel-mix — this package mostly copies files from node_modules to dist.
 * No bundler needed.
 */

import fs from 'fs-extra';
import path from 'path';
import { fileURLToPath } from 'url';
import config from './config-common.js';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const distDir = path.resolve(__dirname, 'resources/dist');
const publicDir = path.resolve(__dirname, '../../public/vendor/microweber-packages/frontend-assets-libs');

function copyFile(target, filePath) {
    const src = path.resolve(__dirname, filePath);
    if (!fs.existsSync(src)) {
        console.warn(`  SKIP (not found): ${filePath}`);
        return;
    }
    const stat = fs.statSync(src);
    if (stat.isDirectory()) {
        const dest = path.join(distDir, target);
        fs.ensureDirSync(dest);
        fs.copySync(src, dest);
    } else {
        // If target ends with a file extension, treat it as a direct file destination
        const targetHasExt = path.extname(target) !== '';
        let dest;
        if (targetHasExt) {
            dest = path.join(distDir, target);
        } else {
            dest = path.join(distDir, target, path.basename(filePath));
        }
        fs.ensureDirSync(path.dirname(dest));
        fs.copySync(src, dest);
    }
}

async function main() {
    console.log('Building frontend-assets-libs...');

    // Copy local libs to frontend-assets
    const localLibCopies = [
        { src: './resources/local-libs/jseldom-jquery.js', dest: '../frontend-assets/resources/assets/libs/jseldom/jseldom-jquery.js' },
        { src: './resources/local-libs/collapse-nav/collapse-nav.js', dest: './resources/dist/collapse-nav/collapse-nav.js' },
        { src: './resources/local-libs/highlight/highlight.min.js', dest: './resources/dist/highlight-js/highlight.min.js' },
    ];
    for (const { src, dest } of localLibCopies) {
        const srcPath = path.resolve(__dirname, src);
        const destPath = path.resolve(__dirname, dest);
        if (fs.existsSync(srcPath)) {
            fs.ensureDirSync(path.dirname(destPath));
            fs.copySync(srcPath, destPath);
            console.log(`  Copied ${src} → ${dest}`);
        }
    }

    // Process scripts (copy from node_modules)
    console.log('Copying scripts...');
    for (const conf of config.scripts) {
        const paths = Array.isArray(conf.path) ? conf.path : [conf.path];
        for (const p of paths) {
            copyFile(conf.target, p);
            console.log(`  ${conf.target} ← ${p}`);
        }
    }

    // Process CSS files
    console.log('Copying CSS...');
    for (const conf of config.css) {
        const paths = Array.isArray(conf.path) ? conf.path : [conf.path];
        for (const p of paths) {
            copyFile(conf.target, p);
            console.log(`  ${conf.target} ← ${p}`);
        }
    }

    // Process assets (directories)
    console.log('Copying assets...');
    for (const conf of config.assets) {
        const paths = Array.isArray(conf.path) ? conf.path : [conf.path];
        for (const p of paths) {
            copyFile(conf.target, p);
            console.log(`  ${conf.target} ← ${p}`);
        }
    }

    // Process copy entries (with afterCopy hooks)
    console.log('Copying additional files...');
    const afterBuild = [];
    for (const conf of config.copy) {
        const paths = Array.isArray(conf.path) ? conf.path : [conf.path];
        for (const p of paths) {
            copyFile(conf.target, p);
            console.log(`  ${conf.target} ← ${p}`);
        }
        if (conf.afterCopy) {
            afterBuild.push({ ...conf, target: path.join(distDir, conf.target) });
        }
    }

    // Copy mw-icons-mind directory
    const iconsSrc = path.resolve(__dirname, 'resources/local-libs/mw-icons-mind');
    const iconsDest = path.join(distDir, 'mw-icons-mind');
    if (fs.existsSync(iconsSrc)) {
        fs.ensureDirSync(iconsDest);
        fs.copySync(iconsSrc, iconsDest);
        console.log('  Copied mw-icons-mind');
    }

    // Run afterCopy hooks (e.g., flag-icons CSS modification)
    for (const conf of afterBuild) {
        await conf.afterCopy(conf);
    }

    // Copy dist to public
    fs.removeSync(publicDir);
    fs.copySync(distDir, publicDir);
    console.log(`Copied dist → ${publicDir}`);
    console.log('Build complete.');
}

main().catch(err => {
    console.error('Build failed:', err);
    process.exit(1);
});
