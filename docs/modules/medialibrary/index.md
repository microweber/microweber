# MediaLibrary Module

> **Slug:** `medialibrary`
> **Tier:** 1 (admin Filament page + helper layer)
> **Source:** `Modules/MediaLibrary/`

The MediaLibrary module is Microweber's **admin-side media browser** — the Filament page operators use to manage uploaded files, organize them into folders, search the library, and (optionally) pull images from Unsplash. Where the Media module owns the data layer (the `media` table + thumbnail engine), MediaLibrary owns the **operator UX** that sits on top.

## What this module does

- Provides a full-page Filament admin browser at `/admin/media-library` (or the panel's configured slug)
- Folder navigation with breadcrumbs, drag-and-drop file organization, and inline rename/delete
- Grid and list view modes (toggleable)
- Full-text search across filename + title + description
- Bulk select + bulk move/delete
- Inline detail panel for editing title / description / alt text without leaving the browser
- Unsplash integration — search + download royalty-free images directly into the library
- Acts as the **picker modal** for every Filament form field that needs a file (forms call this module via `<x-media-library-picker>` blade includes)

## Domain

MediaLibrary is the **admin-side companion** to the Media module — operator UI for the data layer. Where Media owns:

- The `media`, `media_folders`, `media_thumbnails` tables
- The `thumbnail()`, `responsive_thumbnail()`, `pixum()` helpers
- The REST API at `/api/media`
- Programmatic upload via `Media::create()` and the `MediaTrait`

MediaLibrary owns:

- The Filament admin page (`Modules\MediaLibrary\Filament\Admin\Pages\MediaLibrary`)
- The folder-tree Livewire state machine
- The bulk-operation actions (move, delete, attach to content)
- The Unsplash integration (search API + download-to-library)
- The picker modal embedded by other Filament resources

Cross-references:

- **Media module** (`Modules/Media/`) — the data layer. See [`docs/modules/media/`](../media/) for upload, thumbnail, helper docs.
- **Filepicker module** (`Modules/Filepicker/`) — front-end JS for drag-and-drop upload + drop-zone widgets.
- **Content module** — Page/Post/Product use the picker via Filament form fields to attach media.

## Documentation map

| Page | Purpose |
|---|---|
| [`index.md`](./index.md) | This overview |
| [`installation.md`](./installation.md) | Registration, configuration, Unsplash setup |
| [`usage.md`](./usage.md) | Browsing, folders, bulk operations, Unsplash, picker integration |
| [`api.md`](./api.md) | Livewire actions on the `MediaLibrary` page + Unsplash service |
| [`examples.md`](./examples.md) | End-to-end recipes |
| [`troubleshooting.md`](./troubleshooting.md) | Common operator + integration issues |

## Quick start

Open `/admin/media-library` (URL slug may vary by panel config). Drag files in to upload; use the folder tree on the left to organize. Click a file to open the detail panel; click the × in the panel to close.

For programmatic interaction, use the Media module's data layer:

```php
use Modules\Media\Models\Media;

// Create a media row pointing at an existing file
Media::create([
    'rel_type'        => 'content',
    'rel_id'          => $contentId,
    'filename'        => '/userfiles/uploads/2026/05/photo.jpg',
    'media_type'      => 'image',
    'media_folder_id' => $folderId,
]);

// MediaLibrary's UI will show this row on the next page load.
```

## Key files

- `Modules/MediaLibrary/Filament/Admin/Pages/MediaLibrary.php` — Filament page class (state machine + actions)
- `Modules/MediaLibrary/resources/views/filament/admin/pages/media-library-page.blade.php` — the page template
- `Modules/MediaLibrary/resources/views/filament/admin/partials/folder-item.blade.php` — folder tree node
- `Modules/MediaLibrary/Support/Unsplash.php` — Unsplash API client
- `Modules/MediaLibrary/Providers/MediaLibraryServiceProvider.php` — module bootstrap

## Status

Production-stable. The module is a thin admin layer over the Media data module — most logic (upload validation, thumbnail generation, CDN handling) lives in `Modules/Media/`. Changes here are usually UX tweaks; data-layer bugs belong against the Media module.
