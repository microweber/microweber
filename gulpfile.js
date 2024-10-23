import gulp from 'gulp';
import {watch} from 'gulp';


import sass from 'gulp-dart-sass';
import concat from 'gulp-concat';
import cleanCSS from 'gulp-clean-css';
import uglify from 'gulp-uglify';
import sourcemaps from 'gulp-sourcemaps';
import include from 'gulp-include';


import {exec} from 'child_process';


const adminCSS = 'userfiles/modules/microweber/api/libs/mw-ui/grunt/plugins/ui/css';
const adminCSSOutput = adminCSS;

const apiJSPath = 'userfiles/modules/microweber/api'
const apiJSOutputPath = 'userfiles/modules/microweber/api';


const frontEndPath = `packages/frontend-assets/resources/dist`;
const adminFilamentApp = `${frontEndPath}/js/admin.js`;

const packagesWatch = () => {
    const folders = [
        'packages/*/resources/dist/**',
    ];
    const watcher = watch(folders, packages);

}
const packages = () => {
    return exec('php artisan filament:assets', (err, stdout, stderr) => {
        console.log('Publishing assets...');

        if (err) {
            console.log('Error: ');
            console.log(err);
            return;
        }
        return exec('php artisan vendor:publish --tag=public --force --ansi', (err, stdout, stderr) => {
            if (err) {
                console.log('Error: ');
                console.log(err);
                return;
            }

            return exec('php artisan livewire:publish --assets --ansi', (err, stdout, stderr) => {
                if (err) {
                    console.log('Error: ');
                    console.log(err);
                    return;
                }
                console.log(stdout);
                console.log('✅ Done');

            });


        });
    });

}

const _apiJs = () => {
    return gulp.src([
        `${apiJSPath}/apijs_combined.js`,
    ])
        .pipe(include())

        .pipe(sourcemaps.init())
        .pipe(concat('apijs_combined.min.js'))
        .pipe(uglify())
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(apiJSOutputPath));
    console.log('api-js compiled');
};


const adminJs = (prod = false) => {
    let p = gulp.src([
        `${adminFilamentApp}`,
    ]);

    p = p.pipe(include());

    if (!prod) {
        p = p.pipe(sourcemaps.init())
    }


    p = p.pipe(concat('admin-filament-app-dist.js'))
        .pipe(uglify());
    if (!prod) {
        p = p.pipe(sourcemaps.write('.'))
    }

    p = p.pipe(gulp.dest(apiJSOutputPath));

    return p;
};



const _adminCss = () => {
    return gulp.src([
        `${adminCSS}/admin_v2.scss`,
    ]).pipe(sass().on('error', sass.logError))
        .pipe(cleanCSS())
        .pipe(concat('admin_v2.css', {newLine: '/r/n'}))
        .pipe(gulp.dest(adminCSSOutput));
    console.log('admin-css compiled')
}
const _adminCssRtl = () => {
    return gulp.src([
        `${adminCSS}/admin_v2.rtl.scss`,
    ]).pipe(sass().on('error', sass.logError))
        .pipe(cleanCSS())
        .pipe(concat('admin_v2.rtl.css', {newLine: '/r/n'}))
        .pipe(gulp.dest(adminCSSOutput));
    console.log('admin-css compiled')
}

gulp.task('packages', packages);
gulp.task('packages-watch', packagesWatch);
gulp.task('admin-css', _adminCss);
gulp.task('admin-js', adminJs);
gulp.task('admin-css-rtl', _adminCssRtl);
gulp.task('api-js', _apiJs);

const _buildAll = () => {
    console.log('build all');

    return gulp.series(_apiJs, _adminCss, _adminCssRtl);

};

gulp.task('js-build-all', _buildAll());
