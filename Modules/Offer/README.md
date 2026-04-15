# Offer

Special offer and promotion management. Create time-limited deals, bundles, and promotional pricing.

## Structure

- Filament admin
- Eloquent models
- HTTP controllers
- Route definitions
- Blade views
- Database migrations
- Tests

## Usage

### Module tag

```html
<module type="offer" />
```

### Run migrations

```sh
php artisan module:migrate Offer
```

### Publish assets

```sh
php artisan module:publish Offer
```

### Views

```php
view('modules.offer::index')
```

