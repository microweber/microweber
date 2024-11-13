import gulp from 'gulp';
import gulpCopy from 'gulp-copy';
import fs from 'fs';

import fse from "fs-extra";

export const copyFolderSync = (from, to) => {
    fse.removeSync(to);
    fse.copySync(from, to);
}

export class BuildService {

    constructor(options) {
        this.output = options.output || "";
    }

    #createReadWriteStream(obj) {
        let array;
        if(Array.isArray(obj.path)) {
            array = obj.path;
        } else if(typeof obj.path === 'string') {
            array = [obj.path];
        }
        return gulp.src(array);
    }

    buildSingleJS(obj) {
        return new Promise(async (resolve, reject) => {

            this
                .#createReadWriteStream(obj)
                .pipe(gulp.dest(`${obj.output || this.output}/${obj.target}`))
                .on('finish', resolve)
                .on('error', reject)
            console.log(`${obj.target} compiled`);
        })
    }

    buildSingleAsset (obj, prefix) {
        console.log(prefix);
        if(Array.isArray(obj.path)) {

            return Promise.all(obj.path.map(path => this.buildSingleAsset(Object.assign({}, obj, {path}), prefix)));
        }
        return new Promise(async (resolve, reject) => {
            //resolve(this.copyAsset(obj));
             gulp.src([`${obj.path}/**`, `${obj.path}/*`])
                .pipe(gulpCopy(`${obj.output || this.output}/${obj.target}`, {prefix: prefix ?? obj.path.split('/').length - 1}))
                .on('finish', resolve)
                .on('error', err => {

                    reject(err)
                });

        })
    }

    buildSingleCss (obj)  {
        return new Promise(async (resolve, reject) => {
            this
                .#createReadWriteStream(obj)
                .pipe(gulp.dest(`${obj.output || this.output}/${obj.target}`))
                .on('finish', resolve)
                .on('error', reject)
            console.log(`${obj.target} compiled`);
        })
    }



    copyAsset(obj) {
        const output = `${obj.output || this.output}/${obj.target.split('/').slice(0, -1).join('/')}`;
        return new Promise((resolve, reject) => {
            fs.stat(obj.path, (err, stats) => {
                if (err) {
                    return reject(err);
                }

                if (stats.isFile()) {
                    gulp.src(obj.path)
                        .pipe(gulp.dest(output))
                        .on('finish', resolve)
                        .on('error', reject);
                } else if (stats.isDirectory()) {
                    copyFolderSync(obj.path, output);
                    resolve();
                } else {
                    reject(new Error('Path is neither a file nor a directory'));
                }

                console.log(`${obj.target} compiled`);
            });
        });
    }





}
