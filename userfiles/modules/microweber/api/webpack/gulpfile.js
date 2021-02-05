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

gulp.task('mwui', function () {
    return gulp.src(ui)
        .pipe(sourcemaps.init())
        .pipe(gulpLess(lessConfig))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(`${css}`));
});


