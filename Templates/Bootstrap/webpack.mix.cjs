let mix = require('laravel-mix');
let path = require('path');
let fs = require('fs-extra');


mix.webpackConfig({

    resolve: {
        modules: [
            path.resolve(__dirname, 'node_modules')
        ],

        fullySpecified: false,
        extensions: [".*", ".webpack.js", ".web.js", ".js", ".json", ".less"]
    },
    module: {
        rules: [
            {
                test: /\.scss$/,
                use: [
                    {
                        loader: "css-loader",
                    },
                    {
                        loader: "sass-loader",
                    }
                ]
            }
        ]
    },
});

mix.js('resources/assets/js/app.js', 'resources/dist/build')
    .sass('resources/assets/sass/app.scss', 'resources/dist/build')
    .sass('resources/assets/sass/app-rtl.scss', 'resources/dist/build')
    //.copyDirectory('resources/assets', 'public/templates/bootstrap');
mix.after(() => {
    fs.copySync(
        path.resolve(__dirname, 'resources/dist/build'),
        path.resolve(__dirname, '../../public/templates/bootstrap')
    );
});
