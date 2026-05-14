# Examples

Four end-to-end recipes for common Sitemap-module integrations.

---

## 1. Verify your sitemap is being served correctly

Drop these into your project's deploy-smoke-check script:

```bash
#!/usr/bin/env bash
set -euo pipefail

SITE="${1:-https://your-site.com}"

echo "→ Sitemap index"
curl -fsS -o /tmp/sitemap-index.xml "$SITE/sitemap.xml"
xmllint --schema https://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd \
    --noout /tmp/sitemap-index.xml
echo "  valid ✓"

for type in categories products posts pages tags; do
    echo "→ $type"
    curl -fsS -o "/tmp/sitemap-$type.xml" "$SITE/sitemap.xml/$type"
    xmllint --schema https://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd \
        --noout "/tmp/sitemap-$type.xml"
    count=$(xmllint --xpath 'count(//*[local-name()="url"])' "/tmp/sitemap-$type.xml")
    echo "  $count URLs, valid ✓"
done
```

Validates:
- Every route returns 200.
- Every XML response validates against the official sitemap XSD.
- Per-type URL counts are surfaced so you can spot drops (e.g. accidentally bulk-deactivated all products).

Run after every production deploy.

---

## 2. Set sitemap priority for a content type bulk

Boost products to priority 0.9 and demote tag pages to 0.3:

```php
use Modules\Product\Models\Product;
use Modules\Page\Models\Page;

// All active products → priority 0.9
Product::query()
    ->where('is_active', 1)
    ->where('is_deleted', 0)
    ->update(['sitemap_priority' => 0.9]);

// All tag-archive pages → priority 0.3 + changefreq monthly
Page::query()
    ->where('subtype', 'tag')   // or whatever marker your tag pages use
    ->update([
        'sitemap_priority'   => 0.3,
        'sitemap_changefreq' => 'monthly',
    ]);

echo Product::where('sitemap_priority', 0.9)->count() . " products updated\n";
```

Search engines treat priority as a relative hint (within your own site), not an absolute ranking signal. The convention worth respecting:

- 1.0 — homepage, top-level landing pages
- 0.8 — main product / article pages
- 0.5 — default
- 0.3 — tag archives, low-conversion pages
- 0.1 — pagination pages, filter results

---

## 3. Add a `Sitemap:` directive to robots.txt at deploy time

If you don't want to depend on admins remembering to set this, add it to your deploy script:

```php
// In a one-shot artisan command or post-deploy hook:

use MicroweberPackages\Option\Models\Option;

$currentRobots = Option::getValue('robots_txt', 'website') ?? '';
$sitemapLine   = 'Sitemap: ' . url('/sitemap.xml');

if (strpos($currentRobots, $sitemapLine) === false) {
    $updated = rtrim($currentRobots) . "\n\n" . $sitemapLine . "\n";
    Option::setValue('robots_txt', $updated, 'website');
    echo "Added Sitemap: directive to robots.txt\n";
} else {
    echo "Sitemap: directive already present\n";
}
```

The check guards against duplicating the line on repeated deploys. After this runs, `https://your-site.com/robots.txt` will include the sitemap URL — crawlers pick it up on their next visit.

---

## 4. Exclude an entire content section from the sitemap

Suppose `/internal/*` is a private section you do NOT want indexed:

```php
use Modules\Content\Models\Content;
use Modules\Page\Models\Page;

// Find the /internal parent page
$internalParent = Page::where('url', 'internal')->firstOrFail();

// Mark every descendant excluded from sitemap
Content::query()
    ->where('parent', $internalParent->id)
    ->orWhereIn('parent', function ($q) use ($internalParent) {
        $q->select('id')->from('content')->where('parent', $internalParent->id);
    })
    ->update(['exclude_from_sitemap' => true]);

// Also exclude the parent itself
$internalParent->exclude_from_sitemap = true;
$internalParent->save();
```

This guarantees:

- The `/internal/*` rows are filtered out of `/sitemap.xml/<type>` queries.
- The Seo module's robots-meta guard returns `noindex, nofollow` for any of these rows (`exclude_from_sitemap` is one of the inputs to the `getRobotsMeta()` chain — confirm in your version; if not, set `robots_meta = 'noindex, nofollow'` on the rows too).
- Manual visitors who type the URL still see the content; only search crawlers are excluded.

For a more aggressive approach (block crawlers from even fetching the URLs), edit `robots.txt`:

```
User-agent: *
Disallow: /internal/

Sitemap: https://your-site.com/sitemap.xml
```

The combination of `Disallow` in robots.txt + `exclude_from_sitemap = true` in the database is belt-and-braces: crawlers neither find the URLs in the sitemap nor fetch them if linked from elsewhere.
