# Page Module — Examples

End-to-end recipes for common page-management tasks.

## Recipe 1: Create a landing page with a custom layout

```php
use Modules\Page\Models\Page;

$landing = Page::create([
    'title'                    => 'Black Friday 2026',
    'url'                      => 'black-friday-2026',
    'content_body'             => '<h1>50% off everything</h1><module type="shop/products" limit="12" category="sale"/>',
    'layout_file'              => 'layouts/landing-full-width.php',
    'is_active'                => 1,
    'content_meta_title'       => 'Black Friday 2026 — 50% Off Everything',
    'content_meta_description' => 'Our biggest sale of the year. Free shipping over $100.',
    'og_title'                 => 'Black Friday 2026',
    'og_image'                 => '/media/default/promo-bf26.jpg',
    'sitemap_priority'         => '0.9',
]);

echo $landing->link;
// https://yoursite.com/black-friday-2026
```

## Recipe 2: Build a parent/child navigation tree

```php
use Modules\Page\Models\Page;

$services = Page::create(['title' => 'Services', 'url' => 'services', 'position' => 2]);

Page::create(['title' => 'Web Design',       'url' => 'web-design',       'parent' => $services->id, 'position' => 1]);
Page::create(['title' => 'SEO',              'url' => 'seo',              'parent' => $services->id, 'position' => 2]);
Page::create(['title' => 'E-commerce Setup', 'url' => 'ecommerce-setup',  'parent' => $services->id, 'position' => 3]);

// Render in a blade template
echo pages_tree([
    'ul_class'           => 'navbar-nav',
    'parent'             => 0,
    'include_categories' => false,
]);
```

## Recipe 3: Set up a redirect from an old URL

When you rename a page, Microweber's redirect manager handles old URLs automatically if you keep the original `id`. Manually:

```php
$page = Page::find(42);
$page->update(['url' => 'about-us']);

// The redirect from /about to /about-us is registered by the content
// observer; verify it lives in the `url_redirects` table (Modules/Url).
```

For an unrelated redirect:

```php
\DB::table('url_redirects')->insert([
    'old_url'    => 'old-pricing',
    'new_url'    => 'pricing',
    'status'     => 301,
    'is_active'  => 1,
    'created_at' => now(),
]);
```

## Recipe 4: Programmatic homepage swap

```php
use Modules\Page\Models\Page;

// Clear current homepage
Page::where('is_home', 1)->update(['is_home' => 0]);

// Promote a different page
$newHome = Page::findOrFail($newId);
$newHome->is_home = 1;
$newHome->save();

// The site now serves $newHome at "/"
```

## Recipe 5: Embed modules in `content_body`

```php
$page = Page::create([
    'title'        => 'About + Contact',
    'url'          => 'about-and-contact',
    'content_body' => '
        <h1>About Us</h1>
        <p>Founded in 2005, we...</p>
        <h2>Latest news</h2>
        <module type="posts" limit="3"/>
        <h2>Get in touch</h2>
        <module type="contact_form"/>
    ',
]);
```

The frontend renderer expands every `<module type="..."/>` tag at request time. This is the same markup Live Edit emits when you drag a block onto a page.

## Recipe 6: Sitemap exclusion

```php
$staging = Page::create([
    'title'                => 'Internal Staging',
    'url'                  => 'staging',
    'is_active'            => 0,
    'exclude_from_sitemap' => 1,
    'robots_meta'          => 'noindex,nofollow',
]);
```

`is_active = 0` hides the page from public navigation; `exclude_from_sitemap = 1` keeps it out of `sitemap.xml`; `robots_meta = noindex,nofollow` instructs crawlers to skip it.

## Recipe 7: Test factory for unit tests

```php
namespace Modules\Page\Tests\Unit;

use Modules\Page\Models\Page;
use Tests\TestCase;

class HomepageRoutingTest extends TestCase
{
    public function test_homepage_serves_at_root(): void
    {
        Page::factory()->create([
            'is_home' => 1,
            'title'   => 'Home',
            'url'     => 'home',
        ]);

        $this->get('/')->assertOk()->assertSeeText('Home');
    }
}
```

## Recipe 8: Bulk export pages as JSON

```php
use Modules\Page\Models\Page;

$pages = Page::active()
    ->orderBy('position')
    ->get(['id', 'title', 'url', 'parent', 'content_body', 'is_home', 'is_shop'])
    ->toArray();

file_put_contents(
    storage_path('app/pages-export.json'),
    json_encode($pages, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
);
```

## Recipe 9: Live-edit deep link in a Blade view

```html
@auth('admin')
    <a href="{{ $page->liveEditLink() }}" class="btn btn-sm btn-primary mw-edit-this-page-btn">
        Edit this page
    </a>
@endauth
```

`liveEditLink()` is inherited from Content and returns the canvas URL with the right page-id parameter. The auth gate ensures only admins see the link.

## Recipe 10: REST API create from a script

```bash
TOKEN=$(curl -s -X POST https://yoursite.com/api/login \
    -H "Content-Type: application/json" \
    -d '{"email":"admin@yoursite.com","password":"…"}' | jq -r .token)

curl -X POST https://yoursite.com/api/pages \
    -H "Authorization: Bearer $TOKEN" \
    -H "Content-Type: application/json" \
    -d '{
        "title": "New Landing Page",
        "url": "new-landing",
        "content_body": "<p>Hello</p>",
        "is_active": 1
    }' | jq .
```

Returns the created page row with its assigned ID.
