# Menu Module — API Reference

## REST API

Base URL: `/api/menus`

Routes registered in `Modules/Menu/routes/api.php`. Write methods require Sanctum bearer with admin scope.

### `GET /api/menus` — list

| Param | Type | Default | Notes |
|---|---|---|---|
| `name` | string | — | Filter by menu name (e.g. `'header'`) |
| `is_active` | int | `1` | Pass `0` to include inactive |
| `with` | string | — | Comma-separated relations to eager-load: `items`, `items.children` |
| `limit`, `page`, `order_by`, `order` | — | — | Standard pagination + sort |

Response:

```json
{
    "data": [
        {
            "id": 7,
            "name": "header",
            "title": "Header Navigation",
            "is_active": 1,
            "position": 1,
            "mega_menu_columns": 0,
            "items": [
                {"id": 12, "title": "Home", "url": "/", "position": 1, "children": []},
                {"id": 13, "title": "About", "content_id": 5, "position": 2, "children": []}
            ]
        }
    ]
}
```

### `POST /api/menus` — create

```json
{
    "name": "footer",
    "title": "Footer Menu",
    "is_active": 1,
    "position": 2,
    "mega_menu_columns": 0
}
```

Validation:

- `name` required, string, max 64
- `title` required, string, max 255
- `is_active`, `position`, `mega_menu_columns` optional

### `GET /api/menus/{id}` — show

Returns one menu with nested items.

### `PUT /api/menus/{id}` — update

All fields optional.

### `DELETE /api/menus/{id}` — destroy

Hard-deletes the menu AND all its items.

### Menu items

Item endpoints live under `/api/menus/{menuId}/items` (or similar — see `Modules/Menu/routes/api.php` for the registered route shape). The pattern follows the Menus controller: index / store / show / update / destroy with the same Sanctum gating.

## Eloquent reference

### `Modules\Menu\Models\Menu`

Container model.

#### Attributes

`id`, `name`, `title`, `is_active`, `position`, `mega_menu_columns`, `created_at`, `updated_at`.

#### Relations

- `items()` — `hasMany(MenuItem::class, 'parent_id')`
- `content()` — `belongsTo(Content::class, 'content_id')` (when the menu has a fallback content target — rare)
- `category()` — `belongsTo(Category::class, 'category_id')`

#### Accessors

- `getDisplayTitleAttribute()` — title with locale translation applied

### `Modules\Menu\Models\MenuItem`

Item model. Extends `Menu` via PHP class inheritance for shared method reuse, but uses the separate `menu_items` table (NOT single-table inheritance like Content does).

#### Attributes

`id`, `parent_id`, `title`, `url`, `content_id`, `category_id`, `target`, `position`, `column`, `is_active`, `icon`, `css_class`, `created_at`, `updated_at`.

#### Relations

- `content()` — `belongsTo(\Modules\Content\Models\Content::class, 'content_id')`
- `category()` — `belongsTo(\Modules\Category\Models\Category::class, 'category_id')`
- `parent()` — `belongsTo(MenuItem::class, 'parent_id')` (when `parent_id` points at another MenuItem)
- `children()` — `hasMany(MenuItem::class, 'parent_id')`

#### Scopes

There's a custom builder scope at `MenuItem::class` that auto-applies inside the `MenusList` Livewire admin component. Read the live source for exact behavior.

## Repository

`app('menu_repository')` returns the singleton `MenuRepository`.

| Method | Returns |
|---|---|
| `getByName(string $name)` | `Menu` instance or null |
| `getByid(int $id)` | `Menu` instance or null |
| `getTree(string|int $nameOrId)` | nested array ready for blade rendering |
| `getItems(int $menuId)` | flat collection of MenuItem rows |

The repository caches results for the request lifecycle.

## Manager

`app('menu_manager')` returns the singleton `MenuManager`.

| Method | Returns |
|---|---|
| `save(array $data)` | int (id) — upserts a Menu container |
| `saveItem(array $data)` | int (id) — upserts a MenuItem |
| `delete(int $id)` | bool — deletes container + its items |
| `deleteItem(int $id)` | bool — deletes a single item (children re-parented to grandparent) |

The manager flushes the repository cache on every write.

## Global helpers

| Helper | Returns |
|---|---|
| `menu_render(array $opts)` | string — fully-rendered HTML |
| `<module type="menu" name="..." />` | resolves to `menu_render` at template render time |

## Filament admin

- `Modules\Menu\Filament\Admin\Pages\AdminMenusPage` — the menu builder page
- `Modules\Menu\Livewire\Admin\MenusList` — drag-and-drop item list

## Events

Eloquent events fire on `Menu` + `MenuItem` save/update/delete. The `MenuManager` listens to these for cache invalidation. Custom listeners can register via the standard `Menu::saved(fn ($m) => ...)` API.

## Testing

```bash
./vendor/bin/phpunit --filter=MenusApiControllerTest
```

Test coverage lives in `Modules/Menu/Tests/`.
