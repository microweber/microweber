const gulp = require('gulp')/*(require('gulp'), process.argv)*/;
const concat = require('gulp-concat');
const rename = require('gulp-rename');
const cssmin = require('gulp-cssmin');
const watch = require('gulp-watch');
const gulpCopy = require('gulp-copy');
const uglify = require('gulp-uglify');
const inject = require('gulp-inject-string');

let count = 0;
const mwpath = '.';
const dest = './dist';

const cssFiles = [
    mwpath + '/css/ui.css',
    mwpath + '/css/wysiwyg.css',
    mwpath + '/css/liveedit.css',
    mwpath + '/css/layouts.css',
    mwpath + '/microweber.css',
    mwpath + '/css/fonts/mw-ui-icons-v2/css/mwi.css',
    mwpath + '/css/popup.css',
];

const jsFiles = [
    mwpath+'/api/libs/rangy/rangy-core.js',
    mwpath+'/api/libs/rangy/rangy-cssclassapplier.js',
    mwpath+'/api/libs/rangy/rangy-selectionsaverestore.js',
    mwpath+'/api/libs/rangy/rangy-serializer.js',
    mwpath+'/api/components.js',
    mwpath+'/api/jquery-ui.js',
    mwpath+'/api/liveadmin.js',
    mwpath+'/api/wysiwyg.js',
    mwpath+'/api/style_editors.js',
    mwpath+'/api/liveedit.js',
    mwpath+'/api/upgrades.js',
    mwpath+'/api/icon_selector.js',
    mwpath+'/api/control_box.js',
    mwpath+'/api/element_analyzer.js',
    mwpath+'/api/plus.js',
    mwpath+'/api/columns.js',
    mwpath+'/api/jscolor.js',
    mwpath+'/api/editor_externals.js',
    mwpath+'/api/external_callbacks.js',
    mwpath+'/api/content.js',
    mwpath+'/api/plupload.js',
    mwpath+'/api/plupload.html5.js',
    mwpath+'/api/plupload.html4.js',
];

liveEditJS = (prod)=>{
    let mwRequire = [];
    jsFiles.forEach((item)=>{
        mwRequire.push(`mw.require('${item}');`);
    });
    var final = gulp.src(jsFiles)
        .pipe(inject.prepend(mwRequire.join('')))
        .pipe(concat( 'liveedit.js', { newLine: ';\r\n' }))
        .pipe(rename({suffix: '.min'}))
    if(prod){
        final.pipe(uglify({mangle: false}))
    }
    return final.pipe(gulp.dest(dest));
}

liveEditCSS = ()=>{
    return gulp.src(cssFiles)
    .pipe(cssmin())
    .pipe(concat(  'main.css', {newLine: ';\r\n'}))
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest(dest));
}

gulp.task('live-edit',  () => {
    watch( mwpath+'/js/*.js',  { ignoreInitial: false }, (files) => {
        liveEditJS();
        count++;
        console.log(count + ' - js');
    });
    watch( mwpath+'/css/*.css',  { ignoreInitial: false }, (files) => {
        liveEditCSS();
        count++;
        console.log(count + ' - css');
    });
});

gulp.task('default', ['live-edit']);