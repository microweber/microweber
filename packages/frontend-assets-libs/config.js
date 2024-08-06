
const scripts = [
    {target: 'jquery', path: 'node_modules/jquery/dist/jquery.js'},
    {target: 'jquery-ui', path: 'node_modules/jquery-ui/dist/jquery-ui.js'},
];

const css = [
    {target: 'jquery-ui', path: 'node_modules/jquery-ui/dist/themes/base/jquery-ui.css'},
];

const assets = [
    {target: 'jquery-ui', path: 'node_modules/jquery-ui/dist/themes/base/images'},
];

const copy = [
    {target: 'flag-icons/css', path: 'node_modules/flag-icons/css'},
    {target: 'flag-icons/flags', path: 'node_modules/flag-icons/flags'},
    {target: 'flag-icons/country.json', path: 'node_modules/flag-icons/country.json'},
];

const output = `./resources/dist`;


export const config = {
    scripts, css, output, assets, copy
}
