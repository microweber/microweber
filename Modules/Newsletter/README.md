# Newsletter

Email newsletter management. Build subscriber lists, compose campaigns, and send bulk emails.

## Structure

- Filament admin
- Livewire components
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
<module type="newsletter" />
```

### Run migrations

```sh
php artisan module:migrate Newsletter
```

### Publish assets

```sh
php artisan module:publish Newsletter
```

### Views

```php
view('modules.newsletter::index')
```

