# Installation

Sitemap ships as part of Microweber core. No `composer require`. The module is stateless and request-driven, so installation is just provider boot + route loading.

---

## Service provider

`Modules\Sitemap\Providers\SitemapServiceProvider` auto-registers via `module.json`. It extends `BaseModuleServiceProvider` (Microweber convention) and:

- Loads `routes/web.php` (6 routes).
- Loads Blade views under the `modules.sitemap::` namespace (2 templates: `index.blade.php`, `items.blade.php`).
- Calls the base provider's `loadMigrationsFrom()` even though the `database/` directory is empty — harmless no-op kept for forward compatibility.

No singletons are registered. The module's logic is per-request (controllers + helpers traits), not per-process.

---

## Route loading

| File | Mount point | Loaded by |
|---|---|---|
| `routes/web.php` | `/sitemap.xml`, `/sitemap.xml/categories`, `/sitemap.xml/products`, `/sitemap.xml/posts`, `/sitemap.xml/pages`, `/sitemap.xml/tags` | `SitemapServiceProvider::boot()` (web middleware group) |

All six routes use the `web` middleware group, meaning they share session/cookie state with the rest of the site. There is no rate limiting or authentication — these are public endpoints intended for search-engine crawlers.

If you want to restrict access (e.g. only allow specific user agents or IPs during a soft-launch), add a custom middleware to the route definitions in your app provider.

---

## Migrations

Sitemap owns **zero migrations**. The three sitemap-relevant columns (`sitemap_priority`, `sitemap_changefreq`, `exclude_from_sitemap`) are added to the `content` table by the **Seo module's** migration:

```
Modules/Content/database/migrations/2025_03_22_000001_add_seo_metadata_fields_to_content.php
```

When you run `php artisan migrate` (or `composer install` on a fresh install), the Content migration adds them automatically. No Sitemap-specific migrate step.

Verify after install:

```bash
php artisan tinker --execute='dd([
    "priority"   => \Schema::hasColumn("content", "sitemap_priority"),
    "changefreq" => \Schema::hasColumn("content", "sitemap_changefreq"),
    "exclude"    => \Schema::hasColumn("content", "exclude_from_sitemap"),
]);'
# Expected: all three true
```

---

## Configuration

There is **no `config/sitemap.php`** and **no option keys**. The module is stateless and behavioural defaults are hardcoded:

| Behaviour | Where it's set |
|---|---|
| Five sub-sitemap split (categories/products/posts/pages/tags) | `views/index.blade.php` |
| `text/xml` content type | controller response builder |
| Cache file path pattern | `mw_cache_path() . hostname . '_<type>_sitemap.xml'` |
| 3-hour cache TTL | `SitemapHelpersTrait::needToUpdateSitemap()` — **currently disabled** (always returns true) |
| Active-only content filter | `Content::active()` scope (Content module convention) |
| Multilanguage `hreflang` emission | branches on `MultilanguageHelpers::multilanguageIsEnabled()` |

If you need to change any of these, the path is fork-and-edit, not config-and-deploy. The module's design ethos is "minimum surface, maximum convention".

---

## Dependencies on other modules

| Module | Why Sitemap needs it |
|---|---|
| **[Content](/modules/content/)** | the `Content` model + `Page` + `Post` + `Product` + `Category` models that the controllers query; the `Content::active()` scope; the `content_link()` and `content_tags()` helpers |
| **[Seo](/modules/seo/)** | the migration that adds `sitemap_priority`, `sitemap_changefreq`, `exclude_from_sitemap` to the `content` table; the Filament Sitemap Settings section on the Content resource form. Note: Sitemap does NOT currently use `SeoMetadataService::getSitemapData()` — see [Overview](./#dependency-on-the-seo-module--current-state) for the parallel-logic note. |
| **Multilanguage** *(optional)* | when enabled, `MultilanguageHelpers::multilanguageIsEnabled()` returns true and `multilanguage_get_all_*` helpers emit `<xhtml:link rel="alternate" hreflang="...">` rows |
| **Settings** | owns `/robots.txt` (admin Custom Tags page edits `options.website.robots_txt` raw content) — Sitemap does NOT auto-write to it |

If the Multilanguage module is disabled, the sitemap still works — it just emits one `<loc>` per row without `<xhtml:link>` siblings.

---

## Adding `Sitemap:` to robots.txt

The Sitemap module does **not** auto-append a `Sitemap:` directive to `/robots.txt`. You need to add it manually:

1. Open admin → Settings → Custom Tags page (or whatever route surfaces `options.website.robots_txt`).
2. Add the line:
   ```
   Sitemap: https://your-site.com/sitemap.xml
   ```
3. Save.

Or programmatically:

```php
$current = \MicroweberPackages\Option\Models\Option::getValue('robots_txt', 'website');
$updated = ($current ?? '') . "\nSitemap: " . url('/sitemap.xml');
\MicroweberPackages\Option\Models\Option::setValue('robots_txt', $updated, 'website');
```

Some search engines (Google in particular) auto-discover `/sitemap.xml` even without the robots.txt directive, but listing it explicitly is documented best practice and accelerates discovery on smaller crawlers.

---

## Sanity check after install

```bash
# All 6 routes resolve
curl -I http://your-site/sitemap.xml
curl -I http://your-site/sitemap.xml/categories
curl -I http://your-site/sitemap.xml/products
curl -I http://your-site/sitemap.xml/posts
curl -I http://your-site/sitemap.xml/pages
curl -I http://your-site/sitemap.xml/tags

# Expected for each: HTTP/1.1 200 OK + Content-Type: text/xml

# Index XML is well-formed
curl -s http://your-site/sitemap.xml | xmllint --noout -
# Expected: no output (valid XML)

# Per-type XML is well-formed
curl -s http://your-site/sitemap.xml/products | xmllint --noout -
```

If any 404s, check that `SitemapServiceProvider` is in the discovered providers list (`php artisan package:discover --ansi`).

If XML is malformed, check the controller's cache file — it's written before the response. Delete stale cache files at `storage/cache/*_sitemap.xml` and reload.

---

## Performance considerations

Because the TTL check is currently disabled (`needToUpdateSitemap()` always returns `true`), every `/sitemap.xml/<type>` request re-queries the database and re-renders the Blade template. For sites with many content rows, this means:

- Each route runs an `active()` scope query over the relevant table (`content` for pages/posts/products; `categories` for categories; `content_tags` join for tags).
- Multilanguage `hreflang` emission multiplies the query — it runs once per active locale.
- Filesystem write happens on every request (write-through, no read-from-cache).

For sites with > 10k indexed rows, mitigate with:

- A reverse-proxy cache (Cloudflare, Varnish, nginx `proxy_cache`) keyed on the URL + a short TTL (5-15 min).
- Or fix `needToUpdateSitemap()` to honour the file mtime (the documented 3-hour TTL is the intended behaviour).

Search engines crawl `/sitemap.xml` infrequently (typically once per hour-to-day), so even a 5-minute proxy cache eliminates 99% of regeneration.
