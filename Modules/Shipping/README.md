# Shipping

Shipping provider management. Configure shipping methods, rates, zones, and carrier integrations.

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
<module type="shipping" />
```

### Run migrations

```sh
php artisan module:migrate Shipping
```

### Publish assets

```sh
php artisan module:publish Shipping
```

### Configuration

```php
config('modules.shipping.name')
```

