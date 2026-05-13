# MediaLibrary Module — Installation

The MediaLibrary module is a **core module** — ships with Microweber, registered automatically. It depends on the Media module for the data layer.

## Prerequisites

- PHP ≥ 8.2
- Laravel 11 base
- Filament v5 — the admin browser is a Filament Page
- Livewire v4 — drives the folder tree + detail panel state machine
- **Media module** (`Modules/Media/`) — provides the `media`, `media_folders`, `media_thumbnails` tables and the upload pipeline this module browses

## Registration

Standard module pipeline:

1. **`Modules/MediaLibrary/module.json`** declares the module + provider
2. **`Modules/MediaLibrary/Providers/MediaLibraryServiceProvider.php`** registers config, views, the Filament page, and the routes for the picker modal endpoints
3. **`composer.json`** PSR-4: `"Modules\\MediaLibrary\\": "Modules/MediaLibrary/"`

`composer dump-autoload` after a fresh clone is sufficient.

## Filament page registration

The `MediaLibrary` page registers itself with the admin panel automatically via Filament's auto-discovery. The URL slug is whatever the parent panel's config specifies (typically `/admin/media-library`).

To restrict access, override the `canAccess()` method on the page class or guard at the panel level with a permission policy.

## Unsplash integration (optional)

To enable the Unsplash search panel inside the library browser, set your Unsplash API key:

```env
UNSPLASH_ACCESS_KEY=your-api-key-here
```

The key is read by `Modules\MediaLibrary\Support\Unsplash` on first request. Without it, the Unsplash tab in the browser shows an "API key not configured" hint.

Apply for a key at https://unsplash.com/developers — the free tier is sufficient for a single-site install.

## What this module renders

The Filament page lives at `/admin/media-library` and renders three regions:

1. **Folder sidebar** — left rail with the folder tree, file counts per folder, create/rename/delete actions
2. **File grid / list** — main canvas, toggleable view mode, infinite-scroll pagination
3. **Detail panel** — right rail or modal that opens when a file is selected

Picker mode (embedded inside another form) hides the sidebar by default and switches to a compact single-column grid.

## Configuration

MediaLibrary has no module-specific config keys beyond `UNSPLASH_ACCESS_KEY`. Behavior is driven by the Media module's options:

- `default_image_path` — fallback image shown for broken/missing rows
- `pixum_color` — placeholder color
- `max_upload_mb` — per-file upload limit
- `allowed_extensions` — whitelist enforced on upload

See [`Media installation docs`](../media/installation.md) for the full list.

## Disabling / replacing

MediaLibrary can be disabled (operators lose the browser UI but the data layer keeps working — programmatic uploads and the REST API at `/api/media` still function). The Filament form-field picker that embeds MediaLibrary will fail; replace with a plain file-upload input or another picker module.

To customize the browser:

- Extend `Modules\MediaLibrary\Filament\Admin\Pages\MediaLibrary` and override the action methods you want to change
- Replace the page entirely by registering a different one with the same panel URL slug
- Hook into the standard Media events for cache invalidation when files are added/removed via this UI
