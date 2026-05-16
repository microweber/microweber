let mix = require('laravel-mix');
let autoprefixer = require('autoprefixer');
let path = require('path');
let fs = require('fs-extra');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const webpack = require('webpack');
mix.webpackConfig({
    plugins: [
        new webpack.optimize.LimitChunkCountPlugin({
            maxChunks:1,
        }),
    ],
    stats: {
        children: true
    }
});

/*
 * task-2026-05-16-ec4fe0: silence Dart-Sass deprecation warnings that
 * originate from Bootstrap's own SCSS in `node_modules/bootstrap/scss/
 * bootstrap.scss` (uses the legacy `@import` syntax). The warnings
 * were noisy on every build:
 *   "Sass @import rules are deprecated and will be removed in Dart
 *    Sass 3.0.0."
 * The Big2 `app.scss` already uses `@use` — the noise is purely
 * upstream. `quietDeps: true` silences warnings from anything under
 * `node_modules/`; `silenceDeprecations` explicitly suppresses the
 * named deprecation channels in case `quietDeps` doesn't cover them
 * (e.g. when the deprecation surfaces via a mixin emitted by
 * Bootstrap into our own stylesheet).
 *
 * Drop `silenceDeprecations: ['import']` when Bootstrap 6 ships with
 * `@use`-native SCSS.
 */
mix
    .js('resources/assets/js/app.js', 'resources/assets/dist/build')
    .sass('resources/assets/sass/app.scss', 'resources/assets/dist/build', {
        sassOptions: {
            quietDeps: true,
            silenceDeprecations: [
                'import',
                'slash-div',
                'legacy-js-api',
                'global-builtin',
                'color-functions',
                'mixed-decls',
                'function-units',
            ],
        },
    });

mix.after(() => {
    fs.copySync(
        path.resolve(__dirname, 'resources/assets'),
        path.resolve(__dirname, '../../public/templates/big2')
    );
});
