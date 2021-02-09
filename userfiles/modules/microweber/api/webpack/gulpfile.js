const fs = require('fs');

const css = `${__dirname}/../../css`;
const ui = `${css}/ui/assets/ui.less`;

const gulp = require('gulp');
const uglify = require('gulp-uglify');
const watch = require('gulp-watch');
const sourcemaps = require('gulp-sourcemaps');


const gulpLess = require('gulp-less');
const path = require('path');

const lessConfig = {

};

const bootstrapUI = [
    'node_modules/bootstrap-select/dist/css/bootstrap-select.css',
    '../libs/bootstrap_tags/bootstrap-tagsinput.css',
    'node_modules/ion-rangeslider/css/ion.rangeSlider.css',
    'node_modules/@mdi/font/css/materialdesignicons.css',
    'node_modules/aos/dist/aos.css',
];

gulp.task('Microweber CSS UI', function () {
    return gulp.src(ui)
        .pipe(sourcemaps.init())
        .pipe(gulpLess(lessConfig))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(`${css}`));
});

gulp.task('Microweber Bootstrap UI', function () {
    return gulp.src(bootstrapUI)
        .pipe(sourcemaps.init())
        .pipe(gulpLess(lessConfig))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(`${css}`));
});


const all = ['Microweber CSS UI', 'Microweber Bootstrap UI'];

gulp.task('default', gulp.series(...all), function() {

});


gulp.task('watch', function () {
    return watch(css + '/**/*.less', gulp.series(...all));
});


