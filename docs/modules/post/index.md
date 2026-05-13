# Post Module

> **Slug:** `post`
> **Tier:** 1 (full data + REST API + admin resource)
> **Source:** `Modules/Post/`

The Post module manages the **post** content type — Microweber's blog articles. Operators use this to publish news, editorial, and long-form content; contributors touch it to extend blog rendering, add author/category/tag taxonomies, or integrate publishing workflows (drafts → scheduled → published).

## What this module does

- Stores posts as rows in the shared `content` table with `content_type = 'post'`
- Filters all `Post` queries through the global `PostScope` so a `Post::query()` call never returns pages / products / categories
- Exposes a RESTful API at `/api/posts` (index / store / show / update / destroy)
- Provides a Filament admin resource with rich-text editor, featured image, category + tag selectors, scheduled publishing
- Integrates with the existing Microweber category + tag taxonomy (`categories_items` + `taggable` tables)
- Carries full SEO + Open Graph metadata (inherited from Content)
- Provides a frontend `BlogFilter` integration for category/tag/date filtering on the public blog index

## Domain

Post sits at the **editorial** layer of Microweber's content domain. Where Page is "URL structure" and Product is "shop item," Post is "an article a reader subscribes to." Posts typically live under a parent **blog page** (a Page with `subtype = 'dynamic'`) that renders them in a paginated grid via the `posts` module embed.

Cross-references:

- **Content module** (`Modules/Content/`) — parent abstraction. Post extends `Modules\Content\Models\Content` and shares the `content` table; the category/tag relations and SEO fields are inherited from there.
- **Page module** — the parent blog page (`is_active=1`, `subtype='dynamic'`) renders the post listing; individual post URLs are `/{parent-page}/{post-slug}`.
- **Categories module** — the `categories` + `categories_items` tables are the canonical taxonomy; Posts attach via `setCategories([$ids])`.
- **Tags module** — the `taggable` table powers the `tag()` / `withAnyTag()` helpers.

## Documentation map

| Page | Purpose |
|---|---|
| [`index.md`](./index.md) | This overview |
| [`installation.md`](./installation.md) | Registration pipeline + shared content-table columns + microweber:install behavior |
| [`usage.md`](./usage.md) | Authoring patterns — creating posts, categories, tags, SEO, scheduled publishing, RSS |
| [`api.md`](./api.md) | REST + Eloquent reference for `Post` and `PostApiController` |
| [`examples.md`](./examples.md) | End-to-end recipes: blog landing, category archive, tag cloud, sitemap, RSS |
| [`troubleshooting.md`](./troubleshooting.md) | Common issues and fixes |

## Quick start

```php
use Modules\Post\Models\Post;

$post = Post::create([
    'title'        => 'Getting Started with Microweber',
    'url'          => 'getting-started',
    'description'  => 'A quick intro to the CMS.',
    'content_body' => '<p>Full article body here.</p>',
    'is_active'    => 1,
    'parent'       => 8,  // parent blog page ID
]);

$post->setCategories([3, 7]);
$post->tag(['laravel', 'cms']);

echo $post->link;        // /blog/getting-started
echo $post->editLink();  // admin edit URL
```

## Key files

- `Modules/Post/Models/Post.php` — Eloquent model (extends Content, scoped via `PostScope`)
- `Modules/Post/Http/Controllers/Api/PostApiController.php` — REST CRUD controller (5 methods: index/store/show/update/destroy)
- `Modules/Post/Filament/Resources/PostResource/` — admin UI
- `Modules/Post/routes/api.php` — API route registration
- `Modules/Post/Providers/PostServiceProvider.php` — module bootstrap
- `Modules/Post/Database/Factories/PostFactory.php` — test factory (`Post::factory()->create()`)
- `Modules/Post/Tests/Unit/PostApiControllerTest.php` — REST API test coverage
- `Modules/Blog/FrontendFilter/BlogFilter.php` — public-side filter integration

## Status

The module is **production-stable** and ships with every Microweber install. Blog posts are one of the three core content types (alongside pages and products) — touching this module's scopes / event handlers affects every blog index and individual article URL on the public site.
