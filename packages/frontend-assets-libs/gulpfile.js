import gulp from 'gulp';
import fs from 'fs';

import {BuildService} from './build.service.js';
import {config} from './config.js';

const  { scripts, css, output, assets, copy} = config;


const service = new BuildService({
    output
});





const build = () => {


    return Promise.all([
        ...scripts.map(obj => service.buildSingleJS(obj)),
        ...css.map(obj => service.buildSingleCss(obj)),
        ...assets.map(obj => service.buildSingleAsset(obj)),
        ...copy.map(obj => service.copyAsset(obj))
    ]);
};

gulp.task('build', async done => {

    build().catch(err => {
        console.error( err);
    });

    done();
});
