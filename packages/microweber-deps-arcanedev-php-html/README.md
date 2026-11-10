# Deps Arcanedev Php Html

Vendored fork of **[arcanedev/php-html](https://github.com/ARCANEDEV/php-html)** — a fluent, object-oriented HTML element builder — patched for Laravel 13 compatibility. This package `replace`s `arcanedev/php-html` in Composer so the upstream library resolves to this fork across the Microweber monorepo.

> This is a vendored dependency. It has **no Microweber-specific public API, service provider, or facade** — it keeps the upstream `Arcanedev\Html\*` namespace unchanged. Document/consume it as you would the upstream library.

Part of the [Microweber](https://microweber.com) CMS.

## Installation

```bash
composer require microweber-packages/microweber-deps-arcanedev-php-html
```

`"replace": { "arcanedev/php-html": "*" }` — once installed, anything that depends on `arcanedev/php-html` uses this fork. There is no auto-discovered provider; elements are used directly as classes.

## Usage

Elements under `Arcanedev\Html\Elements\*` are built fluently via `::make()` and rendered via `toHtml()` / `__toString()`:

```php
use Arcanedev\Html\Elements\A;
use Arcanedev\Html\Elements\P;

echo A::make()
    ->href('https://microweber.com')
    ->text('Visit Microweber')
    ->class('btn btn-primary')
    ->toHtml();
// <a href="https://microweber.com" class="btn btn-primary">Visit Microweber</a>

echo (string) P::make()->text('Hello world');
// <p>Hello world</p>
```

The optional `Arcanedev\Html\Html` builder exposes shorthand factories — `a()`, `button()`, `div()`, `form()`, `input()`, `label()`, `select()`, `img()`, `ul()`, `ol()`, `span()`, `textarea()`, `email()`, `date()`, etc.

### Real call site in Microweber

`MicroweberPackages\MetaTags\Entities\CustomHeadTagsFromCallback` imports `Arcanedev\Html\Elements\P` from this package when composing custom `<head>` tag output.

## Key classes

- Builder: `Arcanedev\Html\Html`
- Elements: `Arcanedev\Html\Elements\*` (`A`, `Button`, `Div`, `Form`, `Input`, `Label`, `Select`, `Option`, `Img`, `Ul`, `Ol`, `P`, `Span`, `Textarea`, …)
- Base element: `Arcanedev\Html\Elements\HtmlElement` (`make()`, `text()`, `html()`, `class()`, `attribute()`, `children()`, `render()`, `toHtml()`)
- Contracts: `Arcanedev\Html\Contracts\*`

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov

## License

MIT
