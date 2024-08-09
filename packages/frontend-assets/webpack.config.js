import RemoveEmptyScriptsPlugin from "webpack-remove-empty-scripts";
import MiniCssExtractPlugin from "mini-css-extract-plugin";
import path from "path";
import { fileURLToPath } from 'url';
import CopyWebpackPlugin from 'copy-webpack-plugin';
import {config} from './config.js';

const {entry, outputJS, outputCSS} = config;


const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const plugins =  [
    new RemoveEmptyScriptsPlugin(),
    new MiniCssExtractPlugin({
        ignoreOrder: true,
        filename: (pathData) => {
            console.log(pathData.chunk.name, pathData.runtime);
/*
            if(pathData.chunk.name.endsWith('css')){ // todo
                const i = pathData.chunk.name.lastIndexOf('css');
                const name = pathData.chunk.name.substring(0, i);
                pathData.chunk.name = name;
                pathData.runtime = name;
            }*/
            return `${outputCSS}/[name].css`;
        },
        // chunkFilename: "[id].css",
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
            test:  /\.(scss|css)$/,

            use: [
                MiniCssExtractPlugin.loader,
              "css-loader",
              "sass-loader",
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

}
