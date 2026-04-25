# `Sitemap` module

> **Slug:** `sitemap`
> **Tier:** 2
>
> *Auto-generated from filesystem survey on 2026-04-25 with
> column / route / method extraction. Domain section is
> the only hand-edit needed; the rest of this file is
> regenerable from source.*

## Domain

*Hand-edit this section: describe what the module does
operationally, who consumes it, and which sibling modules
it interacts with.*

## Data model

This module owns no migrations of its own.

## API endpoints

Route files exist but no parseable `Route::method` calls were
found:

  - `routes/web.php`

## Controllers

### `Modules\Sitemap\Http\Controllers\SitemapController`

Source: `Http/Controllers/SitemapController.php`.

  - `index()`
  - `categories()`
  - `tags()`
  - `products()`
  - `posts()`
  - `pages()`
  - `getSlugsWithGroups()`

### `Modules\Sitemap\Http\Controllers\SitemapHelpersTrait`

Source: `Http/Controllers/SitemapHelpersTrait.php`.

  - `isMutilangOn()`
  - `fetchTagsLinks()`
  - `fetchProductsLinks()`
  - `fetchPagesLinks()`
  - `fetchPostsLinks()`
  - `fetchCategoriesLinks()`
  - `fetchNotMutilangCategories()`
  - `fetchNotMutilangPosts()`
  - `fetchNotMutilangProducts()`
  - `fetchMultilangContentByType($type)`
  - `needToUpdateSitemap($sitemapFileLocation)`

## Tests

Run: `php vendor/bin/phpunit Modules/Sitemap/Tests`

### `Tests/Unit/SitemapControllerTest.php`

  - `it_categories_returns_xml_response`
  - `it_products_returns_xml_response`
  - `it_pages_returns_xml_response`

### `Tests/Unit/SitemapRoutesTest.php`

  - `it_sitemap_categories_route`
  - `it_sitemap_products_route`
  - `it_sitemap_pages_route`

## Service providers

  - `Modules\Sitemap\Providers\SitemapServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
