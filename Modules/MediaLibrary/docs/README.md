# `MediaLibrary` module

> **Slug:** `media-library`
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

  - `routes/web.php`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\MediaLibrary\Filament\Admin\Pages\MediaLibrary` | Website Settings | — |

## Tests

Run: `php vendor/bin/phpunit Modules/MediaLibrary/Tests`

### `Tests/Unit/Livewire/MediaLibraryTest.php`

  - `it_defaults_to_grid_view_mode`
  - `it_does_not_create_folder_with_whitespace_only_name`
  - `it_can_toggle_to_list_view`
  - `it_ignores_invalid_view_mode`
  - `it_can_clear_all_filters`
  - `it_can_delete_a_media_item`
  - `it_can_switch_back_to_library_tab`
  - `it_filters_media_by_folder_including_subfolders`
  - `it_warns_when_cdn_is_not_configured`

## Service providers

  - `Modules\MediaLibrary\Providers\MediaLibraryServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
