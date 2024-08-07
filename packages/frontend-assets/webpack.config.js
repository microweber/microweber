import MiniCssExtractPlugin from "mini-css-extract-plugin";
import path from "path";
import { fileURLToPath } from 'url';
import CopyWebpackPlugin from 'copy-webpack-plugin';


const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const plugins =  [
    new MiniCssExtractPlugin({


        filename: (pathData) => {


            return '[name].css'
          },
        chunkFilename: "[id].css",
    }),


    new CopyWebpackPlugin({
        patterns: [
            { from: path.resolve(__dirname, './resources/dist/'), to: path.resolve(__dirname, '../../public/vendor/microweber-packages/frontend-assets') }
        ]
    })

];

const module =  {
    rules: [
        {
            test: /\.css$/i,
            use: [
                "style-loader",
                "css-loader",
                {
                    loader: "postcss-loader",
                    options: {
                    postcssOptions: {
                        plugins: [
                            [
                                "postcss-preset-env",
                            ],
                        ],
                    },
                    },
                },
            ],
        },

        {
            test:  /\.(scss|css)$/,
            //test: /\.s[ac]ss$/i,
            use: [
                MiniCssExtractPlugin.loader,
              "css-loader",
              "sass-loader",
            ],
        },
        {
            test: /\.m?js/,
            resolve: {
                fullySpecified: false,
            },
        },
    ],
};

export default {
    entry: {
        //core: './resources/assets/js/core.js',
        //admin: './resources/assets/js/admin.js',
        admincss: './resources/assets/css/admin.scss',
    },
    output: {
        path: path.resolve(__dirname, './resources/dist/js'),
        // filename: '[name].js',
    },
    module,
    plugins,

}
