
const scripts = [

    {target: 'jquery', path: 'node_modules/jquery/dist/jquery.js'},
    {target: 'jquery-ui', path: 'node_modules/jquery-ui/dist/jquery-ui.js'},
    {target: 'jquery-nested-sortable', path: 'node_modules/nestedSortable/jquery.mjs.nestedSortable.js'},
    {target: 'nouislider', path: 'node_modules/nouislider/dist/nouislider.js'},
    {target: 'tinymce', path: 'node_modules/tinymce/tinymce.js'},
];

const css = [
    {target: 'jquery-ui', path: 'node_modules/jquery-ui/dist/themes/base/jquery-ui.css'},
    {target: 'nouislider', path: 'node_modules/nouislider/dist/nouislider.css'},
    {target: 'material-icons', path: 'node_modules/material-icons/iconfont/material-icons.css'},
];

const assets = [
    {target: 'jquery-ui', path: 'node_modules/jquery-ui/dist/themes/base/images'},
    {target: 'material-icons', path: 'node_modules/material-icons/iconfont'},
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
