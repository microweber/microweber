//import mix from 'laravel-mix';
let path = require('path');
let mix = require('laravel-mix');
let fs = require('fs-extra');

const webpack = require('webpack');




mix.webpackConfig({
    // plugins: [
    //     new webpack.optimize.LimitChunkCountPlugin({
    //         maxChunks:1,
    //     }),
    // ],
    resolve: {
        modules: [
            path.resolve(__dirname, 'node_modules')
        ],

        fullySpecified: false,
        extensions: [".*", ".webpack.js", ".web.js", "*.js", ".json", ".less"]
    },
    // stats: {
    //     children: true
    // },
});

mix.js('resources/assets/ui/live-edit-app.js', 'resources/dist/build').setPublicPath('resources/dist/build').vue();
mix.js('resources/assets/ui/apps/ElementStyleEditor/element-style-editor-app.js', 'resources/dist/build').setPublicPath('resources/dist/build').vue();
mix.sass('resources/assets/ui/apps/ElementStyleEditor/element-style-editor-app.scss', 'resources/dist/build').setPublicPath('resources/dist/build').vue();
mix.sass('resources/assets/css/scss/liveedit.scss', 'resources/dist/build').setPublicPath('resources/dist/build');
mix.css('resources/assets/css/microweber/css/default.css', 'resources/dist/build').setPublicPath('resources/dist/build');
mix.css('resources/assets/css/microweber/css/mw-grid.css', 'resources/dist/build').setPublicPath('resources/dist/build');


mix.js('resources/assets/js/core.js', 'resources/dist/build').setPublicPath('resources/dist/build');
mix.js('resources/assets/js/admin.js', 'resources/dist/build').setPublicPath('resources/dist/build');
mix.js('resources/assets/live-edit/live-edit-page-scripts.js', 'resources/dist/build').setPublicPath('resources/dist/build');
mix.js('resources/assets/js/frontend.js', 'resources/dist/build').setPublicPath('resources/dist/build');
mix.js('resources/assets/js/admin-filament-libs.js', 'resources/dist/build').setPublicPath('resources/dist/build');
mix.sass('resources/assets/css/admin.scss', 'resources/dist/build').setPublicPath('resources/dist/build');
mix.sass('resources/assets/css/install.scss', 'resources/dist/build').setPublicPath('resources/dist/build');


mix.after(() => {
    fs.copySync(
        path.resolve(__dirname, 'resources/dist/build'),
        path.resolve(__dirname, '../../public/vendor/microweber-packages/frontend-assets/build')
    );
});
