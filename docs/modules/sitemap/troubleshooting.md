# Troubleshooting

Common Sitemap module issues with diagnostic steps.

---

## `/sitemap.xml` returns 404

**Symptom.** Visiting `/sitemap.xml` returns Laravel's 404 page (or your custom 404).

**Cause.** Either the `SitemapServiceProvider` didn't register (the module's routes aren't loaded) or another route is intercepting the request.

**Diagnosis.**

```bash
php artisan route:list | grep sitemap
```

Expected:

```
GET|HEAD  sitemap.xml             Modules\Sitemap\Http\Controllers\SitemapController@index
GET|HEAD  sitemap.xml/categories  ...
...
```

If the list is empty:

- Run `php artisan package:discover --ansi` to re-discover module providers.
- Confirm `Modules\Sitemap\Providers\SitemapServiceProvider` is in `bootstrap/cache/packages.php` or your `config/app.php` providers list.

If the routes are listed but the request still 404s, another route is registered with a higher-precedence wildcard catching `/sitemap.xml`. Check your app-level `routes/web.php` for `Route::any('/{any}', ...)` patterns that might shadow the module's specific routes — Laravel resolves first-defined-wins.

---

## A sub-sitemap has zero URLs

**Symptom.** `/sitemap.xml/products` returns a well-formed `<urlset>` element but has no `<url>` children.

**Cause.** One of:

1. No active products exist in the database.
2. Every product has `exclude_from_sitemap = true`.
3. Every product has `is_active = 0` or `is_deleted = 1` (filtered by the `active()` scope).
4. The Multilanguage branch failed to find any rows for the active locale.

**Diagnosis.**

```php
// Raw count
echo \Modules\Product\Models\Product::count();

// Active count (what the sitemap actually queries)
echo \Modules\Product\Models\Product::active()->count();

// Excluded count
echo \Modules\Product\Models\Product::where('exclude_from_sitemap', 1)->count();

// Multilanguage check
echo \MicroweberPackages\Multilanguage\MultilanguageHelpers::multilanguageIsEnabled() ? 'multilang on' : 'multilang off';
```

Most common cause is #2 — someone bulk-set `exclude_from_sitemap = true` and forgot. Unbulk:

```php
\Modules\Product\Models\Product::query()->update(['exclude_from_sitemap' => false]);
```

For #4 (multilang), check that at least one locale has content for the type. The multilanguage path calls `multilanguage_get_all_content_links()` which only returns rows that have translations in the currently active locale. If the active locale is `de` but all content was authored in `en` only, the sub-sitemap is empty for `de` requests.

---

## Sitemap regenerates on every request (slow on large sites)

**Symptom.** Every request to `/sitemap.xml/<type>` takes 1-5 seconds; observability shows the database queries running every time.

**Cause (historical).** Earlier versions of `SitemapHelpersTrait::needToUpdateSitemap()` short-circuited with `return true;` at the top of the method — effectively disabling the cache. The AI-333 follow-up shipped a one-line fix that removed the short-circuit and activated the documented 3-hour TTL.

**Verify the fix is active.**

```php
// In tinker
$h = new class { use \Modules\Sitemap\Http\Controllers\SitemapHelpersTrait; };
echo $h->needToUpdateSitemap('/nonexistent') ? 'true' : 'false';   // Expected: true
$path = tempnam(sys_get_temp_dir(), 'sitemap_');
touch($path, time() - 60);                                          // 1 minute old
echo $h->needToUpdateSitemap($path) ? 'true' : 'false';            // Expected: false
@unlink($path);
```

If the second call returns `true` despite a fresh file, the short-circuit is back — file an issue. The `Tests\Feature\SitemapCacheTtlContractTest` regression test should also catch this immediately in CI.

**Performance still not where you want it after the fix?** Layer a reverse-proxy cache (nginx `proxy_cache`, Cloudflare page rule, Varnish) in front. A 5-minute proxy TTL on top of the 3-hour disk cache eliminates ~95% of even the cached regeneration:

```nginx
# nginx example
location ~ ^/sitemap\.xml {
    proxy_pass http://app;
    proxy_cache sitemap_cache;
    proxy_cache_valid 200 5m;
    proxy_cache_key "$scheme$host$request_uri";
}
```

---

## Sitemap exceeds 50k URLs (protocol limit)

**Symptom.** Site has > 50,000 indexed content rows. Search Console flags the sub-sitemap with "Could not fetch" or partial-read warnings.

**Cause.** The sitemap protocol limits each `<urlset>` file to 50,000 URLs (and 50MB uncompressed). The current Sitemap module emits all URLs of a type into a single file without chunking.

**Mitigation (in order of effort):**

### Option A — Bulk-exclude low-value content

Reduce the indexed set so each sub-sitemap fits the limit:

```php
// Exclude pagination pages
Page::query()->where('subtype', 'pagination')->update(['exclude_from_sitemap' => true]);

// Exclude tag archives with < 3 entries
$thinTags = Page::query()
    ->where('subtype', 'tag')
    ->whereHas('contents', null, '<', 3)
    ->get();
foreach ($thinTags as $tag) {
    $tag->exclude_from_sitemap = true;
    $tag->save();
}
```

### Option B — Patch the module to chunk

Override `fetchNotMutilangProducts()` (etc.) in a project-level subclass to chunk at 45k rows + emit multiple `<sitemap>` entries in the index:

```php
// In a custom controller extending SitemapController:

protected function buildSubSitemapEntries(string $type): array
{
    $perFile = 45000;   // headroom under 50k
    $total   = $this->modelForType($type)::active()->count();
    $chunks  = (int) ceil($total / $perFile);

    $entries = [];
    for ($i = 0; $i < $chunks; $i++) {
        $entries[] = [
            'loc' => site_url("sitemap.xml/{$type}-{$i}"),
        ];
    }
    return $entries;
}
```

Then add the chunked routes (`/sitemap.xml/products-0`, `/sitemap.xml/products-1`, etc.) that each query with `->offset($i * 45000)->limit(45000)`.

This is a non-trivial refactor — file a feature request against the Sitemap module rather than rolling your own, unless you have an immediate-deadline need.

### Option C — Drop content from the index entirely

For genuinely indeflationary content (e.g. 100k generated SEO pages with thin content), it may be better to deactivate it than to fight the sitemap limit. Search engines penalise thin content; serving it via sitemap accelerates the penalty.

---

## Multilanguage `hreflang` links missing from sitemap

**Symptom.** Multilanguage module is enabled, but `/sitemap.xml/products` shows `<loc>` entries without any `<xhtml:link rel="alternate" hreflang="...">` siblings.

**Cause.** One of:

1. `MultilanguageHelpers::multilanguageIsEnabled()` returns `false` despite the module being enabled.
2. `multilanguage_get_all_content_links()` returns no rows.
3. The active locale has translations but other locales don't (single-language content).

**Diagnosis.**

```php
echo \MicroweberPackages\Multilanguage\MultilanguageHelpers::multilanguageIsEnabled() ? 'on' : 'OFF';
dd(get_supported_languages(true));   // array of active locale codes
$rows = multilanguage_get_all_content_links();
echo count($rows) . " translated rows\n";
```

If `multilanguageIsEnabled()` returns `false`: the Multilanguage module is installed but disabled. Enable in admin → Settings → Languages.

If `get_supported_languages(true)` returns only one locale: there are no other locales to emit `hreflang` for. The behaviour is correct — single-locale sites don't need `hreflang`.

If `multilanguage_get_all_content_links()` returns empty: no content has been translated yet. Translate at least one content row to populate the `multilanguage_translations` table; the sitemap fills in automatically once translations exist.

---

## `lastmod` shows an unexpected date

**Symptom.** Content row was edited yesterday but the sitemap's `<lastmod>` shows a date weeks ago.

**Cause.** The `<lastmod>` value comes from `content.updated_at`. Either the edit didn't trigger an Eloquent save (raw SQL bypassed the model's `updated_at` auto-touch), or the cache is stale and showing an old snapshot.

**Diagnosis.**

```php
$content = \Modules\Content\Models\Content::find($id);
echo $content->updated_at->format('Y-m-d H:i:s');
```

If `updated_at` is current → the cache is stale. Delete the cache file and retry:

```bash
rm storage/cache/$(hostname)_*_sitemap.xml
curl https://your-site.com/sitemap.xml/products | grep "<lastmod>"
```

If `updated_at` is also old → the row was updated via raw SQL or `Model::query()->update(...)` (which doesn't auto-touch). Fix by re-saving through the model:

```php
$content->touch();   // updates updated_at without changing any other field
```

For bulk operations on production, always use Eloquent saves over raw SQL when you care about timestamps + cache invalidation.

---

## Reverse-proxy cache serves stale sitemap after content edit

**Symptom.** You added a proxy cache (nginx, Cloudflare) with a 1-hour TTL. After publishing a new product, the sitemap doesn't show it for up to an hour.

**Cause.** By design — the proxy cache TTL trades freshness for performance. The cache won't refresh until the TTL elapses or you manually invalidate.

**Mitigation.**

### Cloudflare cache purge from a content-save listener

```php
namespace App\Listeners;

use Illuminate\Support\Facades\Http;

class PurgeSitemapCacheOnContentSave
{
    public function handle($event): void
    {
        $zoneId = config('services.cloudflare.zone_id');
        $apiKey = config('services.cloudflare.api_key');

        Http::asJson()
            ->withToken($apiKey)
            ->post("https://api.cloudflare.com/client/v4/zones/{$zoneId}/purge_cache", [
                'files' => [
                    url('/sitemap.xml'),
                    url('/sitemap.xml/products'),
                    url('/sitemap.xml/categories'),
                    url('/sitemap.xml/posts'),
                    url('/sitemap.xml/pages'),
                    url('/sitemap.xml/tags'),
                ],
            ]);
    }
}
```

Wire to whatever content-save event your Content module fires (varies by Microweber version — grep `Modules/Content/Models/Content.php` for `event(`).

### nginx — make the cache shorter

```nginx
proxy_cache_valid 200 5m;   # 5 minutes is a good balance
```

For most sites, 5-minute proxy TTL means a search crawler sees content within 5 minutes of publish, which is fast enough that crawl-budget effects are negligible.

---

## Tests pass but production sitemap is empty

**Symptom.** `phpunit Modules/Sitemap/Tests` passes all 12 tests. Production `/sitemap.xml/products` returns an empty `<urlset>`.

**Cause.** The shipped tests check route registration + content-type headers but do NOT seed content. They pass on any database state — including an empty one. A passing test does NOT mean "the sitemap is working".

**Diagnosis.** Verify via the smoke-check script in [Examples #1](./examples.md#1-verify-your-sitemap-is-being-served-correctly) — counts URLs per sub-sitemap and surfaces unexpected zeros.

This is a coverage gap worth filing as a follow-up ticket — the existing tests should be augmented with seed-then-assert tests that confirm a known product appears in the rendered XML.
