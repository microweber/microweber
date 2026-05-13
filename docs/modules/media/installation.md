# Media Module — Installation & Configuration

The Media module is a **core module** — ships with Microweber, registered automatically, hard dependency of every content type that displays imagery.

## Prerequisites

- PHP ≥ 8.2 with the GD or Imagick extension installed (for image resizing)
- Laravel 11 base
- Filament v5 — for the admin resource
- Sufficient disk space under `public/media/` (or your configured `userfiles` disk)
- `intervention/image` (or the project's equivalent) — pulled in via `composer.json`

## Registration

Standard module pipeline:

1. **`Modules/Media/module.json`** declares the module + provider
2. **`Modules/Media/Providers/MediaServiceProvider.php`** registers config, views, migrations, API routes, the Filament resource, the singleton `app('media_manager')`, and includes `Support/media_functions.php` as a global helpers file
3. **`composer.json`** PSR-4: `"Modules\\Media\\": "Modules/Media/"`

## Database schema

### `media` table

| Column | Type | Notes |
|---|---|---|
| `id` | bigint primary | |
| `rel_type` | varchar | `'content'`, `'user'`, or any other model class string |
| `rel_id` | bigint | FK → the related model's id |
| `filename` | varchar | URL path relative to the userfiles root (e.g. `/media/default/hero.jpg`) |
| `media_type` | varchar | `'image'`, `'video'`, `'file'`, `'audio'` |
| `position` | int | Sort order within the same rel_id |
| `title`, `description` | text | Optional metadata |
| `embed_code` | text | For embedded video / iframe content |
| `created_by` | bigint | FK → users.id |
| `media_folder_id` | bigint | FK → media_folders.id (nullable) |
| `created_at`, `updated_at` | timestamp | |

### `media_folders` table

Hierarchical folder structure for the media library:

| Column | Type | Notes |
|---|---|---|
| `id` | bigint primary | |
| `parent_id` | bigint | FK → media_folders.id |
| `name` | varchar | Folder display name |
| `path` | varchar | Materialized path for fast tree lookups |
| `created_by` | bigint | FK → users.id |
| `created_at`, `updated_at` | timestamp | |

### `media_thumbnails` table

Cache of generated thumbnail variants:

| Column | Type | Notes |
|---|---|---|
| `id` | bigint primary | |
| `media_id` | bigint | FK → media.id |
| `width`, `height` | int | Thumbnail dimensions |
| `filename` | varchar | Path to the cached thumbnail file |
| `crop_mode` | varchar | `'crop'`, `'resize'`, or null |
| `created_at` | timestamp | |

## Storage adapters

Media routes upload writes through Laravel's filesystem facade. The default disk is configured in `config/filesystems.php`:

```php
'disks' => [
    'userfiles' => [
        'driver' => 'local',
        'root'   => public_path('userfiles'),
        'url'    => env('APP_URL') . '/userfiles',
        'visibility' => 'public',
    ],
],
```

For S3 / CloudFront / etc., switch the driver:

```php
'userfiles' => [
    'driver' => 's3',
    'key'    => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),
    'region' => env('AWS_DEFAULT_REGION'),
    'bucket' => env('AWS_BUCKET'),
    'url'    => env('AWS_URL'),
    'visibility' => 'public',
],
```

Existing media rows reference `filename` as a URL-relative path — switching disks may require a one-time path-rewrite migration if your old URLs were absolute.

## CDN configuration

Set `MEDIA_CDN_URL` in `.env` to prefix every generated thumbnail URL:

```env
MEDIA_CDN_URL=https://cdn.yoursite.com
```

The `Media::isOnCdn()` scope filters rows whose filename matches the CDN prefix. Useful for bulk migration scripts that move files to CDN over time.

## Image processing settings

Default thumbnail max width is 1920px (configurable via the `media.max_thumbnail_width` config key). Larger requests are clamped to prevent runaway resizes.

WebP output is automatic when `_is_webp_supported()` returns true (PHP GD/Imagick built with WebP support). The original format is preserved as a fallback.

## What `microweber:install` does

- Creates the `media`, `media_folders`, `media_thumbnails` tables
- Creates a root media folder
- Copies any template-bundled default images from `Templates/<active>/mw_default_content.zip` to `public/media/default/` via the Restore manager

## Configuration options

Read via `get_option('option_key', 'media')`:

| Option | Default | Purpose |
|---|---|---|
| `default_image_path` | `/media/default/no-image.png` | Fallback when no media attached |
| `pixum_color` | `#e5e7eb` | Background color for `pixum()` placeholder SVG |
| `max_upload_mb` | `50` | Per-file upload limit (PHP `upload_max_filesize` also caps) |
| `allowed_extensions` | `jpg,jpeg,png,gif,webp,svg,pdf,mp4,mp3` | Whitelist enforced on upload |

## Disabling / replacing

Media cannot be disabled — Page/Post/Product all call `$content->image` and `$content->media()`. To customize:

- Swap the storage adapter via filesystem config (no code change)
- Replace the thumbnail engine by binding a custom `media_manager` singleton in `AppServiceProvider`
- Hook into the standard Eloquent events (`Media::saved`, `Media::deleted`) for CDN sync
