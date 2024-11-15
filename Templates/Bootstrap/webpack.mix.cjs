let mix = require('laravel-mix');
let path = require('path');
let fs = require('fs-extra');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");



mix
    .js('resources/assets/js/app.js', 'resources/dist/build')
    .sass('resources/assets/sass/app.scss', 'resources/dist/build')
    .sass('resources/assets/sass/app-rtl.scss', 'resources/dist/build').sourceMaps();
    //.copyDirectory('resources/assets', 'public/templates/bootstrap');
mix.after(() => {
    fs.copySync(
        path.resolve(__dirname, 'resources/dist/build'),
        path.resolve(__dirname, '../../public/templates/bootstrap')
    );
});
