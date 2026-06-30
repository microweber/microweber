# API Reference

Class, method, and route signatures for the Sitemap module.

---

## SitemapController

`Modules\Sitemap\Http\Controllers\SitemapController`. Uses `SitemapHelpersTrait`. All actions return `Response` with `Content-Type: text/xml`.

### `index(): Response`

`GET /sitemap.xml`. Returns the sitemap-index XML — a single `<sitemapindex>` element containing 5 `<sitemap>` children, one per content-type sub-sitemap.

No caching layer (the index is cheap — just a Blade render of 5 hardcoded URLs).

### `categories(): Response`

`GET /sitemap.xml/categories`. Returns a `<urlset>` with one `<url>` per active Category. Uses `SitemapHelpersTrait::fetchCategoriesLinks()` to fetch.

Cache file: `mw_cache_path() . hostname . '_categories_sitemap.xml'`.

### `products(): Response`

`GET /sitemap.xml/products`. Returns a `<urlset>` with one `<url>` per active Product. Uses `fetchProductsLinks()`.

Cache file: `mw_cache_path() . hostname . '_products_sitemap.xml'`.

### `posts(): Response`

`GET /sitemap.xml/posts`. Returns a `<urlset>` with one `<url>` per active Post. Uses `fetchPostsLinks()`.

Cache file: `mw_cache_path() . hostname . '_posts_sitemap.xml'`.

### `pages(): Response`

`GET /sitemap.xml/pages`. Returns a `<urlset>` with one `<url>` per active Page. Uses `fetchPagesLinks()`.

Cache file: `mw_cache_path() . hostname . '_pages_sitemap.xml'`.

### `tags(): Response`

`GET /sitemap.xml/tags`. Returns a `<urlset>` with one `<url>` per page tag. Uses `fetchTagsLinks()` which calls `content_tags($pageId, true)` from the Content module.

Cache file: `mw_cache_path() . hostname . '_tags_sitemap.xml'`.

### `getSlugsWithGroups(): array`

Returns an associative array of `[$contentType => [$slug, $slug, ...]]`. Used internally to assemble the sub-sitemap data. Not bound to a route.

---

## SitemapHelpersTrait

`Modules\Sitemap\Http\Controllers\SitemapHelpersTrait`. 11 public methods. Used by `SitemapController`.

### `isMutilangOn(): bool`

Returns `MultilanguageHelpers::multilanguageIsEnabled()`. Controllers branch on this to pick the multilang or non-multilang fetcher.

### `fetchCategoriesLinks(): array`

If multilang on → `multilanguage_get_all_category_links()` (from Multilanguage module).
Else → `fetchNotMutilangCategories()`.

Returns an array of `['original_link' => ..., 'updated_at' => ..., 'multilanguage_links' => [...]]` rows.

### `fetchProductsLinks(): array`

If multilang on → `fetchMultilangContentByType('product')`.
Else → `fetchNotMutilangProducts()`.

### `fetchPostsLinks(): array`

If multilang on → `fetchMultilangContentByType('post')`.
Else → `fetchNotMutilangPosts()`.

### `fetchPagesLinks(): array`

If multilang on → `fetchMultilangContentByType('page')`.
Else → `fetchNotMutilangPages()`.

### `fetchTagsLinks(): array`

Walks active pages, calls `content_tags($pageId, true)` (Content module helper) to get the tag list per page. Builds tag URLs from the active locale's tag-archive route. Multilang-aware (emits one row per (tag × locale) when multilang is on).

### `fetchNotMutilangCategories(): array`

```php
return \Modules\Category\Models\Category::all()->map(fn($c) => [
    'original_link' => $c->link(),
    'updated_at'    => $c->updated_at?->format('Y-m-d'),
    'multilanguage_links' => [],
])->all();
```

Note: no `active()` filter — categories are returned regardless of activation. If your install has soft-deleted categories you don't want in the sitemap, override this method or add a `Category::active()` scope.

### `fetchNotMutilangPosts(): array`

```php
return \Modules\Post\Models\Post::active()->get()->map(fn($p) => [
    'original_link' => $p->link(),
    'updated_at'    => $p->updated_at?->format('Y-m-d'),
    'multilanguage_links' => [],
])->all();
```

The `active()` scope filters `is_active = 1` AND `is_deleted = 0`. `exclude_from_sitemap = true` rows are filtered by the same scope if the project has wired that into `active()`; otherwise add an explicit `->where('exclude_from_sitemap', false)` clause.

### `fetchNotMutilangProducts(): array`

Same shape as `fetchNotMutilangPosts()` but on `\Modules\Product\Models\Product`.

### `fetchMultilangContentByType(string $type): array`

Calls `multilanguage_get_all_content_links()` and filters the result to rows whose `content_type` matches `$type`. Returns the same array shape with `multilanguage_links` populated.

### `needToUpdateSitemap(string $sitemapFileLocation): bool`

**Currently always returns `true`.** Documented as a 3-hour TTL check but the implementation short-circuits. See [Usage → Cache behaviour](./usage.md#cache-behaviour) for the one-line fix.

---

## Blade templates

### `views/index.blade.php`

The sitemap-index template. Renders:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap><loc>{{ site_url('sitemap.xml/categories') }}</loc></sitemap>
    <sitemap><loc>{{ site_url('sitemap.xml/products')   }}</loc></sitemap>
    <sitemap><loc>{{ site_url('sitemap.xml/posts')      }}</loc></sitemap>
    <sitemap><loc>{{ site_url('sitemap.xml/pages')      }}</loc></sitemap>
    <sitemap><loc>{{ site_url('sitemap.xml/tags')       }}</loc></sitemap>
</sitemapindex>
```

Variables: none (the five URLs are hardcoded).

### `views/items.blade.php`

The URL-set template. Renders:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml">
@foreach ($itemsData as $itemData)
    <url>
        <loc>{{ $itemData['original_link'] }}</loc>
        <lastmod>{{ $itemData['updated_at'] }}</lastmod>
        @if (!empty($itemData['changefreq']))
            <changefreq>{{ $itemData['changefreq'] }}</changefreq>
        @endif
        @if (!empty($itemData['priority']))
            <priority>{{ $itemData['priority'] }}</priority>
        @endif
        @foreach ($itemData['multilanguage_links'] ?? [] as $locale => $localeUrl)
            <xhtml:link rel="alternate" hreflang="{{ $locale }}" href="{{ $localeUrl }}"/>
        @endforeach
    </url>
@endforeach
</urlset>
```

Variables expected in `$itemsData` rows:

| Key | Type | Required | Notes |
|---|---|---|---|
| `original_link` | string | yes | absolute URL for `<loc>` |
| `updated_at` | string | yes | Y-m-d format for `<lastmod>` |
| `changefreq` | string | no | from `content.sitemap_changefreq`; tag omitted if empty |
| `priority` | float-as-string | no | from `content.sitemap_priority`; tag omitted if empty |
| `multilanguage_links` | array | no | `[$locale => $url]`; emits one `<xhtml:link>` per pair |

---

## Filesystem cache path format

```
{mw_cache_path()}{hostname}_<type>_sitemap.xml
```

Where:
- `mw_cache_path()` resolves to the project's cache directory (typically `storage/cache/`).
- `hostname` is `app()->url_manager->hostname()` — the current request's `Host` header, normalised (no port, no scheme).
- `<type>` is one of `categories`, `products`, `posts`, `pages`, `tags`.

E.g. on a multi-tenant install: `storage/cache/site-a.example.com_products_sitemap.xml` and `storage/cache/site-b.example.com_products_sitemap.xml` are separate files.

---

## HTTP routes

| Method | Path | Action | Middleware |
|---|---|---|---|
| GET | `/sitemap.xml` | `SitemapController@index` | `web` |
| GET | `/sitemap.xml/categories` | `SitemapController@categories` | `web` |
| GET | `/sitemap.xml/products` | `SitemapController@products` | `web` |
| GET | `/sitemap.xml/posts` | `SitemapController@posts` | `web` |
| GET | `/sitemap.xml/pages` | `SitemapController@pages` | `web` |
| GET | `/sitemap.xml/tags` | `SitemapController@tags` | `web` |

All routes return `Content-Type: text/xml; charset=UTF-8`.

---

## Events

The Sitemap module **does not fire any events**. There are no listeners, no observers, no event service provider with a `$listen` array. If you need to react to sitemap regeneration (e.g. push to a search-engine ping endpoint), the integration point is **inside each controller action** after the `response()` call — fork the controller or add a custom middleware.

---

## Config

`config/config.php`:

```php
return [
    'icon' => asset('modules/sitemap/img/icon.svg'),
];
```

No other configuration. The module has no option keys.

---

## Tests

`Modules/Sitemap/Tests/Unit/`:

| File | Coverage |
|---|---|
| `SitemapControllerTest.php` | 6 action tests: each route returns HTTP 200 with `Content-Type: text/xml` |
| `SitemapRoutesTest.php` | 6 route tests: each route is registered with the expected URI + middleware |

Run with:

```bash
./vendor/bin/phpunit Modules/Sitemap/Tests
```

Coverage is happy-path only — no tests for the multilanguage branch, no tests for the cache TTL, no tests for `exclude_from_sitemap` filtering. If you add new behaviour, mirror its coverage in these files.

---

## Helpers used (from other modules)

The Sitemap module does not define helpers. It uses:

| Helper | Owner | Purpose |
|---|---|---|
| `site_url($path)` | App | builds absolute URL with current scheme + host |
| `mw_cache_path()` | App | returns the configured cache directory |
| `app()->url_manager->hostname()` | App | current request's hostname |
| `content_tags($pageId, true)` | Content | fetch the tag list for a page |
| `get_supported_languages(true)` | Multilanguage | list of active locale codes |
| `multilanguage_get_all_category_links()` | Multilanguage | all category links across locales |
| `multilanguage_get_all_content_links()` | Multilanguage | all content links across locales |
