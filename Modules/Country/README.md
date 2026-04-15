# Country

Country and region data provider. Supplies country lists, codes, and region data for forms and shipping.

## Structure

- Eloquent models
- Database migrations
- Tests

## Usage

### Module tag

```html
<module type="country" />
```

### Run migrations

```sh
php artisan module:migrate Country
```

### Publish assets

```sh
php artisan module:publish Country
```

### Configuration

```php
config('modules.country.name')
```

