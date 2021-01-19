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
        core: glob.sync(path.resolve(`${input}/{tools,tools/core-tools,core}/*.js`)),
        liveedit: glob.sync(path.resolve(`${input}/liveedit/*.js`)),
        admin: glob.sync(path.resolve(`${input}/{tools/system-tools,tools/widgets,widgets,system,admin}/*.js`)),
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
