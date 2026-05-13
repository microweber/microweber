# Media Module

> **Slug:** `media`
> **Tier:** 1 (full data + REST API + admin resource + helper layer)
> **Source:** `Modules/Media/`

The Media module owns Microweber's file-upload, storage, and image-processing pipeline. Operators use it to upload product images / blog hero photos / page galleries; contributors touch it to integrate new storage adapters, add image-processing steps, or extend the polymorphic attachment to a custom content type.

## What this module does

- Owns the `media` table — polymorphic attachment between any model and a file URL
- Owns the `media_folders` table — hierarchical folder structure for the media library
- Owns the `media_thumbnails` table — cached thumbnail references
- Exposes a RESTful API at `/api/media` (full CRUD)
- Provides Microweber's canonical image helpers: `thumbnail()`, `thumbnail_img()`, `responsive_thumbnail()`, `pixum()` (placeholder)
- Generates resized thumbnails on demand with optional crop modes
- Supports multiple storage adapters via Laravel's filesystem drivers (local, S3, CDN-prefixed)
- Integrates with the MediaLibrary module for the Filament-side picker UI
- Powers the `media_url` accessor on Content models (featured image)
- WebP-aware thumbnail output when `_is_webp_supported()` returns true

## Domain

Media is the **asset layer** of Microweber's content domain. Where Content owns text + structure, Media owns the binary attachments — images for product cards, hero photos for blog posts, gallery files for portfolio pages, downloadable assets for marketing.

Cross-references:

- **Content module** — Page/Post/Product attach media via `rel_type='content', rel_id=$contentId`. The `Content::image` accessor returns the first attached media row's filename
- **MediaLibrary module** (`Modules/MediaLibrary/`) — Filament-side admin UI for browsing + selecting media (picker modal used by every form that needs a file)
- **Filepicker module** — front-end JS for drag-and-drop upload + selection from the library
- **Shop / Product module** — product card images, gallery thumbnails
- **Big2 template** — uses `responsive_thumbnail()` extensively in its product / blog / page hero markup

## Documentation map

| Page | Purpose |
|---|---|
| [`index.md`](./index.md) | This overview |
| [`installation.md`](./installation.md) | Schema, storage adapters, configuration |
| [`usage.md`](./usage.md) | Upload, attach to content, thumbnail helpers, folders, CDN |
| [`api.md`](./api.md) | REST + Media model + helper reference |
| [`examples.md`](./examples.md) | Bulk upload, gallery, custom thumbnail sizes, S3 migration |
| [`troubleshooting.md`](./troubleshooting.md) | Common upload + thumbnail issues |

## Quick start

```php
use Modules\Media\Models\Media;

// Attach an existing file to a content row
$media = Media::create([
    'rel_type'   => 'content',
    'rel_id'     => $post->id,
    'filename'   => '/media/default/post-hero.jpg',
    'media_type' => 'image',
    'position'   => 0,
]);

// Generate a thumbnail URL
$thumb = thumbnail('/media/default/post-hero.jpg', 400, 300);

// Render a responsive <img> with srcset/sizes/lazy/decoding
echo responsive_thumbnail('/media/default/post-hero.jpg', 800, 600, [
    'alt' => 'Post hero',
    'class' => 'w-100 h-auto',
]);
```

## Key files

- `Modules/Media/Models/Media.php` — Eloquent model + scopes
- `Modules/Media/Models/MediaFolder.php` — folder hierarchy
- `Modules/Media/Models/MediaThumbnail.php` — thumbnail cache
- `Modules/Media/Repositories/MediaManager.php` — image processing (resize, thumbnail, WebP)
- `Modules/Media/Repositories/MediaRepository.php` — query layer
- `Modules/Media/Support/media_functions.php` — global helpers (`thumbnail`, `responsive_thumbnail`, `pixum`, `get_media`, `get_pictures`)
- `Modules/Media/Http/Controllers/Api/MediaApiController.php` — REST CRUD
- `Modules/Media/Traits/MediaTrait.php` — gives Content models `media()`, `thumbnail()`, `mediaUrl()`
- `Modules/Media/database/migrations/` — schema

## Status

Production-stable and a hard dependency of every content type that displays imagery. Recent changes (AI-265 first slice + AI-265b LQIP) added `responsive_thumbnail()` helper plus the `.mw-product-card-image-placeholder` blur-up effect — see `Modules/Media/Support/media_functions.php:209+` for the helper source.
