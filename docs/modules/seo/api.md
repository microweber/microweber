# API Reference

Class, method, directive, and constant signatures for the Seo module.

---

## SeoMetadataService

`Modules\Seo\Services\SeoMetadataService`. The only public class. Singleton-bound in `SeoServiceProvider`. Resolve via `app(\Modules\Seo\Services\SeoMetadataService::class)` or inject it via constructor type-hint.

### Data retrieval

Each method accepts an optional `Content` model. Passing `null` returns the site-wide defaults.

#### `getMetadata(?Content $content = null): array`

Returns the full metadata bundle for one content row (or for site-wide defaults). Keys:

```php
[
    'title'           => string,
    'description'     => string,
    'keywords'        => string,
    'canonical_url'   => string,
    'robots_meta'     => string,
    'og'              => array,   // shape from getOpenGraphData()
    'twitter'         => array,   // shape from getTwitterCardData()
]
```

#### `getTitle(?Content $content = null): string`

Walks the title fallback chain: `og_title → content_meta_title → content.title → website_title option`. Returns the first non-empty value.

#### `getDescription(?Content $content = null): string`

Description fallback chain: `og_description → content_meta_description → content.description → website_description option`. Runs `sanitizeMetaText()` (strip tags + truncate to 300 chars) on the result.

#### `getKeywords(?Content $content = null): string`

Keywords fallback chain: `content_meta_keywords → website_keywords option`. Returns the first non-empty string.

#### `getCanonicalUrl(?Content $content = null): string`

If `$content->canonical_url` is set → returns that. Else returns `content_link($content->id)`. If `$content` is null → returns the site root URL.

#### `getRobotsMeta(?Content $content = null): string`

Guard chain (in order):

1. If `$content` is non-null AND (`!$content->is_active OR $content->is_deleted`) → `'noindex, nofollow'`. Guard takes priority over user-set values so deactivated content can never accidentally be indexed.
2. Else if `$content?->robots_meta` is non-empty → returns that.
3. Else → `website_robots_meta` option (default `'index, follow'`).

#### `getOpenGraphData(?Content $content = null): array`

Returns the OG bundle:

```php
[
    'title'       => string,    // og_title chain
    'description' => string,    // og_description chain
    'image'       => string,    // og_image chain via getOpenGraphImage()
    'type'        => string,    // og_type chain via getOpenGraphType()
    'url'         => string,    // canonical URL
    'site_name'   => string,    // website_title option
    'locale'      => string,    // app()->getLocale()
]
```

#### `getTwitterCardData(?Content $content = null): array`

Returns the Twitter Card bundle:

```php
[
    'card'        => string,    // twitter_card or default 'summary_large_image'
    'title'       => string,    // twitter_title chain
    'description' => string,    // twitter_description chain
    'image'       => string,    // twitter_image → og_image → content.thumbnail() → website_twitter_image
    'site'        => string,    // website_twitter_site option (e.g. '@brand')
]
```

#### `getSitemapData(Content $content): array`

Returns the sitemap-row data the [Sitemap module](/modules/sitemap/) consumes:

```php
[
    'url'        => string,
    'lastmod'    => string,    // ISO-8601 datetime from $content->updated_at
    'changefreq' => string,    // sitemap_changefreq or getDefaultChangefreq()
    'priority'   => float,     // sitemap_priority or getDefaultPriority() (0.5)
    'exclude'    => bool,      // exclude_from_sitemap
]
```

Note: `getSitemapData()` requires a non-null `$content` (the Sitemap module always passes one).

### Site-wide defaults

#### `getDefaultMetadata(): array`

Returns the full metadata bundle from site-wide options only (ignores any per-page content). Cached for the request.

#### `getSiteTitle(): string`

Returns `website_title` option (default `'My Site'`).

#### `getSiteDescription(): string`

Returns `website_description` option (default `''`).

#### `getSiteKeywords(): string`

Returns `website_keywords` option (default `''`).

### HTML rendering

These methods produce ready-to-emit HTML. They're what the Blade directives call.

#### `renderMetaTags(?Content $content = null): string`

The full bundle: `<title>`, `<meta name="description">`, `<meta name="keywords">`, `<meta name="robots">`, `<link rel="canonical">`, all `<meta property="og:*">` tags, all `<meta name="twitter:*">` tags. Newline-separated.

#### `renderTitle(?Content $content = null): string`

`<title>&#123;&#123; escaped title }}</title>`.

#### `renderDescription(?Content $content = null): string`

`<meta name="description" content="&#123;&#123; escaped description }}">`.

#### `renderOpenGraph(?Content $content = null): string`

All Open Graph `<meta property="og:*">` tags. Includes `og:title`, `og:description`, `og:image` (only emitted if non-empty), `og:type`, `og:url`, `og:site_name`, `og:locale`.

#### `renderTwitterCard(?Content $content = null): string`

All Twitter Card `<meta name="twitter:*">` tags. Includes `twitter:card`, `twitter:title`, `twitter:description`, `twitter:image` (only emitted if non-empty), `twitter:site` (only emitted if option is set).

---

## Protected methods (extension hooks)

These are protected (not public) so subclassing `SeoMetadataService` and overriding them is the supported extension point.

| Method | Returns | Purpose |
|---|---|---|
| `sanitizeMetaText(string $text, int $maxLength = 300): string` | string | strip tags + collapse whitespace + truncate (for descriptions) |
| `escapeHtml(string $text): string` | string | `htmlspecialchars(..., ENT_QUOTES)` for text-content position |
| `escapeAttribute(string $text): string` | string | `htmlspecialchars(..., ENT_QUOTES \| ENT_HTML5)` for attribute-value position |
| `getOpenGraphType(Content $content): string` | string | resolves content type to OG type (content_type `page` → `website`, `post` → `article`, `product` → `product`); user-set `og_type` overrides |
| `getOpenGraphImage(?Content $content): string` | string | OG image fallback chain |
| `getTwitterImage(?Content $content): string` | string | Twitter image fallback chain (longer than OG: twitter_image → og_image → thumbnail → site default) |
| `getDefaultChangefreq(Content $content): string` | string | per-content-type changefreq default (e.g. blog post → daily; static page → monthly) |
| `getDefaultPriority(Content $content): float` | float | per-content-type priority default |
| `getLocale(): string` | string | current locale via `app()->getLocale()`, used in `og:locale` |
| `getDefaultOpenGraphData(): array` | array | site-wide-only OG bundle |
| `getDefaultTwitterCardData(): array` | array | site-wide-only Twitter bundle |

---

## Blade directives

Registered in `SeoServiceProvider::registerBladeDirectives()`. Each is a one-line wrapper around the service's corresponding `render*()` method.

| Directive | Compiles to | Emits |
|---|---|---|
| `@seoMetaTags($content)` | `<?php echo $__env->yieldContent... renderMetaTags($content); ?>` | full bundle |
| `@seoTitle($content)` | `renderTitle($content)` | `<title>` only |
| `@seoDescription($content)` | `renderDescription($content)` | description `<meta>` only |
| `@seoOpenGraph($content)` | `renderOpenGraph($content)` | all OG tags |
| `@seoTwitterCard($content)` | `renderTwitterCard($content)` | all Twitter tags |

Each directive accepts a `Content` model or `null`. Calling with `null` returns the site-wide defaults.

---

## Constants

### Changefreq values

`SeoMetadataService::CHANGE_FREQ_*`:

```
CHANGE_FREQ_ALWAYS  = 'always'
CHANGE_FREQ_HOURLY  = 'hourly'
CHANGE_FREQ_DAILY   = 'daily'
CHANGE_FREQ_WEEKLY  = 'weekly'
CHANGE_FREQ_MONTHLY = 'monthly'
CHANGE_FREQ_YEARLY  = 'yearly'
CHANGE_FREQ_NEVER   = 'never'
```

### Open Graph types

`SeoMetadataService::OG_TYPE_*`:

```
OG_TYPE_WEBSITE = 'website'
OG_TYPE_ARTICLE = 'article'
OG_TYPE_PRODUCT = 'product'
```

### Twitter Card types

`SeoMetadataService::TWITTER_CARD_*`:

```
TWITTER_CARD_SUMMARY       = 'summary'
TWITTER_CARD_SUMMARY_LARGE = 'summary_large_image'
TWITTER_CARD_APP           = 'app'
TWITTER_CARD_PLAYER        = 'player'
```

Use these in your own code to avoid hardcoded magic strings.

---

## Configuration options

Read via the Option facade (group `website`). Documented at length in [Installation](./installation.md#site-wide-option-keys).

| Option | Default | Purpose |
|---|---|---|
| `website_title` | `My Site` | site-wide `<title>` fallback |
| `website_description` | `''` | site-wide description fallback |
| `website_keywords` | `''` | site-wide keywords fallback |
| `website_robots_meta` | `index, follow` | site-wide robots directive |
| `website_og_image` | `null` | site-wide OG image fallback |
| `website_twitter_image` | `null` | site-wide Twitter image fallback |
| `website_twitter_site` | `null` | Twitter handle for `<meta name="twitter:site">` |

---

## HTTP routes

`Modules/Seo/routes/web.php` contains commented placeholders only. There are **no active HTTP routes** in the Seo module today.

---

## Events

The Seo module **does not fire any events**. The fallback-chain logic is deterministic and self-contained; if you need a hook (e.g. to filter the final meta-tag output before render), the supported extension point is **subclassing `SeoMetadataService`** and overriding the relevant protected method or public render method, then rebinding the singleton in your own provider:

```php
// In your app-level provider's register():
$this->app->singleton(\Modules\Seo\Services\SeoMetadataService::class, MyCustomSeoService::class);
```

The Blade directives resolve through the container, so they automatically use the rebound class.

---

## Tests

`Modules/Seo/Tests/Unit/SeoMetadataServiceTest.php` — 26 unit tests, ~383 lines. Covers:

- Default metadata when content is null
- Per-content metadata retrieval
- Each fallback chain (title, description, image)
- Canonical URL handling (custom override + computed)
- Robots-meta guards for inactive/deleted content
- OG data shape + type detection (website/article/product)
- Twitter Card data shape + fallback image
- Sitemap data shape + defaults
- HTML rendering (proper escaping, no XSS)
- `sanitizeMetaText()` (strip-tags + truncation)
- `escapeHtml()` / `escapeAttribute()`

Run with:

```bash
./vendor/bin/phpunit Modules/Seo/Tests
```

If you add a new fallback or constant, mirror its coverage in this test file.
