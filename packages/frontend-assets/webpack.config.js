import MiniCssExtractPlugin from "mini-css-extract-plugin";
import path from "path";
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const plugins =  [
    new MiniCssExtractPlugin({

        filename: "[name].css",
        chunkFilename: "[id].css",
    }),
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
            test: /\.scss$/i,
            use: [
                "style-loader",
                "scss-loader",
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
            test: /\.m?js/,
            resolve: {
                fullySpecified: false,
            },
        },
    ],
};

export default {
    entry: {
        core: './resources/assets/js/core.js',
        admin: './resources/assets/js/admin.js',
        admincss: './resources/assets/css/admin.css',
    },
    output: {
        path: path.resolve(__dirname, './resources/dist/js'),
        filename: '[name].js',
    },
    module,
    plugins,

}
