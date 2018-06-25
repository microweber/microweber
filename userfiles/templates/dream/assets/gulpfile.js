const gulp = require('gulp')
const concat = require('gulp-concat');
const rename = require('gulp-rename');
const cssmin = require('gulp-cssmin');
const watch = require('gulp-watch');
const gulpCopy = require('gulp-copy');
const uglify = require('gulp-uglify');

let count = 0;
const mwpath = '.';
const dest = './dist';

dreamJS = ()=>{
    const files = [
        mwpath+'/js/isotope.min.js',
        mwpath+'/js/ytplayer.min.js',
        mwpath+'/js/easypiechart.min.js',
        mwpath+'/js/owl.carousel.min.js',
        mwpath+'/js/lightbox.min.js',
        mwpath+'/js/twitterfetcher.min.js',
        mwpath+'/js/smooth-scroll.min.js',
        mwpath+'/js/scrollreveal.min.js',
        mwpath+'/js/parallax.js',
        mwpath+'/js/scripts.js'
    ];
    return gulp.src(files)
        .pipe(concat( 'main.js', { newLine: ';\r\n' }))
        .pipe(rename({suffix: '.min'}))
        .pipe(uglify({mangle: false}))
        .pipe(gulp.dest(dest));
}

dreamCSS = ()=>{
    const files = [
        mwpath + '/css/socicon.css',
        mwpath + '/css/interface-icons.css',
        mwpath + '/css/owl.carousel.css',
        mwpath + '/css/lightbox.min.css',
        mwpath + '/css/theme.css'
    ];
    return gulp.src(files)
        .pipe(concat(  'main.css', {newLine: ';\r\n'}))
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(dest));
}

gulp.task('template-dream',  () => {
    watch( mwpath+'/js/*.js',  { ignoreInitial: false }, (files) => {
        dreamJS();
        count++;
        console.log(count + ' - js');
    });
    watch( mwpath+'/css/*.css',  { ignoreInitial: false }, (files) => {
        dreamCSS();
        count++;
        console.log(count + ' - css');
    });
});

gulp.task('default', ['template-dream']);
