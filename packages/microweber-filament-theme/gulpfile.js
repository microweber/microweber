import gulp from 'gulp';
import { exec } from 'child_process';
import path from 'path';
import { fileURLToPath } from 'url';
import CopyWebpackPlugin from 'copy-webpack-plugin';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const buildJs = (cb) => {
    exec('npx webpack build --mode=production', (err, stdout, stderr) => {
        console.log(stdout);
        console.error(stderr);
        cb(err);
    });
};
const buildCss = (cb) => {
    exec('npx tailwindcss -i resources/assets/css/index.css -o resources/dist/css/microweber-filament-theme.css --postcss', (err, stdout, stderr) => {
        console.log(stdout);
        console.error(stderr);
        cb(err);
    });
};

const copyFiles = () => {
    return gulp.src('resources/dist/**/*')
        .pipe(gulp.dest('../../public/vendor/microweber-packages/microweber-filament-theme'));
};
const build = gulp.parallel(buildJs, buildCss);

const watchFiles = () => {

    gulp.watch('resources/assets/**/*', { interval: 1000, usePolling: true }, gulp.series(build, copyFiles));
};

gulp.task('build', build);
gulp.task('buildJs', buildJs);
gulp.task('buildCss', buildJs);
gulp.task('copyFiles', copyFiles);
gulp.task('watch', watchFiles);
