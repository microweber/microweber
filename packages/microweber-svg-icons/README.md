# Microweber SVG Icons

A standalone Blade-Icons-compatible package that ships all Microweber CMS SVG icons.

## Installation

The package is auto-discovered by Laravel. It registers itself as the `mw` icon set with [Blade Icons](https://github.com/blade-ui-kit/blade-icons).

### In the CMS monorepo

The root `composer.json` already references this package via `path` repository. After `composer install`, all icons are available.

### Standalone (any Laravel app)

```bash
composer require microweber-packages/svg-icons
```

## Usage

### Blade directive

```blade
@svg('mw-text', 'h-6 w-6')
@svg('mw-checkbox', 'h-8 w-8 text-primary-500')
@svg('mw-no-content', 'w-48 h-48')
```

### Blade component

```blade
<x-mw-text class="h-6 w-6" />
<x-mw-no-content class="w-48 h-48" />
```

### PHP (Filament icons, etc.)

```php
Tables\Table::make()
    ->emptyStateIcon('mw-no-content');
```

## Publishing assets

To serve icons via public URL:

```bash
php artisan vendor:publish --tag=mw-svg-icons --force
```

Icons will be available at `/vendor/microweber-packages/svg-icons/<name>.svg`.

## Available icons

| Name | Blade usage |
|------|-------------|
| text | `mw-text` |
| numbers | `mw-numbers` |
| checkbox | `mw-checkbox` |
| dropdown | `mw-dropdown` |
| email | `mw-email` |
| hidden | `mw-hidden` |
| radio-checked | `mw-radio-checked` |
| no-content | `mw-no-content` |
| no-pages | `mw-no-pages` |
| no-products | `mw-no-products` |
| no-orders | `mw-no-orders` |
| no-categories | `mw-no-categories` |
| no-clients | `mw-no-clients` |
| no-invoices | `mw-no-invoices` |
| no-notifications | `mw-no-notifications` |
| ... | See `SvgIconsServiceProvider::availableIcons()` for the full list |

## Naming convention

All icon filenames use **lowercase kebab-case** (`my-icon.svg`). No underscores, no camelCase.

## Testing

```bash
cd packages/microweber-svg-icons
composer install
vendor/bin/phpunit
vendor/bin/phpstan analyse
```

## License

MIT
