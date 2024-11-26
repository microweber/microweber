let mix = require('laravel-mix');
let path = require('path');
let fs = require('fs-extra');
const webpack = require('webpack');

mix.webpackConfig({
    plugins: [
        new webpack.optimize.LimitChunkCountPlugin({
          maxChunks: 5,
        }),
      ],
});



mix
    .js('resources/assets/js/app.js', 'resources/assets/dist/build')
    .sass('resources/assets/sass/app.scss', 'resources/assets/dist/build')
    .sass('resources/assets/sass/app-rtl.scss', 'resources/assets/dist/build').sourceMaps();
    //.copyDirectory('resources/assets', 'public/templates/bootstrap');
mix.after(() => {
    fs.copySync(
        path.resolve(__dirname, 'resources/assets'),
        path.resolve(__dirname, '../../public/templates/bootstrap')
    );
});
