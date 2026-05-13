# Page Module

> **Slug:** `page`
> **Tier:** 1 (full data + REST API + admin resource)
> **Source:** `Modules/Page/`

The Page module manages the **page** content type in Microweber's CMS — the building blocks of every site's structure (homepage, about, contact, landing pages, parent/child navigation trees). Operators use this to author and organize static and dynamic pages; contributors touch it to extend page rendering, integrate new layouts, or hook into URL routing and SEO.

## What this module does

- Stores pages as rows in the shared `content` table with `content_type = 'page'`
- Filters all `Page` queries through the global `PageScope` so a `Page::query()` call never returns posts/products/categories
- Exposes a RESTful API at `/api/pages` (index / store / show / update / destroy)
- Provides a Filament admin resource (`PageResource`) with drag-and-drop reordering, live-edit launching, and layout-file selection
- Supports a parent/child hierarchy for navigation trees
- Carries full SEO + Open Graph metadata fields
- Integrates with the live-edit drag-and-drop editor via `liveEditLink()`

## Domain

Page sits at the **structural** layer of Microweber's content domain. Where Post is "blog article" and Product is "shop item," Page is "the URL the user landed on." Pages can host any module on their canvas (a shop product grid, a blog post list, a contact form, etc.) through Microweber's `<module type="..."/>` markup in `content_body`.

Cross-references:

- **Content module** (`Modules/Content/`) — the parent abstraction. Page extends `Modules\Content\Models\Content` and shares the `content` table. Look at the Content module for the shared scopes (`PageScope`, `PostScope`, `ProductScope`), the `content_data` key/value store, and the global helpers (`save_content`, `get_content`, `pages_tree`, `content_link`).
- **LiveEdit module** — the canvas editor that renders a page's `content_body`. `Page::liveEditLink()` returns the deep link.
- **Menu module** — pages can be referenced from menu items via `parent` for hierarchical navigation.

## Documentation map

| Page | Purpose |
|---|---|
| [`index.md`](./index.md) | This overview |
| [`installation.md`](./installation.md) | How Page is registered, autoloaded, and what gets installed during `microweber:install` |
| [`usage.md`](./usage.md) | Authoring patterns — creating pages, hierarchies, SEO, layouts, live-edit |
| [`api.md`](./api.md) | REST API + Eloquent reference for `Page` and `PageApiController` |
| [`examples.md`](./examples.md) | End-to-end recipes: landing pages, parent/child trees, redirects, sitemaps |
| [`troubleshooting.md`](./troubleshooting.md) | Common issues and fixes |

## Quick start

```php
use Modules\Page\Models\Page;

$page = Page::create([
    'title'        => 'About Us',
    'url'          => 'about',
    'content_body' => '<p>Company info here.</p>',
    'is_active'    => 1,
]);

echo $page->link;            // /about
echo $page->editLink();      // admin edit URL
echo $page->liveEditLink();  // live-edit canvas URL
```

## Key files

- `Modules/Page/Models/Page.php` — Eloquent model (extends `Modules\Content\Models\Content`, scoped via `PageScope`)
- `Modules/Page/Http/Controllers/Api/PageApiController.php` — REST CRUD controller (5 methods: index/store/show/update/destroy)
- `Modules/Page/Filament/Resources/PageResource/` — admin UI
- `Modules/Page/routes/api.php` — API route registration
- `Modules/Page/Providers/PageServiceProvider.php` — module bootstrap
- `Modules/Page/database/factories/PageFactory.php` — test factory (`Page::factory()->create()`)
- `Modules/Page/Tests/Unit/PageApiControllerTest.php` — REST API test coverage

## Status

The module is **production-stable** and ships with every Microweber install. Pages are the most-used content type — touching this module's models or scopes affects every URL the site serves, so changes here need contract-test coverage in `Modules/Page/Tests/Unit/`.
