# Usage

How the Sitemap module is consumed: accessing the six routes, configuring per-row sitemap behaviour from the admin, handling multilanguage `hreflang`, invalidating the cache, submitting to search engines.

---

## Accessing the six routes

All six are GET-only, public, return `Content-Type: text/xml`:

```bash
# The sitemap index — start here for search-engine submission
curl https://your-site.com/sitemap.xml

# The five sub-sitemaps
curl https://your-site.com/sitemap.xml/categories
curl https://your-site.com/sitemap.xml/products
curl https://your-site.com/sitemap.xml/posts
curl https://your-site.com/sitemap.xml/pages
curl https://your-site.com/sitemap.xml/tags
```

The index returns a `<sitemapindex>` element with five `<sitemap>` children — each `<loc>` is the absolute URL of one sub-sitemap. Each sub-sitemap returns a `<urlset>` element with up to N `<url>` children (no chunking — see [troubleshooting](./troubleshooting.md#sitemap-exceeds-50k-urls-protocol-limit) for the limit).

---

## Per-row sitemap settings via Filament

The three per-row columns (`sitemap_priority`, `sitemap_changefreq`, `exclude_from_sitemap`) are edited from the **Content module's** Filament resource, not from a Sitemap admin page. The Sitemap Settings section is one of five collapsible sections at the bottom of the Edit Content form.

| Field | Default | Effect |
|---|---|---|
| Exclude from sitemap | off | when on, the row is filtered out of the sub-sitemap query entirely |
| Priority | 0.5 | the row's `<priority>` value (0.0–1.0, decimal) |
| Change frequency | (per content type default) | the row's `<changefreq>` value (`always` / `hourly` / `daily` / `weekly` / `monthly` / `yearly` / `never`) |

For bulk operations (set priority on every product to 0.8):

```php
\Modules\Product\Models\Product::query()->update(['sitemap_priority' => 0.8]);
```

Or bulk-exclude an entire section:

```php
\Modules\Page\Models\Page::query()
    ->where('parent', $hiddenSectionId)
    ->update(['exclude_from_sitemap' => true]);
```

The `idx_content_active_sitemap` index covers `(is_active, exclude_from_sitemap)` so even on a large `content` table these queries run fast.

---

## Multilanguage `hreflang` emission

When the Multilanguage module is enabled (`MultilanguageHelpers::multilanguageIsEnabled()` returns true), each sub-sitemap fetcher branches into the multilang code path:

```php
public function fetchProductsLinks(): array
{
    return $this->isMutilangOn()
        ? $this->fetchMultilangContentByType('product')
        : $this->fetchNotMutilangProducts();
}
```

The multilang path uses `multilanguage_get_all_content_links()` from the Multilanguage module, which returns one row per (content_id × locale) pair. The Blade template emits an `<xhtml:link>` for every locale:

```xml
<url>
    <loc>https://example.com/products/widget</loc>
    <lastmod>2026-05-14</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
    <xhtml:link rel="alternate" hreflang="en" href="https://example.com/products/widget"/>
    <xhtml:link rel="alternate" hreflang="de" href="https://example.com/de/produkte/widget"/>
    <xhtml:link rel="alternate" hreflang="fr" href="https://example.com/fr/produits/widget"/>
</url>
```

If Multilanguage is disabled, the row emits without any `<xhtml:link>` siblings. The `<urlset xmlns:xhtml="...">` namespace is declared in the template regardless — harmless if unused.

---

## Cache behaviour

The module writes each generated sub-sitemap to disk at:

```
{mw_cache_path}{hostname}_<type>_sitemap.xml
```

E.g. `storage/cache/example.com_products_sitemap.xml`.

**However**, `SitemapHelpersTrait::needToUpdateSitemap($file)` currently returns `true` for all inputs — the documented 3-hour TTL check is short-circuited. Effectively, the cache file is **write-only** (rewritten on every request) and never read.

Consequences:

- **For correctness**: every request reflects the freshest content. Good.
- **For performance**: every request hits the database + Blade. For large sites this is a real cost.

If you need a working cache, fix `needToUpdateSitemap()` to honour the file mtime:

```php
// In SitemapHelpersTrait
public function needToUpdateSitemap($sitemapFileLocation): bool
{
    if (! file_exists($sitemapFileLocation)) {
        return true;
    }
    return filemtime($sitemapFileLocation) < strtotime('-3 hours');
}
```

This is a one-line fix that activates the documented 3-hour TTL.

Alternatively, layer a reverse-proxy cache (Cloudflare, Varnish, nginx) in front. For a 5-minute proxy TTL on a typical search-engine crawl cadence, regeneration drops from ~50/day to ~10/day per route.

---

## Invalidating the cache

If you've activated the 3-hour TTL and need to force a regeneration (e.g. after a bulk content edit):

```bash
# Delete all cached sitemap files for the current host
rm storage/cache/$(hostname)_*_sitemap.xml

# Or all sitemap caches (multi-tenant)
rm storage/cache/*_sitemap.xml
```

The next `/sitemap.xml/<type>` request will regenerate from scratch.

For programmatic invalidation from a content-save listener:

```php
namespace App\Listeners;

use Modules\Content\Events\ContentWasSaved;

class InvalidateSitemapOnContentSave
{
    public function handle(ContentWasSaved $event): void
    {
        $hostname = app('mw')->url_manager->hostname();
        $cacheDir = mw_cache_path();
        foreach (['categories', 'products', 'posts', 'pages', 'tags'] as $type) {
            $file = $cacheDir . $hostname . '_' . $type . '_sitemap.xml';
            if (is_file($file)) {
                @unlink($file);
            }
        }
    }
}
```

Be aware: the Content module's exact event name may differ across versions (Microweber has used various conventions). Confirm via `grep -r "event(" Modules/Content/Models/Content.php` before relying on a specific event name.

---

## Submitting to search engines

The module does **not** ping Google / Bing / Yandex on regenerate. There is no automatic submission. To inform crawlers of your sitemap:

### Google Search Console

1. Sign in to https://search.google.com/search-console
2. Property → Sitemaps → "Add a new sitemap"
3. Enter `sitemap.xml` (relative path).
4. Submit.

Google rechecks the sitemap periodically (typically daily for healthy sites, less for low-traffic).

### Bing Webmaster Tools

1. Sign in to https://www.bing.com/webmasters
2. Sitemaps → "Submit sitemap"
3. Enter the full URL (`https://your-site.com/sitemap.xml`).
4. Submit.

### Automatic pinging (optional, app-level)

If you want automatic ping on a regenerate-after-content-save (e.g. for a high-update news site), add this to your content-save listener:

```php
use Illuminate\Support\Facades\Http;

$sitemapUrl = url('/sitemap.xml');
Http::async()->get('https://www.google.com/ping', ['sitemap' => $sitemapUrl]);
Http::async()->get('https://www.bing.com/ping', ['sitemap' => $sitemapUrl]);
```

Google deprecated their ping endpoint in 2023 in favour of Search Console — the call above is a no-op but harmless. Bing's endpoint is still active. For most sites, Search Console submission once + crawler auto-discovery is enough; ping-on-save is over-engineering.

---

## Verifying sitemap correctness

After deploy, run these checks:

```bash
# Index validates against the sitemap protocol XSD
curl -s https://your-site.com/sitemap.xml | xmllint --schema https://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd --noout -

# Each sub-sitemap validates against the urlset XSD
for type in categories products posts pages tags; do
  echo "$type:"
  curl -s "https://your-site.com/sitemap.xml/$type" | xmllint --schema https://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd --noout -
done

# Count URLs per sub-sitemap (sanity-check for unexpected zeros)
for type in categories products posts pages tags; do
  count=$(curl -s "https://your-site.com/sitemap.xml/$type" | grep -c '<loc>')
  echo "$type: $count URLs"
done
```

Zero URLs in a sub-sitemap usually means either (a) no active content of that type exists yet, (b) every row is `exclude_from_sitemap = true`, or (c) the `active()` scope is filtering out everything (e.g. all rows have `is_active = 0`). See [troubleshooting](./troubleshooting.md#a-sub-sitemap-has-zero-urls).
