const path = require('path');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');

const glob = require("glob");

const webpack = require('webpack');

const dir = __dirname;
const output = `${dir}/dist`;
const input = `${dir}/..`;
const css = `${dir}/../../css`;


const core = [
    'tools','tools/core-tools','core','system'
];


const prod = [
    ...core
];

const liveedit = [
    ...core,
    'liveedit',
    'libs/rangy',
    'tools/widgets',
    'tools/system-tools',
    'widgets',
    'gui-css-edito'
];

const admin = [
    ...core,
    'tools/system-tools','tools/widgets','widgets','admin'
];

const guiEditor = [
    'tools/system-tools','tools/widgets','widgets','gui-css-editor'
];

const config = {
    watchOptions: {
        aggregateTimeout: 600
    },
    entry: {
        'site-libs.js': input + '/entrylibs/prod.js',
        'liveedit-libs.js': input + '/entrylibs/liveedit.js',
        'admin-libs.js': input + '/entrylibs/admin.js',
        'prod.js': glob.sync(path.resolve(`${input}/{${prod.join(',')}}/*.js`)),
        'liveedit.js': glob.sync(path.resolve(`${input}/{${liveedit.join(',')}}/*.js`)),
        'admin.js': glob.sync(path.resolve(`${input}/{${admin.join(',')}}/*.js`)),
        'gui-css-editor.js': glob.sync(path.resolve(`${input}/{${guiEditor.join(',')}}/*.js`)),
        'editor.js': [
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
        ]
    },
    plugins: [
        new CleanWebpackPlugin(),

    ],
    output: {
        filename: '[name]',
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
