# Microweber Frontend Assets

The Vite-built public JS/CSS bundle for the Microweber front end, Live Edit, and the admin UI shell. This package owns the **source** (Vue 3 SFCs, SCSS, api-core JS) and ships the **compiled** output to `public/vendor/microweber-packages/frontend-assets`.

## What it provides

- **`frontend.js`** — the public-site entry (imports `shop.js`, `csp-skin-handlers.js`, gallery/cart handlers, …).
- **`core.js` / `admin.js`** — the core api-core runtime and the admin UI shell.
- **`live-edit-app`**, **`live-edit-page-scripts`**, **`element-style-editor-app`** — the Live Edit canvas/toolbar apps (Vue 3).
- **`booking.js`** and **`ai-chat-sidebar.js`** — standalone feature bundles (separate Vite modes).
- CSS bundles: `admin.css`, `liveedit.css`, `default.css`, `design-system.css`, `mw-grid.css`, `element-style-editor-app.css`, `install.css`.

## Installation

```bash
composer require microweber-packages/frontend-assets
```

The service provider (`MicroweberFrontendAssetsServiceProvider`) is a Microweber package provider. It registers the package assets and, when `resources/dist` exists, publishes the built bundle to `public/`.

## Building the assets

The dev server serves the **built** bundle, not the source — always rebuild after editing anything under `resources/`.

```bash
cd packages/frontend-assets
npm install
npm run build
```

`npm run build` runs four Vite passes (see `package.json`):

```jsonc
"build": "vite build && vite build --mode frontend && vite build --mode booking && vite build --mode ai-chat-sidebar"
```

- **default pass** — all the ES-module entries (`live-edit-app`, `core`, `admin`, CSS bundles, …), with code splitting into `chunks/`.
- **`--mode frontend`** — single-file `frontend.js` public bundle.
- **`--mode booking`** — `booking.js` / `booking.css`.
- **`--mode ai-chat-sidebar`** — `ai-chat-sidebar.js` / `ai-chat-sidebar.css`.

`vite.config.js` writes to `resources/dist/build` and copies the result to `public/vendor/microweber-packages/frontend-assets/build` (clearing `chunks/` first so the public copy mirrors the source build exactly). It also renames a couple of Vite outputs to the names the Blade templates expect (`admin-css.css` → `admin.css`, `element-style-editor-scss.css` → `element-style-editor-app.css`).

For a watch loop while developing:

```bash
npm run dev   # vite build --watch
```

## Publishing to public/

If you consume the prebuilt `resources/dist` from an installed package, re-publish it with:

```bash
php artisan vendor:publish --tag=public --force --ansi
```

## Key files

- `MicroweberPackages\FrontendAssets\MicroweberFrontendAssetsServiceProvider` — package/asset registration + `public` publish.
- `resources/assets/js/frontend.js` — public JS entry.
- `resources/assets/ui/live-edit-app.js` — Live Edit canvas app.
- `vite.config.js` — entry map, CSS rename map, and the copy-to-public step.

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov
