import gulp from 'gulp';

const scripts = [
    'node_modules/jquery/dist/jquery.js',
    'node_modules/jquery-ui/dist/jquery-ui.js',
];

const output = `./resources/dist`;

const build = (path) => {
    return Promise.all(scripts.map(path => buildSingle(path)));
};

const buildSingle = (path) => {
    return new Promise(async (resolve, reject) => {
        gulp.src([
            `${path}`,
        ])
        .pipe(gulp.dest(output))
            .on('finish', resolve)
            .on('error', reject)
        console.log(`${path.split('/').pop()} compiled`);
    })
};

gulp.task('build', build);
