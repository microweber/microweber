# Microweber Security

A reusable Laravel package providing HTML sanitization, XSS cleaning, and stored-XSS stripping.

## Installation

```bash
composer require microweber-packages/security
```

The package auto-discovers its service provider via Laravel's package discovery.

## Usage

### StoredXssStripper

Defense-in-depth pass that strips stored-XSS vectors from HTML strings:

```php
use MicroweberPackages\Security\StoredXssStripper;

$safe = StoredXssStripper::strip('<img src=x onerror=alert(1)>');
// Result: '<img src=x>'
```

### XSSClean

Full XSS cleaning using voku/anti-xss with curated blocklists:

```php
use MicroweberPackages\Security\XSSClean;

$cleaner = new XSSClean();
$safe = $cleaner->clean($userInput);
```

### HtmlClean

HTML sanitization using Symfony HtmlSanitizer with Microweber-specific element/attribute allowlists:

```php
use MicroweberPackages\Security\HtmlClean;

$cleaner = new HtmlClean();
$safe = $cleaner->clean($html);

// Admin mode (allows more elements):
$safe = $cleaner->clean($html, ['admin_mode' => true]);
```

### HtmlSanitizer Components

The package includes customizable HTML sanitizer components:

- `MwHtmlSanitizer` — Custom HTML sanitizer implementation
- `MwHtmlSanitizerConfig` — Configuration with Microweber element/attribute allowlists
- `MwHtmlSanitizerReference` — Reference lists for allowed/blocked attributes
- `MwHtmlSanitizerDomVisitor` — Custom DOM visitor for sanitization
- `MwHtmlSanitizerDomNode` — Custom DOM node rendering
- `MwAttrbuteSanitizer` — Attribute-level sanitizer

## Testing

```bash
composer test
```

## License

MIT
## Standalone validation

A minimal Laravel (Orchestra Testbench) app lives in `standalone-validation/`:

```bash
cd standalone-validation
composer install
./vendor/bin/phpunit
```

## Static analysis

```bash
# From monorepo root (level 7 maximized)
composer analyse -- packages/microweber-security/src
# Or package config
../../vendor/bin/phpstan analyse -c phpstan.neon.dist
```
