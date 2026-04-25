# `Menu` module

> **Slug:** `menu`
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

Migrations under `Modules/Menu/database/migrations/`:

  - `database/migrations/2022_07_04_130209_create_menus_table.php`
  - `database/migrations/2024_07_04_130209_add_mega_menu_column_to_menus_table.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\Menu\Models\Menu` | `Models/Menu.php` |
| `Modules\Menu\Models\MenuItem` | `Models/MenuItem.php` |

## API endpoints

Route files:

  - `routes/api.php`

*Hand-edit to inline the (Method / Path / Auth / Scope /
Controller) table for each route group.*

## Controllers

  - `Modules\Menu\Http\Controllers\Api\MenusApiController`

## Filament admin

  - `Modules\Menu\Filament\Admin\Pages\AdminMenusPage`
  - `Modules\Menu\Filament\MenuModuleSettings`

## Tests

Run: `php vendor/bin/phpunit Modules/Menu/Tests`

Test files:

  - `Tests/Filament/MenuPageTest.php`
  - `Tests/Unit/MenuContentModelTest.php`
  - `Tests/Unit/MenuManagerTest.php`

## Service providers

  - `Modules\Menu\Providers\MenuServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
