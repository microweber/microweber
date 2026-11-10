# Microweber CSS Framework (Bootstrap)

Shared Microweber CSS framework built on Bootstrap and exposing semantic `--mw-*` CSS variables. This is an **asset package**: it ships the stylesheets, JS, design-style definitions and style packs that templates (Base, Plain and their children) load at runtime. It contains no public PHP API — its only job is to publish those assets into the application's `public/` directory.

## What it ships

- `resources/assets/css/mw-framework.css` — the shared framework stylesheet loaded by bundle-less templates.
- `resources/assets/css/mw-variables.css` — the semantic `--mw-*` design tokens.
- `resources/assets/css/vendor/bootstrap.min.css` — the Bootstrap base.
- `resources/assets/css/components/*.css` — component styles (`mw-header`, `mw-shop`, `mw-links`, `mw-sections`, `mw-testimonials`, `mw-utilities`, `mw-animations`, …).
- `resources/assets/design-styles/` — the design-system data the Live Edit style editor reads:
  - `mw-styles/*.json` — colors, typography, forms, button sizes/colors, header/footer/top-header colors.
  - `style-packs/full-styles-properties/*.json` — ready-made palette/style packs (e.g. `blueberry-night`, `vintage-mint`, `cyber-neon`).
- `resources/assets/js/` — supporting scripts.

## Installation

```bash
composer require microweber-packages/microweber-css-framework-bootstrap
```

The provider is registered through `spatie/laravel-package-tools`. In the Microweber monorepo it is loaded in dependency order by `CoreServiceProvider` (Layer 8) as `MicroweberPackages\CssFrameworkBootstrap\Providers\CssFrameworkServiceProvider`.

## Publishing the assets

The provider's only action is to register the asset publish group. The assets are published under both the shared `public` tag and a dedicated `mw-css-framework` tag:

```bash
# Publishes resources/assets → public/packages/microweber-css-framework-bootstrap
php artisan vendor:publish --tag=mw-css-framework

# Or as part of the full public-assets publish
php artisan vendor:publish --tag=public
```

Templates then reference the published files, e.g.:

```
public/packages/microweber-css-framework-bootstrap/css/mw-framework.css
public/packages/microweber-css-framework-bootstrap/css/mw-variables.css
```

## Key classes

- Provider: `MicroweberPackages\CssFrameworkBootstrap\Providers\CssFrameworkServiceProvider`

(No facade or service — this package is assets-only glue.)

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov

## License

MIT
