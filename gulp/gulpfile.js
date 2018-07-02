const gulp = require('gulp')/*(require('gulp'), process.argv)*/;


let count = 0;
const vars = require('./vars');

gulp.task('live-edit',  () => {
    const liveedit = require('./tasks/liveedit');
    liveedit.task();
});

gulp.task('template-dream', ()=>{
    const tpl = require('./tasks/template-dream');
    tpl.task();
});
