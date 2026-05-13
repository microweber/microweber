# Category Module — Installation

The Category module is a **core module** — ships with Microweber, registered automatically, hard dependency of Page/Post/Product.

## Prerequisites

- PHP ≥ 8.2
- Laravel 11 base
- Filament v5
- Content module (`Modules/Content/`) — for the polymorphic join

## Registration

Standard module pipeline:

1. **`Modules/Category/module.json`** — declares the module + provider
2. **`Modules/Category/Providers/CategoryServiceProvider.php`** — bootstraps config, views, migrations, API routes, admin resource
3. **`composer.json`** PSR-4: `"Modules\\Category\\": "Modules/Category/"`

Run `composer dump-autoload` after a fresh clone.

## Database schema

### `categories` table

| Column | Type | Notes |
|---|---|---|
| `id` | bigint primary | |
| `title` | varchar | Display name |
| `description` | text | Public-facing description |
| `content_meta_description` | text | SEO meta description |
| `parent_id` | bigint | FK → `categories.id` (0 for top-level) |
| `rel_type` | varchar | Usually `'content'` — what the category attaches to |
| `is_hidden` | tinyint | `1` hides from menus + public lists |
| `position` | int | Sort order within parent |
| `created_at`, `updated_at` | timestamp | |

### `categories_items` table

Polymorphic many-to-many join between any content type and categories.

| Column | Type | Notes |
|---|---|---|
| `id` | bigint primary | |
| `parent_id` | bigint | FK → `categories.id` (the category) |
| `rel_type` | varchar | Usually `'content'` |
| `rel_id` | bigint | FK → `content.id` (the post/page/product) |

The unique key is `(parent_id, rel_type, rel_id)` — a row can be in a category at most once.

### `categories_translations` table

Per-locale overrides for `title` + `description` + `content_meta_description`:

| Column | Type | Notes |
|---|---|---|
| `id` | bigint primary | |
| `category_id` | bigint | FK → `categories.id` |
| `locale` | varchar(5) | e.g. `'en'`, `'es'`, `'de'` |
| `title`, `description`, `content_meta_description` | text | Translated values |

## What `microweber:install` does

Creates an empty `categories` table. Templates that ship `mw_default_content.zip` (Big2, Bootstrap) may include sample categories.

## Slug generation

Category URLs are slugified from `title` automatically by the controller on save. There is **no `url` column** — slugs are derived live from the title via `Str::slug($title)`. Uniqueness is enforced within the parent only (siblings under the same parent must have unique slugs).

## Configuration

Category has no module-specific config keys. Behavior is driven by:

- The active template's category-tree CSS / blade
- The Menu module's options (whether category nodes are eligible for menu items)

## Disabling / replacing

Category cannot be disabled — Page/Post/Product all reference the `categories_items` table for their archives and admin filters. To customize:

- Extend `Category` and bind the subclass into the container
- Override `CategoryResource` from another module
- Hook the standard Eloquent events (`Category::created`, `Category::deleted`) for cache invalidation
