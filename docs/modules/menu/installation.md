# Menu Module — Installation

The Menu module is a **core module** — ships with Microweber, registered automatically.

## Prerequisites

- PHP ≥ 8.2
- Laravel 11 base
- Filament v5 — admin builder UI
- Livewire v4 — drag-and-drop reorder + form state
- Content + Categories modules — for the internal-link target types

## Registration

Standard module pipeline:

1. **`Modules/Menu/module.json`** declares the module + provider
2. **`Modules/Menu/Providers/MenuServiceProvider.php`** registers config, views, migrations, API routes, the Filament Admin page, the `menu_manager` + `menu_repository` singletons, and the Livewire components
3. **`composer.json`** PSR-4: `"Modules\\Menu\\": "Modules/Menu/"`
4. **TranslateTables/TranslateMenu** registers translation observers for the `menus` + `menu_items` tables when multilanguage is enabled

## Database schema

### `menus` table

The container for a named navigation group.

| Column | Type | Notes |
|---|---|---|
| `id` | bigint primary | |
| `name` | varchar | Identifier used by `<module name="X" />` — typically `'header'`, `'footer'`, `'sidebar'`, `'mega-menu'` |
| `title` | varchar | Display name in the admin |
| `mega_menu_columns` | int | `0` for non-mega; `1..6` for column count |
| `is_active` | tinyint | `0` hides from rendering |
| `position` | int | Sort order in the admin list |
| `created_at`, `updated_at` | timestamp | |

### `menu_items` table

The individual entries within a menu.

| Column | Type | Notes |
|---|---|---|
| `id` | bigint primary | |
| `parent_id` | bigint | FK → `menus.id` (top-level item) OR `menu_items.id` (nested child) |
| `title` | varchar | Display text |
| `url` | varchar | Freeform URL — null when `content_id` / `category_id` is set |
| `content_id` | bigint | FK → `content.id` (internal page / post / product link) |
| `category_id` | bigint | FK → `categories.id` (internal category link) |
| `target` | varchar | `'_self'` / `'_blank'` (open in new tab) |
| `position` | int | Sort order among siblings |
| `column` | int | For mega-menu items, the column number this item lives in |
| `is_active` | tinyint | `0` hides the item |
| `icon` | varchar | Optional Heroicon class / Bootstrap-Icons class |
| `css_class` | varchar | Extra CSS classes on the rendered `<a>` |
| `created_at`, `updated_at` | timestamp | |

### `menus_translations` + `menu_items_translations`

Per-locale title overrides (managed by `TranslateMenu` when multilanguage is on).

## What `microweber:install` does

Creates the schema. Templates that ship `mw_default_content.zip` (Big2, Bootstrap) restore their menus via the Restore manager — typically a `header` menu with Home / About / Blog / Shop / Contact and a `footer` menu with Privacy / Terms / Contact.

## Configuration

Menu has no module-specific config keys. Behavior is driven by the active template's CSS for `.menu-item`, `.menu`, `.mega-menu` (or whatever class names the template chose).

## Multilanguage setup

If the project uses multiple locales:

1. Set the available locales in `config/app.php` `available_locales`
2. The Menu module's `TranslateMenu` provider auto-creates `menus_translations` + `menu_items_translations` rows on every save
3. Read with the standard `setTranslation($field, $locale, $value)` / `getTranslation($field, $locale)` methods on the Menu / MenuItem models

## Disabling / replacing

Menu cannot be safely disabled — every template's `header.blade.php` and `footer.blade.php` calls the menu helper. To customize:

- Extend `Menu` / `MenuItem` and bind subclasses into the container
- Override `AdminMenusPage` for custom admin UX
- Replace the rendering by binding a custom `menu_repository` singleton

## Caching

Menu queries are cached for the request lifecycle by `MenuRepository`. To flush after a write:

```php
\Cache::tags(['menu', 'navigation'])->flush();
```

`MenuManager` does this automatically on every `save()` / `delete()` — manual flushes are only needed when bypassing the manager (direct `\DB::table('menus')->update(...)`).
