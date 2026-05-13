# Menu Module — Usage

Day-to-day patterns for building, rendering, and translating menus.

## Create a menu container

```php
use Modules\Menu\Models\Menu;

$header = Menu::create([
    'name'      => 'header',
    'title'     => 'Header Navigation',
    'is_active' => 1,
    'position'  => 1,
]);
```

`name` is the identifier templates pass to `<module type="menu" name="header" />`. Names are not enforced unique — but rendering picks the first match, so keep them distinct.

## Add menu items

```php
use Modules\Menu\Models\MenuItem;

// Top-level item — internal link to a Content row
MenuItem::create([
    'parent_id'  => $header->id,
    'title'      => 'About',
    'content_id' => $aboutPageId,
    'position'   => 1,
    'is_active'  => 1,
]);

// Top-level item — external URL
MenuItem::create([
    'parent_id'  => $header->id,
    'title'      => 'Support',
    'url'        => 'https://support.example.com',
    'target'     => '_blank',
    'position'   => 2,
]);

// Top-level item — category link
MenuItem::create([
    'parent_id'  => $header->id,
    'title'      => 'Tutorials',
    'category_id' => $tutorialsCategoryId,
    'position'   => 3,
]);
```

`parent_id` here is the container `Menu` id. For nested children, point `parent_id` at another `MenuItem.id`.

## Build a nested hierarchy

```php
$services = MenuItem::create([
    'parent_id' => $header->id,
    'title'     => 'Services',
    'url'       => '/services',
    'position'  => 2,
]);

MenuItem::create([
    'parent_id' => $services->id,
    'title'     => 'Web Design',
    'url'       => '/services/web-design',
    'position'  => 1,
]);

MenuItem::create([
    'parent_id' => $services->id,
    'title'     => 'SEO',
    'url'       => '/services/seo',
    'position'  => 2,
]);
```

Note: `MenuItem` uses `parent_id` for BOTH "belongs to container" AND "belongs to parent item." The render layer disambiguates by checking which `parent_id` resolves first in the join chain. In practice that means: a `MenuItem` row's `parent_id` is its IMMEDIATE parent — either the `Menu` container or another `MenuItem`.

## Mega menus

A mega menu spans multiple columns:

```php
$mega = Menu::create([
    'name'              => 'mega-shop',
    'title'             => 'Shop Mega Menu',
    'mega_menu_columns' => 3,
]);

// Items in column 1
MenuItem::create([
    'parent_id' => $mega->id,
    'title'     => 'Electronics',
    'column'    => 1,
    'position'  => 1,
]);

// Items in column 2
MenuItem::create([
    'parent_id' => $mega->id,
    'title'     => 'Clothing',
    'column'    => 2,
    'position'  => 1,
]);
```

Templates render mega menus by grouping items via the `column` field; see your template's mega-menu blade for the exact markup.

## Render in a template

```html
<!-- Module-tag style (preferred) -->
<module type="menu" name="header" />

<!-- Helper-function style -->
<?php echo menu_render(['name' => 'header']); ?>

<!-- By id -->
<?php echo menu_render(['id' => 7]); ?>
```

The helper supports options:

- `name` / `id` — required, identifies which menu
- `template` — override the rendering blade (default: from active template)
- `class` — extra CSS class on the outer `<ul>`
- `depth` — max nesting depth (default: unlimited)
- `include_inactive` — show items with `is_active = 0` (default false)

## Query menus + items programmatically

```php
use Modules\Menu\Models\Menu;
use Modules\Menu\Models\MenuItem;

// All active menus
$menus = Menu::where('is_active', 1)->orderBy('position')->get();

// Find by name
$header = Menu::where('name', 'header')->first();

// Items in a menu (top-level only)
$items = MenuItem::where('parent_id', $header->id)->orderBy('position')->get();

// Items in a menu (all nested)
function walk(int $parentId, array &$out = []): array {
    $children = MenuItem::where('parent_id', $parentId)->orderBy('position')->get();
    foreach ($children as $child) {
        $out[] = $child;
        walk($child->id, $out);
    }
    return $out;
}
$all = walk($header->id);
```

## Repository pattern (preferred for reads)

```php
$repo = app('menu_repository');

$menu = $repo->getByName('header');
$tree = $repo->getTree('header');  // nested structure ready for rendering
```

`MenuRepository` caches results for the request lifecycle — call it instead of writing your own queries for performance.

## Manager pattern (preferred for writes)

```php
$manager = app('menu_manager');

$id = $manager->save([
    'name' => 'footer',
    'title' => 'Footer Menu',
]);

$manager->saveItem([
    'parent_id' => $id,
    'title' => 'Privacy',
    'content_id' => $privacyPageId,
]);

$manager->delete($menuId);  // soft + flushes cache
```

`MenuManager` handles cache invalidation + multilanguage observers automatically.

## Live Edit integration

The Live Edit canvas renders `<module type="menu" />` tags and offers a Quick Edit panel for each rendered menu:

- Click a menu item in the canvas → Quick Edit panel opens on the right
- Drag to reorder, click X to delete, click "+" to add an item
- Changes save via the Livewire MenusList component

This is the recommended editor for non-technical operators. For programmatic / bulk changes, use `app('menu_manager')`.

## Translations

```php
use Modules\Menu\Models\MenuItem;

$item = MenuItem::find($id);
$item->setTranslation('title', 'es', 'Sobre Nosotros');
$item->setTranslation('title', 'de', 'Über Uns');

app()->setLocale('es');
echo $item->title;  // "Sobre Nosotros"
```

The TranslateMenu provider stores translations in `menu_items_translations` automatically on `setTranslation`. The accessor reads the active locale.

## Active-item highlighting

The render layer marks the currently-active item via `class="menu-item active"` based on the request URL matching:

- `MenuItem.url` exactly
- OR `MenuItem.content_id` matches the currently-rendered Content row
- OR `MenuItem.category_id` matches a category active in the request context

Templates can style `.menu-item.active` to highlight the current page in the navbar.
