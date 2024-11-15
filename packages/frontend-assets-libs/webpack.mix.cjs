let mix = require('laravel-mix');
let path = require('path');
let fs = require('fs-extra');
let config = require('./config-common.js');


mix.webpackConfig({
    resolve: {
        modules: [
            path.resolve(__dirname, 'node_modules')
        ],

        fullySpecified: false,
        extensions: [".*", ".webpack.js", ".web.js", ".js", ".json", ".less"]
    },

});
mix.setPublicPath('public/build');



mix.copy(`./resources/local-libs/jseldom-jquery.js`, `../frontend-assets/resources/assets/libs/jseldom/jseldom-jquery.js`);
mix.copy(`./resources/local-libs/collapse-nav/collapse-nav.js`, `./resources/dist/collapse-nav/collapse-nav.js`);
mix.copy(`./resources/local-libs/highlight/highlight.min.js`, `./resources/dist/highlight-js/highlight.min.js`);


const isFolder = path => {
    const arr = path.split(".");
    return arr.length > 1 && arr.pop().length < 5;
};

const copy = (target, path) => {
    const _isFolder = isFolder(target);
    const action = _isFolder ? 'copyDirectory' : 'copy';
    mix[action](path, `./resources/dist/${target}${!isFolder ? '/' + path.split('/').pop() : ''}`);
}


[
    ...config.scripts,
    ...config.css,
    ...config.copy,
    ...config.assets,

].forEach((conf) => {
      if(Array.isArray(conf.path)) {
        conf.path.forEach((path) => {
            copy(conf.target, path)
        });
      } else {
        copy(conf.target, conf.path)
      }
});




mix.copyDirectory(`./resources/local-libs/mw-icons-mind`, `./resources/dist/mw-icons-mind`);


mix.after(() => {
    const from = './resources/dist';
    const to = '../../public/vendor/microweber-packages/frontend-assets-libs';
    fs.removeSync(to);
    fs.copySync(from, to);
});
