const path = require('path');
const CopyWebpackPlugin = require('copy-webpack-plugin');

const outputPath = path.resolve(__dirname, 'resources/dist/build');

module.exports = {
    mode: process.env.NODE_ENV === 'production' ? 'production' : 'development',
    entry: {
        'microweber-filament-theme': path.resolve(__dirname, 'resources/assets/js/microweber-filament-theme.js'),
    },
    output: {
        path: outputPath,
        filename: '[name].js',
    },
    plugins: [
        new CopyWebpackPlugin({
            patterns: [
                { from: path.resolve(__dirname, 'resources/assets/js/mw-media-browser.js'), to: outputPath },
                { from: path.resolve(__dirname, 'resources/assets/js/filament-translatable.js'), to: outputPath },
                { from: path.resolve(__dirname, 'resources/assets/js/mw-tree-component.js'), to: outputPath },
                { from: path.resolve(__dirname, 'resources/assets/js/tiny-editor.js'), to: outputPath },
            ],
        }),
    ],
    resolve: {
        modules: [path.resolve(__dirname, 'node_modules')],
        fullySpecified: false,
        extensions: ['.*', '.js', '.json'],
    },
    stats: { children: true },
};
