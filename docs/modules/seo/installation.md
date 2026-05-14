# Installation

Seo ships as part of Microweber core. No `composer require`. This page documents what the module pulls in, where settings live, and the sibling-module dependencies.

---

## Service provider

`Modules\Seo\Providers\SeoServiceProvider` auto-registers via `module.json`. It:

- Registers the `SeoMetadataService` singleton in the container — `app(\Modules\Seo\Services\SeoMetadataService::class)` always returns the same instance.
- Calls `registerBladeDirectives()` to attach the five `@seo*` directives to the Blade compiler.
- Loads `routes/web.php` (currently all commented — placeholder for future endpoints).

The singleton registration is important: per-request, the service caches the resolved site-wide options the first time they're read so subsequent meta-tag renders within the same request don't re-hit the Option facade. Don't replace the binding with a non-singleton unless you have a specific reason.

---

## No active routes

`routes/web.php` ships with commented placeholder route groups for `/api/seo/*` and `/admin/seo/*`. They're reserved for future API and admin pages but every line is commented today.

The module's only "endpoints" are the Blade directives in your layout's `<head>`. There is no `/seo/admin` URL.

---

## Migrations

Seo owns **zero migrations of its own**. The 13 SEO columns are added to the `content` table by a migration that ships with the Content module:

```
Modules/Content/database/migrations/2025_03_22_000001_add_seo_metadata_fields_to_content.php
```

When you run `php artisan migrate` (or `composer install` for a fresh install), the Content module's migrations get the columns + indexes onto `content`. There is no Seo-module migrate step.

If you're upgrading an existing install that didn't run migrations recently:

```bash
php artisan migrate --path=Modules/Content/database/migrations
```

Then verify:

```bash
php artisan tinker --execute='dd(\Schema::hasColumn("content", "og_image"));'
# Expected: true
```

---

## Site-wide option keys

These are stored via the Option facade (group `website`) and edited from admin Settings:

| Option key | Default | Effect |
|---|---|---|
| `website_title` | `My Site` | site-wide default `<title>` when content doesn't override |
| `website_description` | `''` | site-wide default `<meta name="description">` |
| `website_keywords` | `''` | site-wide default `<meta name="keywords">` |
| `website_robots_meta` | `index, follow` | site-wide default robots directive |
| `website_og_image` | `null` | site-wide default OG image URL (fallback) |
| `website_twitter_image` | `null` | site-wide default Twitter Card image URL (fallback) |
| `website_twitter_site` | `null` | Twitter handle for `<meta name="twitter:site">` (include the `@`) |

Set programmatically:

```php
\MicroweberPackages\Option\Models\Option::setValue('website_twitter_site', '@microweber', 'website');
```

Read by `SeoMetadataService::getDefaultMetadata()` once per request and cached for that request's lifetime.

---

## Filament integration

Per-page SEO fields are NOT in a dedicated Seo admin page. They're 5 collapsible sections inside the **Content module's** `ContentResource` form:

1. **Basic SEO** — `content_meta_title`, `content_meta_description`, `content_meta_keywords` (all translatable)
2. **Open Graph (Facebook)** — `og_title`, `og_description`, `og_image`, `og_type`
3. **Twitter Card** — `twitter_title`, `twitter_description`, `twitter_image`, `twitter_card`
4. **Advanced SEO** — `canonical_url`, `robots_meta`
5. **Sitemap Settings** — `exclude_from_sitemap`, `sitemap_priority`, `sitemap_changefreq`

If the AI module is installed, an "AI Generate" action appears next to `og_title` that produces a title + description from the content body via the AI module's text-generation provider.

---

## Dependencies on other modules

| Module | Why Seo needs it |
|---|---|
| **[Content](/modules/content/)** | owns the `content` table where all SEO fields live; provides the migration; provides the `HasMultilanguageTrait` for translatable fields; provides the `ContentResource` Filament form where SEO is edited |
| **Option** | site-wide defaults (`website_*` keys) read via the Option facade |
| **Multilanguage** | per-locale SEO fields work via `HasMultilanguageTrait` on the Content model (already wired) |
| **Media** *(optional)* | `content.thumbnail()` is used as a Twitter image fallback when `twitter_image` and `og_image` are both unset |
| **[Sitemap](/modules/sitemap/)** | not a Seo dependency, but Seo provides the data accessor (`getSitemapData()`) that the Sitemap module consumes |

If the Multilanguage module is disabled, the translatable fields still work — they just store one value (the active locale) without locale-keyed storage. The Content model's `HasMultilanguageTrait` degrades gracefully.

---

## Sanity check after install

```bash
# SeoMetadataService resolves as singleton
php artisan tinker --execute='
    $a = app(\Modules\Seo\Services\SeoMetadataService::class);
    $b = app(\Modules\Seo\Services\SeoMetadataService::class);
    echo $a === $b ? "singleton OK" : "NOT singleton - check provider";
'

# Blade directive is registered
php artisan tinker --execute='
    $blade = app("blade.compiler");
    dd(method_exists($blade, "getCustomDirectives") ? array_keys($blade->getCustomDirectives()) : "n/a");
'
# Expected output contains: "seoMetaTags", "seoTitle", "seoDescription", "seoOpenGraph", "seoTwitterCard"

# Site-wide defaults are reachable
php artisan tinker --execute='
    $svc = app(\Modules\Seo\Services\SeoMetadataService::class);
    dd($svc->getDefaultMetadata());
'
# Expected: array with title, description, keywords, robots_meta, etc.
```

If any of these fail, confirm `SeoServiceProvider` is loaded — `php artisan package:discover` should pick it up automatically; if it doesn't, ensure `module.json → providers` lists it.

---

## Performance notes

The metadata service is request-singleton, so multiple `@seo*` directives in the same render only hit the Content row + Option lookup once. The Option facade itself caches via the Option module's own per-request cache.

For high-traffic sites:

- The Option-facade cache is in-memory (per-request only). For multi-request caching, use Microweber's standard Option-module caching (the Option module already provides Redis/file-backed caching where configured).
- Per-page SEO fields are read from the Content row that's already loaded for the current page render. There is no extra database query introduced by the directives.

There are no remote calls (no API to Facebook/Twitter for verification — the meta tags they emit are read-only).
