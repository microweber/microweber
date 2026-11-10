# PHPQuery for Laravel

A server-side, chainable, CSS3 selector driven DOM API based on jQuery. Standalone Laravel package extracted from [phpQuery](http://code.google.com/p/phpquery/).

## Installation

```bash
composer require microweber-packages/microweber-phpquery
```

Inside Microweber the `PhpQueryServiceProvider` is registered automatically by the framework's package discovery. In a standalone Laravel app register it manually (`MicroweberPackages\PhpQuery\Providers\PhpQueryServiceProvider::class`) — but note the library is used **directly via its static API and the `pq()` helper**; there is no manager binding or facade to resolve.

The `pq()` helper and the backward-compatible class aliases are loaded via the composer `files` autoload (`src/helpers.php`), so they are available even without the provider.

## Usage

### Static API (original phpQuery style)

```php
use MicroweberPackages\PhpQuery\PhpQuery;

$pq = PhpQuery::newDocument('<div><p class="intro">Hello World</p></div>');
echo $pq->find('.intro')->text(); // "Hello World"

$pq = PhpQuery::newDocument('<ul><li>A</li><li>B</li></ul>');
echo $pq->find('li')->length(); // 2
```

Other document factories: `newDocumentHTML()`, `newDocumentXML()`, `newDocumentXHTML()`, `newDocumentFile()`, `newDocumentFileHTML()`.

### The `pq()` helper

`pq()` queries against the *last selected document* (set by `newDocument()`), mirroring jQuery's global `$()`:

```php
use MicroweberPackages\PhpQuery\PhpQuery;

PhpQuery::newDocument('<div><p>Hello</p></div>');
echo pq('p')->text();                 // "Hello"
pq('p')->addClass('lead')->append(' World');
echo pq('div')->htmlOuter();
```

### Manipulating the DOM

```php
use MicroweberPackages\PhpQuery\PhpQuery;

$doc = PhpQuery::newDocument('<ul id="list"><li>A</li></ul>');
$doc->find('#list')->append('<li>B</li>');

foreach ($doc->find('li') as $li) {
    echo pq($li)->text(); // "A", "B"
}

echo $doc->htmlOuter(); // serialise the whole document back to HTML
```

### Backward compatibility

The package registers global class aliases, so existing code using `\phpQuery::newDocument()`, `\phpQueryObject`, `\DOMDocumentWrapper`, etc. keeps working without modification:

```php
$pq = phpQuery::newDocument('<p>Legacy call</p>');
echo $pq->find('p')->text(); // "Hello"
```

## Package structure

```
src/
├── Callbacks/          # Callback helper classes (Callback, CallbackBody, ...)
├── Dom/                # DOMDocumentWrapper
├── Events/             # DOMEvent, PhpQueryEvents
├── Providers/          # PhpQueryServiceProvider (spatie package-tools)
├── PhpQuery.php        # Static entry point + jQuery-style utilities (factory methods, ajax, each/map/grep)
├── PhpQueryObject.php  # Chainable query object (find, text, html, append, addClass, ...)
├── PhpQueryPlugins.php # Plugin system (PhpQuery::plugin(), PhpQuery::extend())
└── helpers.php         # pq() function + global class aliases + mbstring shims
```

## Testing

```bash
composer test
# or
vendor/bin/phpunit
```

## Key classes

- `MicroweberPackages\PhpQuery\PhpQuery` — static factory & utility API
- `MicroweberPackages\PhpQuery\PhpQueryObject` — chainable result set
- `MicroweberPackages\PhpQuery\PhpQueryPlugins` — plugin registry
- `pq()` — global helper (composer `files` autoload)

## License

MIT

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov
