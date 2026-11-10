# Microweber Security

A reusable Laravel package providing HTML sanitization, XSS cleaning, and stored-XSS stripping. Each service is registered as a container singleton and exposed through a greppable Facade.

## Installation

```bash
composer require microweber-packages/microweber-security
```

Inside Microweber the `SecurityServiceProvider` is registered automatically by the framework's package discovery. In a standalone Laravel app register it manually (`MicroweberPackages\Security\SecurityServiceProvider::class`); it binds `HtmlClean`, `XSSClean` and `XSSSecurity` as singletons.

## Facades

Three facades expose the services (resolve the singleton of the same name):

| Facade | Service | Main method |
|--------|---------|-------------|
| `MicroweberPackages\Security\Facades\HtmlClean` | `HtmlClean` | `clean($html, $options = [])` |
| `MicroweberPackages\Security\Facades\XSSClean` | `XSSClean` | `clean($html)` |
| `MicroweberPackages\Security\Facades\XSSSecurity` | `XSSSecurity` | `clean($str, $method = 'process')` |

```php
use MicroweberPackages\Security\Facades\HtmlClean;
use MicroweberPackages\Security\Facades\XSSClean;
use MicroweberPackages\Security\Facades\XSSSecurity;

$safeHtml  = HtmlClean::clean($userHtml);
$safeInput = XSSClean::clean($userInput);
$safeAttr  = XSSSecurity::clean($userString);
```

## Usage

### HtmlClean

HTML sanitization using Symfony HtmlSanitizer with Microweber-specific element/attribute allowlists. Available via the facade, dependency injection, or `new`:

```php
use MicroweberPackages\Security\HtmlClean;

$cleaner = app(HtmlClean::class); // singleton
$safe = $cleaner->clean($html);

// Admin mode (allows more elements):
$safe = $cleaner->clean($html, ['admin_mode' => true]);

// Clean every string value in an array:
$safeRow = $cleaner->cleanArray($request->all());

// Keep only an allowlist of inline tags, strip everything else:
$snippet = $cleaner->onlyTags($html); // defaults: i,a,strong,code,pre,blockquote,em,strike,p,span,caption,cite
$snippet = $cleaner->onlyTags($html, ['b', 'i', 'a']);
```

### XSSClean

Full XSS cleaning using `voku/anti-xss` with curated blocklists:

```php
use MicroweberPackages\Security\XSSClean;

$cleaner = app(XSSClean::class);
$safe = $cleaner->clean($userInput);
$safeRow = $cleaner->cleanArray($request->all());
```

### XSSSecurity

CodeIgniter-derived XSS filter. Runs up to three passes and removes "evil" attributes (`on*` handlers, `style`, `formaction`, …). The evil-attribute list can be overridden via the constructor:

```php
use MicroweberPackages\Security\XSSSecurity;

$cleaner = app(XSSSecurity::class);
$safe = $cleaner->clean($str);              // array or string
$safe = $cleaner->removeEvilAttributes($html);

// Custom evil-attribute list:
$cleaner = new XSSSecurity(['on\w*', 'style', 'srcdoc']);
```

### StoredXssStripper

Static defense-in-depth pass that strips stored-XSS vectors from HTML strings:

```php
use MicroweberPackages\Security\StoredXssStripper;

$safe = StoredXssStripper::strip('<img src=x onerror=alert(1)>');
// Result: '<img src=x>'
```

### HtmlSanitizer components

Lower-level, customizable sanitizer building blocks used by `HtmlClean`:

- `MwHtmlSanitizer` — custom HTML sanitizer implementation
- `MwHtmlSanitizerConfig` — configuration with Microweber element/attribute allowlists
- `MwHtmlSanitizerReference` — reference lists for allowed/blocked attributes
- `MwHtmlSanitizerDomVisitor` — custom DOM visitor for sanitization
- `MwHtmlSanitizerDomNode` / `MwHtmlSanitizerTextNode` — custom node rendering
- `MwAttrbuteSanitizer` — attribute-level sanitizer

## Key classes

- `MicroweberPackages\Security\HtmlClean`
- `MicroweberPackages\Security\XSSClean`
- `MicroweberPackages\Security\XSSSecurity`
- `MicroweberPackages\Security\StoredXssStripper`
- `MicroweberPackages\Security\SecurityServiceProvider`

## Testing

```bash
composer test
```

### Standalone validation

A minimal Laravel (Orchestra Testbench) app lives in `standalone-validation/`:

```bash
cd standalone-validation
composer install
./vendor/bin/phpunit
```

### Static analysis

```bash
# From monorepo root
composer analyse -- packages/microweber-security/src
# Or package config
../../vendor/bin/phpstan analyse -c phpstan.neon.dist
```

## License

MIT

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov
