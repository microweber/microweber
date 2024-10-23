//import mix from 'laravel-mix';
require('mix-tailwindcss');
let path = require('path');
 let mix = require('laravel-mix');
let fs = require('fs-extra');
//import 'mix-tailwindcss';
//



mix.webpackConfig({

    module: {
        rules: [
            {
                test: /\.*$/,
                exclude: [
                    path.resolve(__dirname, 'node_modules'),
                    path.resolve(__dirname, 'resources/dist'),
                ]
            }
        ]
    },
    resolve: {
        modules: [
            path.resolve(__dirname, 'node_modules')
        ],

        fullySpecified: false,
        extensions: [".*", ".webpack.js", ".web.js", ".js", ".json", ".less"]
    },
    stats: {
        children: true
    },
});

mix.js('resources/assets/js/microweber-filament-theme.js', 'resources/dist/build').setPublicPath('resources/dist/build').vue();
mix.postCss('resources/assets/css/microweber-filament-theme.css', 'resources/dist/build')
    .setPublicPath('resources/dist/build').tailwind();



mix.after(() => {
    fs.copySync(
        path.resolve(__dirname, 'resources/dist/build'),
        path.resolve(__dirname, '../../public/vendor/microweber-packages/microweber-filament-theme/build')
    );
});
