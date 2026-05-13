# Post Module — Installation & Setup

The Post module is a **core module** — it ships with Microweber and is registered automatically.

## Prerequisites

- PHP ≥ 8.2
- A working Microweber install (Laravel 11 base, MySQL/MariaDB or SQLite)
- The Content module (`Modules/Content/`) — Post extends `Modules\Content\Models\Content` and shares the `content` table
- The Categories module — for the category taxonomy (`categories` + `categories_items` tables)
- Filament v5 admin (`filament/filament`) — for the admin resource
- Livewire v4 — for the admin form

## Registration pipeline

1. **`Modules/Post/module.json`** declares the module:

    ```json
    {
        "name": "Post",
        "alias": "post",
        "providers": ["Modules\\Post\\Providers\\PostServiceProvider"]
    }
    ```

2. **`Modules/Post/Providers/PostServiceProvider.php`** registers config, views, API routes, the Filament `PostResource`, factories, and any module migrations.

3. **`composer.json`** PSR-4 autoload entry:

    ```json
    "Modules\\Post\\": "Modules/Post/"
    ```

No manual setup — `composer dump-autoload` after a clone is enough.

## Database schema

Post does **not own a dedicated table** — it shares `content` with every other content type. The schema lives in the Content module's migrations.

Relevant `content` columns for posts:

| Column | Type | Notes |
|---|---|---|
| `id` | bigint primary | |
| `content_type` | varchar | Always `'post'` (enforced by `Post::__construct`) |
| `subtype` | varchar | `'post'` (default) — distinguishes from `'page'`, `'product'` |
| `parent` | bigint | FK → `content.id` of the parent blog page |
| `title` | varchar | Article title |
| `url` | varchar | URL slug; final link is `{parent-blog-page}/{url}` |
| `description` | text | Short summary (excerpt) |
| `content_body` | longtext | Full article HTML |
| `posted_at` | timestamp | Publish date; used for ordering on the blog index |
| `is_active` | tinyint | `0` hides from public navigation + queries |
| `is_deleted` | tinyint | Soft-delete flag |
| `created_by` | bigint | FK → `users.id` (author) |
| `position` | int | Sort order |
| SEO columns | various | `content_meta_title`, `content_meta_description`, `og_*`, `twitter_*`, `canonical_url`, etc. — inherited from Content |

## Related tables

| Table | Purpose |
|---|---|
| `categories` | Hierarchical taxonomy (also used by Products) |
| `categories_items` | Many-to-many join between content and categories |
| `taggable` | Polymorphic tag attachment (via the Tags module) |
| `content_data` | Key/value sidecar for arbitrary post metadata |
| `media` | Featured image + gallery (polymorphic) |

## What `microweber:install` does for posts

The base install creates:

- A blog parent page (Page row with `subtype = 'dynamic'`, `url = 'blog'`)
- No seed posts (unless the active template ships `mw_default_content.zip` with sample articles)

## Re-seeding

For a Big2 install, `php artisan mw:big2-install-content` restores the canonical Big2 demo (which includes ~59 sample posts) via `TemplateInstaller`. For test fixtures use `Post::factory()`.

## Configuration

Post has no module-specific config keys. Behavior is driven by:

- The parent blog page's settings (pagination size, layout)
- The Content module's options (`content_default_state`, etc.)
- The Categories module's option for the default category tree

## Disabling / replacing

Post is **not safe to disable** if the site uses a blog. To customize:

- Extend `Post` and bind your subclass into the container
- Override `PostResource` from another module
- Hook into `Content::saving` / `Content::deleted` events (they fire for posts since Post extends Content) and gate on `content_type === 'post'`
