# Page Module — Usage

Day-to-day patterns for creating, querying, organizing, and rendering pages.

## Creating a page

```php
use Modules\Page\Models\Page;

// Via Eloquent
$page = Page::create([
    'title'        => 'About Us',
    'url'          => 'about',
    'content_body' => '<p>Company info here.</p>',
    'is_active'    => 1,
    'layout_file'  => 'layouts/about.php',
]);

// Via the content helper (lets save_content normalize the URL slug,
// invalidate caches, fire events, etc.)
$id = save_content([
    'title'        => 'Contact',
    'content_type' => 'page',
    'subtype'      => 'static',
]);
```

`Page::__construct` always sets `content_type = 'page'`, so the helper version's `'content_type' => 'page'` is optional but explicit.

## Designating a homepage

Exactly one row should carry `is_home = 1`. The Page model does not enforce this — you must clear the flag from any previous homepage first:

```php
Page::where('is_home', 1)->update(['is_home' => 0]);

$home = Page::find($id);
$home->is_home = 1;
$home->save();
```

The homepage is served at `/` regardless of its `url` value.

## Designating a shop landing page

```php
$shop = Page::create([
    'title'   => 'Shop',
    'url'     => 'shop',
    'is_shop' => 1,
]);
```

The shop module renders the product grid into this page's canvas. There is generally one shop page per site.

## Parent / child hierarchy

```php
$parent = Page::create(['title' => 'Services', 'url' => 'services']);

Page::create(['title' => 'Web Design', 'url' => 'web-design', 'parent' => $parent->id]);
Page::create(['title' => 'SEO',        'url' => 'seo',        'parent' => $parent->id]);

// Query children
$children = get_content_children($parent->id);

// Walk up the tree
$parents = content_parents($childId);

// Render a full navigation tree
echo pages_tree(['ul_class' => 'nav', 'include_categories' => true]);
```

`pages_tree` is the canonical helper for sidebar / footer navigation; it accepts the standard `nav` array shape used by Bootstrap menus.

## Querying pages

```php
use Modules\Page\Models\Page;

// All active pages
$pages = Page::active()->get();

// Pages directly under the homepage
$topLevel = Page::active()->where('parent', 0)->orderBy('position')->get();

// Children of a specific page
$children = Page::active()->where('parent', $parentId)->get();

// Find by URL slug
$page = Page::where('url', 'about')->first();

// Find homepage
$home = Page::where('is_home', 1)->first();
```

The global `PageScope` (registered in the Content module) automatically filters `Page::query()` to `content_type = 'page'`. To bypass:

```php
Page::withoutGlobalScope(\Modules\Content\Scopes\PageScope::class)->get();
```

## SEO metadata

Pages support every SEO field the Content module owns:

```php
$page->update([
    'content_meta_title'       => 'About Our Company — Trusted Since 2005',
    'content_meta_description' => 'Learn what makes our team different.',
    'content_meta_keywords'    => 'about, team, history',
    'og_title'                 => 'About Our Company',
    'og_description'           => 'Trusted since 2005.',
    'og_image'                 => '/media/default/og-about.jpg',
    'twitter_title'            => 'About Us',
    'twitter_description'      => 'Trusted since 2005.',
    'canonical_url'            => 'https://yoursite.com/about',
    'robots_meta'              => 'index,follow',
    'sitemap_priority'         => '0.7',
    'exclude_from_sitemap'     => 0,
]);
```

Empty `content_meta_title` falls back to the page's `title` when rendering `<title>`.

## Layouts

Each page can be rendered through a specific layout file under `Templates/<active_template>/<layout_file>`. If `layout_file` is empty, the template's default layout is used.

```php
$page->update(['layout_file' => 'layouts/landing-full-width.php']);
```

Microweber's Live Edit canvas reads `layout_file` and renders the corresponding template file with the page's `content_body` embedded.

## Rendering links

```html
<!-- Public-facing link to the page -->
<a href="{{ $page->link }}">{{ $page->title }}</a>

<!-- Admin edit link (Filament resource) -->
<a href="{{ $page->editLink() }}">Edit in admin</a>

<!-- Live-edit canvas link (drag-and-drop editor) -->
<a href="{{ $page->liveEditLink() }}">Edit this page</a>
```

`$page->link` accessor returns the canonical URL via `content_link($page->id)`. Both `editLink()` and `liveEditLink()` are inherited from `Content` and respect the current admin panel prefix.

## Hooking into save events

```php
use Modules\Page\Models\Page;

Page::saving(function (Page $page) {
    if (! $page->url) {
        $page->url = \Str::slug($page->title);
    }
});

Page::deleted(function (Page $page) {
    // Clean up custom related data
});
```

The Content module's events fire for pages too — `creating`, `created`, `updating`, `updated`, `saving`, `saved`, `deleting`, `deleted`, `restored`. Listeners should check `$model->content_type === 'page'` if they only care about pages.

## Embedding modules in page content

`content_body` may include Microweber's `<module>` markup. The frontend renderer expands these tags at request time:

```html
<h1>Our Latest Products</h1>
<module type="shop/products" limit="6" category="featured"/>

<h2>Recent Blog Posts</h2>
<module type="posts" limit="3"/>
```

This is how Live Edit composes pages — every dragged block emits a `<module>` tag into the saved `content_body`.

## Test factory

```php
use Modules\Page\Models\Page;

// Default: a basic page with random title + url
$page = Page::factory()->create();

// Override fields
$home = Page::factory()->create(['is_home' => 1, 'title' => 'Home', 'url' => 'home']);

// Build without saving
$draft = Page::factory()->make(['title' => 'Draft']);
```

The factory lives at `Modules/Page/Database/Factories/PageFactory.php` and follows the standard Laravel factory pattern.
