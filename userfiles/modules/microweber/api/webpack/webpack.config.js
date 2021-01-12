
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
        core: glob.sync(path.resolve(`${input}/core/*.js`)),
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
        config.devtool = 'inline-source-map';
    }
    if (argv.mode === 'production') {
        //...
    }
    return config;
};
