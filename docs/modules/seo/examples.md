# Examples

Four end-to-end recipes for the most common Seo-module integrations.

---

## 1. Minimum-viable layout integration

The smallest correct layout that produces well-formed SEO output:

```blade
{{-- resources/views/layouts/master.blade.php --}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

    @seoMetaTags($content ?? null)

    @stack('head')
</head>
<body>
    @yield('content')
</body>
</html>
```

`$content ?? null` makes the same layout work on both Content-backed routes (where `$content` is the page row) and non-Content routes (where `$content` may be undefined). On the latter, the service returns site-wide defaults.

The `@stack('head')` lets individual pages inject extra `<head>` content (e.g. page-specific structured data — see recipe 3) without forking the master layout.

---

## 2. Pass a custom Open Graph image based on content type

Suppose your editorial team wants the OG image fallback chain to be:

1. The row's explicit `og_image` (if set in admin).
2. The row's `twitter_image` (also set in admin — sometimes editors only set one or the other).
3. The product image (for products only).
4. A content-type-specific default (e.g. a "blog" placeholder for posts).
5. The site-wide `website_og_image`.

Subclass `SeoMetadataService` and override `getOpenGraphImage()`:

```php
namespace App\Services;

use Modules\Content\Models\Content;
use Modules\Seo\Services\SeoMetadataService;

class MyCustomSeoService extends SeoMetadataService
{
    protected function getOpenGraphImage(?Content $content): string
    {
        if ($content) {
            if (! empty($content->og_image))      return asset($content->og_image);
            if (! empty($content->twitter_image)) return asset($content->twitter_image);

            if ($content->content_type === 'product' && method_exists($content, 'getMainImage')) {
                $product = $content->getMainImage();
                if ($product) return asset($product);
            }

            $perTypeDefaults = [
                'post'    => '/img/og-defaults/blog.jpg',
                'product' => '/img/og-defaults/product.jpg',
                'page'    => '/img/og-defaults/page.jpg',
            ];
            if (isset($perTypeDefaults[$content->content_type])) {
                return asset($perTypeDefaults[$content->content_type]);
            }
        }

        return (string) \Option::getValue('website_og_image', 'website');
    }
}
```

Rebind in your app provider:

```php
// app/Providers/AppServiceProvider.php
public function register(): void
{
    $this->app->singleton(
        \Modules\Seo\Services\SeoMetadataService::class,
        \App\Services\MyCustomSeoService::class
    );
}
```

The Blade directives resolve through the container, so they pick up the override automatically. No template changes needed.

---

## 3. Add JSON-LD structured data alongside the OG tags

The Seo module doesn't emit JSON-LD, but you can layer it on top using `SeoMetadataService` data:

```blade
{{-- resources/views/layouts/partials/structured-data.blade.php --}}
@php
    $seo = app(\Modules\Seo\Services\SeoMetadataService::class);
    $meta = $seo->getMetadata($content ?? null);

    $jsonLd = [
        '@context' => 'https://schema.org',
        '@type'    => $meta['og']['type'] === 'article' ? 'Article' : 'WebPage',
        'name'     => $meta['title'],
        'description' => $meta['description'],
        'url'      => $meta['canonical_url'],
    ];

    if (! empty($meta['og']['image'])) {
        $jsonLd['image'] = $meta['og']['image'];
    }

    if (isset($content) && $content && ! empty($content->created_at)) {
        $jsonLd['datePublished'] = $content->created_at->toIso8601String();
        $jsonLd['dateModified']  = $content->updated_at->toIso8601String();
    }
@endphp

<script type="application/ld+json">
{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
```

Include it from your master layout:

```blade
<head>
    @seoMetaTags($content ?? null)
    @include('layouts.partials.structured-data')
</head>
```

The JSON-LD picks up the same fallback chains as the OG tags, so changing the SEO field in admin updates both surfaces automatically.

---

## 4. Programmatic SEO seeding for a content-import migration

When migrating content from another CMS, you'll often have meta-title and meta-description data in the source dump that needs to land on the `content` row directly:

```php
use Modules\Content\Models\Content;

$importRows = json_decode(file_get_contents('legacy-export.json'), true);

foreach ($importRows as $row) {
    $content = Content::updateOrCreate(
        ['url' => $row['url']],
        [
            'title'          => $row['title'],
            'content'        => $row['body'],
            'is_active'      => 1,
            'content_type'   => $row['type'],

            // SEO fields — written directly to the content row
            'content_meta_title'       => $row['seo_title']       ?? null,
            'content_meta_description' => $row['seo_description'] ?? null,
            'content_meta_keywords'    => $row['seo_keywords']    ?? null,
            'og_title'                 => $row['og_title']        ?? null,
            'og_description'           => $row['og_description']  ?? null,
            'og_image'                 => $row['og_image']        ?? null,
            'og_type'                  => $row['type'] === 'post' ? 'article' : 'website',
            'twitter_card'             => 'summary_large_image',

            // Sitemap behaviour
            'sitemap_priority'         => $row['priority']        ?? 0.5,
            'sitemap_changefreq'       => $row['changefreq']      ?? 'weekly',
            'exclude_from_sitemap'     => $row['noindex']         ?? false,
        ]
    );

    // For non-default locales, set translatable values via the multilanguage trait
    if (! empty($row['translations'])) {
        foreach ($row['translations'] as $locale => $t) {
            $content->setTranslation('content_meta_title',       $locale, $t['seo_title']       ?? null);
            $content->setTranslation('content_meta_description', $locale, $t['seo_description'] ?? null);
            $content->setTranslation('og_title',                  $locale, $t['og_title']        ?? null);
            $content->setTranslation('og_description',            $locale, $t['og_description']  ?? null);
        }
        $content->save();
    }
}
```

This bypasses the Filament form but writes the same columns. After the import, every page renders its imported SEO automatically through the Blade directives — no further integration needed.

The Content module's `HasMultilanguageTrait` exposes `setTranslation($field, $locale, $value)` for the seven translatable SEO fields. Non-translatable fields (`og_image`, `og_type`, `twitter_image`, `twitter_card`, `canonical_url`, `robots_meta`, sitemap fields) are set directly on the row.
