# Customer

Customer management for e-commerce. Track customer accounts, order history, and profile information.

## Structure

- Filament admin
- Eloquent models
- Service classes
- Route definitions
- Database migrations
- Tests

## Usage

### Module tag

```html
<module type="customer" />
```

### Run migrations

```sh
php artisan module:migrate Customer
```

### Publish assets

```sh
php artisan module:publish Customer
```

### Configuration

```php
config('modules.customer.name')
```

