const gulp = require('gulp');
const concat = require('gulp-concat');
const rename = require('gulp-rename');
const cssmin = require('gulp-cssmin');
const watch = require('gulp-watch');
const uglify = require('gulp-uglify');

let count = 0;

const vars = require('../vars');
const mwpath = vars.templates + '/dream';

const dist = mwpath + '/assets/dist';
const assets = mwpath + '/assets';

dreamJS = ()=>{
    const files = [
        assets+'/js/isotope.min.js',
        assets+'/js/ytplayer.min.js',
        assets+'/js/easypiechart.min.js',
        assets+'/js/owl.carousel.min.js',
        assets+'/js/lightbox.min.js',
        assets+'/js/twitterfetcher.min.js',
        assets+'/js/smooth-scroll.min.js',
        assets+'/js/scrollreveal.min.js',
        assets+'/js/parallax.js',
        assets+'/js/scripts.js'
    ];
    return gulp.src(files)
        .pipe(concat( 'main.js', { newLine: ';\r\n' }))
        .pipe(rename({suffix: '.min'}))
        .pipe(uglify({mangle: false}))
        .pipe(gulp.dest(dist));
}

dreamCSS = ()=>{
    const files = [
        assets + '/css/socicon.css',
        assets + '/css/interface-icons.css',
        assets + '/css/owl.carousel.css',
        assets + '/css/lightbox.min.css',
        assets + '/css/theme.css'
    ];
    return gulp.src(files)
        .pipe(cssmin())
        .pipe(concat(  'main.css', {newLine: ';\r\n'}))
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(dist));
}



module.exports.css = dreamCSS;
module.exports.js = dreamJS;
module.exports.task = () => {
    watch( vars.templates + '/dream/assets/js/*.js',  { ignoreInitial: false }, (files) => {
        dreamJS();
        count++;
        console.log(count + ' - js');
    });
    watch( vars.templates + '/dream/assets/css/*.css',  { ignoreInitial: false }, (files) => {
        dreamCSS();
        count++;
        console.log(count + ' - css');
    });
};

