# Sitemap Module

The Sitemap module generates and serves `/sitemap.xml` and five content-type sub-sitemaps for search-engine crawlers. It is **request-driven and stateless** — no models, no migrations, no admin UI. Each request to `/sitemap.xml` (or one of the five `/sitemap.xml/<type>` URLs) renders a Blade-templated XML response, optionally cached to disk.

> **TL;DR** — Six GET routes: `/sitemap.xml` returns a **sitemap index** pointing at 5 sub-sitemaps (`categories`, `products`, `posts`, `tags`, `pages`). Each sub-sitemap is built by `SitemapController` from active, non-soft-deleted content rows (with the [Seo module's](/modules/seo/) `exclude_from_sitemap` flag honoured via the underlying query). Output is `Content-Type: text/xml`, sitemap protocol 0.9, multilanguage `hreflang` links emitted when the Multilanguage module is enabled.

---

## What this module owns

| Concern | Surface |
|---|---|
| Six XML endpoints | `routes/web.php` + `SitemapController` |
| Sitemap-index template | `resources/views/index.blade.php` (5 sub-sitemap links) |
| URL-set template | `resources/views/items.blade.php` (per-type sitemap rows) |
| Per-type fetchers | `SitemapHelpersTrait` (11 public methods) |
| Multilanguage `hreflang` emission | `fetch*Links()` helpers branch on `MultilanguageHelpers::multilanguageIsEnabled()` |
| Filesystem cache writer | `mw_cache_path() . hostname . '_<type>_sitemap.xml'` |
| 12 unit tests | `Tests/Unit/SitemapControllerTest.php` + `SitemapRoutesTest.php` |

What this module does **NOT** own:

- The `content` table or any data — owned by the [Content module](/modules/content/) and queried directly via Eloquent `active()` scopes.
- The `sitemap_priority`, `sitemap_changefreq`, `exclude_from_sitemap` columns — added to the `content` table by a [Seo module](/modules/seo/) migration; surfaced in the Content Filament form's Sitemap Settings section.
- The `/robots.txt` file — owned by the **Settings module**; admin Custom Tags page edits raw `robots_txt` option content. Sitemap does **not** auto-append a `Sitemap: <url>` directive there — that's a manual step (see [usage](./usage.md#adding-sitemap-to-robotstxt)).
- A Filament admin page — there is no Sitemap admin UI. Sitemap behaviour is configured per-content-row from the Seo collapsible section of the Content Filament resource.
- Search-engine pinging — the module does not call Google/Bing webmaster APIs on regenerate. No event fires when content saves.
- Sitemap-protocol chunking — the module emits a single sub-sitemap per content type without splitting at the 50k-URL ceiling (see [troubleshooting](./troubleshooting.md#sitemap-exceeds-50k-urls-protocol-limit)).

---

## Architectural fact: stateless, request-driven, zero models

The Sitemap module is intentionally minimal:

- **Zero migrations** — all sitemap-relevant columns live on `content` (added by the Seo module's migration).
- **Zero models** — every query reads from `Content`, `Page`, `Post`, `Product`, `Category` models owned by other modules.
- **Zero events** — no listeners fire on content save. Sitemap is regenerated lazily on the next `/sitemap.xml/*` request.
- **Zero admin pages** — per-content-row sitemap behaviour is set from the Content Filament resource's Seo section. Site-wide sitemap behaviour is hardcoded (5 sub-sitemap split).

The trade-off: regenerating a multi-thousand-row sitemap on every request would be costly. The module mitigates this with **filesystem caching** — but the cache-validity check (`needToUpdateSitemap()`) currently always returns `true`, so the cache is effectively a write-through scratchpad rather than a TTL'd cache. See [troubleshooting](./troubleshooting.md#sitemap-regenerates-on-every-request) for the workaround.

---

## The six routes

| Route | Returns | Built by |
|---|---|---|
| `GET /sitemap.xml` | sitemap-index XML pointing at the 5 sub-sitemaps | `SitemapController::index()` + `views/index.blade.php` |
| `GET /sitemap.xml/categories` | URL-set XML for active categories | `SitemapController::categories()` + `views/items.blade.php` |
| `GET /sitemap.xml/products` | URL-set XML for active products | `SitemapController::products()` |
| `GET /sitemap.xml/posts` | URL-set XML for active posts | `SitemapController::posts()` |
| `GET /sitemap.xml/pages` | URL-set XML for active pages | `SitemapController::pages()` |
| `GET /sitemap.xml/tags` | URL-set XML for page tags (from `content_tags()` helper) | `SitemapController::tags()` |

All six are wired in `routes/web.php` under the `web` middleware group. All return `Content-Type: text/xml`.

The index file is a **sitemap-index** (`<sitemapindex>`) — not a `<urlset>` — so Google/Bing crawlers know to follow the five inner sitemap links and fetch each as a separate URL-set.

---

## The URL-set shape

Each sub-sitemap row carries:

```xml
<url>
    <loc>https://example.com/products/awesome-widget</loc>
    <lastmod>2026-05-14</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
    <xhtml:link rel="alternate" hreflang="de" href="https://example.com/de/produkte/awesome-widget" />
    <xhtml:link rel="alternate" hreflang="fr" href="https://example.com/fr/produits/awesome-widget" />
</url>
```

- `<loc>` — the row's canonical permalink (from `Content::link()`)
- `<lastmod>` — `updated_at` formatted Y-m-d
- `<changefreq>` — from `content.sitemap_changefreq` (nullable; if null, the tag is omitted)
- `<priority>` — from `content.sitemap_priority` (default 0.5; if null, the tag is omitted)
- `<xhtml:link rel="alternate" hreflang="...">` — emitted once per active locale when the Multilanguage module is enabled

If `content.exclude_from_sitemap = true`, the row is filtered out at the query level (the `Content` active scope respects this).

---

## Dependency on the Seo module — current state

The [Seo module](/modules/seo/) exposes `SeoMetadataService::getSitemapData($content)` which returns the canonical `loc / lastmod / changefreq / priority` tuple with all the right fallbacks (per-content-type default changefreq, default priority of 0.5, etc.).

**Sitemap does not currently call `getSitemapData()`.** It implements parallel logic in `SitemapHelpersTrait` — fetching the same columns directly. The two paths produce identical results for the supported content types, but the duplication means future Seo changes (e.g. a new per-content-type priority rule) don't propagate automatically.

This is a known refactoring target. Until then, treat the two paths as parallel but kept-in-sync-by-convention.

---

## Surfaces

| Surface | Where | Audience |
|---|---|---|
| `/sitemap.xml` + 5 sub-sitemaps | `routes/web.php` | search engines, agencies |
| Per-row sitemap behaviour | Content Filament form → Sitemap Settings section (owned by Content/Seo) | staff |
| `is_active`, `is_deleted`, `exclude_from_sitemap` filters | `Content::active()` scope | end-user-invisible |
| Filesystem cache | `mw_cache_path() . hostname . '_<type>_sitemap.xml'` | infrastructure |

---

## Where to next

- [Installation](./installation.md) — service provider, routes, dependencies, `Sitemap:` line in robots.txt.
- [Usage](./usage.md) — accessing the six routes, per-row sitemap settings via Content Filament, multilanguage hreflang, cache invalidation, search-engine submission.
- [API](./api.md) — `SitemapController` (7 actions), `SitemapHelpersTrait` (11 methods), the URL-set Blade template variables, the filesystem cache path format.
- [Examples](./examples.md) — verify the XML output, custom `sitemap_priority` per content type, add a sitemap entry to robots.txt, prevent search-engine indexing of a section.
- [Troubleshooting](./troubleshooting.md) — sitemap regenerates on every request (always-true cache check), missing content, multilanguage `hreflang` missing, 50k URL ceiling, 404 on `/sitemap.xml/<type>`.
