# Category Module

> **Slug:** `category`
> **Tier:** 1 (full data + REST API + admin resource)
> **Source:** `Modules/Category/`

The Category module manages the **hierarchical taxonomy** that organizes pages, posts, and products. Operators use it to build navigation structures, archive pages, and product groupings; contributors touch it to extend the taxonomy with custom attributes or hook into category-change events.

## What this module does

- Owns the `categories` table — a hierarchical (parent/child) taxonomy with URL-friendly slugs + meta descriptions
- Owns the `categories_items` join table — the polymorphic many-to-many between content and categories
- Exposes RESTful APIs at `/api/categories` (full CRUD)
- Provides a Filament admin resource with drag-and-drop hierarchy + bulk operations
- Integrates with Content via `whereCategoryIds()` query scope and the `setCategories()` / `categoriesIds()` methods on the Content model
- Generates URL-friendly slugs that are unique within each parent
- Carries `title`, `description`, `content_meta_description`, `position`, `parent_id`, `is_hidden` fields

## Domain

Category is the **taxonomy layer** of Microweber's content domain. Where Page is "URL," Post is "article," and Product is "shop item," Category is "the bucket that groups them." A single category can contain mixed types (a "Tutorials" category might list both blog posts AND video products).

Cross-references:

- **Content module** — every taggable row in the `content` table can attach to one or more categories via `categories_items`
- **Page module** — category archive pages are typically Page rows (`subtype = 'dynamic'`) that list categorized children
- **Post module** — `Post::active()->whereCategoryIds($id)` is the canonical category-archive query
- **Product module** — categories drive the shop's category-tree sidebar; the menu module reads from `categories` for tree-style menus
- **Url module** — category URLs are dynamic (matched by slug → category lookup)

## Documentation map

| Page | Purpose |
|---|---|
| [`index.md`](./index.md) | This overview |
| [`installation.md`](./installation.md) | Schema, registration, related tables |
| [`usage.md`](./usage.md) | CRUD, hierarchy, slug generation, attaching content, archive pages |
| [`api.md`](./api.md) | REST + Eloquent reference |
| [`examples.md`](./examples.md) | End-to-end recipes |
| [`troubleshooting.md`](./troubleshooting.md) | Common issues |

## Quick start

```php
use Modules\Category\Models\Category;

$root = Category::create([
    'title'       => 'Tutorials',
    'description' => 'How-to guides and walkthroughs.',
    'parent_id'   => 0,
    'is_hidden'   => 0,
]);

$child = Category::create([
    'title'       => 'Microweber',
    'parent_id'   => $root->id,
]);

// Attach content to the category
$post->setCategories([$child->id]);

// Query content by category
\Modules\Post\Models\Post::active()->whereCategoryIds($child->id)->get();
```

## Key files

- `Modules/Category/Models/Category.php` — Eloquent model
- `Modules/Category/Models/CategoryItem.php` — pivot model for `categories_items`
- `Modules/Category/Http/Controllers/Api/CategoriesApiController.php` — primary REST controller
- `Modules/Category/Filament/Resources/CategoryResource/` — admin UI
- `Modules/Category/database/migrations/` — `categories` + `categories_items` schema
- `Modules/Category/Repositories/CategoryRepository.php` — query layer

## Status

The module is **production-stable** and is a hard dependency of Page, Post, and Product. The schema has been stable for many releases — category-related bugs usually live in consumer modules (e.g. how Posts filter by category) rather than in this module itself.
