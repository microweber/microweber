# `Marketplace` module

> **Slug:** `marketplace`
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

## Models

### `Modules\Marketplace\Models\MarketplaceItem`

Source: `Models/MarketplaceItem.php`. 

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Marketplace\Filament\Admin\ListLicenses` | — | — |
  | `Modules\Marketplace\Filament\Admin\MarketplaceResource` | Marketplace | Marketplace |
  | `Modules\Marketplace\Filament\Admin\MarketplaceResource\Pages\ListMarketplaces` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Marketplace/Tests`

### `Tests/Filament/MarketplaceResourceTest.php`

  - `it_resource_has_correct_model`

### `Tests/Unit/Filament/MarketplaceResourceTest.php`

  - `it_index_page_shows_all_records`
  - `it_table_displays_as_grid`
  - `it_has_update_action_for_installed_modules_with_updates`
  - `it_has_refresh_cache_action`
  - `it_has_bulk_update_action`
  - `it_has_type_filter`
  - `it_has_pricing_filter`
  - `it_has_templates_tab`
  - `it_has_installed_tab`
  - `it_has_reload_packages_header_action`
  - …7 more.

## Service providers

  - `Modules\Marketplace\Providers\MarketplaceServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
