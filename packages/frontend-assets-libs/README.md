# Microweber Frontend Assets Libs

The third-party JavaScript/CSS vendor libraries the Microweber front end and Live Edit depend on — jQuery, jQuery UI, Swiper, Slick, nouislider, CodeMirror, EasyMDE, DOMPurify/xss, Masonry, flatpickr-style date pickers, flag/material icons, and more.

Unlike `frontend-assets`, this package does **not** bundle with Vite/Webpack — its build step simply **copies** the required files out of `node_modules` (and a few hand-maintained local libs) into `resources/dist` and the public vendor folder.

## What it provides

- Vendored libs under `resources/dist/<lib>/` (e.g. `jquery`, `jquery-ui`, `swiper`, `slick`, `nouislider`, `codemirror`, `easymde`, `purify`, `masonry`, `bootstrap_datepicker`, `webfontloader`, `rangy`, `flag-icons`, `material-icons`, …).
- Local Microweber libs under `resources/local-libs/` (e.g. `mw-slick`, `justified-gallery`, `jquery-nested-sortable`).
- The published copy at `public/vendor/microweber-packages/frontend-assets-libs`.

The exact list of copied targets is defined in `config.js` / `config-common.js` (node-module scripts + local scripts).

## Installation

```bash
composer require microweber-packages/frontend-assets-libs
```

The service provider (`MicroweberFrontendAssetsLibsServiceProvider`) registers the package assets and, when `resources/dist` exists, publishes them to `public/`.

## Building the assets

```bash
cd packages/frontend-assets-libs
npm install
npm run build   # node build.mjs
```

`build.mjs` is a plain copy script (it replaces the old laravel-mix pipeline — "no bundler needed"). It reads the target list from `config-common.js`, copies each lib from `node_modules`/`resources/local-libs` into `resources/dist/<target>/`, and then mirrors `resources/dist` into:

```
public/vendor/microweber-packages/frontend-assets-libs
```

A few local libs are also copied into the sibling `frontend-assets` package (e.g. `jseldom-jquery.js`).

To add or update a vendored library, add the npm dependency to `package.json` and register its source path in `config.js`, then rebuild.

## Publishing to public/

For an installed package with a prebuilt `resources/dist`:

```bash
php artisan vendor:publish --tag=public --force --ansi
```

## Key files

- `MicroweberPackages\FrontendAssetsLibs\MicroweberFrontendAssetsLibsServiceProvider` — package/asset registration + `public` publish.
- `build.mjs` — the copy-based build script.
- `config.js` / `config-common.js` — the list of libraries and their source paths.

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov
