# Menu Module

> **Slug:** `menu`
> **Tier:** 1 (full data + REST API + admin resource + helper layer)
> **Source:** `Modules/Menu/`

The Menu module is Microweber's **navigation builder**. Operators use it to create header / footer / sidebar menus with drag-and-drop ordering, nested children, and mega-menu columns; contributors touch it to integrate custom menu-item types, hook into menu-render events, or extend the multilanguage layer.

## What this module does

- Owns the `menus` table (containers — header, footer, sidebar, mega-menu definitions)
- Owns the `menu_items` table (the individual entries: text, URL, internal content link, separator, mega-menu column header)
- Provides Filament admin: `AdminMenusPage` + a `MenusList` Livewire component
- Exposes a RESTful API at `/api/menus` (full CRUD)
- Provides `app('menu_manager')` (writes) and `app('menu_repository')` (reads) as service-locator singletons
- Renders menus via `<module type="menu" id="X" />` in `content_body` or `<?php echo menu_render(['id' => X]); ?>` in templates
- Integrates with the Categories + Content modules — menu items can point at a page, category, or arbitrary URL
- Supports per-locale translation via `TranslateMenu` provider (`menus_translations` + `menu_items_translations` tables)
- Lives next to the LiveEdit canvas — operators can rearrange a menu in-place via the Live Edit quick-settings panel

## Domain

Menu sits at the **navigation layer** of Microweber's content domain. Where Page is "URL," Post is "article," Category is "bucket," Menu is "the navigation tree you compose from those." A menu item references either:

- An internal content row (page, category, post, product) — `content_id` set
- An external / freeform URL — `url` set
- A textual separator / heading inside a mega-menu column — no link

Cross-references:

- **Content module** — `MenuItem::content()` `belongsTo` Content via `content_id`
- **Categories module** — `MenuItem::category()` `belongsTo` Category via `category_id`
- **LiveEdit module** — the canvas renders `<module type="menu" />` tags; the Quick Edit panel offers per-menu item-add/remove/reorder
- **Big2 / Bootstrap templates** — both ship multiple named menus (`name = 'header'`, `name = 'footer'`, etc.) that templates render via `<module type="menu" name="header" />`

## Documentation map

| Page | Purpose |
|---|---|
| [`index.md`](./index.md) | This overview |
| [`installation.md`](./installation.md) | Schema, registration, multilanguage setup |
| [`usage.md`](./usage.md) | Build menus, add items, hierarchy, mega menus, render in templates, translations |
| [`api.md`](./api.md) | REST + Menu/MenuItem Eloquent reference, helpers, repository |
| [`examples.md`](./examples.md) | End-to-end recipes |
| [`troubleshooting.md`](./troubleshooting.md) | Common issues |

## Quick start

```php
use Modules\Menu\Models\Menu;
use Modules\Menu\Models\MenuItem;

// Container
$header = Menu::create(['name' => 'header', 'title' => 'Header']);

// Items
MenuItem::create([
    'parent_id'  => $header->id,
    'title'      => 'Home',
    'url'        => '/',
    'position'   => 1,
]);

MenuItem::create([
    'parent_id'  => $header->id,
    'title'      => 'About',
    'content_id' => $aboutPageId,  // internal link
    'position'   => 2,
]);

// Render in a template
echo menu_render(['name' => 'header']);
```

## Key files

- `Modules/Menu/Models/Menu.php` — container model
- `Modules/Menu/Models/MenuItem.php` — item model (extends Menu via STI; same `menus` table NOT — actually a separate `menu_items` table, see `installation.md`)
- `Modules/Menu/Repositories/MenuManager.php` — write operations (`app('menu_manager')`)
- `Modules/Menu/Repositories/MenuRepository.php` — read operations (`app('menu_repository')`)
- `Modules/Menu/Http/Controllers/Api/MenusApiController.php` — REST CRUD (5 methods)
- `Modules/Menu/Filament/Admin/Pages/AdminMenusPage.php` — admin builder UI
- `Modules/Menu/Livewire/Admin/MenusList.php` — drag-and-drop menu list
- `Modules/Menu/TranslateTables/TranslateMenu.php` — multilanguage layer
- `Modules/Menu/database/migrations/` — `menus` + `menu_items` schema

## Status

Production-stable. The schema has been stable for many releases. Most menu-related bugs trace to template rendering (does the active template's `header.blade.php` actually call `<module type="menu" />`?) rather than the module itself.
