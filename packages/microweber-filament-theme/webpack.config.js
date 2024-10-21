import path from "path";
import { fileURLToPath } from 'url';
import CopyWebpackPlugin from 'copy-webpack-plugin';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

export default {
    entry: {
        'microweber-filament-theme.js': './resources/assets/js/microweber-filament-theme.js',
    },
    output: {
        path: path.resolve(__dirname, './resources/dist/js'),
        filename: '[name]',
    }
    ,
    plugins: [
        new CopyWebpackPlugin({
            patterns: [
                { from: path.resolve(__dirname, './resources/dist/'), to: path.resolve(__dirname, '../../public/vendor/microweber-packages/microweber-filament-theme') }
            ]
        })
    ]
}
