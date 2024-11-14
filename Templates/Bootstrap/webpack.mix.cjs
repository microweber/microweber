let mix = require('laravel-mix');
let path = require('path');
let fs = require('fs-extra');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

mix.webpackConfig = () => {};


mix.webpackConfig({
    plugins: [new MiniCssExtractPlugin()],
    stats: {
        children: true,
        warningsFilter: [
          /\-\-underline\-color/,
        ]
      },

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
                test: /\.(scss)$/,
                use: [
                    {
                        loader: MiniCssExtractPlugin.loader,
                    },
                    "style-loader",
                    // Translates CSS into CommonJS
                    "css-loader",
                    // Compiles Sass to CSS
                    "sass-loader",

                ],
            }
                /*use: [

                    {
                        loader: MiniCssExtractPlugin.loader,
                    },
                    { loader: "sass-loader", options: { sourceMap: true } },
                    {
                        loader: "css-loader",
                        options: {
                            sourceMap: true,
                            modules: false,
                        },
                    },
                    {
                        loader: "postcss-loader",
                        options: {
                            postcssOptions: {
                                plugins: ["autoprefixer"],
                            },
                        },
                    },

                ],
            }*/
            /*{
                test: /\.scss$/,
                use: [
                    {
                        loader: "file-loader",
                        options: {
                            name: "[name].css",
                            outputPath: "css",
                            esModule: false,
                        }
                    },
                    MiniCssExtractPlugin.loader,
                    {
                        loader: "css-loader",
                        options: {
                            sourceMap: true,
                            modules: false,
                        },
                    },
                    {
                        loader: "sass-loader",
                    }
                ]
            }*/
        ]
    },
});

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
