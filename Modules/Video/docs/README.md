# `Video` module

> **Slug:** `video`
> **Tier:** 3
>
> *Auto-generated from filesystem survey on 2026-04-25 with
> column / route / method extraction. Domain section is
> the only hand-edit needed; the rest of this file is
> regenerable from source.*

## Domain

*Hand-edit this section: describe what the module does
operationally, who consumes it, and which sibling modules
it interacts with.*

## Data model

This module owns no migrations of its own.

## API endpoints

Route files exist but no parseable `Route::method` calls were
found:

  - `routes/api.php`
  - `routes/web.php`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Video\Filament\VideoModuleSettings` | — | Video |

## Tests

Run: `php vendor/bin/phpunit Modules/Video/Tests`

### `Tests/Unit/VideoEmbedIntegrationTest.php`

  - `it_vimeo_embed_rendering`
  - `it_uploaded_video_rendering`

### `Tests/Unit/VideoEmbedModernTest.php`

  - `vimeo_embed_rendering`
  - `uploaded_video_rendering`

### `Tests/Unit/VideoEmbedTest.php`

  - `it_vimeo_embed`
  - `it_embed_options`

## Service providers

  - `Modules\Video\Providers\EventServiceProvider`
  - `Modules\Video\Providers\VideoServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
