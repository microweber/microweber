# Microweber Pagination

Unified, reusable pagination package for Laravel. Supports Bootstrap, Tailwind, dropdown styles with size variants and windowed page links for large datasets.

## Features

- **Multiple themes:** `bootstrap`, `bootstrap-flex`, `bootstrap-dropdown`, `tailwind`, `tailwind-flex`, `tailwind-dropdown`
- **Size variants:** `sm`, `md`, `lg`, `xl`
- **Windowed page links:** Shows a configurable offset of pages around the current page for large lists (e.g., 95 96 97 98 99 **100** 101 102 103 104 105)
- **Custom CSS classes:** Override any class (active, disabled, item, link, wrapper, etc.)
- **View overrides:** Use any registered Blade view from your template
- **Legacy array format:** Compatible with existing Microweber pagination templates
- **Factory with config defaults:** Reads from `config/mw-pagination.php`
- **Laravel LengthAwarePaginator integration:** Static factory method

## Usage

```php
use MicroweberPackages\Pagination\Paginator;

// Basic usage
$paginator = new Paginator([
    'currentPage' => 5,
    'lastPage'    => 100,
    'baseUrl'     => url('/items'),
    'theme'       => 'bootstrap',
    'size'        => 'md',
    'onEachSide'  => 5,
]);

echo $paginator->render();

// Fluent API
$paginator = (new Paginator())
    ->currentPage(50)
    ->lastPage(1000)
    ->baseUrl('/products')
    ->theme('tailwind')
    ->size('lg')
    ->onEachSide(5)
    ->customClasses(['active' => 'bg-indigo-500 text-white']);

echo $paginator;

// From Laravel paginator
$laravelPaginator = Product::paginate(15);
$paginator = Paginator::fromLaravel($laravelPaginator, [
    'theme'     => 'bootstrap-flex',
    'onEachSide' => 3,
]);

// View override from template
$paginator = new Paginator([
    'currentPage' => 5,
    'lastPage'    => 100,
    'baseUrl'     => '/items',
    'view'        => 'templates.my-theme.pagination',
]);
```

## Configuration

Publish the config file:

```bash
php artisan vendor:publish --tag=mw-pagination-config
```

## License

MIT