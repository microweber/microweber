# Troubleshooting

Common Seo module issues with diagnostic steps.

---

## Meta tags don't appear in the rendered HTML

**Symptom.** `@seoMetaTags($content)` is in your layout but View Source shows no `<title>`, no `<meta>`, no OG tags.

**Cause.** Three common roots:

1. The Blade directive isn't registered (provider not loaded).
2. `$content` is undefined in the layout's scope (`null` coalescing isn't being used).
3. The directive output is being escaped (e.g. wrapped in `&#123;&#123; ... }}` instead of `{!! ... !!}` — though directives shouldn't be wrapped at all).

**Diagnosis.**

```bash
# Confirm directives are registered
php artisan tinker --execute='
    $blade = app("blade.compiler");
    $dir = $blade->getCustomDirectives();
    dd(isset($dir["seoMetaTags"]));
'
# Expected: true
```

If false:

- Confirm `SeoServiceProvider` is loaded. `php artisan package:discover` should auto-discover from `module.json`. If you have a non-standard provider list in `config/app.php`, add `\Modules\Seo\Providers\SeoServiceProvider::class` manually.
- Confirm the module is enabled. Check `php artisan module:list` for "Seo".

If `$content` is undefined in the layout:

```blade
{{-- WRONG — fails on routes that don't bind $content --}}
@seoMetaTags($content)

{{-- RIGHT — falls back to site-wide defaults on non-content routes --}}
@seoMetaTags($content ?? null)
```

---

## Fallback chain returns an unexpected value

**Symptom.** You set `og_title = 'Hello'` on a content row but the rendered `<meta property="og:title">` shows the row's plain `title` instead.

**Cause.** Almost always: the row's `og_title` is empty after save. Filament's TextInput stores `null` for empty strings, but JSON imports or direct SQL might be storing the literal string `''` — both count as empty in the fallback chain, but it's worth verifying.

**Diagnosis.**

```php
$content = \Modules\Content\Models\Content::find($id);

dd([
    'og_title'           => $content->og_title,
    'content_meta_title' => $content->content_meta_title,
    'content.title'      => $content->title,
    'fallback resolves to' => app(\Modules\Seo\Services\SeoMetadataService::class)->getTitle($content),
]);
```

The first non-empty value of the chain wins. If `og_title` is `null` or `''` it's skipped. If you expected it set but it's not, check the Filament form save path — most commonly the field was edited in a non-default locale but read in the default locale, so the translation key doesn't match.

For multilanguage: switch the locale before reading:

```php
app()->setLocale('de');
echo $content->og_title;   // reads the German translation, not the English one
```

---

## A deactivated content row is still showing in search engine results

**Symptom.** Set `is_active = 0` on a content row in admin; expected Google to drop it from results, but it's still indexed.

**Cause.** The `<meta name="robots">` guard in `SeoMetadataService::getRobotsMeta()` forces `'noindex, nofollow'` when the row is inactive or soft-deleted. That tells crawlers to drop the page on *next visit* — but search-engine indexes are not updated in real time.

**Mitigations (in order):**

1. **Wait** — Google typically recrawls within days to weeks; the row will drop naturally.
2. **Force a recrawl** via Google Search Console → URL Inspection → Request Indexing.
3. **Block the URL via robots.txt** for immediate disappearance from crawlers (but robots.txt blocking does not remove existing indexed entries — it only prevents new crawls).
4. **Use Google Search Console's URL removal tool** for a 6-month temporary hide while the recrawl catches up.

The Seo module's job ends at emitting the right `<meta>` tag on the next render — propagation to the search index is on Google's timeline, not yours.

**Verify the emission first:**

```php
$content = \Modules\Content\Models\Content::find($id);
echo app(\Modules\Seo\Services\SeoMetadataService::class)->getRobotsMeta($content);
// Expected for inactive: "noindex, nofollow"
```

If it doesn't say `noindex, nofollow`, the guard isn't firing — check `is_active` and `is_deleted` are both `0` / `false`, and that you're looking at the right Content model instance.

---

## OG image not appearing in social-media previews

**Symptom.** Set `og_image` in admin, but when you share the URL on Facebook / LinkedIn / Slack, the preview card uses the wrong image (or no image).

**Cause.** Two layers can be wrong:

1. The `<meta property="og:image" content="...">` HTML emitted by Seo.
2. The social platform's URL preview cache, which is independent of your meta tags.

**Diagnosis layer 1 — confirm the right tag is being emitted:**

```bash
curl -s https://your-site/your-page | grep og:image
# Expected: <meta property="og:image" content="https://your-site/path/to/image.jpg">
```

If empty: the fallback chain didn't find an image. Check (in order):

```php
echo $content->og_image;             // primary
echo $content->twitter_image;        // not used in OG fallback (only Twitter)
$thumb = $content->thumbnail();      // OG falls back to this if og_image empty
echo $thumb;
echo \Option::getValue('website_og_image', 'website');   // final fallback
```

If layer 1 is correct but the social card is wrong, layer 2 (the cache) is the culprit:

- **Facebook**: use the Sharing Debugger at `https://developers.facebook.com/tools/debug/` → paste your URL → "Scrape Again". This forces Facebook to re-fetch the meta tags.
- **LinkedIn**: use the Post Inspector at `https://www.linkedin.com/post-inspector/`. Same idea.
- **Twitter / X**: use the Card Validator at `https://cards-dev.twitter.com/validator`. (May require login.)
- **Slack**: post the URL again — Slack re-fetches every time but may show its own short-cache; deleting and reposting refreshes.

For images: ensure your og_image is publicly accessible (not behind an auth wall), is < 5MB, and is at least 1200×630 px for `summary_large_image` cards.

---

## `sitemap_priority` not reflected in `/sitemap.xml`

**Symptom.** Set `sitemap_priority = 1.0` on a content row, but the generated `/sitemap.xml` still shows `<priority>0.5</priority>`.

**Cause.** The Seo module's `getSitemapData($content)` returns the right value — the bug is downstream in the [Sitemap module](/modules/sitemap/) consumption.

**Diagnosis.**

```php
$content = \Modules\Content\Models\Content::find($id);
$svc = app(\Modules\Seo\Services\SeoMetadataService::class);
dd($svc->getSitemapData($content));
```

Check the output's `priority` key:

- If it shows the correct `1.0` → Seo is fine; the Sitemap module's XML emitter has a regression. File against Sitemap.
- If it shows `0.5` → the Seo fallback is firing because the column is empty. Confirm `$content->sitemap_priority` is actually set. Note: `0.0` is treated as the "use default" sentinel by the cast — if you genuinely want a 0.0 priority, set 0.1.

---

## Multilanguage SEO showing wrong-locale value

**Symptom.** German visitors see the English `og_title` instead of the German translation.

**Cause.** Either the locale isn't being set to `de` before the layout renders, or the German translation isn't actually stored.

**Diagnosis.**

```php
app()->setLocale('de');

$content = \Modules\Content\Models\Content::find($id);

dd([
    'current_locale'     => app()->getLocale(),
    'og_title (current)' => $content->og_title,
    'og_title (en)'      => $content->getTranslation('og_title', 'en'),
    'og_title (de)'      => $content->getTranslation('og_title', 'de'),
]);
```

If `og_title (de)` returns `null` → the German translation was never saved. Open the Filament form, switch the locale switcher to German, fill the field, save.

If `og_title (de)` returns the right value but `og_title (current)` shows English — the locale switch isn't taking effect in the layout's render context. Common causes:

- The locale middleware runs AFTER the layout view is selected; check middleware order.
- A previous response cached the wrong-locale HTML; clear the response cache.
- The route is hit before locale negotiation runs (e.g. a static-marketing route that bypasses Microweber's locale middleware).

---

## XSS-looking output in a meta tag

**Symptom.** A meta tag's `content="..."` contains literal HTML, e.g. `<meta name="description" content="<script>...">`.

**Cause.** Either the `sanitizeMetaText()` step was bypassed, OR the value is escaping correctly and the literal `<script>` is the rendered escape — view-source shows `&lt;script&gt;` not `<script>`.

**Diagnosis.**

```bash
# View raw HTML
curl -s https://your-site/some-page | grep 'meta name="description"'
```

If the output contains `&lt;script&gt;` → Seo is correctly escaping; the HTML is safe. The browser's view-source decodes entities back to literals for display, which can confuse a quick glance.

If the output contains literal `<script>` un-escaped — that's a real XSS hole. Check whether a custom Blade directive or a `renderMetaTags()` override bypassed `escapeAttribute()`. The shipped service always escapes; only forks can break this.

**Always run** `escapeHtml()` / `escapeAttribute()` in any custom SEO-rendering code. The two methods are protected — subclass to extend, never copy-paste-without-the-escape.

---

## "Generate from content" AI action does nothing

**Symptom.** Click the AI action next to `og_title` in the Filament form, no error, no fields update.

**Cause.** AI module isn't enabled, or its text-generation provider isn't configured.

**Diagnosis.**

```bash
php artisan module:list | grep -i ai
# Expected: "Ai" module Enabled

php artisan tinker --execute='dd(config("ai.provider"));'
# Expected: "openai" or similar, not null
```

If AI module is disabled, the action button should be hidden (the SEO Filament form does an availability check). If you see the button, AI is enabled but the provider config is wrong:

- Check `config/ai.php` for the active provider's API key.
- Run a smoke test against the provider directly to confirm it's reachable.

If the provider is configured but the action still fails silently, check the browser DevTools network tab for the Livewire request that fires when the button is clicked. The response should contain the generated title/description text; if it contains an error message (truncated by the empty UI), that's the real failure.
