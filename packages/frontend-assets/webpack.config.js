import RemoveEmptyScriptsPlugin from "webpack-remove-empty-scripts";
import MiniCssExtractPlugin from "mini-css-extract-plugin";
import path from "path";
import { fileURLToPath } from 'url';
import CopyWebpackPlugin from 'copy-webpack-plugin';
import { config } from './config.js';
import { exec } from 'child_process';
import { VueLoaderPlugin } from 'vue-loader';



const { entry, outputJS, outputCSS } = config;

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const plugins = [
    new RemoveEmptyScriptsPlugin(),
    new VueLoaderPlugin(),
    new MiniCssExtractPlugin({
        ignoreOrder: true,
        filename: (pathData) => {
            return `${outputCSS}/[name].css`;
        },
    }),
    {
        apply: (compiler) => {
           // compiler.hooks.afterEmit.tap('AfterEmitPlugin', (compilation) => {
                exec('vite build', (err, stdout, stderr) => {
                    if (err) {
                        console.error(`Error executing Vite build: ${err}`);
                        return;
                    }
                    console.log(`Vite build output: ${stdout}`);
                    if (stderr) {
                        console.error(`Vite build errors: ${stderr}`);
                    }
                });
          //  });
        }
    },
    new CopyWebpackPlugin({
        patterns: [
            { from: path.resolve(__dirname, './resources/dist/'), to: path.resolve(__dirname, '../../public/vendor/microweber-packages/frontend-assets') }
        ]
    })

];

const module = {
    rules: [
        {
            test: /\.(scss|css)$/,
            use: [
                MiniCssExtractPlugin.loader,
                "css-loader",
                "sass-loader",
            ],
        },{
            test: /\.(vue|vuejs)$/,
            use: [
                "vue-loader"
            ],
        },
    ],
};

export default {
    entry,
    output: {
        path: path.resolve(__dirname, outputJS),
        filename: '[name].js',
    },
    module,
    plugins,
    infrastructureLogging: {
        level: 'log'
    },
    watchOptions: {
        aggregateTimeout: 200,

        ignored: [ '**/node_modules', '**/dist'],
        poll: 1000,
    },
};
