# Menu

Navigation menu builder. Create, edit, and render site menus with drag-and-drop ordering, nesting, mega menu support, and multilanguage translation.

## Key Features

- Multiple named menus (header, footer, sidebar, etc.)
- Hierarchical menu items with nesting
- Drag-and-drop reordering
- Mega menu column support
- Multilanguage translation via TranslateMenu provider
- Live-edit quick settings integration
- API endpoints for menu data
- Livewire-based admin menu list

## Key Classes

| Class | Purpose |
|---|---|
| `Repositories\MenuManager` | Menu operations (`app('menu_manager')`) |
| `Repositories\MenuRepository` | Query layer (`app('menu_repository')`) |
| `Models\Menu` | Menu container model |
| `Models\MenuItem` | Individual menu item |
| `Livewire\Admin\MenusList` | Admin menu listing component |

## Database Tables

- `menus` -- menu containers (with mega menu column)
- `menu_items` -- individual items with parent/position

## Admin Panel (Filament)

- **AdminMenusPage** -- menu management page
- **MenuModuleSettings** -- menu module configuration

## API Endpoints

Defined in `routes/api.php` for CRUD operations on menus and menu items.

## Usage

```html
<module type="menu" />
```

```php
$menuManager = app('menu_manager');
$menus = app('menu_repository');
```
