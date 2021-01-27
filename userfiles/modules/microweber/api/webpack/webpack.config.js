const path = require('path');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const glob = require("glob");

const webpack = require('webpack');

const dir = __dirname;
const output = `${dir}/dist`;
const input = `${dir}/..`;
const inputs = arr => arr.map(item => path.resolve(`${input}/${item}`));



const config = {
    watchOptions: {
        aggregateTimeout: 600
    },
    entry: {
        core: glob.sync(path.resolve(`${input}/{tools,tools/core-tools,core,system}/*.js`)),
        liveedit: glob.sync(path.resolve(`${input}/{liveedit,libs/rangy,tools/widgets,tools/system-tools,widgets,gui-css-editor}/*.js`)),
        admin: glob.sync(path.resolve(`${input}/{tools/system-tools,tools/widgets,widgets,admin}/*.js`)),
        'gui-css-editor': glob.sync(path.resolve(`${input}/{tools/system-tools,tools/widgets,widgets,gui-css-editor}/*.js`)),
        editor: [
            input + '/editor/editor.js',
            input + '/editor/bar.js',
            input + '/editor/api.js',
            input + '/editor/helpers.js',
            input + '/editor/tools.js',
            input + '/editor/core.js',
            input + '/editor/controllers.js',
            input + '/editor/add.controller.js',
            input + '/editor/interaction-controls.js',
            input + '/editor/i18n.js',
            input + '/editor/liveeditmode.js',
        ],
    },
    plugins: [
        new CleanWebpackPlugin(),
    ],
    output: {
        filename: '[name].js',
        path: path.resolve(output)
    },
};

module.exports = (env, argv) => {
    argv.mode = argv.mode || 'development';
    if (argv.mode === 'development') {
        config.devtool = 'cheap-module-source-map';
    }
    if (argv.mode === 'production') {
        //...
    }
    return config;
};
