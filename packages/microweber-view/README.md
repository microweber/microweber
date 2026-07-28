# microweber-view

Reusable Laravel package extracted from Microweber CMS.

## Features

- **View** – PHP file-based template renderer with assign/set API
- **StringBlade** – render Blade template strings
- **TwigView** – render Twig template strings
- **MicroweberModuleTagCompiler** – compile `<module />` tags into `@module([...])`
- **@module** Blade directive – pluggable via `ModuleProcessorInterface`
- Optional health / demo HTTP routes
- Optional Filament “View Tools” page

## Install (standalone Laravel)

```bash
composer require microweber-packages/view
```

Register the service provider (auto-discovered unless disabled):

```php
MicroweberPackages\View\ViewServiceProvider::class,
```

Publish config:

```bash
php artisan vendor:publish --tag=microweber-view-config
```

## Usage

```php
use MicroweberPackages\View\View;
use MicroweberPackages\View\StringBlade;
use MicroweberPackages\View\TwigView;

$view = new View('/path/to/template.php');
$view->assign('title', 'Hello');
echo $view; // or $view->display()

$html = app(StringBlade::class)->render('Hello {{ $name }}', ['name' => 'World']);
$html = (new TwigView())->render('Hello {{ name }}', ['name' => 'World']);
```

## Tests

```bash
# From monorepo root
php artisan test --filter=MicroweberPackages\\\\View

# Package-local (after composer install in the package)
composer test
composer analyse
```

## Databases

Package tests exercise SQLite, MySQL (`root`/`root`) and PostgreSQL (`postgres`/`postgres`) via `MW_TEST_DB_DRIVER`.
