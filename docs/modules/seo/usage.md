# Usage

How the Seo module is consumed: dropping directives into your layout, editing per-page SEO from the admin, multilanguage SEO, robots and canonical handling, sitemap inclusion, and the AI-generation flow.

---

## Drop the directives into your master layout

The minimum integration is one directive in your master layout's `<head>`:

```blade
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

    @seoMetaTags($content)

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    @yield('content')
</body>
</html>
```

`$content` is the current page's `Modules\Content\Models\Content` model. If your route is backed by Content (most public-site routes are), it's already in scope. If you're rendering a non-Content route (e.g. a custom search-results page), pass `null` — the service returns site-wide defaults:

```blade
@seoMetaTags(null)
```

For finer-grained control (e.g. you want to emit `<title>` and description in one place but OG tags somewhere else):

```blade
<head>
    @seoTitle($content)
    @seoDescription($content)
    {{-- ... your own canonical or robots ... --}}
    @seoOpenGraph($content)
    @seoTwitterCard($content)
</head>
```

Combining `@seoMetaTags` AND a subset emitter will produce duplicate tags — pick one approach per layout.

---

## Editing per-page SEO from the admin

Open any Content row in admin → the form has five collapsible sections at the bottom. Open them:

- **Basic SEO** — title (≤500 chars), description (textarea), keywords (textarea). All three are translatable.
- **Open Graph (Facebook)** — OG title, description, image (file upload), type select (`website` / `article` / `product`).
- **Twitter Card** — Twitter title, description, image, card type (`summary` / `summary_large_image`).
- **Advanced SEO** — canonical URL (custom override), robots meta select (`index, follow` / `noindex, nofollow` / etc.).
- **Sitemap Settings** — exclude-from-sitemap toggle, priority number (0.0-1.0), changefreq select.

Any field you leave empty falls back through the chain documented in [Overview](./#the-fallback-chains).

The fastest way to bulk-set SEO across many pages is to use the Filament bulk-edit action (select rows in the list view → Bulk Edit → choose the SEO column → save). The SEO migration index `idx_content_active_sitemap` keeps bulk operations fast.

---

## AI-generated meta from content body

If the AI module is installed and enabled, an action button appears next to `og_title` on the Filament form labelled "Generate from content". Click it to:

1. Read the row's body HTML, strip tags.
2. Send the plain-text body to the AI module's text-generation provider.
3. The provider returns a `{ title, description, keywords }` triple.
4. The three fields are pre-filled in the form (you can still edit before saving).

The action does NOT auto-save — you commit by hitting Save on the form. If the AI module is not installed, the action is hidden.

For programmatic SEO generation across many existing content rows, use a job that calls the same AI provider directly — that's an app-level integration outside this module.

---

## Multilanguage SEO

The seven translatable SEO columns (`content_meta_title`, `content_meta_description`, `content_meta_keywords`, `og_title`, `og_description`, `twitter_title`, `twitter_description`) use the Content module's `HasMultilanguageTrait` which stores per-locale values in the `multilanguage_translations` table.

Reading them honours the current locale:

```php
app()->setLocale('de');
$svc = app(\Modules\Seo\Services\SeoMetadataService::class);
echo $svc->getTitle($content);
// → German content_meta_title if it's set; else the chain falls back through
//   German content.title → English (default-locale) content_meta_title → German
//   website_title option → English website_title option
```

Writing locale-specific values via Filament: the form fields show a small locale switcher when the multilanguage module is enabled. Switch locale → edit field → save. The locale-keyed value lands in `multilanguage_translations`.

The non-translatable columns (`og_image`, `og_type`, `twitter_image`, `twitter_card`, `canonical_url`, `robots_meta`, sitemap fields) are single-value across locales by design — a Twitter image typically doesn't change per language, robots directives don't either.

---

## Robots directive

The `robots_meta` field controls the `<meta name="robots" content="...">` tag. Common values:

| Value | Meaning |
|---|---|
| `index, follow` *(default)* | indexable + crawlable; most pages |
| `noindex, follow` | hide from search results but crawl outbound links (good for thin archive pages) |
| `index, nofollow` | indexable but don't follow links (rare) |
| `noindex, nofollow` | fully hidden from search engines |

Guard logic (in `SeoMetadataService::getRobotsMeta()`):

1. If `$content->is_active === false` OR `$content->is_deleted === true` → forced to `'noindex, nofollow'` regardless of what the column says. This means deactivated or soft-deleted pages can never accidentally leak into search results.
2. Else if `$content->robots_meta` is non-empty → use the column.
3. Else → use the `website_robots_meta` option.

To programmatically hide an entire site (e.g. a staging environment):

```php
\MicroweberPackages\Option\Models\Option::setValue('website_robots_meta', 'noindex, nofollow', 'website');
```

Every page renders `<meta name="robots" content="noindex, nofollow">` immediately, unless its own `content.robots_meta` explicitly overrides.

---

## Canonical URL handling

If `$content->canonical_url` is set, that value is used verbatim. Otherwise, the canonical URL is computed via `content_link($content->id)` (the Content module's URL helper).

Common reasons to set a custom canonical:

- Two content rows have the same body but live at different URLs (e.g. a featured article also re-published as a campaign landing page).
- A category and a tag both render the same product list.
- A multilanguage variant should point to a "primary" language's version.

If your install has `noindex` on a duplicate-content page AND a custom canonical pointing at the original, you're doing belt + braces — that's fine and recommended.

---

## Sitemap inclusion

Three columns control sitemap behaviour:

- `exclude_from_sitemap` (boolean, default false) — when true, the row is omitted from `/sitemap.xml` entirely.
- `sitemap_priority` (decimal 0.0–1.0, default 0.5) — search-engine hint for relative importance.
- `sitemap_changefreq` (enum) — search-engine hint for crawl cadence.

The Seo module exposes these via `SeoMetadataService::getSitemapData($content)`. The [Sitemap module](/modules/sitemap/) calls that method for each row when generating `/sitemap.xml`.

Bulk-excluding a section: in admin, filter the content list to the section's parent, select all, Bulk Edit → set `exclude_from_sitemap = true` → save.

---

## XSS-safe rendering

Every string the Seo module emits passes through one or both of these methods:

- `escapeHtml($s)` — `htmlspecialchars()` for content rendered between tags.
- `escapeAttribute($s)` — `htmlspecialchars()` with quote-escaping for `content=""` attribute values.

For text that might contain HTML (e.g. a content body fragment being used as meta description), `sanitizeMetaText()` first runs `strip_tags()` + `mb_substr(0, 300)` to strip markup and cap length.

If you fork `SeoMetadataService` or write a custom Blade directive that emits SEO-related HTML, **always run the corresponding escape method**. Bypassing the escape opens a stored-XSS hole when an admin pastes user-generated content into an SEO field.

---

## Switching site-wide defaults at runtime

If you operate a multi-site install where different domains share a database, you can override site-wide defaults per request:

```php
\MicroweberPackages\Option\Models\Option::setValueForCurrentSite('website_title', 'Brand B', 'website');
```

The Option module's `setValueForCurrentSite` writes a domain-scoped value; the Seo service reads through the same facade and picks the right one for the current host.

For per-locale defaults (e.g. different `website_description` per language), the same pattern works with the multilanguage helper — but you typically don't want per-locale `website_*` defaults; you want per-locale **content** values, which are already handled by the translatable columns.
