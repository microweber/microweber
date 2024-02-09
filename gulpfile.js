const gulp = require('gulp');
const sass = require('gulp-dart-sass');
const concat = require('gulp-concat');
const cleanCSS = require('gulp-clean-css');
const uglify = require('gulp-uglify');
const sourcemaps = require('gulp-sourcemaps');
const include = require('gulp-include');
const inlineSource = require('gulp-inline-source');


const adminCSS = 'userfiles/modules/microweber/api/libs/mw-ui/grunt/plugins/ui/css';
const adminCSSOutput = adminCSS;

const apiJSPath = 'userfiles/modules/microweber/api'
const apiJSOutputPath = 'userfiles/modules/microweber/api';

const _apiJs = () => {
    return gulp.src([
        `${apiJSPath}/apijs_combined.js`,
    ])
        .pipe(include())

        .pipe(sourcemaps.init()) // Initialize sourcemaps
        .pipe(concat('apijs_combined.min.js'))
        .pipe(uglify())
        .pipe(sourcemaps.write('.')) // Write sourcemaps to the same directory
        .pipe(gulp.dest(apiJSOutputPath));
    console.log('api-js compiled');
};
const _mwEditor = () => {
    return gulp.src([
        `${apiJSPath}/editor/mweditor.js`, // Include all JS files within the editor directory and its subdirectories
    ] )
        .pipe(include())
        .pipe(sourcemaps.init()) // Initialize sourcemaps
        .pipe(concat('mweditor.min.js')) // Concatenate all files into mweditor.min.js
        .pipe(uglify())
        .pipe(sourcemaps.write('.')) // Write sourcemaps to the same directory
        .pipe(inlineSource()) // Inline the content of mweditor-core.js

        .on('end', () => {
            console.log('mweditor compiled');
        });
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
gulp.task('admin-css', _adminCss);
gulp.task('admin-css-rtl', _adminCssRtl);
gulp.task('api-js', _apiJs);
gulp.task('mw-editor', _mwEditor);
const _buildAll = () => {
    _apiJs();
    _adminCss();
    _adminCssRtl();
    _mwEditor();
}
gulp.task('admin-css-dev', () => {
     _buildAll();
    gulp.watch('userfiles/modules/microweber/api/libs/mw-ui/grunt/plugins/ui/**/*.scss', gulp.series(['admin-css','admin-css-rtl']));
    gulp.watch('userfiles/modules/microweber/api/apijs_combined.js', gulp.series(['api-js']));
    gulp.watch('userfiles/modules/microweber/api/editor/services/*.js', gulp.series(['mw-editor']));
    gulp.watch('userfiles/modules/microweber/api/editor/mweditor.js', gulp.series(['mw-editor']));


})
gulp.task('js-build-all', () => {
    _buildAll();
})
