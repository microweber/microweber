# Invoice

Invoice generation and management for e-commerce orders. Create, view, and download PDF invoices.

## Structure

- Filament admin
- Eloquent models
- HTTP controllers
- Service classes
- Route definitions
- Blade views
- Database migrations
- Tests

## Usage

### Module tag

```html
<module type="invoice" />
```

### Run migrations

```sh
php artisan module:migrate Invoice
```

### Publish assets

```sh
php artisan module:publish Invoice
```

### Configuration

```php
config('modules.invoice.name')
```

### Views

```php
view('modules.invoice::index')
```

