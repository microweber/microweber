# PHPQuery for Laravel

A server-side, chainable, CSS3 selector driven DOM API based on jQuery. Standalone Laravel package extracted from [phpQuery](http://code.google.com/p/phpquery/).

## Installation

```bash
composer require microweber-packages/phpquery
```

The service provider and facade are auto-discovered by Laravel.

## Usage

### Static API (original phpQuery style)

```php
use MicroweberPackages\PhpQuery\PhpQuery;

$pq = PhpQuery::newDocument('<div><p class="intro">Hello World</p></div>');
echo $pq->find('.intro')->text(); // "Hello World"
```

### Via Facade

```php
use MicroweberPackages\PhpQuery\Facades\PhpQuery as PhpQuery;

$pq = PhpQuery::newDocument('<ul><li>A</li><li>B</li></ul>');
echo $pq->find('li')->length(); // 2
```

### Via Service Container

```php
$phpquery = PhpQuery::;
$pq = $phpquery->newDocument('<div>Content</div>');
```

### Using the `pq()` helper function

```php
PhpQuery::newDocument('<div><p>Hello</p></div>');
$text = pq('p')->text(); // "Hello"
```

### Backward Compatibility

The package registers global class aliases, so existing code using `\phpQuery::newDocument()` continues to work without modification.

## Package Structure

```
src/
├── Callbacks/          # Callback helper classes
├── Dom/                # DOMDocumentWrapper
├── Events/             # DOMEvent, PhpQueryEvents
├── Facades/            # Laravel Facade
├── Providers/          # Laravel ServiceProvider
├── PhpQuery.php        # Static entry point (factory methods)
├── PhpQueryManager.php # Manager for DI / PhpQuery::
├── PhpQueryObject.php  # Chainable query object (jQuery-like API)
├── PhpQueryPlugins.php # Plugin system
└── helpers.php         # pq() function + class aliases
```

## Testing

```bash
composer test
# or
vendor/bin/phpunit
```

## License

MIT
