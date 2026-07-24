# Microweber Image Optimization

Standalone Laravel package for image optimization: WebP conversion, lazy loading HTML, responsive `srcset` generation, and WebP cache management.

Works in Microweber CMS and in any standalone Laravel application.

## Requirements

- PHP 8.1+
- Laravel 10 / 11 / 12
- GD with WebP support **or** Imagick
- `intervention/image` ^3.0

## Installation

```bash
composer require microweber-packages/image-optimization
```

The service provider is auto-discovered. Publish the config if needed:

```bash
php artisan vendor:publish --tag=image-optimization-config
```

## Usage

```php
use MicroweberPackages\ImageOptimization\Services\ImageOptimizationService;

$service = app(ImageOptimizationService::class);

// WebP conversion
$result = $service->convertToWebp('/path/to/photo.jpg', ['quality' => 85]);
$url = $service->getWebpOrOriginal('/path/to/photo.jpg');

// Optimized URL (optional resize + WebP)
$url = $service->getOptimizedUrl('/images/photo.jpg', 800, 600);

// Lazy / responsive HTML
echo $service->generateLazyImage('/images/photo.jpg', 'Alt text');
echo $service->generateResponsiveImage('/images/photo.jpg', [320 => 320, 640 => 640], 'Alt');
```

### Helper functions

```php
optimized_image_url($src, $width = null, $height = null, $allowWebp = true);
webp_image($src);
lazy_image($src, $alt = null, $attributes = []);
responsive_image($src, $sizes, $alt = null, $attributes = []);
webp_supported();
webp_enabled();
lazy_loading_enabled();
client_supports_webp();
clear_webp_cache();
image_optimization_stats();
```

### Blade directives

```blade
@optimizedImage($src, $width, $height)
@webpImage($src)
@lazyImage($src, $alt, $attributes)
@responsiveImage($src, $sizes, $alt, $attributes)
@webpPicture($src, $alt, $attributes)
@lazyCss
@lazyJs
```

### Routes

| Method | URI | Name |
|--------|-----|------|
| GET | `/image-optimization/webp` | `image-optimization.webp` |
| GET | `/image-optimization/stats` | `image-optimization.stats` |
| POST | `/image-optimization/clear-cache` | `image-optimization.clear-cache` |
| GET | `/api/image-optimization/convert` | `image-optimization.convert` |

### Filament admin

Register the plugin on your panel:

```php
use MicroweberPackages\ImageOptimization\Filament\ImageOptimizationPlugin;

$panel->plugin(ImageOptimizationPlugin::make());
```

## Multi-database

The package is filesystem-oriented. Settings may optionally use Microweber `get_option` / `save_option` when available. Tests cover SQLite, MySQL, and PostgreSQL for package bootstrapping.

## License

MIT
