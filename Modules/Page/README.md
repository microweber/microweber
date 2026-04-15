# Page

Page content type management. Pages are Content items with `content_type = 'page'` and `subtype = 'static'`, used for website structure, landing pages, and navigation.

## Relationship to the Content Module

`Page` extends `Modules\Content\Models\Content` and shares the `content` table. A global `PageScope` filters queries to only return pages. All Content helpers and events work with pages.

## Creating Pages

```php
use Modules\Page\Models\Page;

// Via Eloquent
$page = Page::create([
    'title'       => 'About Us',
    'url'         => 'about',
    'content_body'=> '<p>Company info here.</p>',
    'is_active'   => 1,
    'layout_file' => 'layouts/about.php',
]);

// Set as homepage
$page->update(['is_home' => 1]);

// Set as shop page
$page->update(['is_shop' => 1]);

// Via the content helper
$id = save_content([
    'title'        => 'Contact',
    'content_type' => 'page',
    'subtype'      => 'static',
]);
```

## Page Hierarchy (Parent / Child)

```php
// Create a parent page
$parent = Page::create(['title' => 'Services', 'url' => 'services']);

// Create child pages under it
Page::create(['title' => 'Web Design', 'url' => 'web-design', 'parent' => $parent->id]);
Page::create(['title' => 'SEO',        'url' => 'seo',        'parent' => $parent->id]);

// Query children
$children = get_content_children($parent->id);

// Walk up the tree
$parents = content_parents($childId);

// Render a navigation tree
echo pages_tree(['ul_class' => 'nav', 'include_categories' => true]);
```

## REST API

```bash
# List pages (public)
curl https://yoursite.com/api/pages?limit=20

# Get a single page
curl https://yoursite.com/api/pages/3

# Create (Sanctum, admin only)
curl -X POST https://yoursite.com/api/pages \
  -H "Authorization: Bearer $TOKEN" \
  -d '{"title":"New Landing Page","content_body":"<p>Hello</p>"}'

# Update
curl -X PUT https://yoursite.com/api/pages/3 \
  -H "Authorization: Bearer $TOKEN" \
  -d '{"title":"Renamed Page","layout_file":"layouts/landing.php"}'

# Delete
curl -X DELETE https://yoursite.com/api/pages/3 \
  -H "Authorization: Bearer $TOKEN"
```

## Template Integration

```html
<!-- Render child pages as a section listing -->
@foreach(Page::active()->where('parent', $page->id)->get() as $child)
  <a href="{{ $child->link }}">{{ $child->title }}</a>
@endforeach

<!-- Live-edit link (opens the drag-and-drop editor) -->
<a href="{{ $page->liveEditLink() }}">Edit this page</a>

<!-- Edit link (admin panel) -->
<a href="{{ $page->editLink() }}">Edit in admin</a>
```

## SEO Metadata

Pages support full SEO fields: `content_meta_title`, `content_meta_description`, `content_meta_keywords`, `og_title`, `og_description`, `og_image`, `twitter_title`, `twitter_description`, `canonical_url`, `robots_meta`, `sitemap_priority`, and `exclude_from_sitemap`.

## Key Classes

| Class | Purpose |
|---|---|
| `Models\Page` | Eloquent model (extends Content, scoped to pages) |
| `Http\Controllers\Api\PageApiController` | RESTful CRUD controller |
| `Scopes\PageScope` | Global scope filtering to `content_type = 'page'` |

## Admin Panel (Filament)

Pages are managed through the **PageResource** in the Filament admin, with drag-and-drop reordering, live-edit launching, and layout file selection.
