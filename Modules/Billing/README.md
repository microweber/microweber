# Billing

Subscription billing and plan management. Handle recurring payments, subscription plans, and billing cycles.

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
<module type="billing" />
```

### Run migrations

```sh
php artisan module:migrate Billing
```

### Publish assets

```sh
php artisan module:publish Billing
```

### Configuration

```php
config('modules.billing.name')
```

### Views

```php
view('modules.billing::index')
```

