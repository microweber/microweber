import gulp from 'gulp';
import fs from 'fs';

import {BuildService} from './build.service.js';
import {config} from './config.js';
import fse from "fs-extra";

const {scripts, css, output, assets, copy} = config;

async function copyFolderSync (from, to) {
    fse.removeSync(to);
    fse.copySync(from, to);
}

const service = new BuildService({
    output
});


const build = async () => {
    Promise.all([
        ...scripts.map(obj => service.buildSingleJS(obj)),
        ...css.map(obj => service.buildSingleCss(obj)),
        ...assets.map(obj => service.buildSingleAsset(obj)),
        ...copy.map(obj => service.copyAsset(obj))
    ]);
    console.log('done');
};

gulp.task('build', async done => {
    try {
        build();
    } catch (err) {
        console.error(err);
    }



    done();

    var from = './resources/dist';
    var to = '../../public/vendor/microweber-packages/frontend-assets-libs';
    await copyFolderSync(from, to);

});
