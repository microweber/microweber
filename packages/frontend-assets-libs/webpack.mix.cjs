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
    // stats: {
    //     children: true
    // },
});
mix.setPublicPath('public/build');


// mix.js('resources/assets/ui/apps/ElementStyleEditor/element-style-editor-app.js', 'resources/dist/build').setPublicPath('resources/dist/build').vue();
// mix.sass('resources/assets/ui/apps/ElementStyleEditor/element-style-editor-app.scss', 'resources/dist/build').setPublicPath('resources/dist/build').vue();
// mix.sass('resources/assets/css/scss/liveedit.scss', 'resources/dist/build').setPublicPath('resources/dist/build').vue();

// Assets

mix.after(() => {
    const from = './resources/dist';
    const to = '../../public/vendor/microweber-packages/frontend-assets-libs';
    fs.removeSync(to);
    fs.copySync(from, to);
});
