# Microweber View

Reusable Laravel view package extracted from Microweber CMS: PHP file-based views, Blade string rendering, Twig string rendering, and a pluggable `<module />` tag compiler + `@module` Blade directive. Usable in standalone Laravel apps.

## Features

- **`View`** — lightweight PHP file-based template renderer with an `assign()` / `set()` API; understands templates that `return` a Symfony/Illuminate `Response` or `Renderable`.
- **`StringBlade`** — render (or compile) Blade template **strings** without writing files.
- **`TwigView`** — render Twig template strings via an in-memory `ArrayLoader`.
- **`MicroweberModuleTagCompiler`** — precompiles `<module ... />` tags into `@module([...])`.
- **`@module` Blade directive** — resolves a `ModuleProcessorInterface` from the container; CMS-agnostic (falls back to a `NullModuleProcessor` when no CMS parser is bound).

## Installation

```bash
composer require microweber-packages/microweber-view
```

Inside the Microweber monorepo the provider is registered by the core
`MicroweberPackages\App\Providers\MicroweberServiceProvider`. In a standalone
Laravel app, register it manually:

```php
MicroweberPackages\View\ViewServiceProvider::class,
```

Publish the config (optional):

```bash
php artisan vendor:publish --tag=microweber-view-config
```

## Usage

### PHP file views

```php
use MicroweberPackages\View\View;

$view = new View('/path/to/template.php');   // throws if the file is missing
$view->assign('title', 'Hello');             // single var
$view->set(['name' => 'World', 'items' => []]); // bulk

echo $view;                 // or $view->render()
$html = $view->display(true); // return instead of echo

// If the template `return`ed a Response:
$response = $view->getResponse();
```

### Blade strings (facade or DI)

```php
use MicroweberPackages\View\Facades\StringBlade;

$html = StringBlade::render('Hello {{ $name }}', ['name' => 'World']);
$php  = StringBlade::compile('Hello {{ $name }}'); // compiled PHP source

// or inject the service (registered as a singleton)
$html = app(\MicroweberPackages\View\StringBlade::class)->render('Hi {{ $n }}', ['n' => 1]);
```

### Twig strings (facade or DI)

```php
use MicroweberPackages\View\Facades\TwigView;

$html = TwigView::render('Hello {{ name }}', ['name' => 'World']);

// or: (new \MicroweberPackages\View\TwigView())->render('{{ a }}', ['a' => 1], $twigOptions);
```

### `@module` directive & `<module />` tags

When `module_directive_enabled` is on, the provider registers a Blade
precompiler that rewrites `<module ... />` tags, plus the `@module` directive:

```blade
{{-- Both forms emit a <module /> string and pass it through the resolved
     ModuleProcessorInterface (the CMS parser when bound, else NullModuleProcessor) --}}
<module type="shop/products" template="skin-1" />

@module(['type' => 'posts', 'template' => 'skin-2'])
```

Standalone apps can bind their own processor:

```php
app()->bind(
    \MicroweberPackages\View\Contracts\ModuleProcessorInterface::class,
    MyModuleProcessor::class
);
```

## Configuration

`config/microweber-view.php`:

| Key | Env | Default | Purpose |
| --- | --- | --- | --- |
| `module_directive_enabled` | `MW_VIEW_MODULE_DIRECTIVE` | `true` | Register the `@module` directive and the `<module />` precompiler. |

## Key classes

- `MicroweberPackages\View\View` — PHP file view renderer.
- `MicroweberPackages\View\StringBlade` + `Facades\StringBlade` — Blade string rendering.
- `MicroweberPackages\View\TwigView` + `Facades\TwigView` — Twig string rendering.
- `MicroweberPackages\View\MicroweberModuleTagCompiler` — `<module />` tag compiler.
- `MicroweberPackages\View\MicroweberBladeDirectives` — the `@module` directive.
- `MicroweberPackages\View\Contracts\ModuleProcessorInterface` (+ `Support\CmsModuleProcessorAdapter`, `Support\NullModuleProcessor`) — pluggable module processing.
- `MicroweberPackages\View\ViewServiceProvider` — package registration.

## Tests

```bash
# From the monorepo root
php artisan test --filter=MicroweberPackages\\\\View

# Package-local (after composer install in the package)
composer test
composer analyse
```

Package tests exercise SQLite, MySQL (`root`/`root`) and PostgreSQL
(`postgres`/`postgres`) via `MW_TEST_DB_DRIVER`.

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov

## License

MIT
