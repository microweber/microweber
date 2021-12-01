const fs = require('fs');

const css = `${__dirname}/../../css`;
const ui = `userfiles/modules/microweber/css/ui/assets/ui.less`;
const uiDist = `userfiles/modules/microweber/css`;

const gulp = require('gulp');
const uglify = require('gulp-uglify');
const watch = require('gulp-watch');
const sourcemaps = require('gulp-sourcemaps');


const less = require('gulp-less');
const sass = require('gulp-sass');
const path = require('path');
const concat = require('gulp-concat');
const rename = require('gulp-rename');
const rebaseUrls = require('gulp-css-url-fix');


const cssmin = require('gulp-cssmin');


const cssMode = {
    sass: sass,
    less: less
};

const createCSS = async (config, prod) => {
    /*

    config {
        css: string | string[] // globs https://gulpjs.com/docs/en/api/concepts#globs
        cssMode: 'less' | 'sass',
        output: string // target path with file name e.g. path/to/folder/file.css,
        suffix?: string //e.g. .min
    }

    */
    return new Promise(resolve => {
        let stream = gulp.src(config.css);
        config.suffix = config.suffix || '';

        const outputArr = config.output.split('/');
        const name = outputArr.pop();

        stream = stream.pipe(rebaseUrls());
        if (!prod) {
            stream = stream.pipe(sourcemaps.init());
        }
        stream = stream.pipe(cssMode[config.cssMode]());
        if (prod) {
            stream = stream.pipe(cssmin());
        }
        stream = stream.pipe(concat( name, {newLine: ';\r\n'}));
        if(!prod) {
            stream = stream.pipe(sourcemaps.write());
        }
        stream = stream
            .pipe(rename({suffix: config.suffix }))
            .pipe(gulp.dest(outputArr))
            .on("end", resolve);
    });
};

const lessConfig = {

};

const bootstrapUI = [
    'node_modules/bootstrap-select/dist/css/bootstrap-select.css',
    '../libs/bootstrap_tags/bootstrap-tagsinput.css',
    'node_modules/ion-rangeslider/css/ion.rangeSlider.css',
    'node_modules/@mdi/font/css/materialdesignicons.css',
    'node_modules/aos/dist/aos.css',
];

gulp.task('mwui', function () {
    return gulp.src(ui)
        .pipe(sourcemaps.init())
        .pipe(less(lessConfig))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(`${uiDist}`));
});

gulp.task('Microweber Bootstrap UI', function () {
    return gulp.src(bootstrapUI)
        .pipe(sourcemaps.init())
        .pipe(less(lessConfig))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(`${css}`));

});

    gulp.task('AdminCss', async () => {
        const config  = {
            css: '',
            cssMode: 'less',
            output: css + '/admin.css'
        };
        await createCSS(config, false);
    });


    const all = ['mwui', 'Microweber Bootstrap UI'];

    gulp.task('default', gulp.series(...all), function() {

    });


    gulp.task('watch', function () {
        return watch(css + '/**/*.less', gulp.series(...all));
    });
