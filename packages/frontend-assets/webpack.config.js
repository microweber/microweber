import path from "path";
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

export default {
    entry: {
        core: './resources/assets/js/core.js',
        admin: './resources/assets/js/admin.js',
    },
    output: {
        path: path.resolve(__dirname, './resources/dist/js'),
        filename: '[name].js',
    },
}
