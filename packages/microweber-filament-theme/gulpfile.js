import gulp from 'gulp';
import { exec } from 'child_process';

const build = (cb) => {
    exec('npx tailwindcss -i resources/assets/css/index.css -o resources/dist/css/microweber-filament-theme.css --postcss --minify && npx webpack build --mode=production', (err, stdout, stderr) => {
        console.log(stdout);
        console.error(stderr);
        cb(err);
    });
};

const watchFiles = () => {

    gulp.watch('resources/assets/**/*', {interval: 1000, usePolling: true}, build);
};

gulp.task('build', build);
gulp.task('watch', watchFiles);
