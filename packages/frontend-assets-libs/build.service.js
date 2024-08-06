import gulp from 'gulp';
import gulpCopy from 'gulp-copy';
import fs from 'fs';

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



    copyAsset(obj) {
        return new Promise(async (resolve, reject) => {
            fs.stat(obj.path, (err, stats) => {
                if (err) {
                    return reject(err);
                }

                if (stats.isFile()) {
                    gulp.src([`${obj.path}`])
                        .pipe(gulp.dest(`${this.output}/${obj.target.split('/').slice(0, -1).join('/')}`))
                        .on('finish', resolve)
                        .on('error', reject);
                    console.log(`${obj.path.split('/').pop()} compiled`);
                } else {
                    gulp.src([`${obj.path}/**/*`])
                        .pipe(gulp.dest(`${this.output}/${obj.target}`))
                        .on('finish', resolve)
                        .on('error', reject);
                    console.log(`${obj.path.split('/').pop()} compiled`);
                }
            });
        });
    }


}
