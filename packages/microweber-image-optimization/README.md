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
composer require microweber-packages/microweber-image-optimization
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

// Introspection / cache
$service->isWebpSupported();   // GD/Imagick WebP capability
$service->isWebpEnabled();     // config flag
$service->clearWebpCache();    // returns number of files removed
$service->getStatistics();     // cache file count / size, etc.
```

Or through the **ImageOptimization** facade (same `ImageOptimizationService` behind it):

```php
use MicroweberPackages\ImageOptimization\Facades\ImageOptimization;

$url = ImageOptimization::getOptimizedUrl('/images/photo.jpg', 800, 600);
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

### Artisan command

```bash
php artisan image-optimization:clear-cache   # purge all generated WebP files
```

### Filament admin

Register the plugin on your panel:

```php
use MicroweberPackages\ImageOptimization\Filament\ImageOptimizationPlugin;

$panel->plugin(ImageOptimizationPlugin::make());
```

## Configuration

`config/image-optimization.php` (publish with `--tag=image-optimization-config`):

| Key | Default | Env | Purpose |
|-----|---------|-----|---------|
| `webp_enabled` | `true` | `MW_WEBP_ENABLED` | Enable WebP conversion. |
| `webp_quality` | `85` | `MW_WEBP_QUALITY` | WebP encode quality. |
| `webp_cache` | `true` | `MW_WEBP_CACHE` | Cache generated WebP files. |
| `webp_cache_ttl` | `604800` | `MW_WEBP_CACHE_TTL` | Cache lifetime (seconds). |
| `auto_convert_uploads` | `false` | `MW_WEBP_AUTO_CONVERT` | Convert uploads to WebP automatically. |
| `lazy_loading_enabled` | `true` | `MW_LAZY_LOADING_ENABLED` | Emit lazy-loading markup. |
| `cache_path` | `cache/webp` | `IMAGE_OPTIMIZATION_CACHE_PATH` | Cache directory on the disk. |
| `disk` | `public` | `IMAGE_OPTIMIZATION_DISK` | Filesystem disk for cached images. |
| `driver` | auto | `IMAGE_OPTIMIZATION_DRIVER` | Intervention driver (`gd` / `imagick`). |
| `supported_formats` | `jpg,jpeg,png,gif,bmp,tiff` | — | Source formats eligible for optimization. |

## Key classes

- `MicroweberPackages\ImageOptimization\Services\ImageOptimizationService` — all conversion/HTML/cache logic.
- `MicroweberPackages\ImageOptimization\Facades\ImageOptimization` — facade → the service.
- `MicroweberPackages\ImageOptimization\ImageOptimizationServiceProvider` — routes, Blade directives, helpers, command.
- `MicroweberPackages\ImageOptimization\Console\Commands\ClearWebpCacheCommand` — `image-optimization:clear-cache`.
- `MicroweberPackages\ImageOptimization\Http\Controllers\ImageOptimizationController` — WebP/stats/convert endpoints.
- `MicroweberPackages\ImageOptimization\Filament\ImageOptimizationPlugin` / `...\Filament\Pages\ImageOptimizationSettings` — admin UI.

## Multi-database

The package is filesystem-oriented. Settings may optionally use Microweber `get_option` / `save_option` when available. Tests cover SQLite, MySQL, and PostgreSQL for package bootstrapping.

## License

MIT

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov
