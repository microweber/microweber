---
name: layout-screenshot-naming
description: >-
  The Insert Layout modal shows thumbnail screenshots for each layout card. Use
  this whenever you need to add or diagnose missing thumbnails in the layout
  picker. Blank white layout cards are the symptom — the root cause is always
  a missing PNG at the expected public path. This skill covers the full chain:
  naming convention, physical file placement, and how ScanForBladeTemplates
  decides whether a screenshot renders.
---

# Layout Screenshot Naming Convention

## Problem

The Insert Layout modal (ListLayouts.vue) can show blank white areas where
thumbnails should appear. The symptom is 100% reproducible: all cards blank for
a specific template, yet the Vue component is rendering correctly. Adding a
screenshot file immediately fixes the card without any code change.

## Root Cause

`ScanForBladeTemplates.php` (lines 286–323) only sets `screenshot_public_url`
on a layout entry when `is_file($screen2)` passes:

```php
$imgName = str_replace('/', '.', $layoutFilePath) . '.png'; // e.g. blog.skin-1.png
$screen2 = public_path('templates/' . $templateName . '/img/screenshots/modules/layouts/templates/' . $imgName);
if (is_file($screen2)) {
    $to_return_temp['screenshot_public_url'] = $screenshotPublic;
}
```

If the PNG is absent, `screenshot_public_url` is never set → Vue renders a blank
white div.

## Naming Convention

Layout file path → PNG filename: replace every `/` with `.` and append `.png`.

| Layout file (relative to `modules/layouts/templates/`) | PNG filename |
|---|---|
| `default.blade.php` | `default.png` |
| `blog/skin-1.blade.php` | `blog.skin-1.png` |
| `ecommerce/skin-1.blade.php` | `ecommerce.skin-1.png` |
| `footers/footer_cart.blade.php` | `footers.footer_cart.png` |
| `pricing/skin-2.blade.php` | `pricing.skin-2.png` |

## File Placement

Screenshots must exist at **two** locations (one is the PHP `is_file()` check, one
is the build source that gets deployed):

```
public/templates/<template>/img/screenshots/modules/layouts/templates/<name>.png
Templates/<Template>/resources/assets/img/screenshots/modules/layouts/templates/<name>.png
```

`public/` is the served location; `Templates/<Template>/resources/assets/` is the
source. Keep them in sync. The served `public/` path is what `is_file()` checks.

## Static Proxy Screenshots

When a template has no original screenshots, copy representative PNGs from
another template (e.g. Big2) as stand-ins. The named convention lets you pick
the most visually similar layout:

```bash
# Inside the template's resources dir
mkdir -p img/screenshots/modules/layouts/templates/
cp /path/to/big2-source/blog.skin-1.png img/screenshots/modules/layouts/templates/blog.skin-1.png
# Repeat for each layout
```

Proxies are fine for MVP; designers can swap them for real screenshots later.

## Discovery Command

To enumerate all layouts for a template and their expected screenshot names:

```bash
find Templates/<Template>/modules/layouts/templates/ -name '*.blade.php' \
  | sed 's|.*/layouts/templates/||; s|\.blade\.php||; s|/|.|g' \
  | sort
```

## Contract Test Pattern

```php
#[DataProvider('bootstrapLayoutScreenshotProvider')]
public function bootstrap_layout_screenshot_file_exists(string $filename): void
{
    $this->assertFileExists(
        $this->screenshotDir . '/' . $filename,
        "Bootstrap layout screenshot '{$filename}' must exist..."
    );
}

public static function bootstrapLayoutScreenshotProvider(): array
{
    return [
        'default layout'   => ['default.png'],
        'blog skin-1'      => ['blog.skin-1.png'],
        // ... one entry per layout
    ];
}
```

## Do NOT

- Do NOT add `screenshot_public_url` as a Vue data property — it is set by the PHP
  `ScanForBladeTemplates.php` layer. Adding it in JS bypasses the is_file() check
  and would show broken images if the file later disappears.
- Do NOT name screenshots with slashes (`blog/skin-1.png`) — the convention uses
  dots as separators.
- Do NOT forget `public/` placement — the `Templates/` source path alone is not
  enough; `is_file()` reads from `public/`.

## Applies To

- `ScanForBladeTemplates.php` (line 286+)
- `public/templates/<template>/img/screenshots/modules/layouts/templates/`
- `Templates/<Template>/resources/assets/img/screenshots/modules/layouts/templates/`
- `ListLayouts.vue` (screenshot_public_url binding)
- Any ticket where layout cards appear blank white in the Insert Layout modal
