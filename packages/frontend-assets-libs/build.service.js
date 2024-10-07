import gulp from 'gulp';
import gulpCopy from 'gulp-copy';
import fs from 'fs';/*
import babel from 'gulp-babel';
import browserify  from 'browserify';*/

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
        if(Array.isArray(obj.path)) {

            return Promise.all(obj.path.map(path => this.buildSingleAsset(Object.assign({}, obj, {path}), prefix)));
        }
        return new Promise(async (resolve, reject) => {
            gulp.src([`${obj.path}/**`, `${obj.path}/*`])
            .pipe(gulpCopy(`${obj.output || this.output}/${obj.target}`, {prefix: prefix ?? obj.path.split('/').length - 1}))
                .on('finish', resolve)
                .on('error', err => {
                    console.log(err);
                    reject(err)
                });
            console.log(`${obj.target} asset compiled`);
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
        const output = `${obj.output || this.output}/${obj.target.split('/').slice(0, -1).join('/')}`
        return new Promise(async (resolve, reject) => {
            copyFolderSync(obj.path, output);
            resolve();
            console.log(`${obj.target} compiled`);
        })
    }

    copyAsset__(obj) {
        return new Promise(async (resolve, reject) => {
            fs.stat(obj.path, (err, stats) => {
                if (err) {
                    return reject(err);
                }

                if (stats.isFile()) {
                    this
                    .#createReadWriteStream(obj)
                        .pipe(gulp.dest(`${obj.output || this.output}/${obj.target.split('/').slice(0, -1).join('/')}`))
                        .on('finish', resolve)
                        .on('error', reject);
                    console.log(`${obj.target} compiled`);
                } else {
                    gulp.src([`${obj.path}/**/*`])
                        .pipe(gulp.dest(`${obj.output || this.output}/${obj.target}`))
                        .on('finish', resolve)
                        .on('error', reject);
                    console.log(`${obj.target} compiled`);
                }
            });
        });
    }


}
