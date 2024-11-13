let mix = require('laravel-mix');
let path = require('path');
let fs = require('fs-extra');



let options = {
    minimize:true,
    uglify: {
        uglifyOptions: {
            warnings: false,
            comments: false,
            beautify: false,
            minify: true,
            minimize: true,
            compress: {
                drop_console: true,
                minimize: true,
            }
        }
    },
    cssnano:false,
};


mix.webpackConfig({
    resolve: {
        modules: [
            path.resolve(__dirname, 'node_modules')
        ],

        fullySpecified: false,
        extensions: [".*", ".webpack.js", ".web.js", ".js", ".json", ".less"]
    },
    // stats: {
    //     children: true
    // },
});



mix.copy('./resources/assets/js/layouts-module-settings.js', '../../public/modules/layouts/js/layouts-module-settings.js');
