# Menu Module — Examples

## Recipe 1: Build a complete header menu programmatically

```php
use Modules\Menu\Models\Menu;
use Modules\Menu\Models\MenuItem;
use Modules\Page\Models\Page;

$header = Menu::firstOrCreate(['name' => 'header'], [
    'title' => 'Header Navigation',
    'is_active' => 1,
    'position' => 1,
]);

$entries = [
    ['title' => 'Home', 'url' => '/'],
    ['title' => 'About', 'page' => 'about'],
    ['title' => 'Services', 'page' => 'services'],
    ['title' => 'Blog', 'url' => '/blog'],
    ['title' => 'Contact', 'page' => 'contact'],
];

foreach ($entries as $i => $entry) {
    $data = [
        'parent_id' => $header->id,
        'title'     => $entry['title'],
        'position'  => $i + 1,
        'is_active' => 1,
    ];

    if (isset($entry['page'])) {
        $page = Page::where('url', $entry['page'])->first();
        $data['content_id'] = $page?->id;
    } else {
        $data['url'] = $entry['url'];
    }

    MenuItem::create($data);
}
```

## Recipe 2: Build a 3-column mega menu

```php
$mega = Menu::create([
    'name'              => 'mega-shop',
    'title'             => 'Shop Mega Menu',
    'is_active'         => 1,
    'mega_menu_columns' => 3,
]);

// Column 1: Categories
$col1 = ['Electronics', 'Computers', 'Phones', 'Tablets'];
foreach ($col1 as $i => $title) {
    MenuItem::create([
        'parent_id' => $mega->id,
        'title'     => $title,
        'url'       => '/shop/' . \Str::slug($title),
        'column'    => 1,
        'position'  => $i + 1,
    ]);
}

// Column 2: Featured
$col2 = ['Black Friday Sale', 'New Arrivals', 'Best Sellers'];
foreach ($col2 as $i => $title) {
    MenuItem::create([
        'parent_id' => $mega->id,
        'title'     => $title,
        'url'       => '/promo/' . \Str::slug($title),
        'column'    => 2,
        'position'  => $i + 1,
    ]);
}

// Column 3: Help
$col3 = ['Shipping Info', 'Returns', 'Contact Support'];
foreach ($col3 as $i => $title) {
    MenuItem::create([
        'parent_id' => $mega->id,
        'title'     => $title,
        'url'       => '/help/' . \Str::slug($title),
        'column'    => 3,
        'position'  => $i + 1,
    ]);
}
```

## Recipe 3: Render the menu with a custom class

```html
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="/">{{ option('website_title') }}</a>
        <module type="menu" name="header" class="navbar-nav ms-auto" />
    </div>
</nav>
```

The `class` attribute on the `<module>` tag is passed to the rendered `<ul>` wrapper.

## Recipe 4: Highlight the current page

The render layer auto-applies `class="menu-item active"` based on the current request — style it in your CSS:

```css
.navbar .menu-item.active > a {
    font-weight: bold;
    color: var(--bs-primary);
    border-bottom: 2px solid var(--bs-primary);
}
```

## Recipe 5: Filter menu items by user role

Subclass `MenuRepository` and filter the result:

```php
namespace App\Repositories;

use Modules\Menu\Repositories\MenuRepository;

class AuthAwareMenuRepository extends MenuRepository
{
    public function getTree($identifier): array
    {
        $tree = parent::getTree($identifier);

        // Hide admin-only items for non-admin users
        if (! auth()->user()?->hasRole('admin')) {
            $tree = $this->stripAdminItems($tree);
        }

        return $tree;
    }

    private function stripAdminItems(array $tree): array
    {
        return collect($tree)
            ->reject(fn ($item) => str_contains(strtolower($item['title']), 'admin'))
            ->values()
            ->all();
    }
}
```

Then bind in `AppServiceProvider`:

```php
$this->app->singleton('menu_repository', \App\Repositories\AuthAwareMenuRepository::class);
```

## Recipe 6: Multilanguage menu

```php
$item = MenuItem::find($itemId);

$item->setTranslation('title', 'es', 'Sobre Nosotros');
$item->setTranslation('title', 'de', 'Über Uns');
$item->setTranslation('title', 'fr', 'À Propos');

app()->setLocale('es');
echo $item->title;  // "Sobre Nosotros"
```

The TranslateMenu provider stores translations in `menu_items_translations` and applies them via the model's `title` accessor when the active locale matches.

## Recipe 7: Bulk reorder via drag-and-drop persistence

When the Livewire `MenusList` component receives a drag-and-drop event:

```php
public function reorder(array $sortedIds): void
{
    foreach ($sortedIds as $position => $itemId) {
        \DB::table('menu_items')
            ->where('id', $itemId)
            ->update(['position' => $position + 1]);
    }

    app('menu_manager')->flushCache();
}
```

The cache flush is critical — otherwise the next page render shows the old order.

## Recipe 8: Listen for menu-item save events

```php
// In a ServiceProvider boot()
\Modules\Menu\Models\MenuItem::saved(function ($item) {
    \Log::info("Menu item saved: {$item->title} (id {$item->id})");
});

\Modules\Menu\Models\MenuItem::deleted(function ($item) {
    // Cascade-delete any cached `nav_data` keyed by the menu item
    \Cache::forget("nav_data_for_item_{$item->id}");
});
```

## Recipe 9: Build a sitemap from the header menu

```php
use Modules\Menu\Models\Menu;
use Modules\Menu\Models\MenuItem;

$header = Menu::where('name', 'header')->first();
$items = MenuItem::where('parent_id', $header->id)
    ->where('is_active', 1)
    ->orderBy('position')
    ->get();

$urls = $items->map(function ($item) {
    if ($item->url) {
        return $item->url;
    }
    if ($item->content_id) {
        return content_link($item->content_id);
    }
    if ($item->category_id) {
        return category_link($item->category_id);
    }
    return null;
})->filter()->values();

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
foreach ($urls as $url) {
    echo "  <url><loc>" . htmlspecialchars(url($url)) . "</loc></url>\n";
}
echo "</urlset>\n";
```

(For a real sitemap, use the Sitemap module instead — but this shows how to walk a menu programmatically.)

## Recipe 10: REST API — replace the entire footer menu

```bash
TOKEN=$(curl -s -X POST https://yoursite.com/api/login \
    -H "Content-Type: application/json" \
    -d '{"email":"admin@yoursite.com","password":"…"}' | jq -r .token)

# Delete the existing footer
FOOTER_ID=$(curl -s "https://yoursite.com/api/menus?name=footer" | jq -r '.data[0].id')
curl -X DELETE -H "Authorization: Bearer $TOKEN" "https://yoursite.com/api/menus/${FOOTER_ID}"

# Create a fresh footer with new items
NEW_ID=$(curl -s -X POST "https://yoursite.com/api/menus" \
    -H "Authorization: Bearer $TOKEN" \
    -d '{"name":"footer","title":"Footer","is_active":1}' | jq -r '.data.id')

for entry in 'Privacy:/privacy' 'Terms:/terms' 'Cookie:/cookies'; do
    title="${entry%%:*}"
    url="${entry##*:}"
    curl -X POST "https://yoursite.com/api/menus/${NEW_ID}/items" \
        -H "Authorization: Bearer $TOKEN" \
        -d "{\"title\":\"$title\",\"url\":\"$url\",\"is_active\":1}"
done
```
