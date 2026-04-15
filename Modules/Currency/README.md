# Currency

Multi-currency support for the shop. Configure exchange rates, default currency, and currency display format.

## Structure

- Filament admin
- Livewire components
- Eloquent models
- HTTP controllers
- Service classes
- Blade views
- Database migrations
- Tests

## Usage

### Module tag

```html
<module type="currency" />
```

### Run migrations

```sh
php artisan module:migrate Currency
```

### Publish assets

```sh
php artisan module:publish Currency
```

### Configuration

```php
config('modules.currency.name')
```

### Views

```php
view('modules.currency::index')
```

