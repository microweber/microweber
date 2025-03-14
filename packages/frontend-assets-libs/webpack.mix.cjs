let mix = require('laravel-mix');
let path = require('path');
let fs = require('fs-extra');
let config = require('./config-common.js');

/*
mix.webpackConfig({
    resolve: {
        modules: [
            path.resolve(__dirname, 'node_modules')
        ],

        fullySpecified: false,
        extensions: [".*", ".webpack.js", ".web.js", ".js", ".json", ".less"]
    },

});
mix.setPublicPath('public/build');*/



mix.copy(`./resources/local-libs/jseldom-jquery.js`, `../frontend-assets/resources/assets/libs/jseldom/jseldom-jquery.js`);
mix.copy(`./resources/local-libs/collapse-nav/collapse-nav.js`, `./resources/dist/collapse-nav/collapse-nav.js`);
mix.copy(`./resources/local-libs/highlight/highlight.min.js`, `./resources/dist/highlight-js/highlight.min.js`);


const isFolder = path => {
    const arr = path.split(".");
    return arr.length > 1 && arr.pop().length < 5;
};

const copy = async (target, path, afterCopy) => {
    const _isFolder = isFolder(target);
    const action = _isFolder ? 'copyDirectory' : 'copy';
    const targetFolder = `./resources/dist/${target}${!isFolder ? '/' + path.split('/').pop() : ''}`;
    mix[action](path, targetFolder);



    if(typeof afterCopy === 'function') {
       await  afterCopy({
            path,
            targetFolder
        })
    }
}

const js = async (target, path) => {


    await mix.js(path, `./resources/dist/${target}/${path.split('/').pop()}`);

};


const afterBuild = [];



;[
    ...config.scripts,
    ...config.css,
    ...config.copy,
    ...config.assets,

].forEach( async (conf) => {

    let action = 'copy';
    if(conf.process) {
        action = 'js';
    }
    const actions = {js, copy};
    if(Array.isArray(conf.path)) {
        conf.path.forEach(async (path) => {
            if(conf.afterCopy) {
                afterBuild.push(
                    conf
                )
            }
            await actions[action](conf.target, path/*, conf.afterCopy*/)
        });
    } else {
        if(conf.afterCopy) {
            afterBuild.push(
                conf
            )
        }
        await actions[action](conf.target, conf.path/*, conf.afterCopy*/)
    }
});




mix.copyDirectory(`./resources/local-libs/mw-icons-mind`, `./resources/dist/mw-icons-mind`);


mix.after(async () => {
    const from = './resources/dist';
    const to = '../../public/vendor/microweber-packages/frontend-assets-libs';

    for (let i = 0; i < afterBuild.length; i++ ) {
        const conf = afterBuild[i];
        conf.target = `${from}/${conf.target}`;
        await conf.afterCopy(conf);
    }

    fs.removeSync(to);
    fs.copySync(from, to);
});
