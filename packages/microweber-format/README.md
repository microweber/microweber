# microweber-format

Reusable Laravel formatting & utility package – dates, strings, arrays, encoding, XSS/HTML cleaning helpers.

## Installation

```bash
composer require microweber-packages/format
```

## Usage

Register via the package service provider (auto-discovered). Access through the **Format** facade (preferred) or inject `FormatService`.

```php
// Via the Facade (recommended)
use MicroweberPackages\Format\Facades\Format;

Format::autolink('Visit https://example.com today');
Format::limit('Hello world this is a long string', 10);

// Via dependency injection
use MicroweberPackages\Format\FormatService;

public function __construct(private FormatService $format) {}
```

Service class: `MicroweberPackages\Format\FormatService`  
Facade class: `MicroweberPackages\Format\Facades\Format`

## License

MIT
