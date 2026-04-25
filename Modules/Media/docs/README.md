# `Media` module

> **Slug:** `media`
> **Tier:** 1
>
> Tier-1 module — owns its own data + exposes a public API.
>
> *(Auto-generated from filesystem survey on 2026-04-25;
> hand-edit to add operator-side context. The canonical
> shape lives in [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md);
> use `Modules/Settings/docs/README.md` as the
> hand-curated example.)*

## Domain

*Hand-edit this section to describe what the module does
operationally and which sibling modules it interacts
with.*

## Data model

Migrations under `Modules/Media/database/migrations/`:

  - `database/migrations/2020_00_00_000000_create_media_table.php`
  - `database/migrations/2020_00_00_000000_create_media_thumbnails_table.php`
  - `database/migrations/2026_03_21_000000_add_metadata_to_media_table.php`
  - `database/migrations/2026_03_22_000000_add_cdn_fields_to_media_table.php`
  - `database/migrations/2026_03_22_000000_create_media_folders_table.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\Media\Models\Media` | `Models/Media.php` |
| `Modules\Media\Models\MediaFolder` | `Models/MediaFolder.php` |
| `Modules\Media\Models\MediaThumbnail` | `Models/MediaThumbnail.php` |

## API endpoints

Route files:

  - `routes/api.php`

*Hand-edit to inline the (Method / Path / Auth / Scope /
Controller) table for each route group.*

## Controllers

  - `Modules\Media\Http\Controllers\Api\MediaApiController`

## Service classes

  - `Modules\Media\Services\BulkUploadService`
  - `Modules\Media\Services\CdnIntegrationService`
  - `Modules\Media\Services\ImageOptimizationService`

## Filament admin

  - `Modules\Media\Filament\Resources\MediaResource`
  - `Modules\Media\Filament\Resources\MediaResource\Pages\CreateMedia`
  - `Modules\Media\Filament\Resources\MediaResource\Pages\EditMedia`
  - `Modules\Media\Filament\Resources\MediaResource\Pages\ListMedia`

## Tests

Run: `php vendor/bin/phpunit Modules/Media/Tests`

Test files:

  - `Tests/Filament/MediaResourceTest.php`
  - `Tests/Unit/LegacyMediaTest.php`
  - `Tests/Unit/MediaTest.php`
  - `Tests/Unit/Models/MediaFolderTest.php`
  - `Tests/Unit/Models/MediaTest.php`
  - `Tests/Unit/Services/BulkUploadServiceTest.php`
  - `Tests/Unit/Services/CdnIntegrationServiceTest.php`
  - `Tests/Unit/Services/ImageOptimizationServiceTest.php`
  - `Tests/Unit/UnsplashTest.php`

## Service providers

  - `Modules\Media\Providers\ImageOptimizationServiceProvider`
  - `Modules\Media\Providers\MediaServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
