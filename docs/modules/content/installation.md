# Content Module — Installation

The Content module is the **foundational** module of Microweber's CMS. It cannot be disabled and is loaded before every typed-content module that depends on it.

## Prerequisites

- PHP ≥ 8.2
- A working Laravel 11 application (Microweber base install)
- MySQL/MariaDB or SQLite — the schema uses `longtext` columns for `content_body` which work on both
- Filament v5 — for the admin resource UI

## Registration pipeline

1. **`Modules/Content/module.json`** declares the module:

    ```json
    {
        "name": "Content",
        "alias": "content",
        "priority": 1000,
        "providers": ["Modules\\Content\\Providers\\ContentServiceProvider"]
    }
    ```

   The high `priority` ensures Content loads before Page/Post/Product.

2. **`Modules/Content/Providers/ContentServiceProvider.php`** registers:
    - Migrations from `Modules/Content/database/migrations/`
    - The `Content` model bindings + global scopes (`PageScope`, `PostScope`, `ProductScope`, `CategoryScope`)
    - The `ContentRepository` singleton on `app('content_manager')`
    - REST routes from `Modules/Content/routes/api.php`
    - Lifecycle events from `Modules/Content/Events/`
    - Filament resources (Content listing, custom field management)

3. **`composer.json`** PSR-4 autoload:

    ```json
    "Modules\\Content\\": "Modules/Content/"
    ```

## Database schema

The `content` table is the central content store. Key columns:

| Column | Type | Notes |
|---|---|---|
| `id` | bigint primary | |
| `content_type` | varchar | `'page'`, `'post'`, `'product'`, `'category'`, custom values |
| `subtype` | varchar | Sub-type (`'static'` / `'dynamic'` for pages, `'post'` for posts) |
| `subtype_value` | varchar | Free-form per-subtype value (rarely used) |
| `parent` | bigint | FK → `content.id` (parent page / blog / category) |
| `title` | varchar | Display title |
| `url` | varchar | URL slug |
| `description` | text | Short summary / excerpt |
| `content_body` | longtext | Full HTML body (may contain `<module>` embeds) |
| `content` | longtext | Legacy compat field |
| `content_meta_title` | text | SEO `<title>` override |
| `content_meta_description` | text | SEO meta description |
| `content_meta_keywords` | text | SEO keywords |
| `og_title`, `og_description`, `og_image` | text | Open Graph |
| `twitter_title`, `twitter_description` | text | Twitter Card |
| `canonical_url` | varchar | Canonical override |
| `robots_meta` | varchar | `index,follow` / `noindex,nofollow` etc. |
| `sitemap_priority` | varchar | `0.0` – `1.0` |
| `exclude_from_sitemap` | tinyint | |
| `posted_at` | timestamp | Publish date (used by Posts) |
| `layout_file` | varchar | Template layout path |
| `active_site_template` | varchar | Which template this row was authored against |
| `is_home`, `is_shop`, `is_active`, `is_deleted` | tinyint | Flags |
| `require_login` | tinyint | Gate access |
| `original_link` | varchar | External canonical (for syndicated content) |
| `created_by` | bigint | FK → `users.id` |
| `position` | int | Sort order |
| `created_at`, `updated_at` | timestamp | |

## Related tables owned by Content

| Table | Purpose |
|---|---|
| `content_data` | Key/value sidecar — `key`, `value`, `rel_id`, `rel_type` |
| `content_fields` | Revision/version history of content_body |
| `content_fields_drafts` | Pending edits not yet published |
| `content_translations` | Per-language overrides for title / body / SEO |

## Related tables owned by other modules but referenced

| Table | Owner | Joins via |
|---|---|---|
| `categories_items` | Category module | `rel_type = 'content', rel_id = content.id` |
| `taggable` | Tags module | Polymorphic on `Modules\Content\Models\Content` |
| `media` | Media module | `rel_type = 'content', rel_id = content.id` |
| `url_redirects` | Url module | Old URL → new URL |

## What `microweber:install` does

The base install runs all Content migrations and creates:

- The `content` table + all related tables
- The homepage (Page row, `is_home = 1`)
- The blog parent page (`subtype = 'dynamic'`)
- The shop parent page (`is_shop = 1`)

If a template ships `mw_default_content.zip`, `TemplateInstaller` restores additional rows on top.

## Configuration

Content respects a small set of options (in the `options` table, group `content`):

| Option | Default | Purpose |
|---|---|---|
| `content_default_state` | `1` | Default `is_active` for new rows |
| `revision_history_enabled` | `1` | Save to `content_fields` on every update |
| `max_revisions_per_content` | `20` | Older revisions pruned beyond this |

Read via `get_option('option_key', 'content')`.

## Disabling / replacing

Content **cannot be disabled** — every typed content module would fail to autoload. To customize:

- Add a custom content type by registering a new model that extends `Content` and a global scope that filters `content_type`
- Hook into the lifecycle events (see `Modules/Content/Events/`) instead of overriding the model
- Add new shared columns via a migration in this module, then update the typed models' `$fillable` arrays
