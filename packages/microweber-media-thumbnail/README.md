# microweber-media-thumbnail

Standalone Laravel package for media thumbnail caching and on-demand generation.

## Features

- **MediaThumbnail model** — Stores cached thumbnail metadata (filename → image options + UUID) in a `media_thumbnails` table.
- **Repository** — Thin caching layer over the model with find, store, remove, and prune operations.
- **Routes** — `pixum_img`, `thumbnail_img`, and `api/media-thumbnail/generate/{uuid}` endpoints for serving thumbnails.
- **Database-agnostic** — Works on SQLite, MySQL, and PostgreSQL.

## Installation

```bash
composer require microweber-packages/media-thumbnail
```

The package auto-discovers its service provider. Publish the config and migration:

```bash
php artisan vendor:publish --tag=media-thumbnail-config
php artisan vendor:publish --tag=media-thumbnail-migrations
php artisan migrate
```

## Dependencies

- `microweber-packages/thumbnailer` — the low-level image thumbnail generator (GD-based).

## Configuration

```php
// config/media-thumbnail.php
return [
    'thumbnails_path' => env('MEDIA_THUMBNAIL_PATH', storage_path('app/public/thumbnails')),
    'thumbnails_url'  => env('MEDIA_THUMBNAIL_URL', '/storage/thumbnails'),
    'table'           => 'media_thumbnails',
];
```

## Usage

```php
use MicroweberPackages\MediaThumbnail\Repositories\MediaThumbnailRepository;

$repo = app(MediaThumbnailRepository::class);

// Store a new thumbnail cache entry
$model = $repo->store('tn-my-image-123', [
    'src'    => '/images/photo.jpg',
    'width'  => 300,
    'height' => 200,
]);

// Look up by filename (cache key)
$result = $repo->findByFilename('tn-my-image-123');

// Look up by UUID (for route-based generation)
$model = $repo->findByUuid($uuid);

// Remove cached entries
$repo->removeByFilename('tn-my-image-123');

// Prune old entries
$repo->pruneOlderThan(now()->subDays(90));
```

## Testing

```bash
composer test
```

## License

MIT