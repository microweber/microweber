import gulp from 'gulp';
import gulpCopy from 'gulp-copy';

export class BuildService {

    constructor(options) {
        this.output = options.output || "";
    }

    buildSingleJS(obj) {
        return new Promise(async (resolve, reject) => {
            gulp.src([
                `${obj.path}`,
            ])
            .pipe(gulp.dest(`${this.output}/${obj.target}`))
                .on('finish', resolve)
                .on('error', reject)
            console.log(`${obj.path.split('/').pop()} compiled`);
        })
    }

    buildSingleAsset (obj, prefix) {
        return new Promise(async (resolve, reject) => {
            gulp.src([
                `${obj.path}/*`,
            ]).pipe(gulpCopy(`${this.output}/${obj.target}`, {prefix: prefix ?? obj.path.split('/').length - 1}))
                .on('finish', resolve)
                .on('error', reject)
            console.log(`${obj.path.split('/').pop()} compiled`);
        })
    }

    buildSingleCss (obj)  {
        return new Promise(async (resolve, reject) => {
            gulp.src([
                `${obj.path}`,
            ])
            .pipe(gulp.dest(`${this.output}/${obj.target}`))
                .on('finish', resolve)
                .on('error', reject)
            console.log(`${obj.path.split('/').pop()} compiled`);
        })
    }
}
