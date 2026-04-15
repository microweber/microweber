# Coupons

Discount coupon system for e-commerce. Create percentage or fixed-amount coupons with usage rules and expiration.

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
<module type="coupons" />
```

### Run migrations

```sh
php artisan module:migrate Coupons
```

### Publish assets

```sh
php artisan module:publish Coupons
```

### Configuration

```php
config('modules.coupons.name')
```

