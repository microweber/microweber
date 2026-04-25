# `Seo` module

> **Slug:** `seo`
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

## Service classes

### `Modules\Seo\Services\SeoMetadataService`

Source: `Services/SeoMetadataService.php`.

  - `getMetadata(?Content $content = null): array`
  - `getTitle(?Content $content = null): string`
  - `getDescription(?Content $content = null): string`
  - `getKeywords(?Content $content = null): string`
  - `getCanonicalUrl(?Content $content = null): string`
  - `getRobotsMeta(?Content $content = null): string`
  - `getOpenGraphData(?Content $content = null): array`
  - `getTwitterCardData(?Content $content = null): array`
  - `getSitemapData(Content $content): array`
  - `getDefaultMetadata(): array`
  - `getSiteTitle(): string`
  - `getSiteDescription(): string`
  - `getSiteKeywords(): string`
  - `renderMetaTags(?Content $content = null): string`
  - `renderTitle(?Content $content = null): string`
  - `renderDescription(?Content $content = null): string`
  - `renderOpenGraph(?Content $content = null): string`
  - `renderTwitterCard(?Content $content = null): string`

## Tests

Run: `php vendor/bin/phpunit Modules/Seo/Tests`

### `Tests/Unit/SeoMetadataServiceTest.php`

  - `it_returns_seo_metadata_for_content`
  - `it_truncates_long_text`
  - `it_returns_default_site_title_when_option_is_empty`

## Service providers

  - `Modules\Seo\Providers\SeoServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
