# Seo Module

The Seo module owns the **metadata pipeline** that produces every `<title>`, `<meta>`, Open Graph, and Twitter Card tag for the public-facing site. It does not own a database table — every SEO field lives on the `content` table (added by a [Content module](/modules/content/) migration). Seo's job is to **read those fields, layer site-wide defaults underneath, sanitize, and emit HTML**.

> **TL;DR** — Seo is a single service (`SeoMetadataService`, registered as a singleton) plus five Blade directives (`@seoMetaTags`, `@seoTitle`, `@seoDescription`, `@seoOpenGraph`, `@seoTwitterCard`). Drop `@seoMetaTags($content)` into your master layout's `<head>` and every page renders the right tags automatically — falling back through OG/meta/content fields to the site-wide options when a per-page value is absent.

---

## What this module owns

| Concern | Surface |
|---|---|
| Metadata generation pipeline | `Services\SeoMetadataService` |
| Five Blade directives for `<head>` rendering | `@seoMetaTags`, `@seoTitle`, `@seoDescription`, `@seoOpenGraph`, `@seoTwitterCard` |
| Fallback chains (per-page → content → site-wide) | `SeoMetadataService::get*()` methods |
| Sitemap-data accessor for the [Sitemap module](/modules/sitemap/) | `SeoMetadataService::getSitemapData()` |
| XSS escaping + meta-text sanitization | `SeoMetadataService::sanitizeMetaText()` + `escapeHtml()` |
| Per-page robots directive logic | `SeoMetadataService::getRobotsMeta()` |
| 26 unit tests covering all paths | `Tests/Unit/SeoMetadataServiceTest.php` |

What this module does **NOT** own:

- The `content` table — owned by [Content module](/modules/content/). Seo adds 13 columns via a Content-module migration but doesn't define a model.
- The Filament form fields for SEO — those live on `ContentResource` in the Content module (under collapsible sections: Basic SEO, Open Graph, Twitter Card, Advanced SEO, Sitemap).
- `/sitemap.xml` — owned by [Sitemap module](/modules/sitemap/) (which calls into `SeoMetadataService::getSitemapData()` for each row).
- `/robots.txt` — not implemented in either Seo or Sitemap module today. Seo only provides per-page `robots_meta` (`<meta name="robots">`) and a site-wide default option.
- Translation infrastructure — multilanguage SEO fields are translated via the Content module's `HasMultilanguageTrait`.
- Option storage — the 7 site-wide defaults are written via the Option facade (admin Settings UI); Seo only reads.

---

## Architectural fact: zero models, all-service, Blade-directive surface

Seo deliberately ships:

- **No Eloquent models.** The SEO fields are columns on `content` (via the Content-owned migration `2025_03_22_000001_add_seo_metadata_fields_to_content.php`). Reading SEO data means reading the Content row.
- **No HTTP routes.** `routes/web.php` exists but every route is commented (placeholder for future admin/API endpoints). The module's public surface is the Blade-directive set + the singleton service.
- **One service class.** `SeoMetadataService` is 621 lines, registered as a singleton, with ~18 public methods grouped into "data retrieval", "site-wide defaults", and "HTML rendering".

This is the lean, library-style design: drop the directives into your layout, configure site-wide defaults in admin Settings, and per-page SEO is editable from the existing Content Filament resource. No bespoke admin page, no separate routes, no listener wiring.

---

## The data model — 13 columns on the `content` table

Added by `Modules/Content/database/migrations/2025_03_22_000001_add_seo_metadata_fields_to_content.php`:

| Column | Type | Translatable | Purpose |
|---|---|---|---|
| `content_meta_title` | text | yes | per-page `<title>` override |
| `content_meta_description` | text | yes | per-page `<meta name="description">` |
| `content_meta_keywords` | text | yes | per-page `<meta name="keywords">` |
| `og_title` | varchar(500) | yes | Open Graph title (Facebook + LinkedIn) |
| `og_description` | text | yes | OG description |
| `og_image` | varchar | no | OG image URL |
| `og_type` | varchar(50) | no | `website` / `article` / `product` |
| `twitter_title` | varchar(500) | yes | Twitter Card title |
| `twitter_description` | text | yes | Twitter Card description |
| `twitter_image` | varchar | no | Twitter Card image URL |
| `twitter_card` | varchar(50) | no | `summary` / `summary_large_image` |
| `canonical_url` | varchar(1000) | no | custom canonical (else `content_link()` is used) |
| `robots_meta` | varchar(100) | no | `index, follow` / `noindex, nofollow` / etc. |
| `sitemap_priority` | decimal(2,1) | no | 0.0–1.0, default 0.5 |
| `sitemap_changefreq` | varchar(20) | no | `always` / `hourly` / `daily` / … / `never` |
| `exclude_from_sitemap` | boolean | no | default false |

Indexes added by the same migration:

- `idx_content_exclude_sitemap` on `exclude_from_sitemap`
- `idx_content_active_sitemap` on `(is_active, exclude_from_sitemap)`

Both indexes accelerate the [Sitemap module's](/modules/sitemap/) bulk query.

---

## The fallback chains

When a per-page field is empty, `SeoMetadataService` walks a chain of fallbacks. The three most important chains:

```
Title:        og_title → content_meta_title → content.title → website_title option

Description:  og_description → content_meta_description → content.description → website_description option

Twitter image: twitter_image → og_image → content.thumbnail() → website_twitter_image option
```

The chain stops at the first non-empty value. Empty strings count as empty; explicit `null` counts as empty. This means an admin who sets `og_title = ''` (cleared the field) still gets the `content_meta_title` fallback automatically.

Robots directive uses a different chain — explicit guards take priority over user-set values:

```
robots:
  if content.is_active === false || content.is_deleted === true
    → 'noindex, nofollow'        ← guard wins regardless of user-set value
  else if content.robots_meta is set
    → use content.robots_meta
  else
    → website_robots_meta option (default 'index, follow')
```

This guarantees deactivated or soft-deleted content can never accidentally be indexed.

---

## The five Blade directives

Registered in `SeoServiceProvider::registerBladeDirectives()`. Drop them into your `<head>` in your master layout:

```blade
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @seoMetaTags($content)
</head>
```

`@seoMetaTags` emits the full set: `<title>`, description, keywords, robots, canonical, all OG `<meta>` tags, all Twitter Card tags. The other four directives are subset emitters — use them if you want fine-grained control over which sections render.

| Directive | Emits |
|---|---|
| `@seoMetaTags($content)` | everything below combined |
| `@seoTitle($content)` | just `<title>...</title>` |
| `@seoDescription($content)` | just `<meta name="description">` |
| `@seoOpenGraph($content)` | all `<meta property="og:*">` tags |
| `@seoTwitterCard($content)` | all `<meta name="twitter:*">` tags |

`$content` is the current page's Content model. If you call any directive with `null` (e.g. on a route that's not backed by a Content row), the service returns the site-wide defaults.

---

## Surfaces

| Surface | Where | Audience |
|---|---|---|
| Per-page SEO form (5 collapsible sections) | `Modules/Content/Filament/Admin/ContentResource.php` | staff |
| Site-wide defaults (7 option keys) | admin Settings page | staff |
| `<head>` rendering on every page | five Blade directives | end visitors |
| Sitemap-data accessor | `SeoMetadataService::getSitemapData()` | Sitemap module |
| 26 unit tests | `Tests/Unit/SeoMetadataServiceTest.php` | CI |

---

## Where to next

- [Installation](./installation.md) — service provider, Blade-directive registration, site-wide option keys, sibling-module dependencies.
- [Usage](./usage.md) — drop the directives into your layout, per-page SEO via Filament, multilanguage, robots, sitemap inclusion, canonical handling.
- [API](./api.md) — `SeoMetadataService` (18 public methods), constants, fallback-chain order, Blade directives.
- [Examples](./examples.md) — minimal `<head>` integration, custom Open Graph image per content type, AI-generated meta from content body, structured-data injection alongside.
- [Troubleshooting](./troubleshooting.md) — meta tags don't render, fallback chain returning the wrong value, robots `noindex` on deactivated content, sitemap_priority not applied, multilanguage meta missing.
