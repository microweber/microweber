# Content Module

> **Slug:** `content`
> **Tier:** 1 (full data + REST API + admin resource + parent abstraction)
> **Source:** `Modules/Content/`

The Content module is the **core abstraction** of Microweber's CMS. Every typed content (Page, Post, Product, Category, Custom) lives as a row in the shared `content` table that this module owns. Operators rarely interact with the Content module directly — they use Page / Post / Product. Contributors touch it constantly: every scope, every event, every helper, every shared column lives here.

## What this module does

- Owns the `content` table — the central content store for the whole CMS
- Defines the canonical `Content` Eloquent model that all typed content extends (`Page extends Content`, `Post extends Content`, `Product extends Content`)
- Registers the per-type global scopes (`PageScope`, `PostScope`, `ProductScope`, `CategoryScope`) that filter typed queries
- Owns the SEO + Open Graph + Twitter metadata columns shared across all content types
- Owns the `content_data` key/value sidecar for arbitrary per-row metadata
- Owns the `content_fields` revision/version table
- Exposes a RESTful API at `/api/content` that works across all types (with `content_type` query param)
- Provides the helper layer (`save_content`, `get_content`, `delete_content`, `content_link`, `content_parents`, `get_content_children`, `pages_tree`) that the rest of the CMS depends on
- Fires lifecycle events (`ContentIsCreating`, `ContentWasCreated`, `ContentWasUpdated`, etc.) every typed model inherits

## Domain

Content is the **root of the content domain**. It's a single-table-inheritance pattern: every typed content model points at the same `content` table but layers on a global scope and type-specific helpers. This means:

- A `content_type = 'page'` row is a Page
- A `content_type = 'post'` row is a Post
- A `content_type = 'product'` row is a Product
- All share the same SEO fields, the same media-library attachments, the same category/tag taxonomies, the same lifecycle events

Cross-references:

- **Page / Post / Product modules** — typed children. Each declares its own model, controller, factory, and admin resource on top of Content
- **Category module** — uses `categories` + `categories_items` to attach taxonomy to Content rows
- **Tags module** — uses the polymorphic `taggable` table on `Modules\Content\Models\Content`
- **Media module** — `media` table joins to Content via `rel_type = 'content', rel_id = $id` for featured images + galleries
- **Restore / Backup modules** — operate on the entire `content` table for full-site restores (see `mw:big2-install-content`)
- **LiveEdit module** — reads `content_body` and renders it through the Live Edit canvas

## Documentation map

| Page | Purpose |
|---|---|
| [`index.md`](./index.md) | This overview |
| [`installation.md`](./installation.md) | Schema, registration, related tables, migration order |
| [`usage.md`](./usage.md) | Helper layer, querying, save/delete, events, custom types |
| [`api.md`](./api.md) | REST endpoints, Content model reference, scopes, helpers |
| [`examples.md`](./examples.md) | End-to-end recipes: bulk operations, custom types, content_data, listeners |
| [`troubleshooting.md`](./troubleshooting.md) | Common issues and fixes |

## Quick start

```php
use Modules\Content\Models\Content;

// Helper (works for any content_type)
$id = save_content([
    'title'        => 'New Article',
    'content_type' => 'post',
    'content_body' => '<p>Body text</p>',
    'is_active'    => 1,
]);

// Direct Eloquent (no scope — returns all types)
$row = Content::find($id);
echo $row->title;

// Listen to lifecycle events
\Event::listen(\Modules\Content\Events\ContentWasCreated::class, function ($event) {
    \Log::info('Content created: ' . $event->content->title);
});
```

## Key files

- `Modules/Content/Models/Content.php` — the parent Eloquent model
- `Modules/Content/Scopes/` — `PageScope`, `PostScope`, `ProductScope`, `CategoryScope`
- `Modules/Content/Events/` — `ContentIsCreating`, `ContentWasCreated`, `ContentWasUpdated`, `ContentWasDeleted`, `ContentWasRestored`, `ContentWasDestroyed`
- `Modules/Content/Repositories/ContentRepository.php` — heavyweight query layer used by the admin
- `Modules/Content/Http/Controllers/Api/ContentApiController.php` — generic REST controller
- `Modules/Content/database/migrations/` — schema for `content`, `content_data`, `content_fields`, and SEO columns
- `Modules/Content/Providers/ContentServiceProvider.php` — module bootstrap

## Status

The module is **production-stable** and **foundational** — every other content-type module depends on it. Changes to Content's scopes, events, or shared columns affect Page, Post, Product, Category, and any custom content type. New columns must be added via migration in this module, not in the typed children.
