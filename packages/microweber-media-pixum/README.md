# Microweber Media Pixum

Standalone Laravel package for generating and serving placeholder (pixum) PNG images.

## Features

- **No `exit()` calls** — fully testable with PHPUnit, Dusk, and Livewire
- **Cached generation** — images are generated once and served from disk
- **Configurable** — background colour, max dimensions, cache path
- **Standalone** — works in any Laravel 10+ application
- **Route & helpers** — `pixum($width, $height)` helper and `/pixum_img` route

## Installation

```bash
composer require microweber-packages/microweber-media-pixum
```

The package auto-discovers its service provider. Publish the config:

```bash
php artisan vendor:publish --tag=media-pixum-config
```

## Usage

### Helper function

```php
// Returns URL to a 200×200 placeholder
$url = pixum(200, 200);

// Returns filesystem path
$path = pixum_path(300, 200);
```

### Facade

```php
use MicroweberPackages\MediaPixum\Facades\Pixum;

$path = Pixum::generate(400, 300);
$url  = Pixum::url(400, 300);
```

### Route

```
GET /pixum_img?width=200&height=200
```

Returns a PNG image with appropriate caching headers.

## Configuration

`config/media-pixum.php` (publish with `--tag=media-pixum-config`):

| Key | Default | Description |
|-----|---------|-------------|
| `cache_path` | `storage/app/public/pixum` | Where generated PNGs are cached |
| `default_width` / `default_height` | `200` | Fallback dimensions |
| `background_color` | `rgba(239,236,236,0)` | Placeholder fill (`r`,`g`,`b`,`a`) |
| `max_width` / `max_height` | `4000` | Safety cap against abusive sizes |

## Key classes

- `MicroweberPackages\MediaPixum\Facades\Pixum` — facade (`generate()`, `url()`, `getCachePath()`).
- `MicroweberPackages\MediaPixum\PixumGenerator` — generates/caches the placeholder image.
- `MicroweberPackages\MediaPixum\Http\Controllers\PixumController` — serves `GET /pixum_img`.
- `MicroweberPackages\MediaPixum\MediaPixumServiceProvider` — registration + route/config loading.

Global helpers (`src/helpers.php`, Composer `files` autoload): `pixum($width, $height = false)` → URL, `pixum_path($width, $height = false)` → filesystem path.

## Testing

```bash
composer test
```

## License

MIT

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov
