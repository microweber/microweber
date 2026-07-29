# microweber-packages/template-fonts

Standalone Laravel package for template font management:

- **Google Fonts** provider (catalog + optional local download)
- **Custom font** uploads (TTF, WOFF, WOFF2, OTF)
- **System fonts** list for pickers
- `template_fonts` database table (MySQL / SQLite / PostgreSQL)
- CSS generation (`@import` / `@font-face`)
- Filament admin resource + settings page
- Legacy migration from `options.enabled_custom_fonts`

## Install

```bash
composer require microweber-packages/template-fonts
php artisan migrate
```

Or path-repo in a monorepo:

```json
{
  "repositories": [{ "type": "path", "url": "packages/microweber-template-fonts" }],
  "require": { "microweber-packages/template-fonts": "^1.0" }
}
```

Register the service provider (auto-discovery works; Microweber disables discovery and registers manually).

## Usage

```php
use MicroweberPackages\TemplateFonts\Facades\TemplateFonts;

TemplateFonts::enableFont('Roboto');
TemplateFonts::getEnabledFonts();
TemplateFonts::getFontsStylesheetCss();
TemplateFonts::uploadCustomFont($uploadedFile, 'My Font');
```

## Routes

| Method | Path | Name |
|--------|------|------|
| GET | `/api/template/get-fonts` | `api.template.get-fonts` |
| GET | `/api/template/get-favorite-fonts` | `api.template.get-favorite-fonts` |
| POST | `/api/template/save-template-fonts` | `api.template.save-template-fonts` |
| POST | `/api/template/remove-favorite-font` | `api.template.remove-favorite-font` |
| POST | `/api/template/upload-custom-font` | `api.template.upload-custom-font` |
| ANY | `/api/template/print_custom_css_fonts` | `print_custom_css_fonts` |

## Filament

```php
$panel->plugin(\MicroweberPackages\TemplateFonts\Filament\TemplateFontsPlugin::make());
```

## Tests / analysis

```bash
composer test
composer analyse
# or from monorepo root:
php artisan test --filter=TemplateFonts
composer analyse -- packages/microweber-template-fonts/src
```
