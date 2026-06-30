# microweber-format

Reusable Laravel formatting & utility package – dates, strings, arrays, encoding, XSS/HTML cleaning helpers.

## Installation

```bash
composer require microweber-packages/format
```

## Usage

```php
// Via the container
app()->format->limit('Hello world this is a long string', 10);

// Via the Facade
use MicroweberPackages\Format\Facades\FormatFacade as Format;
Format::autolink('Visit https://example.com today');
```

## License

MIT