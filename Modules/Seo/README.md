# Seo

SEO metadata service. Generates and renders meta tags, Open Graph tags, and Twitter Cards for content pages using Blade directives.

## Key Features

- Automatic meta tag generation for content items
- Open Graph (og:) tag rendering
- Twitter Card meta tag rendering
- Blade directives for easy template integration
- Centralized SeoMetadataService singleton

## Blade Directives

| Directive | Description |
|---|---|
| `@seoMetaTags($content)` | Render all meta tags for a content item |
| `@seoTitle($content)` | Render title meta tag |
| `@seoDescription($content)` | Render description meta tag |
| `@seoOpenGraph($content)` | Render Open Graph tags |
| `@seoTwitterCard($content)` | Render Twitter Card tags |

## Key Classes

| Class | Purpose |
|---|---|
| `Services\SeoMetadataService` | SEO tag generation and rendering (singleton) |

The Content module stores SEO fields (`seo_title`, `seo_description`, `seo_og_*`, `seo_twitter_*`) directly on the `content` table (added via migration `2025_03_22_000001_add_seo_metadata_fields_to_content.php`).

## Usage

```blade
{{-- In your layout template --}}
<head>
    @seoMetaTags($content)
</head>

{{-- Or render individual tags --}}
@seoTitle($content)
@seoDescription($content)
@seoOpenGraph($content)
@seoTwitterCard($content)
```

```php
$seo = app(\Modules\Seo\Services\SeoMetadataService::class);
$html = $seo->renderMetaTags($content);
```
