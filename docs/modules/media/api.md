# Media Module — API Reference

## REST API

Base URL: `/api/media`

Routes registered in `Modules/Media/routes/api.php`. Write methods require Sanctum bearer with admin scope. Read methods are public for active rows.

### `GET /api/media` — list

| Param | Type | Default | Notes |
|---|---|---|---|
| `rel_type` | string | — | Filter by attached model class |
| `rel_id` | int | — | Filter by attached row id |
| `media_type` | string | — | `'image'` / `'video'` / `'file'` / `'audio'` |
| `media_folder_id` | int | — | Filter by folder; pass `0` for root |
| `limit` | int | `15` | Page size |
| `page` | int | `1` | Page number |
| `search` | string | — | Substring on filename + title |
| `order_by` | string | `position` | Column |
| `order` | string | `asc` | `asc` / `desc` |

Response:

```json
{
    "data": [
        {
            "id": 87,
            "rel_type": "content",
            "rel_id": 42,
            "filename": "/media/default/hero.jpg",
            "url": "https://cdn.yoursite.com/media/default/hero.jpg",
            "media_type": "image",
            "position": 0,
            "title": null,
            "description": null,
            "media_folder_id": null,
            "created_by": 1,
            "created_at": "2026-05-13T10:00:00Z",
            "updated_at": "2026-05-13T10:00:00Z"
        }
    ]
}
```

### `POST /api/media` — create / upload

Two modes:

**Mode A: upload a new file** — multipart with `file` field:

```bash
curl -X POST https://yoursite.com/api/media \
    -H "Authorization: Bearer $TOKEN" \
    -F "file=@hero.jpg" \
    -F "rel_type=content" \
    -F "rel_id=42" \
    -F "media_type=image"
```

**Mode B: attach an existing file by URL** — JSON body:

```json
{
    "filename": "/media/default/existing.jpg",
    "rel_type": "content",
    "rel_id": 42,
    "media_type": "image"
}
```

Validation rules (`MediaApiController::store`):

- `file` (mode A) — required when present, must pass mime + size validation against `allowed_extensions` + `max_upload_mb` options
- `filename` (mode B) — required when `file` is absent
- `rel_type` — string, max 255
- `rel_id` — integer
- `media_type` — string, one of `image`/`video`/`audio`/`file`
- `position` — optional integer

### `GET /api/media/{id}` — show

Returns one media row including `url`, `file_type`, `isOnCdn` flag. `404` if not found.

### `PUT /api/media/{id}` — update

All fields optional. Updating `filename` does NOT move the file on disk — it only updates the DB pointer. Use a separate migration script for actual file moves.

### `DELETE /api/media/{id}` — destroy

Soft-deletes the media row (or hard-deletes — depends on whether the table has soft-delete columns). Does NOT delete the file from disk by default; pair with `Storage::disk('userfiles')->delete($filename)` for full cleanup.

## Eloquent reference

### `Modules\Media\Models\Media`

Standalone Eloquent model (not extending Content).

#### Attributes

`id`, `rel_type`, `rel_id`, `filename`, `media_type`, `position`, `title`, `description`, `embed_code`, `created_by`, `media_folder_id`, `created_at`, `updated_at`.

#### Relations

- `folder()` — `belongsTo(MediaFolder::class, 'media_folder_id')`

#### Accessors

- `url` — full URL via `getUrlAttribute()`. CDN-prefixed if `MEDIA_CDN_URL` is set
- `file_type` — derived from filename extension (`'image'` / `'video'` / `'audio'` / `'document'`)

#### Methods

- `isOnCdn(): bool` — true if filename matches the CDN prefix

#### Scopes

- `inFolder(?int $folderId)` — `WHERE media_folder_id = ?` (use `null` for root)
- `onCdn()` — `WHERE filename LIKE '<cdn_prefix>%'`
- `byType(string $type)` — `WHERE media_type = ?`

### `Modules\Media\Models\MediaFolder`

Hierarchical folder tree.

Attributes: `id`, `parent_id`, `name`, `path`, `created_by`, `created_at`, `updated_at`.

### `Modules\Media\Models\MediaThumbnail`

Cached thumbnail variants.

Attributes: `id`, `media_id`, `width`, `height`, `filename`, `crop_mode`, `created_at`.

## Helpers

Defined in `Modules/Media/Support/media_functions.php`:

| Helper | Signature | Returns |
|---|---|---|
| `thumbnail($src, $width = 200, $height = null, $crop = null)` | url string | Resized thumbnail URL |
| `thumbnail_img($params)` | array | Full `<img>` tag (legacy) |
| `responsive_thumbnail($src, $width, $height, array $options)` | string | Full responsive `<img>` with srcset/sizes/lazy/decoding |
| `pixum($width, $height)` | string | Placeholder image URL (configured `pixum_color`) |
| `get_media($params)` | array | Query media (legacy) |
| `get_pictures($params)` | array | Image-typed media |

`responsive_thumbnail()` options:

```php
[
    'alt'             => 'string',          // required; falls back to filename basename
    'class'           => 'string',
    'sizes'           => '100vw',           // default
    'loading'         => 'lazy'|'eager',    // default: eager-first-N pattern
    'eager_first_n'   => 2,                 // default
    'decoding'        => 'async',           // default
    'crop'            => true|false|null,
    'srcset'          => [400, 800, 1600],  // default: [width, width * 2]
    'itemprop'        => 'string',
    'id'              => 'string',
    'style'           => 'string',
]
```

## Repository

`app('media_manager')` returns the `MediaManager` singleton.

Methods of note:

- `thumbnail($src, $width, $height, $crop)` — underlying image-processing routine
- `pixum($width, $height)` — placeholder generator
- `thumbnail_img($params)` — legacy wrapper

The manager handles GD vs Imagick selection, WebP output when supported, and writes to the thumbnail cache.

## Content-side methods (via MediaTrait)

When Page/Post/Product call these methods, they're provided by `Modules\Media\Traits\MediaTrait` which is applied to the `Content` parent:

- `media()` — `hasMany(Media::class, 'rel_id')->where('rel_type', $this->getMorphClass())`
- `thumbnail($width = 100, $height = 100, $crop = false)` — first media row's thumbnail URL
- `mediaUrl()` — first media row's filename (or `pixum()` fallback)

## Testing

```bash
./vendor/bin/phpunit --filter=MediaApiControllerTest
```

The Media tests live in `Modules/Media/Tests/`. Image-processing tests use small fixture files under `Modules/Media/Tests/fixtures/`.
