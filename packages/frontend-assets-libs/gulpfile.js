import gulp from 'gulp';

const scripts = [
    {target: 'jquery', path: 'node_modules/jquery/dist/jquery.js'},
    {target: 'jquery-ui', path: 'node_modules/jquery-ui/dist/jquery-ui.js'},

];

const css = [];

const output = `./resources/dist`;

const build = () => {
    return Promise.all(scripts.map(obj => buildSingle(obj)));
};

const buildSingle = (obj) => {
    return new Promise(async (resolve, reject) => {
        gulp.src([
            `${obj.path}`,
        ])
        .pipe(gulp.dest(`${output}/${obj.target}`))
            .on('finish', resolve)
            .on('error', reject)
        console.log(`${obj.path.split('/').pop()} compiled`);
    })
};

gulp.task('build', build);
