# microweber-media-thumbnail

Standalone Laravel package for media thumbnail caching and on-demand generation.

## Features

- **MediaThumbnail model** — Stores cached thumbnail metadata (filename → image options + UUID) in a `media_thumbnails` table.
- **Repository** — Thin caching layer over the model with find, store, remove, and prune operations.
- **Routes** — `pixum_img`, `thumbnail_img`, and `api/media-thumbnail/generate/{uuid}` endpoints for serving thumbnails.
- **Database-agnostic** — Works on SQLite, MySQL, and PostgreSQL.

## Installation

```bash
composer require microweber-packages/microweber-media-thumbnail
```

The package auto-discovers its service provider. Publish the config and migration:

```bash
php artisan vendor:publish --tag=media-thumbnail-config
php artisan vendor:publish --tag=media-thumbnail-migrations
php artisan migrate
```

## Dependencies

- `microweber-packages/microweber-thumbnailer` — the low-level image thumbnail generator (GD-based).

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

Use the `MediaThumbnail` facade or resolve the repository from the container — both proxy the same `MediaThumbnailRepository`:

```php
use MicroweberPackages\MediaThumbnail\Facades\MediaThumbnail;

MediaThumbnail::findByFilename('tn-my-image-123');
```

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

## Routes

| Method | Route | Name |
|--------|-------|------|
| GET | `thumbnail_img` | `media-thumbnail.thumbnail` |
| GET | `api/media-thumbnail/generate/{uuid}` | `media-thumbnail.generate` |

(The `pixum_img` placeholder route comes from the required `microweber-media-pixum` package.)

## Key classes

- `MicroweberPackages\MediaThumbnail\Facades\MediaThumbnail` — facade for the repository.
- `MicroweberPackages\MediaThumbnail\Repositories\MediaThumbnailRepository` — caching layer (`findByFilename()`, `findByUuid()`, `store()`, `removeByFilename()`, `pruneOlderThan()`).
- `MicroweberPackages\MediaThumbnail\Models\MediaThumbnail` — the `media_thumbnails` Eloquent model.
- `MicroweberPackages\MediaThumbnail\Http\Controllers\ThumbnailController` — serves `thumbnail_img` / `generateByUuid`.
- `MicroweberPackages\MediaThumbnail\MediaThumbnailServiceProvider` — registration + route/config/migration publishing.

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
