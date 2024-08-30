
const path = require('path');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const glob = require("glob");

const dir = __dirname;
const output = `${dir}/dist`;
const input = `${dir}/../userfiles/modules/microweber/api`;
const inputs = arr => arr.map(item => path.resolve(`${input}/${item}`));
const config = {
    watchOptions: {
        aggregateTimeout: 600
    },
    entry: {
        admin: glob.sync(path.resolve(`${input}/admin/*.js`)),
        content: glob.sync(path.resolve(`${input}/content/*.js`)),
        core: glob.sync(path.resolve(`${input}/core/*.js`)),
        editor: glob.sync(path.resolve(`${input}/editor/*.js`)),
        liveedit:  glob.sync(path.resolve(`${input}/liveedit/*.js`)),
        'system-tools':  glob.sync(path.resolve(`${input}/system-tools/*.js`)),
        'system-widgets':  glob.sync(path.resolve(`${input}/system-widgets/*.js`)),
        tools:  glob.sync(path.resolve(`${input}/tools/*.js`)),
        widgets: glob.sync(path.resolve(`${input}/widgets/*.js`)),
    },
    plugins: [
        new CleanWebpackPlugin(),
    ],
    output: {
        filename: '[name].js',
        path: path.resolve(output)
    },
    "scripts": {
        "dev": "webpack --mode=development --watch",
        "prod": "webpack --mode=production",
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
