# Microweber Minifier

Standalone Laravel package for **JavaScript** and **CSS** minification. Extracted from Microweber CMS so it can be reused in any Laravel application.

- JS engine: fixed JShrink-based `JsMinifier` (no infinite-loop on EOF, proper string/regex handling)
- CSS engine: lightweight comment/whitespace minifier (`CssMinifier`)

## Requirements

- PHP 8.1+
- Laravel 10 / 11 / 12

## Installation

```bash
composer require microweber-packages/microweber-minifier
```

The service provider is auto-discovered. Publish the config if needed:

```bash
php artisan vendor:publish --tag=minifier-config
```

## Usage

```php
use MicroweberPackages\Minifier\Services\JsMinify;
use MicroweberPackages\Minifier\Services\CssMinify;
use MicroweberPackages\Minifier\Facades\Minifier;

$js = app(JsMinify::class)->minify("function a() { return 1; }");
$css = app(CssMinify::class)->minify(".a { color: red; }");

// Or via facade / helpers
$js = Minifier::minifyJs($source);
$css = Minifier::minifyCss($source);
$js = js_minify($source);
$css = css_minify($source);
```

### Helper functions

```php
js_minify(string $js, array $options = []): string;
css_minify(string $css, array $options = []): string;
minify_js(string $js, array $options = []): string;  // alias
minify_css(string $css, array $options = []): string; // alias
minifier_stats(): array;
minifier_enabled(): bool;
```

### HTTP routes

| Method | Route | Name |
|--------|-------|------|
| GET | `/minifier/stats` | `minifier.stats` |
| GET | `/minifier/self-test` | `minifier.self-test` |
| GET | `/api/minifier/ping` | `minifier.ping` |
| POST | `/minifier/js` | `minifier.js` |
| POST | `/minifier/css` | `minifier.css` |

### Filament

```php
use MicroweberPackages\Minifier\Filament\MinifierPlugin;

$panel->plugin(MinifierPlugin::make());
```

## Configuration

`config/minifier.php` (publish with `--tag=minifier-config`):

| Key | Default | Description |
|-----|---------|-------------|
| `enabled` | `false` | Master on/off (`MW_MINIFIER_ENABLED`) |
| `minify_js` / `minify_css` | `true` | Per-type toggles |
| `js.flaggedComments` | `false` | Keep `/*! … */` flagged comments |
| `css.remove_comments` | `true` | Strip CSS comments |
| `css.shorten_zeros` | `true` | Collapse `0px` → `0` |
| `middleware` | `['admin']` | Middleware for the HTTP routes |

## Key classes

- `MicroweberPackages\Minifier\Facades\Minifier` — facade (`minifyJs()`, `minifyCss()`, `isEnabled()`, `getStatistics()`, `selfTest()`).
- `MicroweberPackages\Minifier\Services\MinifierService` — orchestrator used by the facade/helpers.
- `MicroweberPackages\Minifier\Services\JsMinify` / `CssMinify` — per-type services (`minify()`, `minifyOrFail()`).
- `MicroweberPackages\Minifier\Minifiers\JsMinifier` / `CssMinifier` — the low-level engines.
- `MicroweberPackages\Minifier\MinifierServiceProvider` — registration + route/config loading.
- `MicroweberPackages\Minifier\Filament\MinifierPlugin` / `Filament\Pages\MinifierSettings` — admin settings page.

## CMS integration

`AssetOptimizationService` in Microweber uses `js_minify()` / `css_minify()` from this package.

## License

MIT (package). JavaScript minifier derived from [JShrink](https://github.com/tedious/JShrink) (BSD-3-Clause). See `LICENSE-JSHRINK`.

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov
