# `FileManager` module

> **Slug:** `file-manager`
> **Tier:** 2
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

### `routes/web.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `POST` | `/plupload` | `PluploadController::upload` |

## Controllers

### `Modules\FileManager\Http\Controllers\Api\FileManagerApiController`

Source: `Http/Controllers/Api/FileManagerApiController.php`.

  - `list(Request $request)`
  - `paginateArray($items, $perPage = 50, $page = null, $options = [])`
  - `rename(Request $request)`
  - `delete(Request $request)`
  - `createFolder(Request $request)`

### `Modules\FileManager\Http\Controllers\Exceptions\UploadException`

Source: `Http/Controllers/Exceptions/UploadException.php`.

### `Modules\FileManager\Http\Controllers\PluploadController`

Source: `Http/Controllers/PluploadController.php`.

  - `getUploadPath()`
  - `upload()`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\FileManager\Filament\Pages\FileManagerPageAdmin` | Website Settings | — |

## Tests

Run: `php vendor/bin/phpunit Modules/FileManager/Tests`

## Service providers

  - `Modules\FileManager\Providers\FileManagerServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
