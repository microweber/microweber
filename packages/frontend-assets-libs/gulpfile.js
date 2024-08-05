import gulp from 'gulp';
import {BuildService} from './build.service.js';
import {config} from './config.js';

const  { scripts, css, output, assets} = config;


const service = new BuildService({
    output
});

const build = () => {
    return Promise.all([
        ...scripts.map(obj => service.buildSingleJS(obj)),
        ...css.map(obj => service.buildSingleCss(obj)),
        ...assets.map(obj => service.buildSingleAsset(obj))
    ]);
};

gulp.task('build', build);
