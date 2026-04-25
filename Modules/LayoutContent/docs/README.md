# `LayoutContent` module

> **Slug:** `layout-content`
> **Tier:** 3
>
> Tier-3 module — admin tool / widget driven by a Filament page or resource.
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

Migrations under `Modules/LayoutContent/database/migrations/`:

  - `database/migrations/2025_02_07_163943_create_layout_content_items_table.php`
  - `database/migrations/2025_04_10_000000_add_position_to_layout_content_items_table.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\LayoutContent\Models\LayoutContentItem` | `Models/LayoutContentItem.php` |

## API endpoints

Route files:

  - `routes/api.php`
  - `routes/web.php`

*Hand-edit to inline the (Method / Path / Auth / Scope /
Controller) table for each route group.*

## Filament admin

  - `Modules\LayoutContent\Filament\LayoutContentModuleSettings`
  - `Modules\LayoutContent\Filament\LayoutContentTableList`

## Service providers

  - `Modules\LayoutContent\Providers\EventServiceProvider`
  - `Modules\LayoutContent\Providers\LayoutContentServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
