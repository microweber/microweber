# microweber-template-custom-css

Standalone Laravel package for **template CSS management**:

- **Live-edit CSS** (`live_edit.css` per template)
- **User custom CSS** (option-stored `custom_css`)
- **Arbitrary multi-file registry** (register per-page or custom slots)
- **CSS validation** via [sabberworm/php-css-parser](https://github.com/sabberworm/PHP-CSS-Parser)
- **URL rewriting** for portable backups (`userfiles_url` → `../../`)
- **Multisite** filenames (`live_edit_{environment}.css`)
- **Filament** admin page for editing CSS
- Routes compatible with Microweber live-edit frontend JS

## Installation

```bash
composer require microweber-packages/template-custom-css
```

Publish config (optional):

```bash
php artisan vendor:publish --tag=template-custom-css-config
```

## File locations (CMS-compatible)

| Slot | Path |
|------|------|
| Live edit | `{css_base_path}/{template}/live_edit.css` |
| Multisite live edit | `{css_base_path}/{template}/live_edit_{env}.css` |
| Custom CSS cache | `{css_cache_path}/custom_css.{crc32(site)}.{version}.css` |

In Microweber CMS these resolve to:

- `userfiles/css/{template}/live_edit.css`
- `userfiles/cache/custom_css.*.css`

**Do not change these paths** — template backup/restore depends on them.

## Usage (standalone Laravel)

```php
use MicroweberPackages\TemplateCustomCss\Facades\TemplateCustomCss;

// Save live-edit CSS for a template
TemplateCustomCss::liveEdit()->saveLiveEditCssContent('.hero { color: red; }', 'my-theme');

// User custom CSS
TemplateCustomCss::customCss()->saveCustomCss('body { font-size: 16px; }');

// Validate
$result = TemplateCustomCss::validate('a { color: #fff; }'); // ['valid' => true, 'errors' => []]

// Register a future per-page file
TemplateCustomCss::registerFileType(new \MicroweberPackages\TemplateCustomCss\Services\RegisteredCssFileHandler(
    'page_42',
    ['filename' => 'page_42.css', 'storage' => 'file', 'validate' => true, 'rewrite_urls' => true],
    config('template-custom-css'),
    app(\MicroweberPackages\TemplateCustomCss\Contracts\OptionStoreInterface::class),
    app(\MicroweberPackages\TemplateCustomCss\Services\CssValidator::class),
    app(\MicroweberPackages\TemplateCustomCss\Services\CssUrlRewriter::class),
));
```

## Routes

| Method | Path | Name |
|--------|------|------|
| POST | `/api/current_template_save_custom_css` | `current_template_save_custom_css` |
| POST | `/api/layouts/template_remove_custom_css` | `template_remove_custom_css` |
| ANY | `/api/template/print_custom_css` | `print_custom_css` |
| POST | `/api/template/save_custom_css` | `api.template.save_custom_css` |
| POST | `/api/template/validate_css` | `api.template.validate_css` |
| GET | `/api/template/live_edit_css_url` | `api.template.live_edit_css_url` |

## Tests

```bash
# From monorepo root
php artisan test packages/microweber-template-custom-css

# PHPStan (level 9)
composer analyse -- packages/microweber-template-custom-css/src
# or
./vendor/bin/phpstan analyse -c packages/microweber-template-custom-css/phpstan.neon.dist
```

## License

MIT
