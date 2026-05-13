# Page Module — Installation & Configuration

The Page module is one of Microweber's **core modules** — it ships with the framework, is registered automatically, and cannot be uninstalled without breaking the CMS.

## Prerequisites

- PHP ≥ 8.2 (matches the project root requirement)
- A working Microweber install (Laravel 11 base, MySQL/MariaDB or SQLite)
- The Content module (`Modules/Content/`) — Page extends `Modules\Content\Models\Content` and shares the `content` table
- Filament v5 admin (`filament/filament`) — for the admin resource
- Livewire v4 — for the admin form

## How Page registers itself

Page is loaded via the standard module pipeline that `nwidart/laravel-modules` drives:

1. **`Modules/Page/module.json`** declares the module:

    ```json
    {
        "name": "Page",
        "alias": "page",
        "description": "",
        "providers": [
            "Modules\\Page\\Providers\\PageServiceProvider"
        ]
    }
    ```

2. **`Modules/Page/Providers/PageServiceProvider.php`** is the entry point Laravel boots. It registers:
    - Config + views via the `BaseModuleServiceProvider` parent
    - Migrations from `Modules/Page/Database/migrations/`
    - API routes from `Modules/Page/routes/api.php`
    - The `PageResource` with the Filament admin panel
    - Database factories via `PageFactory`

3. **`composer.json`** PSR-4 autoload entry:

    ```json
    "Modules\\Page\\": "Modules/Page/"
    ```

No manual registration is needed — running `composer dump-autoload` after a fresh clone is sufficient.

## Database schema

Page does **not own a dedicated table** — it shares `content` with every other content type via single-table inheritance. The schema lives under `Modules/Content/database/migrations/`.

Relevant `content` columns for pages:

| Column | Type | Notes |
|---|---|---|
| `id` | `bigint` primary | |
| `content_type` | `varchar` | Always `'page'` for Page rows (enforced by `Page::__construct`) |
| `subtype` | `varchar` | `'static'` (default), `'dynamic'` (shop / blog landing) |
| `parent` | `bigint` | FK → `content.id` for hierarchical pages |
| `title` | `varchar` | Page title (also used as `<title>` if no `content_meta_title`) |
| `url` | `varchar` | URL slug; `link` attribute computes full URL via `content_link()` |
| `content_body` | `longtext` | HTML body — may embed `<module type="..."/>` markup |
| `layout_file` | `varchar` | Path to the layout file under `Templates/<template>/` |
| `is_home` | `tinyint` | `1` on the homepage row (exactly one per site) |
| `is_shop` | `tinyint` | `1` on the page that hosts the shop module |
| `is_active` | `tinyint` | `0` hides from public navigation + queries |
| `position` | `int` | Sort order |
| `content_meta_title`, `content_meta_description`, `content_meta_keywords` | text | SEO |
| `og_title`, `og_description`, `og_image` | text | Open Graph |
| `twitter_title`, `twitter_description` | text | Twitter Card |
| `canonical_url`, `robots_meta` | varchar | SEO directives |
| `sitemap_priority`, `exclude_from_sitemap` | varchar / tinyint | Sitemap control |

## What `microweber:install` does for pages

The base install creates at least:

- The homepage (`is_home = 1`) — title "Home", url "home"
- A blog page (`is_shop = 0`, `subtype = 'dynamic'`)
- A shop page (`is_shop = 1`)

If `install_default_content` is true AND the active template ships a `mw_default_content.zip` (Big2, Bootstrap, etc.), `MicroweberPackages\Install\TemplateInstaller` restores additional pages from that zip via `Modules\Restore\Restore`.

## Re-seeding pages

The `mw:big2-install-content` artisan command (Big2 template only) restores the full Big2 demo content — including all pages — via `TemplateInstaller`:

```bash
php artisan mw:big2-install-content
```

For generic page seeding, use the existing `mw:big2-demo-seed` (creates one page with all Big2 layouts embedded) or write your own Seeder using the `Page` factory.

## Configuration

Page has no module-specific config keys. Page behavior is driven by:

- Active template (`current_template` option) — determines available layouts under `Templates/<name>/`
- The Content module's options (`Modules/Content/config/`)
- Site-wide options like `language`, `timezone`, and `default_layout`

## Disabling / replacing

Page is **not safe to disable** — it provides the homepage, navigation, and most route resolution. To customize page behavior:

- Extend `Page` and register a new model binding (rare)
- Override the Filament `PageResource` from another module
- Hook into the `Modules\Content\Models\Content` model events (`creating`, `saving`, `deleted`) — they fire for pages too
