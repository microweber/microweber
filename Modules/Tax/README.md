# Tax

Tax calculation and management. Configure tax rates, rules, and regional tax settings for e-commerce.

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
<module type="tax" />
```

### Run migrations

```sh
php artisan module:migrate Tax
```

### Publish assets

```sh
php artisan module:publish Tax
```

### Configuration

```php
config('modules.tax.name')
```

