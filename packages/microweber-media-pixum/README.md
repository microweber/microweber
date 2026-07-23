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
composer require microweber-packages/media-pixum
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

See `config/media-pixum.php` for all options.

## Testing

```bash
composer test
```

## License

MIT