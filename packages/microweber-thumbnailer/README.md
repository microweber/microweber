# Thumbnailer

Standalone Laravel package for image thumbnail generation. Resizes/crops source images into a cached thumbnails directory and serves them by URL. Built on the bundled PHPImageMagician library, with WebP output when the host GD build supports it.

> Not to be confused with `microweber-media-thumbnail` (an HTTP controller that resolves media and delegates here). This package owns the low-level generation API.

## Features

- `ThumbnailGenerator` service (singleton) + `Thumbnailer` facade.
- Generate cached thumbnails with width, optional height and crop.
- Pixum placeholder image generation.
- Content-addressed cache ids (same src + dimensions + crop → same file, regenerated only when the source changes).
- Skips SVGs (returns the source untouched) and rejects unsupported extensions.
- Multisite-aware storage paths (defaults under the per-site `userfiles` tree when available).
- Global helper functions for use outside the container.

## Installation

```bash
composer require microweber-packages/microweber-thumbnailer
```

The `ThumbnailerServiceProvider` is auto-discovered. It merges config and binds `ThumbnailGenerator` as a singleton configured from `thumbnailer.thumbnails_path` / `thumbnailer.thumbnails_url`.

Publish the config:

```bash
php artisan vendor:publish --tag=thumbnailer-config
```

## Usage

### Facade

```php
use MicroweberPackages\Thumbnailer\Facades\Thumbnailer;

// Generate a 200px-wide thumbnail (height auto). Returns the absolute path, or null on failure.
$path = Thumbnailer::generate('/abs/path/to/image.jpg', 200);

// Fixed width + height with crop
$path = Thumbnailer::generate('/abs/path/to/image.jpg', 300, 200, true);

// Placeholder (pixum) image
$placeholder = Thumbnailer::pixum(150, 150);

// Stream an image to the browser with the right headers
Thumbnailer::outputImage($path);

// Introspection
Thumbnailer::getThumbnailsDir();   // configured thumbnails directory
Thumbnailer::isWebpSupported();    // bool — true when GD can emit WebP
```

### Dependency injection

```php
use MicroweberPackages\Thumbnailer\ThumbnailGenerator;

public function __construct(private ThumbnailGenerator $thumbs) {}

$path = $this->thumbs->generate($src, (int) $width, (int) $height, $crop);
```

### Global helpers

Autoloaded via `src/helpers.php`:

```php
// Generate a thumbnail
$path = thumbnailer_generate('/abs/path/image.jpg', 200, null, null);

// Generate a placeholder
$path = thumbnailer_pixum(150, 0);
```

### Method signatures

```php
ThumbnailGenerator::generate(string $srcPath, int $width = 200, ?int $height = null, bool|string|null $crop = null): ?string
ThumbnailGenerator::pixum(int $width = 150, int $height = 0): string
ThumbnailGenerator::outputImage(string $path): void
ThumbnailGenerator::getThumbnailsDir(): string
ThumbnailGenerator::isWebpSupported(): bool // static
```

Supported source extensions: `jpg`, `jpeg`, `gif`, `png`, `bmp`, `webp`. SVGs are returned as-is; anything else returns `null`.

## Configuration

`config/thumbnailer.php`:

| Key | Default | Env override |
|-----|---------|--------------|
| `thumbnails_path` | per-site `userfiles/.../thumbnails`, else `storage_path('app/public/thumbnails')` | `THUMBNAILER_PATH` |
| `thumbnails_url`  | per-site `userfiles` URL `/thumbnails`, else `/storage/thumbnails` | `THUMBNAILER_URL` |

On a multisite install the defaults resolve under the active site's `userfiles()` tree so each domain keeps its own thumbnail cache; standalone apps fall back to the plain storage path.

## Key classes

- `MicroweberPackages\Thumbnailer\Facades\Thumbnailer`
- `MicroweberPackages\Thumbnailer\ThumbnailGenerator`
- `MicroweberPackages\Thumbnailer\Support\Thumbnailer` (array-spec `createThumb()` wrapper)
- `MicroweberPackages\Thumbnailer\Support\ImageRotator`
- `MicroweberPackages\Thumbnailer\Libs\PHPImageMagician\ImageLib` (bundled engine)
- `MicroweberPackages\Thumbnailer\ThumbnailerServiceProvider`

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov

## License

MIT
